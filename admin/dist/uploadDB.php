<?php
session_start();
include 'function.php';

// Set time limit dan memory limit untuk proses yang panjang
set_time_limit(0);
ini_set('memory_limit', '512M');
ini_set('max_execution_time', 0);

// Konfigurasi
define('UPLOAD_DIR', __DIR__ . '/uploads/');
define('CHUNK_SIZE', 1000); // Jumlah query per chunk untuk import
define('TABLES_PER_EXPORT', 1); // Jumlah table per file export
define('LOG_FILE', __DIR__ . '/import_export_log.txt');
define('MAX_LINE_LENGTH', 10485760); // 10MB per line max

// Buat folder upload jika belum ada
if (!is_dir(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0755, true);
}

// Function untuk logging
function logMessage($message) {
    $timestamp = date('Y-m-d H:i:s');
    $logMsg = "[$timestamp] $message\n";
    @file_put_contents(LOG_FILE, $logMsg, FILE_APPEND);
    return $logMsg;
}

// ==================== EXPORT FUNCTIONS ====================

// Function untuk get semua tables dari database
function getAllTables($conn, $dbName) {
    $tables = [];
    $result = $conn->query("SHOW TABLES FROM `$dbName`");
    if ($result) {
        while ($row = $result->fetch_array()) {
            $tables[] = $row[0];
        }
    }
    return $tables;
}

// Function untuk export table structure
function exportTableStructure($conn, $tableName) {
    $sql = "SHOW CREATE TABLE `$tableName`";
    $result = $conn->query($sql);
    
    if ($result && $row = $result->fetch_array()) {
        return "-- Structure for table `$tableName`\n" .
               "DROP TABLE IF EXISTS `$tableName`;\n" .
               $row[1] . ";\n\n";
    }
    return "";
}

// Function untuk export table data
function exportTableData($conn, $tableName) {
    $output = "-- Data for table `$tableName`\n";
    $result = $conn->query("SELECT * FROM `$tableName`");
    
    if (!$result) {
        return $output . "-- Error: " . $conn->error . "\n\n";
    }
    
    if ($result->num_rows == 0) {
        return $output . "-- No data\n\n";
    }
    
    while ($row = $result->fetch_assoc()) {
        $values = [];
        foreach ($row as $value) {
            if ($value === null) {
                $values[] = "NULL";
            } else {
                $values[] = "'" . $conn->real_escape_string($value) . "'";
            }
        }
        
        $output .= "INSERT INTO `$tableName` VALUES (" . implode(", ", $values) . ");\n";
    }
    
    return $output . "\n";
}

// Function untuk extract foreign keys dari CREATE TABLE
function extractForeignKeys($createTableSQL) {
    $foreignKeys = [];
    
    // Pattern untuk CONSTRAINT ... FOREIGN KEY
    preg_match_all(
        '/CONSTRAINT\s+`([^`]+)`\s+FOREIGN\s+KEY\s*\(([^)]+)\)\s*REFERENCES\s+`([^`]+)`\s*\(([^)]+)\)([^,\n]*)/i',
        $createTableSQL,
        $matches,
        PREG_SET_ORDER
    );
    
    foreach ($matches as $match) {
        $foreignKeys[] = [
            'constraint' => $match[1],
            'columns' => $match[2],
            'ref_table' => $match[3],
            'ref_columns' => $match[4],
            'actions' => $match[5]
        ];
    }
    
    return $foreignKeys;
}

// Function untuk remove foreign keys dari CREATE TABLE
function removeForeignKeysFromSQL($createTableSQL) {
    // Remove CONSTRAINT ... FOREIGN KEY
    $sql = preg_replace(
        '/,?\s*CONSTRAINT\s+`[^`]+`\s+FOREIGN\s+KEY\s*\([^)]+\)\s*REFERENCES\s+`[^`]+`\s*\([^)]+\)([^,\n]*)/i',
        '',
        $createTableSQL
    );
    
    // Clean up trailing commas
    $sql = preg_replace('/,(\s*\))/', '$1', $sql);
    
    return $sql;
}

// Function untuk export tables dalam chunks
function exportTablesChunk($conn, $dbName, $tables, $startIndex) {
    $endIndex = min($startIndex + TABLES_PER_EXPORT, count($tables));
    $chunkTables = array_slice($tables, $startIndex, TABLES_PER_EXPORT);
    
    if (empty($chunkTables)) {
        return false;
    }
    
    $filename = sprintf("export_%s_part_%03d_tables_%d_to_%d.sql", 
        $dbName, 
        floor($startIndex / TABLES_PER_EXPORT) + 1,
        $startIndex + 1,
        $endIndex
    );
    
    $filepath = UPLOAD_DIR . $filename;
    $output = "-- Export Part: Tables " . ($startIndex + 1) . " to " . $endIndex . "\n";
    $output .= "-- Database: $dbName\n";
    $output .= "-- Date: " . date('Y-m-d H:i:s') . "\n\n";
    $output .= "SET FOREIGN_KEY_CHECKS=0;\n";
    $output .= "SET SQL_MODE='';\n\n";
    
    foreach ($chunkTables as $table) {
        // Get CREATE TABLE
        $createSQL = exportTableStructure($conn, $table);
        
        // Remove foreign keys dari structure
        $result = $conn->query("SHOW CREATE TABLE `$table`");
        if ($result && $row = $result->fetch_array()) {
            $createSQLClean = removeForeignKeysFromSQL($row[1]);
            $output .= "-- Structure for table `$table` (without foreign keys)\n";
            $output .= "DROP TABLE IF EXISTS `$table`;\n";
            $output .= $createSQLClean . ";\n\n";
        }
        
        // Get data
        $output .= exportTableData($conn, $table);
    }
    
    $output .= "SET FOREIGN_KEY_CHECKS=1;\n";
    
    file_put_contents($filepath, $output);
    logMessage("Exported tables " . ($startIndex + 1) . " to " . $endIndex . ": " . implode(", ", $chunkTables));
    
    return [
        'filename' => $filename,
        'tables' => $chunkTables,
        'start' => $startIndex,
        'end' => $endIndex
    ];
}

// Function untuk export semua foreign keys
function exportAllForeignKeys($conn, $dbName, $tables) {
    $filename = "export_{$dbName}_foreign_keys.sql";
    $filepath = UPLOAD_DIR . $filename;
    
    $output = "-- Foreign Keys for Database: $dbName\n";
    $output .= "-- Date: " . date('Y-m-d H:i:s') . "\n\n";
    $output .= "SET FOREIGN_KEY_CHECKS=0;\n\n";
    
    foreach ($tables as $table) {
        $result = $conn->query("SHOW CREATE TABLE `$table`");
        if ($result && $row = $result->fetch_array()) {
            $createSQL = $row[1];
            $foreignKeys = extractForeignKeys($createSQL);
            
            if (!empty($foreignKeys)) {
                $output .= "-- Foreign keys for table `$table`\n";
                foreach ($foreignKeys as $fk) {
                    $output .= "ALTER TABLE `$table` ADD CONSTRAINT `{$fk['constraint']}` ";
                    $output .= "FOREIGN KEY ({$fk['columns']}) ";
                    $output .= "REFERENCES `{$fk['ref_table']}` ({$fk['ref_columns']})";
                    $output .= $fk['actions'] . ";\n";
                }
                $output .= "\n";
            }
        }
    }
    
    $output .= "SET FOREIGN_KEY_CHECKS=1;\n";
    
    file_put_contents($filepath, $output);
    logMessage("Exported all foreign keys to: $filename");
    
    return $filename;
}

// ==================== IMPORT FUNCTIONS ====================

// Function untuk get semua file SQL di folder uploads (urut)
function getSQLFilesInOrder() {
    $files = glob(UPLOAD_DIR . "export_*.sql");
    
    if (empty($files)) {
        return [];
    }
    
    // Pisahkan file foreign keys dan table files
    $foreignKeyFile = null;
    $tableFiles = [];
    
    foreach ($files as $file) {
        $basename = basename($file);
        if (strpos($basename, 'foreign_keys') !== false) {
            $foreignKeyFile = $file;
        } else {
            $tableFiles[] = $file;
        }
    }
    
    // Sort table files by part number
    usort($tableFiles, function($a, $b) {
        preg_match('/part_(\d+)/', basename($a), $matchA);
        preg_match('/part_(\d+)/', basename($b), $matchB);
        return (int)$matchA[1] - (int)$matchB[1];
    });
    
    // Foreign keys di akhir
    if ($foreignKeyFile) {
        $tableFiles[] = $foreignKeyFile;
    }
    
    return $tableFiles;
}

// Function untuk count queries dalam file
function countQueriesInFile($filepath) {
    $handle = fopen($filepath, 'r');
    if (!$handle) {
        return false;
    }
    
    $count = 0;
    $buffer = '';
    $inString = false;
    $stringChar = '';
    
    while (!feof($handle)) {
        $line = fgets($handle, MAX_LINE_LENGTH);
        if ($line === false) break;
        
        $line = preg_replace('/--.*$/', '', $line);
        $buffer .= $line;
        
        for ($i = 0; $i < strlen($line); $i++) {
            $char = $line[$i];
            
            if (($char === '"' || $char === "'") && ($i === 0 || $line[$i-1] !== '\\')) {
                if (!$inString) {
                    $inString = true;
                    $stringChar = $char;
                } elseif ($char === $stringChar) {
                    $inString = false;
                }
            }
            
            if ($char === ';' && !$inString) {
                if (!empty(trim($buffer))) {
                    $count++;
                }
                $buffer = '';
            }
        }
    }
    
    fclose($handle);
    return $count;
}

// Function untuk extract queries dari file
function extractQueriesFromFile($filepath) {
    $handle = fopen($filepath, 'r');
    if (!$handle) {
        return false;
    }
    
    $queries = [];
    $buffer = '';
    $inString = false;
    $stringChar = '';
    
    while (!feof($handle)) {
        $line = fgets($handle, MAX_LINE_LENGTH);
        if ($line === false) break;
        
        $line = preg_replace('/--.*$/', '', $line);
        
        for ($i = 0; $i < strlen($line); $i++) {
            $char = $line[$i];
            
            if (($char === '"' || $char === "'") && ($i === 0 || $line[$i-1] !== '\\')) {
                if (!$inString) {
                    $inString = true;
                    $stringChar = $char;
                } elseif ($char === $stringChar) {
                    $inString = false;
                }
            }
            
            $buffer .= $char;
            
            if ($char === ';' && !$inString) {
                $queryStr = trim($buffer);
                
                if (!empty($queryStr) && !preg_match('/^\/\*.*\*\/$/s', $queryStr)) {
                    $queries[] = $queryStr;
                }
                
                $buffer = '';
            }
        }
    }
    
    fclose($handle);
    return $queries;
}

// Function untuk execute queries dengan error handling
function executeQueries($queries, $conn) {
    $success = 0;
    $failed = 0;
    $errors = [];
    
    mysqli_report(MYSQLI_REPORT_OFF);
    
    foreach ($queries as $query) {
        $query = trim($query);
        if (empty($query)) continue;
        
        if (preg_match('/^(USE|SET)\s+/i', $query)) {
            @$conn->query($query);
            $success++;
            continue;
        }
        
        $result = @$conn->query($query);
        
        if ($result) {
            $success++;
        } else {
            $errorMsg = @$conn->error;
            $errorCode = @$conn->errno;
            
            // Skip non-critical errors
            $nonCriticalErrors = [1007, 1050, 1060, 1061, 1091, 1062, 1146];
            
            if (in_array($errorCode, $nonCriticalErrors)) {
                $success++;
                continue;
            }
            
            $failed++;
            if ($errorCode && $errorMsg) {
                $errors[] = "[$errorCode] $errorMsg";
                @logMessage("Error [$errorCode]: $errorMsg - Query: " . substr($query, 0, 100));
            }
        }
    }
    
    return [
        'success' => $success,
        'failed' => $failed,
        'errors' => array_slice($errors, 0, 5)
    ];
}

// ==================== AJAX HANDLERS ====================

// Handle AJAX Export
if (isset($_POST['action']) && $_POST['action'] === 'export_chunk') {
    @ob_clean();
    header('Content-Type: application/json');
    error_reporting(0);
    
    $database = $_POST['database'] ?? '';
    $startIndex = intval($_POST['start_index'] ?? 0);
    
    if (empty($database)) {
        echo json_encode(['error' => 'Database not specified']);
        exit;
    }
    
    $conn = ($database === 'simkhmid_database') ? $koneksi : $koneksimaster;
    
    if (!$conn) {
        echo json_encode(['error' => 'Database connection failed']);
        exit;
    }
    
    // Get all tables jika belum ada di session
    if (!isset($_SESSION['export_tables'])) {
        $_SESSION['export_tables'] = getAllTables($conn, $database);
        $_SESSION['export_database'] = $database;
        $_SESSION['total_tables'] = count($_SESSION['export_tables']);
    }
    
    $tables = $_SESSION['export_tables'];
    $totalTables = count($tables);
    
    // Check if all tables exported
    if ($startIndex >= $totalTables) {
        // Export foreign keys
        $fkFile = exportAllForeignKeys($conn, $database, $tables);
        
        // Clear session
        unset($_SESSION['export_tables']);
        unset($_SESSION['export_database']);
        unset($_SESSION['total_tables']);
        
        echo json_encode([
            'completed' => true,
            'processed' => $totalTables,
            'total' => $totalTables,
            'percentage' => 100,
            'message' => 'Export completed! Foreign keys saved to: ' . $fkFile
        ]);
        exit;
    }
    
    // Export chunk
    $result = exportTablesChunk($conn, $database, $tables, $startIndex);
    
    if (!$result) {
        echo json_encode(['error' => 'Failed to export tables']);
        exit;
    }
    
    $processed = $result['end'];
    $percentage = round(($processed / $totalTables) * 100, 2);
    
    echo json_encode([
        'success' => true,
        'processed' => $processed,
        'total' => $totalTables,
        'percentage' => $percentage,
        'filename' => $result['filename'],
        'tables' => $result['tables'],
        'completed' => false
    ]);
    exit;
}

// Handle AJAX Import
if (isset($_POST['action']) && $_POST['action'] === 'import_file') {
    @ob_clean();
    header('Content-Type: application/json');
    error_reporting(0);
    
    $database = $_POST['database'] ?? '';
    $fileIndex = intval($_POST['file_index'] ?? 0);
    
    if (empty($database)) {
        echo json_encode(['error' => 'Database not specified']);
        exit;
    }
    
    $conn = ($database === 'simkhmid_database') ? $koneksi : $koneksimaster;
    
    if (!$conn) {
        echo json_encode(['error' => 'Database connection failed']);
        exit;
    }
    
    // Get all SQL files
    if (!isset($_SESSION['import_files'])) {
        $_SESSION['import_files'] = getSQLFilesInOrder();
        $_SESSION['import_database'] = $database;
    }
    
    $files = $_SESSION['import_files'];
    $totalFiles = count($files);
    
    // Check if completed
    if ($fileIndex >= $totalFiles) {
        unset($_SESSION['import_files']);
        unset($_SESSION['import_database']);
        
        echo json_encode([
            'completed' => true,
            'processed' => $totalFiles,
            'total' => $totalFiles,
            'percentage' => 100,
            'message' => 'All files imported successfully!'
        ]);
        exit;
    }
    
    $filepath = $files[$fileIndex];
    $filename = basename($filepath);
    
    // Set MySQL config
    @$conn->query("SET FOREIGN_KEY_CHECKS=0");
    @$conn->query("SET SQL_MODE=''");
    @$conn->query("SET UNIQUE_CHECKS=0");
    
    // Extract and execute queries
    $queries = extractQueriesFromFile($filepath);
    
    if ($queries === false) {
        echo json_encode(['error' => 'Failed to read file: ' . $filename]);
        exit;
    }
    
    $result = executeQueries($queries, $conn);
    
    @$conn->query("SET FOREIGN_KEY_CHECKS=1");
    @$conn->query("SET UNIQUE_CHECKS=1");
    
    $processed = $fileIndex + 1;
    $percentage = round(($processed / $totalFiles) * 100, 2);
    
    echo json_encode([
        'success' => true,
        'processed' => $processed,
        'total' => $totalFiles,
        'percentage' => $percentage,
        'filename' => $filename,
        'queries_success' => $result['success'],
        'queries_failed' => $result['failed'],
        'errors' => $result['errors'],
        'completed' => false
    ]);
    exit;
}

// Handle clear uploads folder
if (isset($_POST['clear_uploads'])) {
    $files = glob(UPLOAD_DIR . "*.sql");
    $count = 0;
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
            $count++;
        }
    }
    $_SESSION['message'] = "Deleted $count SQL files from uploads folder";
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Helper function
function formatBytes($bytes, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return round($bytes, $precision) . ' ' . $units[$pow];
}

// Count files in uploads
$uploadedFiles = glob(UPLOAD_DIR . "*.sql");
$fileCount = count($uploadedFiles);
$totalSize = 0;
foreach ($uploadedFiles as $file) {
    $totalSize += filesize($file);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Export & Import Tool</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .header p {
            opacity: 0.9;
            font-size: 14px;
        }
        
        .content {
            padding: 30px;
        }
        
        .tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            border-bottom: 2px solid #e0e0e0;
        }
        
        .tab {
            padding: 12px 30px;
            background: transparent;
            border: none;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            color: #666;
            border-bottom: 3px solid transparent;
            transition: all 0.3s;
        }
        
        .tab:hover {
            color: #667eea;
        }
        
        .tab.active {
            color: #667eea;
            border-bottom-color: #667eea;
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            animation: slideIn 0.3s ease;
        }
        
        @keyframes slideIn {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
        
        .alert-info {
            background: #e7f3ff;
            border-left: 4px solid #2196F3;
            color: #1976D2;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 600;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        
        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
        
        .btn-success {
            background: #28a745;
            color: white;
        }
        
        .btn-success:hover {
            background: #218838;
        }
        
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        
        .btn-danger:hover {
            background: #c82333;
        }
        
        .btn-group {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        
        .progress-container {
            display: none;
            margin-top: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .progress-bar-wrapper {
            background: #e0e0e0;
            border-radius: 10px;
            height: 30px;
            overflow: hidden;
            position: relative;
            margin-bottom: 15px;
        }
        
        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            transition: width 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 14px;
        }
        
        .progress-info {
            background: white;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #667eea;
        }
        
        .progress-info p {
            margin: 5px 0;
            font-size: 14px;
            color: #555;
        }
        
        .progress-info strong {
            color: #333;
        }
        
        .spinner {
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-left: 10px;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .info-box {
            background: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .info-box h3 {
            color: #1976D2;
            margin-bottom: 10px;
            font-size: 16px;
        }
        
        .info-box ul {
            margin-left: 20px;
            color: #555;
            font-size: 14px;
        }
        
        .info-box li {
            margin: 5px 0;
        }
        
        .file-list {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }
        
        .file-list h4 {
            margin-bottom: 10px;
            color: #333;
        }
        
        .file-item {
            padding: 8px;
            background: white;
            margin-bottom: 5px;
            border-radius: 3px;
            font-size: 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .file-item .filename {
            color: #667eea;
            font-weight: 500;
        }
        
        .file-item .filesize {
            color: #999;
            font-size: 12px;
        }
        
        .stats-box {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }
        
        .stat-card h3 {
            font-size: 32px;
            margin-bottom: 5px;
        }
        
        .stat-card p {
            font-size: 14px;
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🗄️ Database Export & Import Tool</h1>
            <p>Export database dalam chunks kecil & Import secara bertahap</p>
        </div>
        
        <div class="content">
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    ❌ <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-success">
                    ✅ <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
                </div>
            <?php endif; ?>
            
            <!-- Tabs -->
            <div class="tabs">
                <button class="tab active" onclick="switchTab('export')">📤 Export Database</button>
                <button class="tab" onclick="switchTab('import')">📥 Import Database</button>
            </div>
            
            <!-- Export Tab -->
            <div id="export-tab" class="tab-content active">
                <div class="info-box">
                    <h3>ℹ️ Export Mode</h3>
                    <ul>
                        <li>Export database dalam chunks kecil (<?php echo TABLES_PER_EXPORT; ?> tables per file)</li>
                        <li>Foreign Keys di-export terpisah di akhir</li>
                        <li>File disimpan di folder: <code>uploads/</code></li>
                        <li>Setiap file sudah siap untuk di-import secara bertahap</li>
                    </ul>
                </div>
                
                <div class="form-group">
                    <label for="export_database">Pilih Database untuk di-Export:</label>
                    <select id="export_database">
                        <option value="">-- Pilih Database --</option>
                        <option value="simkhmid_database">simkhmid_database (Main Database)</option>
                        <option value="simkhmid_master">simkhmid_master (Master Database)</option>
                    </select>
                </div>
                
                <div class="btn-group">
                    <button onclick="startExport()" class="btn btn-primary" id="exportBtn">
                        📤 Start Export
                    </button>
                    <?php if ($fileCount > 0): ?>
                    <form method="post" style="display: inline;">
                        <button type="submit" name="clear_uploads" class="btn btn-danger" 
                                onclick="return confirm('Delete all <?php echo $fileCount; ?> SQL files?')">
                            🗑️ Clear Uploads
                        </button>
                    </form>
                    <?php endif; ?>
                </div>
                
                <div class="progress-container" id="exportProgress">
                    <div class="progress-bar-wrapper">
                        <div class="progress-bar" id="exportProgressBar" style="width: 0%">0%</div>
                    </div>
                    
                    <div class="progress-info">
                        <p><strong>Status:</strong> <span id="exportStatus">Starting...</span></p>
                        <p><strong>Processed:</strong> <span id="exportProcessed">0</span> / <span id="exportTotal">0</span> tables</p>
                        <p><strong>Current File:</strong> <span id="exportCurrentFile">-</span></p>
                    </div>
                </div>
                
                <?php if ($fileCount > 0): ?>
                <div class="file-list">
                    <h4>📁 Exported Files (<?php echo $fileCount; ?> files, <?php echo formatBytes($totalSize); ?>)</h4>
                    <?php foreach ($uploadedFiles as $file): ?>
                    <div class="file-item">
                        <span class="filename"><?php echo basename($file); ?></span>
                        <span class="filesize"><?php echo formatBytes(filesize($file)); ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Import Tab -->
            <div id="import-tab" class="tab-content">
                <div class="info-box">
                    <h3>ℹ️ Import Mode</h3>
                    <ul>
                        <li>Import semua file SQL dari folder <code>uploads/</code></li>
                        <li>File di-import secara berurutan (part 1, 2, 3, ...)</li>
                        <li>Foreign Keys di-import di akhir</li>
                        <li>Progress ditampilkan secara real-time</li>
                    </ul>
                </div>
                
                <?php if ($fileCount > 0): ?>
                <div class="stats-box">
                    <div class="stat-card">
                        <h3><?php echo $fileCount; ?></h3>
                        <p>SQL Files Ready</p>
                    </div>
                    <div class="stat-card">
                        <h3><?php echo formatBytes($totalSize); ?></h3>
                        <p>Total Size</p>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="import_database">Pilih Target Database:</label>
                    <select id="import_database">
                        <option value="">-- Pilih Database --</option>
                        <option value="simkhmid_database">simkhmid_database (Main Database)</option>
                        <option value="simkhmid_master">simkhmid_master (Master Database)</option>
                    </select>
                </div>
                
                <div class="btn-group">
                    <button onclick="startImport()" class="btn btn-success" id="importBtn">
                        📥 Start Import
                    </button>
                </div>
                
                <div class="progress-container" id="importProgress">
                    <div class="progress-bar-wrapper">
                        <div class="progress-bar" id="importProgressBar" style="width: 0%">0%</div>
                    </div>
                    
                    <div class="progress-info">
                        <p><strong>Status:</strong> <span id="importStatus">Starting...</span></p>
                        <p><strong>Files Processed:</strong> <span id="importProcessed">0</span> / <span id="importTotal"><?php echo $fileCount; ?></span></p>
                        <p><strong>Current File:</strong> <span id="importCurrentFile">-</span></p>
                        <p><strong>Queries Success:</strong> <span id="importSuccess" style="color: green;">0</span></p>
                        <p><strong>Queries Failed:</strong> <span id="importFailed" style="color: red;">0</span></p>
                    </div>
                </div>
                
                <?php else: ?>
                <div class="alert alert-info">
                    ⚠️ No SQL files found in uploads folder. Please export database first.
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script>
        // Tab switching
        function switchTab(tab) {
            // Update tab buttons
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            event.target.classList.add('active');
            
            // Update tab content
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            document.getElementById(tab + '-tab').classList.add('active');
        }
        
        // Export functions
        function startExport() {
            const database = document.getElementById('export_database').value;
            
            if (!database) {
                alert('Please select a database to export');
                return;
            }
            
            document.getElementById('exportBtn').disabled = true;
            document.getElementById('exportProgress').style.display = 'block';
            
            processExport(database, 0);
        }
        
        function processExport(database, startIndex) {
            document.getElementById('exportStatus').innerHTML = 'Exporting... <span class="spinner"></span>';
            
            fetch(window.location.href, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=export_chunk&database=' + database + '&start_index=' + startIndex
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert('Error: ' + data.error);
                    document.getElementById('exportStatus').innerHTML = '❌ Error occurred!';
                    document.getElementById('exportBtn').disabled = false;
                    return;
                }
                
                // Update progress
                const percentage = data.percentage || 0;
                document.getElementById('exportProgressBar').style.width = percentage + '%';
                document.getElementById('exportProgressBar').textContent = percentage + '%';
                document.getElementById('exportProcessed').textContent = data.processed || 0;
                document.getElementById('exportTotal').textContent = data.total || 0;
                
                if (data.filename) {
                    document.getElementById('exportCurrentFile').textContent = data.filename;
                }
                
                if (data.completed) {
                    document.getElementById('exportStatus').innerHTML = '✅ Export Completed!';
                    alert(data.message || 'Export completed successfully!');
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    // Continue with next chunk
                    setTimeout(() => processExport(database, data.processed), 100);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('exportStatus').innerHTML = '❌ Error occurred!';
                document.getElementById('exportBtn').disabled = false;
                alert('An error occurred during export:\n' + error.message);
            });
        }
        
        // Import functions
        let totalImportSuccess = 0;
        let totalImportFailed = 0;
        
        function startImport() {
            const database = document.getElementById('import_database').value;
            
            if (!database) {
                alert('Please select a target database');
                return;
            }
            
            totalImportSuccess = 0;
            totalImportFailed = 0;
            
            document.getElementById('importBtn').disabled = true;
            document.getElementById('importProgress').style.display = 'block';
            
            processImport(database, 0);
        }
        
        function processImport(database, fileIndex) {
            document.getElementById('importStatus').innerHTML = 'Importing... <span class="spinner"></span>';
            
            fetch(window.location.href, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=import_file&database=' + database + '&file_index=' + fileIndex
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert('Error: ' + data.error);
                    document.getElementById('importStatus').innerHTML = '❌ Error occurred!';
                    document.getElementById('importBtn').disabled = false;
                    return;
                }
                
                // Update counters
                totalImportSuccess += data.queries_success || 0;
                totalImportFailed += data.queries_failed || 0;
                
                // Update progress
                const percentage = data.percentage || 0;
                document.getElementById('importProgressBar').style.width = percentage + '%';
                document.getElementById('importProgressBar').textContent = percentage + '%';
                document.getElementById('importProcessed').textContent = data.processed || 0;
                document.getElementById('importTotal').textContent = data.total || 0;
                document.getElementById('importSuccess').textContent = totalImportSuccess;
                document.getElementById('importFailed').textContent = totalImportFailed;
                
                if (data.filename) {
                    document.getElementById('importCurrentFile').textContent = data.filename;
                }
                
                if (data.completed) {
                    document.getElementById('importStatus').innerHTML = '✅ Import Completed!';
                    alert(data.message || 'Import completed successfully!\n\nSuccess: ' + totalImportSuccess + '\nFailed: ' + totalImportFailed);
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    // Continue with next file
                    setTimeout(() => processImport(database, data.processed), 100);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('importStatus').innerHTML = '❌ Error occurred!';
                document.getElementById('importBtn').disabled = false;
                alert('An error occurred during import:\n' + error.message);
            });
        }
    </script>
</body>
</html>
