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
        <?php foreach ($board as $index => $petak): 
            $iconClass = 'fa-star';
            if ($petak['tipe'] === 'properti') $iconClass = 'fa-building';
            else if ($petak['tipe'] === 'dana_umum') $iconClass = 'fa-gift';
            else if ($petak['tipe'] === 'kesempatan') $iconClass = 'fa-question';
            else if ($petak['tipe'] === 'pajak') $iconClass = 'fa-money-bill-wave';
            else if ($petak['tipe'] === 'bandara') $iconClass = 'fa-plane';
            else if ($petak['tipe'] === 'utilitas') $iconClass = 'fa-lightbulb';
        ?>
            <div class="tile tile-<?= $index ?> <?= $petak['tipe'] ?>" id="tile-<?= $index ?>" onclick="handleTileClick(<?= $index ?>)" style="background-image: url('/public/images/tiles/<?= $index ?>.png');">
                <i class="fa-solid <?= $iconClass ?> fallback-icon"></i>
                <div class="tile-overlay"></div>
                <div class="house-indicator" id="houses-<?= $index ?>"></div>
                <?php if(isset($petak['grup'])): ?>
                    <div class="tile-color-bar" style="background-color: <?= $petak['grup'] ?>"></div>
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

            <!-- Card Decks -->
            <div class="card-decks">
                <div class="deck deck-chance" onclick="Swal.fire('Info', 'Kartu Kesempatan akan terbuka otomatis jika Anda mendarat di petaknya.', 'info')">
                    <i class="fa-solid fa-question"></i><span>KESEMPATAN</span>
                </div>
                <div class="deck deck-stats" onclick="Swal.fire('Info', 'Klik tanah kosong di papan untuk membelinya (Akan menarik kartu Statistika).', 'info')">
                    <i class="fa-solid fa-brain"></i><span>STATISTIKA</span>
                </div>
                <div class="deck deck-chest" onclick="Swal.fire('Info', 'Kartu Dana Umum akan terbuka otomatis jika Anda mendarat di petaknya.', 'info')">
                    <i class="fa-solid fa-gift"></i><span>DANA UMUM</span>
                </div>
            </div>

            <!-- Action Panel -->
            <div class="action-panel">
                <!-- Stock Rumah -->
                <div class="stock-minimal" style="color: #10b981;">
                    <span class="stock-label">Rumah</span>
                    <i class="fa-solid fa-house"></i>
                    <span class="fs-4 fw-bold" id="stock-rumah">32</span>
                </div>

                <!-- Dice & Controls -->
                <div class="dice-area">
                    <div id="turn-indicator" class="turn-indicator-text fw-bold text-player-0 mb-1">GILIRAN PLAYER 1</div>
                    <i id="dice-css-icon" class="fa-solid fa-dice-one text-secondary dice-icon-main"></i><br>
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
                <div class="stock-minimal" style="color: #ef4444;">
                    <span class="stock-label">Hotel</span>
                    <i class="fa-solid fa-building"></i>
                    <span class="fs-4 fw-bold" id="stock-hotel">12</span>
                </div>
            </div>

            <!-- Bank Mascot -->
            <div class="bank-mascot" onclick="Swal.fire({title:'🐷 Pak Bankir', html:'<div style=\'font-size:0.95rem; color:#555;\'>Selamat datang di Bank Monika!<br><br>💰 Mulai dengan <b>Rp 3.000.000</b><br>🎲 Lewati START = <b>+Rp 200.000</b><br>⭐ 3 bintang = masuk <b>Penjara</b><br>🎲 Dadu 6 = bebas dari penjara!</div>',icon:\'info\',confirmButtonText:\'Mengerti!\'})">
                <div class="mascot-body">
                    <div class="mascot-hat"></div>
                    <i class="fa-solid fa-piggy-bank mascot-icon"></i>
                    <div class="mascot-name">Pak Bankir</div>
                    <div class="mascot-coins">
                        <span>💰</span><span>💰</span><span>💰</span>
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
