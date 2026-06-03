<?php
session_start();

@include 'config.php';
@include 'questions.php'; 
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MONIKA — Monopoli Statistika</title>
    <meta name="description" content="Game Monopoli Statistika Edukatif — Belajar statistika sambil bermain!">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="../src/styles/main.css?v=<?= time() ?>">
</head>
<body>

<!-- Cinematic Game Background Overlay -->
<div class="game-bg-overlay">
    <div class="bg-orb orb-1"></div>
    <div class="bg-orb orb-2"></div>
    <div class="bg-orb orb-3"></div>
    <div class="bg-grid-lines"></div>
</div>

<script>
    const BOARD = <?= json_encode($board) ?>;
    const QUESTIONS = <?= json_encode($questions) ?>;
    const ACTION_COUNTDOWN = <?= $actionCountdown ?>;
</script>
<!-- ===== INTRO / SPLASH SCREEN ===== -->
<div id="intro-screen" class="intro-screen">
    <!-- Cinematic Visual Background Accents -->
    <div class="intro-glow-bg blur-1"></div>
    <div class="intro-glow-bg blur-2"></div>
    <div class="intro-glow-bg blur-3"></div>
    <div class="intro-grid-pattern"></div>
    
    <!-- Floating Stats Pillars for Gaming Vibe -->
    <div class="intro-floating-charts">
        <div class="chart-bar bar-1"></div>
        <div class="chart-bar bar-2"></div>
        <div class="chart-bar bar-3"></div>
        <div class="chart-bar bar-4"></div>
        <div class="chart-bar bar-5"></div>
    </div>

    <!-- Phase 1: Splash Welcome Card -->
    <div id="intro-main-card" class="intro-card main-splash-card animate__animated animate__zoomIn">
        <!-- Premium Rotating Tech Logo -->
        <div class="intro-logo-container">
            <div class="logo-ring ring-outer"></div>
            <div class="logo-ring ring-middle"></div>
            <div class="logo-ring ring-inner"></div>
            <div class="intro-logo-icon">
                <i class="fa-solid fa-chart-simple"></i>
            </div>
        </div>
        
        <h1 class="intro-title">MONIKA</h1>
        <div class="intro-subtitle-capsule">MONOPOLI STATISTIKA</div>
        
        <div class="intro-divider-glow"></div>
        
        <div class="intro-creators-capsule">
            <span class="label">DEVELOPMENT TEAM</span>
            <span class="name">Kelompok 2 media pembelajaran kelas A</span>
        </div>
        
        <button id="btn-start-game" class="intro-start-btn" onclick="startGameWithIntro()">
            <span>🚀 MULAI PERMAINAN</span>
            <div class="btn-ripple"></div>
        </button>
    </div>

    <!-- Phase 2: Rules Booklet Card -->
    <div id="intro-rules-card" class="intro-card rules-card d-none animate__animated">
        <h2 class="rules-title"><i class="fa-solid fa-book-open text-primary me-2"></i>BUKU PANDUAN MONIKA</h2>
        <div class="rules-tabs-container">
            <button class="rules-tab-btn active" onclick="switchRulesTab(0)">📘 Aturan Dasar</button>
            <button class="rules-tab-btn" onclick="switchRulesTab(1)">⭐ Kuis & Bintang</button>
            <button class="rules-tab-btn" onclick="switchRulesTab(2)">🃏 Petak Khusus</button>
        </div>
        <div class="rules-content-box">
            <!-- Tab 0 content -->
            <div id="rules-tab-content-0" class="rules-tab-content">
                <h5><i class="fa-solid fa-scroll text-primary me-2"></i>Aturan Dasar</h5>
                <ol class="ps-3 mb-0" style="line-height: 1.6;">
                    <li class="mb-2">Lempar dadu dan maju sesuai angka.</li>
                    <li class="mb-2">Jika berhenti di petak kuis, jawab soal yang diberikan.</li>
                    <li class="mb-2">Jawaban benar mendapat <strong class="text-primary">1 Bintang Biru <i class="fa-solid fa-star text-primary"></i></strong>.</li>
                    <li class="mb-2">Jawaban salah mendapat <strong class="text-danger">1 Bintang Merah <i class="fa-solid fa-star text-danger"></i></strong>.</li>
                    <li class="mb-2">Permainan dibatasi durasi maksimal 15 menit.</li>
                    <li class="mb-2">Pemenang ditentukan berdasarkan skor tertinggi: <strong class="text-primary">Bintang Biru</strong> dikurangi <strong class="text-danger">Bintang Merah</strong>.</li>
                </ol>
            </div>
            <!-- Tab 1 content -->
            <div id="rules-tab-content-1" class="rules-tab-content d-none">
                <h5><i class="fa-solid fa-star text-warning me-2"></i>Kuis dan Bintang ⭐</h5>
                <p>Setiap petak kuis berisikan kode soal dan tingkat kesulitan (ditunjukkan dengan jumlah bintang pada petak).</p>
                <div class="mb-3">
                    <strong class="text-success"><i class="fa-solid fa-circle-check me-1"></i> Jawaban Benar:</strong>
                    <p class="mb-1 ms-3">Pemain memperoleh <strong>1 Bintang Biru</strong>.</p>
                </div>
                <div>
                    <strong class="text-danger"><i class="fa-solid fa-circle-xmark me-1"></i> Jawaban Salah:</strong>
                    <p class="mb-0 ms-3">Pemain memperoleh <strong>1 Bintang Merah</strong>.</p>
                </div>
            </div>
            <!-- Tab 2 content -->
            <div id="rules-tab-content-2" class="rules-tab-content d-none">
                <h5><i class="fa-solid fa-layer-group text-info me-2"></i>Petak Khusus</h5>
                <div class="mb-2">
                    <h6 class="fw-bold text-success mb-1">🌸 Taman Bunga (Petak 10)</h6>
                    <p class="mb-2 ms-2">Bebas kuis! Pemain langsung membuka satu Kartu Taman Bunga yang berisi efek menguntungkan.</p>
                </div>
                <div class="mb-2">
                    <h6 class="fw-bold text-danger mb-1">🌪️ Bencana Alam (Petak 30)</h6>
                    <p class="mb-2 ms-2">Bebas kuis! Pemain langsung membuka satu Kartu Bencana Alam yang berisi efek merugikan.</p>
                </div>
                <div>
                    <h6 class="fw-bold text-warning mb-1">🗺️ Kesempatan (Petak 20)</h6>
                    <p class="mb-0 ms-2">Pemain berhak berteleportasi bebas ke petak soal mana pun dan wajib menjawab soal di petak tujuan tersebut.</p>
                </div>
            </div>
        </div>
        <button id="btn-confirm-rules" class="intro-confirm-btn" onclick="confirmRulesAndStartCountdown()">
            🎮 SAYA SIAP BERMAIN!
        </button>
    </div>
</div>

<!-- ===== DRAMATIC COUNTDOWN OVERLAY ===== -->
<div id="countdown-overlay" class="countdown-overlay d-none">
    <div class="countdown-ring"></div>
    <div id="countdown-number" class="countdown-number">3</div>
    <div id="countdown-text" class="countdown-text">Mempersiapkan Papan Permainan...</div>
</div>

<div class="app-container">
    <div class="game-wrapper">



    <!-- ===== MONOPOLY BOARD ===== -->
    <div class="monopoly-board" id="board">
        <?php 
        // Emoji map for each tile
        $tileEmojis = [
            0 => '🚀', // START
            1 => 'fa-solid fa-house-chimney text-warning', // Properti Coklat
            2 => 'fa-solid fa-wind text-info', // Peristiwa Alam
            3 => 'fa-solid fa-house text-warning', // Properti Coklat
            4 => 'fa-solid fa-scale-balanced text-danger', // Pajak
            5 => 'fa-solid fa-train-subway text-secondary', // Bandara/Stasiun
            6 => 'fa-solid fa-leaf text-success', // Properti Teal
            7 => 'fa-solid fa-clover text-success', // Taman Bunga
            8 => 'fa-solid fa-graduation-cap text-primary', // Properti Teal
            9 => 'fa-solid fa-industry text-secondary', // Properti Teal
            10 => '⛓️', // Penjara
            11 => 'fa-solid fa-shrimp text-danger', // Properti Pink
            12 => 'fa-solid fa-book-open text-info', // Properti Pink
            13 => 'fa-solid fa-mountain text-secondary', // Properti Pink
            14 => 'fa-solid fa-masks-theater text-warning', // Properti Rose
            15 => 'fa-solid fa-plane text-primary', // Bandara
            16 => 'fa-solid fa-torii-gate text-danger', // Properti Rose
            17 => 'fa-solid fa-wind text-info', // Peristiwa Alam
            18 => 'fa-solid fa-monument text-secondary', // Properti Rose
            19 => 'fa-solid fa-volcano text-danger', // Properti Merah
            20 => '🅿️', // Bebas Parkir
            21 => 'fa-solid fa-fish text-primary', // Properti Merah
            22 => 'fa-solid fa-clover text-success', // Taman Bunga
            23 => 'fa-solid fa-umbrella-beach text-warning', // Properti Merah
            24 => 'fa-solid fa-tree text-success', // Properti Lime
            25 => 'fa-solid fa-plane text-primary', // Bandara
            26 => 'fa-solid fa-ship text-info', // Properti Lime
            27 => 'fa-solid fa-otter text-secondary', // Properti Lime
            28 => 'fa-solid fa-laptop text-primary', // Properti Hijau
            29 => 'fa-solid fa-screwdriver-wrench text-secondary', // Properti Hijau
            30 => '🚨', // Masuk Penjara
            31 => 'fa-solid fa-droplet text-info', // Properti Hijau
            32 => 'fa-solid fa-water text-primary', // Properti Violet
            33 => 'fa-solid fa-wind text-info', // Peristiwa Alam
            34 => 'fa-solid fa-shapes text-warning', // Properti Violet
            35 => 'fa-solid fa-plane text-primary', // Bandara
            36 => 'fa-solid fa-clover text-success', // Taman Bunga
            37 => 'fa-solid fa-bridge text-secondary', // Properti Violet
            38 => 'fa-solid fa-building-columns text-danger', // Pajak/Bank
            39 => 'fa-solid fa-mosque text-warning', // Properti Coklat
        ];
        
        $tileQuizConfig = [
            1 => ['cat' => 'R', 'level' => 1],
            2 => ['cat' => 'M', 'level' => 1],
            3 => ['cat' => 'J', 'level' => 2],
            4 => ['cat' => 'D', 'level' => 2],
            5 => ['cat' => 'R', 'level' => 1],
            6 => ['cat' => 'K', 'level' => 2],
            7 => ['cat' => 'J', 'level' => 1],
            8 => ['cat' => 'D', 'level' => 2],
            9 => ['cat' => 'M', 'level' => 1],
            // 10: Special (Taman Bunga)
            11 => ['cat' => 'R', 'level' => 2],
            12 => ['cat' => 'K', 'level' => 3],
            13 => ['cat' => 'M', 'level' => 2],
            14 => ['cat' => 'D', 'level' => 4],
            15 => ['cat' => 'J', 'level' => 3],
            16 => ['cat' => 'R', 'level' => 2],
            17 => ['cat' => 'M', 'level' => 4],
            18 => ['cat' => 'D', 'level' => 3],
            19 => ['cat' => 'K', 'level' => 2],
            // 20: No kuis
            21 => ['cat' => 'M', 'level' => 3],
            22 => ['cat' => 'J', 'level' => 2],
            23 => ['cat' => 'K', 'level' => 1],
            24 => ['cat' => 'D', 'level' => 2],
            25 => ['cat' => 'M', 'level' => 2],
            26 => ['cat' => 'J', 'level' => 2],
            27 => ['cat' => 'R', 'level' => 2],
            28 => ['cat' => 'J', 'level' => 3],
            29 => ['cat' => 'K', 'level' => 2],
            // 30: Special (Peristiwa Alam)
            31 => ['cat' => 'M', 'level' => 2],
            32 => ['cat' => 'D', 'level' => 3],
            33 => ['cat' => 'R', 'level' => 3],
            34 => ['cat' => 'K', 'level' => 4],
            35 => ['cat' => 'M', 'level' => 3],
            36 => ['cat' => 'R', 'level' => 4],
            37 => ['cat' => 'D', 'level' => 1],
            38 => ['cat' => 'J', 'level' => 4],
            39 => ['cat' => 'K', 'level' => 1]
        ];


        foreach ($board as $index => $petak): 
            // Determine side
            if ($index >= 1 && $index <= 9) $side = 'bottom';
            else if ($index >= 11 && $index <= 19) $side = 'left';
            else if ($index >= 21 && $index <= 29) $side = 'top';
            else if ($index >= 31 && $index <= 39) $side = 'right';
            else $side = 'corner';
            
            $barColor = isset($petak['grup']) ? $petak['grup'] : '#e8e8eb';
            
            // Set emoji/icon for special corner tiles
            $emoji = '';
            if ($index === 0) $emoji = '🚀';
            else if ($index === 10) $emoji = '🌸';
            else if ($index === 20) $emoji = '🗺️';
            else if ($index === 30) $emoji = '🌪️';
        ?>
            <div class="tile tile-<?= $index ?> <?= $petak['tipe'] ?> side-<?= $side ?>" id="tile-<?= $index ?>" data-side="<?= $side ?>" onclick="handleTileClick(<?= $index ?>)" style="--tile-color: <?= $barColor ?>;">
                <div class="tile-overlay"></div>
                <?php if ($side === 'corner'): ?>
                    <div class="tile-emoji" style="font-size:2.2rem; margin-bottom: 4px;"><?= $emoji ?></div>
                    <div class="tile-name" style="font-weight:900; font-size:0.65rem; color:#1e293b; text-transform:uppercase;"><?= $petak['nama'] ?></div>
                <?php else: ?>
                    <div class="tile-color-bar" style="background-color: <?= $barColor ?>"></div>
                    <?php if (isset($tileQuizConfig[$index])): ?>
                        <div class="tile-quiz-container">
                            <div class="tile-quiz-code">
                                <?= $tileQuizConfig[$index]['cat'] . $tileQuizConfig[$index]['level'] ?>
                            </div>
                            <div class="tile-quiz-stars">
                                <?php for ($s = 0; $s < $tileQuizConfig[$index]['level']; $s++): ?>
                                    <i class="fa-solid fa-star"></i>
                                <?php endfor; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

        <!-- ===== CENTER BOARD ===== -->
        <div class="center-board">
            <!-- Decorative Background -->
            <div class="cb-bg-pattern"></div>
            <div class="cb-corner-ornament cb-tl"></div>
            <div class="cb-corner-ornament cb-tr"></div>
            <div class="cb-corner-ornament cb-bl"></div>
            <div class="cb-corner-ornament cb-br"></div>



            <!-- Card Decks -->
            <div class="cb-card-decks">
                <div class="cb-deck cb-deck-chance" onclick="Swal.fire('Info', 'Kartu Taman Bunga akan terbuka otomatis jika Anda mendarat di petaknya.', 'info')">
                    <div class="cb-deck-glow"></div>
                    <div class="cb-deck-icon"><i class="fa-solid fa-leaf"></i></div>
                    <span class="cb-deck-label">TAMAN BUNGA</span>
                </div>
                <div class="cb-deck cb-deck-chest" onclick="Swal.fire('Info', 'Kartu Peristiwa Alam akan terbuka otomatis jika Anda mendarat di petaknya.', 'info')">
                    <div class="cb-deck-glow"></div>
                    <div class="cb-deck-icon"><i class="fa-solid fa-bolt"></i></div>
                    <span class="cb-deck-label">PERISTIWA ALAM</span>
                </div>
            </div>

            <!-- Control Center -->
            <div class="cb-control-center">
                <!-- Left Players: Player 1 & 2 -->
                <div class="cb-players-group cb-players-left">
                    <?php for($i=0; $i<2; $i++): ?>
                    <div id="ui-p<?= $i ?>" class="cb-player-box p-indicator-<?= $i ?>">
                        <div class="cb-player-header">
                            <span class="cb-player-dot bg-player-<?= $i ?>"></span>
                            <span class="cb-player-name">Player <?= $i+1 ?></span>
                            <span id="active-badge-<?= $i ?>" class="active-turn-badge d-none">GILIRAN</span>
                        </div>
                        <div class="cb-player-stats">
                            <span id="blue-stars-p<?= $i ?>" title="Bintang Biru"></span>
                            <span id="stars-p<?= $i ?>" title="Bintang Merah"></span>
                            <span id="net-p<?= $i ?>" class="cb-net-score" title="Skor Bersih">0</span>
                        </div>
                    </div>
                    <?php endfor; ?>
                </div>

                <!-- Dice & Turn Area -->
                <div class="cb-dice-area">
                    <div id="turn-indicator" class="cb-turn-indicator fw-bold text-player-0">GILIRAN PLAYER 1</div>
                    <div class="cb-dice-display">
                        <i id="dice-css-icon" class="fa-solid fa-dice-one cb-dice-icon"></i>
                    </div>
                    <button id="btn-roll" class="btn-action btn-roll" onclick="processTurn()">🎲 KOCOK DADU (60s)</button>
                    <button id="btn-end" class="btn-action btn-end d-none" onclick="promptEndTurn()">✅ AKHIRI GILIRAN</button>
                    <button id="btn-bankrupt" class="btn-action btn-bankrupt d-none" onclick="declareBankrupt()">💸 BANGKRUT</button>
                    <!-- Action Phase Timer -->
                    <div id="action-timer-container" class="action-timer-container d-none">
                        <span id="action-timer-text" class="action-timer-text">⏱️ 1:00</span>
                        <div class="action-timer-bar">
                            <div id="action-timer-progress" class="action-timer-progress"></div>
                        </div>
                    </div>
                </div>

                <!-- Right Players: Player 3 & 4 -->
                <div class="cb-players-group cb-players-right">
                    <?php for($i=2; $i<4; $i++): ?>
                    <div id="ui-p<?= $i ?>" class="cb-player-box p-indicator-<?= $i ?>">
                        <div class="cb-player-header">
                            <span class="cb-player-dot bg-player-<?= $i ?>"></span>
                            <span class="cb-player-name">Player <?= $i+1 ?></span>
                            <span id="active-badge-<?= $i ?>" class="active-turn-badge d-none">GILIRAN</span>
                        </div>
                        <div class="cb-player-stats">
                            <span id="blue-stars-p<?= $i ?>" title="Bintang Biru"></span>
                            <span id="stars-p<?= $i ?>" title="Bintang Merah"></span>
                            <span id="net-p<?= $i ?>" class="cb-net-score" title="Skor Bersih">0</span>
                        </div>
                    </div>
                    <?php endfor; ?>
                </div>
            </div>

            <!-- ===== CENTER BOARD DASHBOARD ===== -->
            <div class="cb-dashboard">
                <div class="cb-dashboard-timer" title="Durasi Permainan">
                    <i class="fa-solid fa-clock"></i>
                    <span id="game-duration">00:00:00</span>
                </div>
                <div class="cb-dashboard-divider"></div>
                <div class="cb-dashboard-actions">
                    <button id="btn-sound-toggle" class="cb-dashboard-btn" onclick="toggleMute()" title="Mute/Unmute Suara">
                        <i class="fa-solid fa-volume-high"></i>
                    </button>
                    <button class="cb-dashboard-btn" onclick="showHelpModal()" title="Bantuan & Aturan Main">
                        <i class="fa-solid fa-book-open"></i> Aturan
                    </button>
                    <button class="cb-dashboard-btn" onclick="Swal.fire({title:'⭐ Sistem Poin', html:'<div style=\'font-size:0.95rem; color:#f1f5f9; text-align:left; line-height: 1.6;\'><ul style=\'padding-left:16px; margin:0;\'><li>✔️ <b>Jawaban Benar:</b> Mendapatkan 1 Bintang Biru</li><li>❌ <b>Jawaban Salah:</b> Mendapatkan 1 Bintang Merah</li><li>⏱️ <b>Durasi Game:</b> Maksimal 15 Menit</li><li>🏆 <b>Skor Akhir:</b> Bintang Biru - Bintang Merah</li></ul></div>',icon:'info',confirmButtonText:'Mengerti!',customClass:{popup:'swal-card-stats'}})" title="Informasi Sistem Poin">
                        <i class="fa-solid fa-circle-info"></i> Sistem Poin
                    </button>
                </div>
            </div>

        </div>

        <!-- Pawns -->
        <div id="pawn-0" class="pawn-container text-player-0"><i class="fa-solid fa-chess-pawn"></i></div>
        <div id="pawn-1" class="pawn-container text-player-1"><i class="fa-solid fa-chess-knight"></i></div>
        <div id="pawn-2" class="pawn-container text-player-2"><i class="fa-solid fa-chess-rook"></i></div>
        <div id="pawn-3" class="pawn-container text-player-3"><i class="fa-solid fa-chess-queen"></i></div>
    </div>


</div>
</div>



<!-- ===== HELP MODAL ===== -->
<div id="help-modal" class="custom-modal-overlay d-none" onclick="closeHelpModalOnOuterClick(event)">
    <div class="custom-modal-content">
        <div class="custom-modal-header">
            <h3><i class="fa-solid fa-book-open me-2"></i>Panduan & Aturan Main MONIKA</h3>
            <button class="modal-close-btn" onclick="hideHelpModal()">&times;</button>
        </div>
        <div class="custom-modal-body">
            <!-- Aturan Dasar -->
            <div class="rule-section">
                <h5><i class="fa-solid fa-scroll text-primary me-2"></i>Aturan Dasar</h5>
                <ol class="ps-3 mb-0" style="line-height: 1.6;">
                    <li class="mb-1">Lempar dadu dan maju sesuai angka.</li>
                    <li class="mb-1">Jika berhenti di petak kuis, jawab soal yang diberikan.</li>
                    <li class="mb-1">Jawaban benar mendapat <strong class="text-primary">1 Bintang Biru <i class="fa-solid fa-star text-primary"></i></strong>.</li>
                    <li class="mb-1">Jawaban salah mendapat <strong class="text-danger">1 Bintang Merah <i class="fa-solid fa-star text-danger"></i></strong>.</li>
                    <li class="mb-1">Permainan dibatasi durasi maksimal 15 menit.</li>
                    <li class="mb-1">Pemenang ditentukan berdasarkan skor tertinggi: <strong class="text-primary">Bintang Biru</strong> dikurangi <strong class="text-danger">Bintang Merah</strong>.</li>
                </ol>
            </div>
            
            <!-- Kuis & Bintang -->
            <div class="rule-section">
                <h5><i class="fa-solid fa-star text-warning me-2"></i>Kuis dan Bintang ⭐</h5>
                <p class="mb-2">Setiap petak kuis berisikan kode soal dan tingkat kesulitan (mudah ⭐ hingga sangat sulit ⭐⭐⭐⭐).</p>
                <div class="mb-2 ms-2">
                    <strong class="text-success"><i class="fa-solid fa-circle-check me-1"></i> Jawaban Benar:</strong>
                    <p class="mb-1 ms-3">Pemain memperoleh <strong>1 Bintang Biru</strong>.</p>
                </div>
                <div class="ms-2">
                    <strong class="text-danger"><i class="fa-solid fa-circle-xmark me-1"></i> Jawaban Salah:</strong>
                    <p class="mb-0 ms-3">Pemain memperoleh <strong>1 Bintang Merah</strong>. Tidak ada denda uang atau hukuman diskualifikasi.</p>
                </div>
            </div>

            <!-- Petak Khusus -->
            <div class="rule-section">
                <h5><i class="fa-solid fa-layer-group text-info me-2"></i>Petak Khusus</h5>
                <div class="mb-2">
                    <h6 class="fw-bold text-success mb-1">🌸 Taman Bunga (Petak 10)</h6>
                    <p class="mb-1 ms-2">Bebas kuis! Pemain langsung membuka satu Kartu Taman Bunga dengan efek menguntungkan.</p>
                </div>
                <div class="mb-2">
                    <h6 class="fw-bold text-danger mb-1">🌪️ Bencana Alam (Petak 30)</h6>
                    <p class="mb-1 ms-2">Bebas kuis! Pemain langsung membuka satu Kartu Bencana Alam dengan efek merugikan.</p>
                </div>
                <div>
                    <h6 class="fw-bold text-warning mb-1">🗺️ Kesempatan (Petak 20)</h6>
                    <p class="mb-0 ms-2">Pemain berhak berteleportasi bebas ke petak soal mana pun dan wajib menjawab soal di petak tujuan tersebut.</p>
                </div>
            </div>
        </div>
        <div class="custom-modal-footer">
            <button class="btn btn-primary" onclick="hideHelpModal()">Saya Mengerti, Mulai Main!</button>
        </div>
    </div>
</div>

<script src="../src/script/state.js?v=<?= time() ?>"></script>
<script src="../src/script/ui.js?v=<?= time() ?>"></script>
<script src="../src/script/cards.js?v=<?= time() ?>"></script>
<script src="../src/script/turn.js?v=<?= time() ?>"></script>
<script src="../src/script/main.js?v=<?= time() ?>"></script>

</body>
</html>
