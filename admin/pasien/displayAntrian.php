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
        function speakText(text) {
            console.log('Attempting to speak:', text);

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

                        // If no Indonesian voice, try English
                        // if (!selectedVoice) {
                        //     selectedVoice = voices.find(voice =>
                        //         voice.lang.toLowerCase().includes('en')
                        //     );
                        // }

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
            console.log('Fetching antrian baru...');
            fetch('../api/api_getAntrianTerakhir.php?fungsi=getAntrianTerbaru')
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.json();
                })
                .then(result => {
                    console.log('API Response:', result);
                    if (result.status === 'success' && result.data && result.data.length > 0) {
                        console.log('Found', result.data.length, 'new antrian(s)');
                        result.data.forEach(antrian => {
                            console.log('Processing antrian:', antrian);

                            let text = '';
                            let elementId = '';

                            if (['umum', 'bpjs', 'malam'].includes(antrian.tipe)) {
                                if (antrian.status === 'Dokter') {
                                    text = `Antrian Poli Umum nomor ${antrian.antrian}. Silahkan menuju ke ruang dokter umum`;
                                    elementId = 'dokter-umum-nomor';
                                } else if (antrian.status === 'Perawat Poli') {
                                    text = `Antrian Poli Umum nomor ${antrian.antrian}. Silahkan menuju ke tempat perawat poli`;
                                    elementId = 'perawat-umum-nomor';
                                }
                            } else if (['gigi umum', 'gigi bpjs'].includes(antrian.tipe)) {
                                if (antrian.status === 'Dokter') {
                                    text = `Antrian Poli Gigi nomor ${antrian.antrian}. Silahkan menuju ke ruang dokter gigi`;
                                    elementId = 'dokter-gigi-nomor';
                                } else if (antrian.status === 'Perawat Poli') {
                                    text = `Antrian Poli Gigi nomor ${antrian.antrian}. Silahkan menuju ke tempat perawat poli`;
                                    elementId = 'perawat-gigi-nomor';
                                }
                            } else if (['spesialis anak', 'spesialis penyakit dalam'].includes(antrian.tipe)) {
                                if (antrian.status === 'Dokter') {
                                    text = `Antrian Poli Spesialis nomor ${antrian.antrian}. Silahkan menuju ke ruang dokter spesialis`;
                                    elementId = 'dokter-spesialis-nomor';
                                } else if (antrian.status === 'Perawat Poli') {
                                    text = `Antrian Poli Spesialis nomor ${antrian.antrian}. Silahkan menuju ke tempat perawat poli`;
                                    elementId = 'perawat-spesialis-nomor';
                                }
                            }

                            if (text && elementId) {
                                const element = document.getElementById(elementId);
                                if (element) {
                                    element.textContent = antrian.antrian;
                                    console.log('Updated display:', elementId, 'to', antrian.antrian);
                                }

                                console.log('Playing sound:', text);
                                speakText(text);

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
                                console.log('No matching text for tipe:', antrian.tipe, 'or status:', antrian.status);
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

        setInterval(getAntrianBaru, 5000);

        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, initializing...');
            let speechEnabled = false;

            const enableSpeech = function() {
                if (!speechEnabled && 'speechSynthesis' in window) {
                    const testUtterance = new SpeechSynthesisUtterance(' ');
                    testUtterance.volume = 0;
                    window.speechSynthesis.speak(testUtterance);
                    speechEnabled = true;
                    console.log('✅ Speech synthesis enabled by user interaction');
                    setTimeout(() => {
                        const testAudible = new SpeechSynthesisUtterance('Sistem antrian siap');
                        testAudible.volume = 0.5;
                        testAudible.rate = 0.8;
                        window.speechSynthesis.speak(testAudible);
                    }, 500);
                }
            };

            ['click', 'touchstart', 'keydown'].forEach(eventType => {
                document.addEventListener(eventType, enableSpeech, {
                    once: true
                });
            });

            setTimeout(() => {
                if (!speechEnabled) {
                    enableSpeech();
                }
            }, 3000);

            loadAntrianAktif();
            getAntrianBaru();
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" integrity="sha384-7qAoOXltbVP82dhxHAUje59V5r2YsVfBafyUDxEdApLPmcdhBPg1DKg1ERo0BZlK" crossorigin="anonymous"></script>
</body>

</html>