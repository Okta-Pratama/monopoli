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
    <link rel="stylesheet" href="/src/styles/main.css">
</head>
<body>

<script>
    const BOARD = <?= json_encode($board) ?>;
    const QUESTIONS = <?= json_encode($questions) ?>;
</script>

<div class="game-wrapper">

    <!-- ===== LEFT PANEL: Player 1 & 2 ===== -->
    <div class="side-panel left-panel">
        <div class="players-container">
            <?php for($i=0; $i<2; $i++): ?>
            <div id="ui-p<?= $i ?>" class="player-section p-indicator-<?= $i ?>">
                <div class="player-watermark">
                    <i class="fa-solid <?= ['fa-chess-pawn', 'fa-chess-knight', 'fa-chess-rook', 'fa-chess-queen'][$i] ?>"></i>
                </div>
                <div class="player-header">
                    <div class="player-icon bg-player-<?= $i ?>">
                        <i class="fa-solid <?= ['fa-chess-pawn', 'fa-chess-knight', 'fa-chess-rook', 'fa-chess-queen'][$i] ?>"></i>
                    </div>
                    <div class="player-info">
                        <div class="player-name-row">
                            <span class="player-name">Player <?= $i+1 ?></span>
                            <span id="active-badge-<?= $i ?>" class="active-turn-badge d-none">GILIRAN!</span>
                            <span id="jail-badge-<?= $i ?>" class="jail-badge d-none"><i class="fa-solid fa-bars-staggered"></i> JAIL</span>
                        </div>
                        <div class="player-money" id="money-p<?= $i ?>">Rp 3.000.000</div>
                        <div class="star-indicator" id="stars-p<?= $i ?>">
                            <span class="star-empty">☆</span>
                            <span class="star-empty">☆</span>
                            <span class="star-empty">☆</span>
                        </div>
                    </div>
                </div>
                <div class="inventory-flat" id="inv-p<?= $i ?>"></div>
            </div>
            <?php endfor; ?>
        </div>
    </div>

    <!-- ===== MONOPOLY BOARD ===== -->
    <div class="monopoly-board" id="board">
        <?php 
        // Emoji map for each tile
        $tileEmojis = [
            0 => '🚀', // START
            1 => '🏘️', 2 => '💎', 3 => '🏡', 4 => '🚔',
            5 => '🚂', 6 => '🌿', 7 => '❓', 8 => '🎓', 9 => '🏭',
            10 => '⛓️', // Penjara
            11 => '🦐', 12 => '📚', 13 => '🏔️', 14 => '🎭',
            15 => '✈️', 16 => '⛩️', 17 => '💎', 18 => '🏛️', 19 => '🌋',
            20 => '🅿️', // Bebas Parkir
            21 => '🦈', 22 => '❓', 23 => '🏖️', 24 => '🌺',
            25 => '✈️', 26 => '⛵', 27 => '🐠', 28 => '💻', 29 => '⛏️',
            30 => '🚨', // Masuk Penjara
            31 => '🛢️', 32 => '🌊', 33 => '💎', 34 => '💠',
            35 => '✈️', 36 => '❓', 37 => '🌉', 38 => '🏦', 39 => '🕌',
        ];
        
        foreach ($board as $index => $petak): 
            $iconClass = 'fa-star';
            if ($petak['tipe'] === 'properti') $iconClass = 'fa-building';
            else if ($petak['tipe'] === 'dana_umum') $iconClass = 'fa-gift';
            else if ($petak['tipe'] === 'kesempatan') $iconClass = 'fa-question';
            else if ($petak['tipe'] === 'pajak') $iconClass = 'fa-money-bill-wave';
            else if ($petak['tipe'] === 'bandara') $iconClass = 'fa-plane';
            else if ($petak['tipe'] === 'utilitas') $iconClass = 'fa-lightbulb';
            
            // Determine which side of the board the tile is on
            if ($index >= 1 && $index <= 9) $side = 'bottom';
            else if ($index >= 11 && $index <= 19) $side = 'left';
            else if ($index >= 21 && $index <= 29) $side = 'top';
            else if ($index >= 31 && $index <= 39) $side = 'right';
            else $side = 'corner';
            
            // Determine color bar color for non-property tiles
            $barColor = '';
            if (isset($petak['grup'])) {
                $barColor = $petak['grup'];
            } else {
                switch($petak['tipe']) {
                    case 'dana_umum': $barColor = '#3b82f6'; break;
                    case 'kesempatan': $barColor = '#f59e0b'; break;
                    case 'pajak': $barColor = '#ef4444'; break;
                    case 'bandara': $barColor = '#64748b'; break;
                    case 'utilitas': $barColor = '#8b5cf6'; break;
                }
            }
            
            $emoji = $tileEmojis[$index] ?? '🏠';
        ?>
            <div class="tile tile-<?= $index ?> <?= $petak['tipe'] ?> side-<?= $side ?>" id="tile-<?= $index ?>" data-side="<?= $side ?>" onclick="handleTileClick(<?= $index ?>)" style="--tile-color: <?= $barColor ?: '#94a3b8' ?>;">
                <i class="fa-solid <?= $iconClass ?> fallback-icon"></i>
                <div class="tile-overlay"></div>
                <div class="tile-emoji"><?= $emoji ?></div>
                <div class="house-indicator" id="houses-<?= $index ?>"></div>
                <?php if($barColor): ?>
                    <div class="tile-color-bar" style="background-color: <?= $barColor ?>"></div>
                <?php endif; ?>
                <div class="tile-name">
                    <?= $petak['nama'] ?>
                </div>
                <?php if($petak['harga'] > 0): ?>
                    <div class="tile-price"><?= formatRp($petak['harga']) ?></div>
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

            <!-- Game Title -->
            <div class="cb-title-section">
                <div class="cb-title-frame">
                    <div class="cb-title-badge">📊</div>
                    <h1 class="cb-title">MONIKA</h1>
                    <p class="cb-subtitle">Monopoli Statistika</p>
                </div>
            </div>

            <!-- Card Decks -->
            <div class="cb-card-decks">
                <div class="cb-deck cb-deck-chance" onclick="Swal.fire('Info', 'Kartu Kesempatan akan terbuka otomatis jika Anda mendarat di petaknya.', 'info')">
                    <div class="cb-deck-glow"></div>
                    <div class="cb-deck-icon"><i class="fa-solid fa-question"></i></div>
                    <span class="cb-deck-label">KESEMPATAN</span>
                </div>
                <div class="cb-deck cb-deck-stats" onclick="Swal.fire('Info', 'Klik tanah kosong di papan untuk membelinya (Akan menarik kartu Statistika).', 'info')">
                    <div class="cb-deck-glow"></div>
                    <div class="cb-deck-icon"><i class="fa-solid fa-brain"></i></div>
                    <span class="cb-deck-label">STATISTIKA</span>
                </div>
                <div class="cb-deck cb-deck-chest" onclick="Swal.fire('Info', 'Kartu Dana Umum akan terbuka otomatis jika Anda mendarat di petaknya.', 'info')">
                    <div class="cb-deck-glow"></div>
                    <div class="cb-deck-icon"><i class="fa-solid fa-gift"></i></div>
                    <span class="cb-deck-label">DANA UMUM</span>
                </div>
            </div>

            <!-- Control Center -->
            <div class="cb-control-center">
                <!-- Stock Rumah -->
                <div class="cb-stock cb-stock-house">
                    <div class="cb-stock-ring">
                        <i class="fa-solid fa-house"></i>
                    </div>
                    <span class="cb-stock-num" id="stock-rumah">32</span>
                    <span class="cb-stock-label">Rumah</span>
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

                <!-- Stock Hotel -->
                <div class="cb-stock cb-stock-hotel">
                    <div class="cb-stock-ring">
                        <i class="fa-solid fa-building"></i>
                    </div>
                    <span class="cb-stock-num" id="stock-hotel">12</span>
                    <span class="cb-stock-label">Hotel</span>
                </div>
            </div>

            <!-- Bank Mascot -->
            <div class="cb-bank" onclick="Swal.fire({title:'🐷 Pak Bankir', html:'<div style=\'font-size:0.95rem; color:#555;\'>Selamat datang di Bank Monika!<br><br>💰 Mulai dengan <b>Rp 3.000.000</b><br>🎲 Lewati START = <b>+Rp 200.000</b><br>⭐ 3 bintang = masuk <b>Penjara</b><br>🎲 Dadu 6 = bebas dari penjara!</div>',icon:'info',confirmButtonText:'Mengerti!'})">
                <div class="cb-bank-body">
                    <i class="fa-solid fa-piggy-bank cb-bank-icon"></i>
                    <div class="cb-bank-info">
                        <span class="cb-bank-name">Pak Bankir</span>
                        <span class="cb-bank-tagline">Klik untuk info 💰</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- Pawns -->
        <div id="pawn-0" class="pawn-container text-player-0"><i class="fa-solid fa-chess-pawn"></i></div>
        <div id="pawn-1" class="pawn-container text-player-1"><i class="fa-solid fa-chess-knight"></i></div>
        <div id="pawn-2" class="pawn-container text-player-2"><i class="fa-solid fa-chess-rook"></i></div>
        <div id="pawn-3" class="pawn-container text-player-3"><i class="fa-solid fa-chess-queen"></i></div>
    </div>

    <!-- ===== RIGHT PANEL: Player 3 & 4 ===== -->
    <div class="side-panel right-panel">
        <div class="players-container">
            <?php for($i=2; $i<4; $i++): ?>
            <div id="ui-p<?= $i ?>" class="player-section p-indicator-<?= $i ?>">
                <div class="player-watermark">
                    <i class="fa-solid <?= ['fa-chess-pawn', 'fa-chess-knight', 'fa-chess-rook', 'fa-chess-queen'][$i] ?>"></i>
                </div>
                <div class="player-header">
                    <div class="player-icon bg-player-<?= $i ?>">
                        <i class="fa-solid <?= ['fa-chess-pawn', 'fa-chess-knight', 'fa-chess-rook', 'fa-chess-queen'][$i] ?>"></i>
                    </div>
                    <div class="player-info">
                        <div class="player-name-row">
                            <span class="player-name">Player <?= $i+1 ?></span>
                            <span id="active-badge-<?= $i ?>" class="active-turn-badge d-none">GILIRAN!</span>
                            <span id="jail-badge-<?= $i ?>" class="jail-badge d-none"><i class="fa-solid fa-bars-staggered"></i> JAIL</span>
                        </div>
                        <div class="player-money" id="money-p<?= $i ?>">Rp 3.000.000</div>
                        <div class="star-indicator" id="stars-p<?= $i ?>">
                            <span class="star-empty">☆</span>
                            <span class="star-empty">☆</span>
                            <span class="star-empty">☆</span>
                        </div>
                    </div>
                </div>
                <div class="inventory-flat" id="inv-p<?= $i ?>"></div>
            </div>
            <?php endfor; ?>
        </div>
    </div>
</div>

<script src="/src/script/state.js"></script>
<script src="/src/script/ui.js"></script>
<script src="/src/script/cards.js"></script>
<script src="/src/script/property.js"></script>
<script src="/src/script/jail.js"></script>
<script src="/src/script/turn.js"></script>
<script src="/src/script/main.js"></script>

</body>
</html>
