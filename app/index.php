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
    <title>Monika</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
    <div class="side-panel left-panel">
        <div class="players-container">
            <?php for($i=0; $i<2; $i++): ?>
            <div id="ui-p<?= $i ?>" class="player-section p-indicator-<?= $i ?>">
                <div class="player-watermark">
                    <i class="fa-solid <?= ['fa-chess-pawn', 'fa-chess-knight', 'fa-chess-rook', 'fa-chess-queen'][$i] ?>"></i>
                </div>
                <div class="player-header">
                    <div class="player-icon bg-player-<?= $i ?>"><i class="fa-solid fa-chess-pawn"></i></div>
                    <div class="player-info">
                        <div class="player-name">Player <?= $i+1 ?> <span id="jail-badge-<?= $i ?>" class="badge bg-danger ms-1 d-none" style="font-size:0.5rem; padding:2px 4px;">JAIL</span></div>
                        <div class="player-money" id="money-p<?= $i ?>">Rp 1.500.000</div>
                    </div>
                </div>
                <div class="inventory-flat" id="inv-p<?= $i ?>"></div>
            </div>
            <?php endfor; ?>
        </div>
    </div>

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

        <div class="center-board">
            <div class="card-decks">
                <div class="deck deck-chance" onclick="Swal.fire('Info', 'Kartu Kesempatan akan terbuka otomatis jika Anda mendarat di petaknya.', 'info')"><i class="fa-solid fa-question"></i><span>KESEMPATAN</span></div>
                <div class="deck deck-stats" onclick="Swal.fire('Info', 'Klik tanah kosong di papan untuk membelinya (Akan menarik kartu ini).', 'info')"><i class="fa-solid fa-brain"></i><span>STATISTIKA</span></div>
                <div class="deck deck-chest" onclick="Swal.fire('Info', 'Kartu Dana Umum akan terbuka otomatis jika Anda mendarat di petaknya.', 'info')"><i class="fa-solid fa-gift"></i><span>DANA UMUM</span></div>
            </div>

            <div class="action-panel">
                <div class="stock-minimal" style="color: #10b981;">
                    <span class="stock-label">Rumah</span><i class="fa-solid fa-house"></i><span class="fs-4" id="stock-rumah">32</span>
                </div>

                <div class="dice-area">
                    <div id="turn-indicator" class="fw-bold text-player-0 mb-1" style="font-size: 0.85rem; letter-spacing: 1px;">GILIRAN PLAYER 1</div>
                    <i id="dice-css-icon" class="fa-solid fa-dice-one text-secondary" style="font-size: 3rem; margin: 10px 0;"></i><br>
                    <button id="btn-roll" class="btn-action btn-roll" onclick="processTurn()">KOCOK DADU</button>
                    <button id="btn-end" class="btn-action btn-end d-none" onclick="promptEndTurn()">AKHIRI GILIRAN</button>
                    <button id="btn-bankrupt" class="btn-action btn-danger d-none fw-bold" onclick="declareBankrupt()">BANGKRUT</button>
                </div>

                <div class="stock-minimal" style="color: #ef4444;">
                    <span class="stock-label">Hotel</span><i class="fa-solid fa-building"></i><span class="fs-4" id="stock-hotel">11</span>
                </div>
            </div>
        </div>
        
        <div id="pawn-0" class="pawn-container text-player-0 fs-4"><i class="fa-solid fa-chess-pawn"></i></div>
        <div id="pawn-1" class="pawn-container text-player-1 fs-4"><i class="fa-solid fa-chess-knight"></i></div>
        <div id="pawn-2" class="pawn-container text-player-2 fs-4"><i class="fa-solid fa-chess-rook"></i></div>
        <div id="pawn-3" class="pawn-container text-player-3 fs-4"><i class="fa-solid fa-chess-queen"></i></div>
    </div>

    <div class="side-panel right-panel">
        <div class="players-container">
            <?php for($i=2; $i<4; $i++): ?>
            <div id="ui-p<?= $i ?>" class="player-section p-indicator-<?= $i ?>">
                <div class="player-watermark">
                    <i class="fa-solid <?= ['fa-chess-pawn', 'fa-chess-knight', 'fa-chess-rook', 'fa-chess-queen'][$i] ?>"></i>
                </div>
                <div class="player-header">
                    <div class="player-icon bg-player-<?= $i ?>"><i class="fa-solid fa-chess-pawn"></i></div>
                    <div class="player-info">
                        <div class="player-name">Player <?= $i+1 ?> <span id="jail-badge-<?= $i ?>" class="badge bg-danger ms-1 d-none" style="font-size:0.5rem; padding:2px 4px;">JAIL</span></div>
                        <div class="player-money" id="money-p<?= $i ?>">Rp 1.500.000</div>
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
