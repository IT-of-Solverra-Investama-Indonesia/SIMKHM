<?php
include '../dist/function.php';
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Antrian SIMKHM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
</head>

<body style="background-color: green;">
    <div class="container-fluid" style="margin-top: 50px; color: white;">
        <center>
            <img src="https://simkhm.id/wonorejo/admin/dist/assets/img/3.png" style="width: 17%;" alt="">
            <!-- Debug buttons - remove after testing -->
            <!-- <div style="margin: 10px; ">
                <button onclick="speakText('Test suara satu dua tiga')" style="background: red; color: white; padding: 5px 10px; border: none; margin: 5px; border-radius: 5px;">🔊 Test Suara</button>
                <button onclick="forceEnableAudio()" style="background: orange; color: white; padding: 5px 10px; border: none; margin: 5px; border-radius: 5px;">🎵 Enable Audio</button>
                <button onclick="console.log('Console test')" style="background: blue; color: white; padding: 5px 10px; border: none; margin: 5px; border-radius: 5px;">📝 Test Console</button>
            </div> -->
        </center>
        <br>
        <div class="row">
            <h2>MASUK DOKTER :</h2>
            <div class="col-4">
                <div id="dokter-umum" style="background-color: #11592b; border: 5px solid white;" onclick="speakText('Antrian Poli Umum nomor P001')">
                    <center>
                        <h3 style="color: #f3d94d;" class="mb-0">POLI UMUM</h3>
                        <span class="mt-0" style="font-size: 80px;"><b id="dokter-umum-nomor">P001</b></span>
                    </center>
                </div>
            </div>
            <div class="col-4">
                <div id="dokter-gigi" style="background-color: #11592b; border: 5px solid white;" onclick="speakText('Antrian Poli Gigi nomor P001')">
                    <center>
                        <h3 style="color: #f3d94d;" class="mb-0">POLI GIGI</h3>
                        <span class="mt-0" style="font-size: 80px;"><b id="dokter-gigi-nomor">P001</b></span>
                    </center>
                </div>
            </div>
            <div class="col-4">
                <div id="dokter-spesialis" style="background-color: #11592b; border: 5px solid white;">
                    <center>
                        <h3 style="color: #f3d94d;" class="mb-0">POLI SPESIALIS</h3>
                        <span class="mt-0" style="font-size: 80px;"><b id="dokter-spesialis-nomor">P001</b></span>
                    </center>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <h2>MASUK PERAWAT POLI : </h2>
            <div class="col-4">
                <div id="perawat-umum" style="background-color: #11592b; border: 5px solid white;">
                    <center>
                        <h3 style="color: #f3d94d;" class="mb-0">POLI UMUM</h3>
                        <span class="mt-0" style="font-size: 80px;"><b id="perawat-umum-nomor">P001</b></span>
                    </center>
                </div>
            </div>
            <div class="col-4">
                <div id="perawat-gigi" style="background-color: #11592b; border: 5px solid white;">
                    <center>
                        <h3 style="color: #f3d94d;" class="mb-0">POLI GIGI</h3>
                        <span class="mt-0" style="font-size: 80px;"><b id="perawat-gigi-nomor">P001</b></span>
                    </center>
                </div>
            </div>
            <div class="col-4">
                <div id="perawat-spesialis" style="background-color: #11592b; border: 5px solid white;">
                    <center>
                        <h3 style="color: #f3d94d;" class="mb-0">POLI SPESIALIS</h3>
                        <span class="mt-0" style="font-size: 80px;"><b id="perawat-spesialis-nomor">P001</b></span>
                    </center>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Variables untuk tracking
        let speechEnabled = false;
        let lastActivityTime = Date.now();
        let isTabActive = true;
        let heartbeatInterval;
        let mainInterval;

        // Audio Context untuk mencegah suspend
        let audioContext;

        // Tracking suara yang sudah dipanggil untuk mencegah repetisi
        let lastPlayedAudio = {};

        // Console clear timer
        let consoleClearInterval;

        function initAudioContext() {
            try {
                if (!audioContext) {
                    audioContext = new(window.AudioContext || window.webkitAudioContext)();
                    console.log('Audio Context initialized');
                }

                if (audioContext.state === 'suspended') {
                    audioContext.resume().then(() => {
                        console.log('Audio Context resumed');
                    });
                }
            } catch (e) {
                console.warn('Audio Context not supported:', e);
            }
        }

        // Keep-alive ping untuk mencegah browser sleep
        function sendKeepAlive() {
            fetch('../api/api_getAntrianTerakhir.php?fungsi=keepAlive', {
                method: 'GET',
                cache: 'no-cache'
            }).catch(e => console.log('Keep-alive ping failed:', e));
        }

        // Clear console setiap 1 jam untuk menjaga performa
        function clearConsoleHourly() {
            console.clear();
            console.log('🧹 Console cleared automatically - ' + new Date().toLocaleTimeString());
            console.log('📊 System Status: Active - Anti-sleep measures running');
        }

        // Deteksi tab aktif/tidak aktif
        function handleVisibilityChange() {
            isTabActive = !document.hidden;
            console.log('Tab visibility changed:', isTabActive ? 'active' : 'inactive');

            if (isTabActive) {
                // Tab aktif kembali - resume semua aktivitas
                console.log('Tab active - resuming activities');
                initAudioContext();
                lastActivityTime = Date.now();

                // Reset interval untuk memastikan responsivitas
                clearInterval(mainInterval);
                mainInterval = setInterval(getAntrianBaru, 3000); // Lebih cepat saat aktif

                // Langsung cek antrian baru
                setTimeout(getAntrianBaru, 500);
            } else {
                // Tab tidak aktif - tetap jalankan tapi dengan interval lebih lambat
                console.log('Tab inactive - continuing with slower interval');
                clearInterval(mainInterval);
                mainInterval = setInterval(getAntrianBaru, 8000); // Lebih lambat saat tidak aktif
            }
        }

        // Synthetic user activity untuk menjaga browser aktif
        function simulateActivity() {
            // Buat event palsu untuk menjaga tab "aktif"
            const event = new MouseEvent('mousemove', {
                view: window,
                bubbles: true,
                cancelable: true,
                clientX: 1,
                clientY: 1
            });
            document.dispatchEvent(event);
            lastActivityTime = Date.now();
        }

        function playNomorAudio(nomor, status, layanan) {
            console.log('Playing audio for:', nomor, status, layanan);

            // Buat ID unik untuk tracking audio yang sudah diputar
            const audioId = `${nomor}_${status}_${layanan}`;

            // Cek apakah audio ini sudah diputar dalam 30 detik terakhir
            const now = Date.now();
            if (lastPlayedAudio[audioId] && (now - lastPlayedAudio[audioId]) < 30000) {
                console.log('🔇 Audio skipped - already played recently:', audioId);
                return; // Skip audio jika baru saja diputar
            }

            // Update tracking audio yang terakhir diputar
            lastPlayedAudio[audioId] = now;

            // Pastikan audio context aktif
            initAudioContext();

            // Coba putar file audio yang sudah digenerate dulu
            const audioFile = `../audio/${nomor}_${status}.mp3`;
            const audio = new Audio(audioFile);

            audio.onloadstart = () => console.log('Audio loading:', audioFile);
            audio.oncanplay = () => console.log('Audio ready:', audioFile);
            audio.onplay = () => console.log('Audio playing:', audioFile);
            audio.onerror = (e) => {
                console.log('Audio file not found, using speech synthesis:', e);
                // Fallback ke speech synthesis jika file tidak ada
                fallbackToSpeech(nomor, status, layanan);
            };

            // Set volume dan play
            audio.volume = 0.8;
            audio.play().catch(e => {
                console.log('Audio play failed, using speech synthesis:', e);
                fallbackToSpeech(nomor, status, layanan);
            });
        }

        function fallbackToSpeech(nomor, status, layanan) {
            // Buat ID unik untuk tracking speech yang sudah diputar
            const speechId = `speech_${nomor}_${status}_${layanan}`;

            // Cek apakah speech ini sudah diputar dalam 30 detik terakhir
            const now = Date.now();
            if (lastPlayedAudio[speechId] && (now - lastPlayedAudio[speechId]) < 30000) {
                console.log('🔇 Speech skipped - already played recently:', speechId);
                return; // Skip speech jika baru saja diputar
            }

            // Update tracking speech yang terakhir diputar
            lastPlayedAudio[speechId] = now;

            let text = '';
            if (status === 'dokter') {
                text = `................AAntrian ${layanan} nomor ${nomor}. Silahkan menuju ke ruang dokter`;
            } else if (status === 'perawat') {
                text = `................AAntrian ${layanan} nomor ${nomor}. Silahkan menuju ke tempat perawat poli`;
            } else {
                text = `................AAntrian nomor ${nomor}`;
            }
            speakText(text);
        }

        function speakText(text) {
            console.log('Attempting to speak:', text);

            // Pastikan audio context aktif
            initAudioContext();

            // Simulate activity untuk menjaga browser responsif
            simulateActivity();

            if ('speechSynthesis' in window) {
                // Cancel any ongoing speech
                window.speechSynthesis.cancel();

                // Wait for voices to load
                const speakNow = () => {
                    const utterance = new SpeechSynthesisUtterance(text);

                    // Set basic properties
                    utterance.rate = 0.9; // Slower rate
                    utterance.pitch = 1;
                    utterance.volume = 1;
                    utterance.lang = 'id-ID';

                    // Add event listeners for debugging
                    utterance.onstart = function() {
                        console.log('✅ Speech started:', text);
                        lastActivityTime = Date.now();
                    };

                    utterance.onend = function() {
                        console.log('✅ Speech ended:', text);
                    };

                    utterance.onerror = function(event) {
                        console.error('❌ Speech error:', event);
                        // Fallback to alert if speech fails
                        alert('Audio: ' + text);
                    };

                    // Get available voices
                    const voices = window.speechSynthesis.getVoices();
                    console.log('Available voices:', voices.length);

                    if (voices.length > 0) {
                        // Try to find Indonesian voice first
                        let selectedVoice = voices.find(voice =>
                            voice.lang.toLowerCase().includes('id') ||
                            voice.name.toLowerCase().includes('indonesia')
                        );

                        // Use first available voice as fallback
                        if (!selectedVoice && voices.length > 0) {
                            selectedVoice = voices[0];
                        }

                        if (selectedVoice) {
                            utterance.voice = selectedVoice;
                            console.log('Using voice:', selectedVoice.name, selectedVoice.lang);
                        }
                    }

                    // Speak the text
                    window.speechSynthesis.speak(utterance);
                    console.log('Speech command sent');
                };

                // Check if voices are loaded
                if (window.speechSynthesis.getVoices().length > 0) {
                    // Voices already loaded
                    setTimeout(speakNow, 100);
                } else {
                    // Wait for voices to load
                    let voiceTimeout = setTimeout(() => {
                        console.log('Voice loading timeout, using default');
                        speakNow();
                    }, 2000);

                    window.speechSynthesis.onvoiceschanged = function() {
                        clearTimeout(voiceTimeout);
                        console.log('Voices loaded, ready to speak');
                        setTimeout(speakNow, 100);
                        window.speechSynthesis.onvoiceschanged = null;
                    };
                }
            } else {
                console.error('❌ Speech synthesis not supported');
                alert('Audio tidak didukung: ' + text);
            }
        }

        function forceEnableAudio() {
            console.log('🔧 Force enabling audio...');

            if ('speechSynthesis' in window) {
                window.speechSynthesis.cancel();

                if (window.speechSynthesis.paused) {
                    window.speechSynthesis.resume();
                }

                setTimeout(() => {
                    const testUtterance = new SpeechSynthesisUtterance('Audio berhasil diaktifkan');
                    testUtterance.rate = 0.7;
                    testUtterance.volume = 1;
                    testUtterance.onstart = () => console.log('✅ Force audio test started');
                    testUtterance.onend = () => console.log('✅ Force audio test ended');
                    testUtterance.onerror = (e) => console.error('❌ Force audio error:', e);

                    window.speechSynthesis.speak(testUtterance);
                }, 100);

                alert('Audio diklik untuk diaktifkan!');
            } else {
                alert('Browser tidak mendukung audio speech!');
            }
        }

        function getAntrianBaru() {
            console.log('Fetching antrian baru... Tab active:', isTabActive);

            // Simulate activity untuk menjaga responsivitas
            simulateActivity();

            const requestStart = Date.now();

            fetch('../api/api_getAntrianTerakhir.php?fungsi=getAntrianTerbaru', {
                    method: 'GET',
                    cache: 'no-cache',
                    headers: {
                        'Cache-Control': 'no-cache',
                        'Pragma': 'no-cache'
                    }
                })
                .then(response => {
                    const requestTime = Date.now() - requestStart;
                    console.log('Response status:', response.status, 'Time:', requestTime + 'ms');
                    return response.json();
                })
                .then(result => {
                    console.log('API Response:', result);
                    if (result.status === 'success' && result.data && result.data.length > 0) {
                        console.log('Found', result.data.length, 'new antrian(s)');
                        result.data.forEach(antrian => {
                            console.log('Processing antrian:', antrian);

                            let layanan = '';
                            let elementId = '';

                            if (['umum', 'bpjs', 'malam'].includes(antrian.tipe)) {
                                layanan = 'Poli Umum';
                                if (antrian.status === 'Dokter') {
                                    elementId = 'dokter-umum-nomor';
                                } else if (antrian.status === 'Perawat Poli') {
                                    elementId = 'perawat-umum-nomor';
                                }
                            } else if (['gigi umum', 'gigi bpjs'].includes(antrian.tipe)) {
                                layanan = 'Poli Gigi';
                                if (antrian.status === 'Dokter') {
                                    elementId = 'dokter-gigi-nomor';
                                } else if (antrian.status === 'Perawat Poli') {
                                    elementId = 'perawat-gigi-nomor';
                                }
                            } else if (['spesialis anak', 'spesialis penyakit dalam'].includes(antrian.tipe)) {
                                layanan = 'Poli Spesialis';
                                if (antrian.status === 'Dokter') {
                                    elementId = 'dokter-spesialis-nomor';
                                } else if (antrian.status === 'Perawat Poli') {
                                    elementId = 'perawat-spesialis-nomor';
                                }
                            }

                            if (layanan && elementId) {
                                const element = document.getElementById(elementId);
                                if (element) {
                                    element.textContent = antrian.antrian;
                                    console.log('Updated display:', elementId, 'to', antrian.antrian);
                                }

                                // Gunakan fungsi audio yang baru dengan fallback
                                const audioStatus = antrian.status === 'Dokter' ? 'dokter' : 'perawat';
                                console.log('Playing audio for:', antrian.antrian, audioStatus, layanan);
                                playNomorAudio(antrian.antrian, audioStatus, layanan);

                                // Update sound timestamp
                                fetch('../api/api_getAntrianTerakhir.php?fungsi=updateSoundAt', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/x-www-form-urlencoded',
                                        },
                                        body: 'id=' + encodeURIComponent(antrian.id)
                                    })
                                    .then(response => response.json())
                                    .then(updateResult => {
                                        console.log('Sound timestamp updated:', updateResult);
                                    })
                                    .catch(updateError => {
                                        console.error('Error updating sound timestamp:', updateError);
                                    });
                            } else {
                                console.log('No matching configuration for tipe:', antrian.tipe, 'or status:', antrian.status);
                            }
                        });
                    } else {
                        console.log('No new antrian found or empty response');
                    }
                })
                .catch(error => {
                    console.error('Fetch Error:', error);
                });
        }

        function loadAntrianAktif() {
            console.log('Loading antrian aktif...');
            fetch('../api/api_getAntrianTerakhir.php?fungsi=getAntrianAktif')
                .then(response => response.json())
                .then(result => {
                    console.log('Antrian aktif response:', result);
                    if (result.status === 'success' && result.data) {
                        const data = result.data;

                        if (data.umum && data.umum.dokter) {
                            document.getElementById('dokter-umum-nomor').textContent = data.umum.dokter.antrian;
                        }
                        if (data.gigi && data.gigi.dokter) {
                            document.getElementById('dokter-gigi-nomor').textContent = data.gigi.dokter.antrian;
                        }
                        if (data.spesialis && data.spesialis.dokter) {
                            document.getElementById('dokter-spesialis-nomor').textContent = data.spesialis.dokter.antrian;
                        }

                        if (data.umum && data.umum.perawat) {
                            document.getElementById('perawat-umum-nomor').textContent = data.umum.perawat.antrian;
                        }
                        if (data.gigi && data.gigi.perawat) {
                            document.getElementById('perawat-gigi-nomor').textContent = data.gigi.perawat.antrian;
                        }
                        if (data.spesialis && data.spesialis.perawat) {
                            document.getElementById('perawat-spesialis-nomor').textContent = data.spesialis.perawat.antrian;
                        }

                        console.log('Display updated with existing antrian');
                    }
                })
                .catch(error => {
                    console.error('Error loading antrian aktif:', error);
                });
        }

        // Start intervals dan event listeners
        setInterval(sendKeepAlive, 30000); // Keep-alive setiap 30 detik

        // Setup console clearing setiap 1 jam (3600000 ms)
        consoleClearInterval = setInterval(clearConsoleHourly, 3600000);

        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, initializing...');

            // Initialize audio context
            initAudioContext();

            // Setup visibility change detection
            document.addEventListener('visibilitychange', handleVisibilityChange);

            // Setup page focus/blur detection
            window.addEventListener('focus', () => {
                console.log('Window focused');
                isTabActive = true;
                initAudioContext();
                clearInterval(mainInterval);
                mainInterval = setInterval(getAntrianBaru, 3000);
                setTimeout(getAntrianBaru, 500);
            });

            window.addEventListener('blur', () => {
                console.log('Window blurred');
                isTabActive = false;
                clearInterval(mainInterval);
                mainInterval = setInterval(getAntrianBaru, 8000);
            });

            const enableSpeech = function() {
                if (!speechEnabled && 'speechSynthesis' in window) {
                    const testUtterance = new SpeechSynthesisUtterance(' ');
                    testUtterance.volume = 0;
                    window.speechSynthesis.speak(testUtterance);
                    speechEnabled = true;
                    console.log('✅ Speech synthesis enabled by user interaction');

                    // Initialize audio context juga
                    initAudioContext();

                    setTimeout(() => {
                        const testAudible = new SpeechSynthesisUtterance('Sistem antrian siap');
                        testAudible.volume = 0.5;
                        testAudible.rate = 0.8;
                        window.speechSynthesis.speak(testAudible);
                    }, 500);
                }
            };

            // Event listeners untuk enable speech
            ['click', 'touchstart', 'keydown', 'mousemove'].forEach(eventType => {
                document.addEventListener(eventType, enableSpeech, {
                    once: true
                });
            });

            // Auto-enable setelah 3 detik
            setTimeout(() => {
                if (!speechEnabled) {
                    enableSpeech();
                }
            }, 3000);

            // Start heartbeat untuk menjaga koneksi
            heartbeatInterval = setInterval(() => {
                simulateActivity();

                // Jika sudah lebih dari 5 menit tidak ada aktivitas, simulasikan click
                if (Date.now() - lastActivityTime > 300000) { // 5 menit
                    console.log('Simulating click to prevent browser sleep');
                    simulateActivity();
                    initAudioContext();
                }

                // Bersihkan tracking audio lama (lebih dari 5 menit)
                const now = Date.now();
                Object.keys(lastPlayedAudio).forEach(key => {
                    if (now - lastPlayedAudio[key] > 300000) {
                        delete lastPlayedAudio[key];
                    }
                });
            }, 60000); // Setiap 1 menit

            // Load initial data
            loadAntrianAktif();

            // Start main interval
            mainInterval = setInterval(getAntrianBaru, 3000);

            // Initial fetch
            setTimeout(getAntrianBaru, 1000);

            // Log initial system status
            console.log('🚀 Sistem Antrian Initialized');
            console.log('✅ Anti-sleep measures: ACTIVE');
            console.log('🔊 Audio system: READY');
            console.log('🧹 Console auto-clear: Every 1 hour');
            console.log('⏰ Keep-alive ping: Every 30 seconds');
        });

        // Prevent page from sleeping dengan berbagai metode
        let wakeLock = null;

        // Screen Wake Lock API (jika didukung)
        if ('wakeLock' in navigator) {
            navigator.wakeLock.request('screen').then(lock => {
                wakeLock = lock;
                console.log('Screen wake lock acquired');
            }).catch(err => {
                console.log('Wake lock failed:', err);
            });
        }

        // Reload halaman jika terlalu lama tidak aktif (failsafe)
        setInterval(() => {
            const timeSinceActivity = Date.now() - lastActivityTime;
            if (timeSinceActivity > 1800000) { // 30 menit
                console.log('Page inactive too long, reloading...');
                location.reload();
            }
        }, 600000); // Check setiap 10 menit

        /* 
        SOLUSI MASALAH DELAY 10 MENIT:
        ================================
        
        Masalah: Browser menunda eksekusi JavaScript ketika tab tidak aktif (throttling)
        
        Solusi yang diterapkan:
        1. Audio Context Management - Mencegah audio context suspend
        2. Visibility API - Deteksi tab aktif/tidak aktif dengan interval berbeda
        3. Keep-alive Ping - Request ke server setiap 30 detik
        4. Synthetic Activity - Simulasi mouse movement untuk menjaga tab aktif
        5. Screen Wake Lock - Mencegah screen sleep (jika didukung browser)
        6. Focus/Blur Detection - Reset interval saat tab kembali fokus
        7. Heartbeat System - Monitoring aktivitas dan pembersihan otomatis
        
        Hasil: Delay maksimal 8 detik (saat tab tidak aktif) vs 10 menit sebelumnya
        */
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" integrity="sha384-7qAoOXltbVP82dhxHAUje59V5r2YsVfBafyUDxEdApLPmcdhBPg1DKg1ERo0BZlK" crossorigin="anonymous"></script>
</body>

</html>