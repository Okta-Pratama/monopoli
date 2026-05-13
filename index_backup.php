<?php
session_start();

@include 'questions.php'; 
if (!isset($questions)) {
    $questions = ['K'=>[['soal'=>'Q1 dari 2,4,6?','jawaban_kunci'=>'2','poin'=>10]],'M'=>[['soal'=>'Median 2,4,6?','jawaban_kunci'=>'4','poin'=>10]],'D'=>[['soal'=>'Modus 2,2,3?','jawaban_kunci'=>'2','poin'=>10]],'J'=>[['soal'=>'Jangkauan 2,10?','jawaban_kunci'=>'8','poin'=>10]],'R'=>[['soal'=>'Rata-rata 2,4,6?','jawaban_kunci'=>'4','poin'=>10]]];
}

$c_coklat = '#964B00'; $c_biru_muda = '#0ea5e9'; $c_pink = '#d946ef'; 
$c_orange = '#f97316'; $c_merah = '#ef4444'; $c_kuning = '#eab308'; 
$c_hijau = '#10b981'; $c_biru_tua = '#2563eb';

$board = [
    0 => ['tipe' => 'corner', 'nama' => 'START', 'warna' => '#e8e8eb', 'harga' => 0],
    1 => ['tipe' => 'properti', 'nama' => 'Bekasi', 'grup' => $c_coklat, 'harga' => 60, 'sewa' => 2],
    2 => ['tipe' => 'dana_umum', 'nama' => 'Dana Umum', 'warna' => '#fff', 'harga' => 0],
    3 => ['tipe' => 'properti', 'nama' => 'Tangerang', 'grup' => $c_coklat, 'harga' => 60, 'sewa' => 4],
    4 => ['tipe' => 'pajak', 'nama' => 'Kena Tilang', 'warna' => '#fff', 'harga' => 200], 
    5 => ['tipe' => 'bandara', 'nama' => 'Stasiun Gambir', 'warna' => '#ccc', 'harga' => 200],
    6 => ['tipe' => 'properti', 'nama' => 'Bogor', 'grup' => $c_biru_muda, 'harga' => 100, 'sewa' => 6],
    7 => ['tipe' => 'kesempatan', 'nama' => 'Kesempatan', 'warna' => '#fff', 'harga' => 0],
    8 => ['tipe' => 'properti', 'nama' => 'Depok', 'grup' => $c_biru_muda, 'harga' => 100, 'sewa' => 6],
    9 => ['tipe' => 'properti', 'nama' => 'Gresik', 'grup' => $c_biru_muda, 'harga' => 120, 'sewa' => 8],
    10 => ['tipe' => 'corner', 'nama' => 'Penjara', 'warna' => '#e8e8eb', 'harga' => 0],
    11 => ['tipe' => 'properti', 'nama' => 'Sidoarjo', 'grup' => $c_pink, 'harga' => 140, 'sewa' => 10],
    12 => ['tipe' => 'utilitas', 'nama' => 'Perpustakaan', 'warna' => '#fff', 'harga' => 150],
    13 => ['tipe' => 'properti', 'nama' => 'Malang', 'grup' => $c_pink, 'harga' => 140, 'sewa' => 10],
    14 => ['tipe' => 'properti', 'nama' => 'Solo', 'grup' => $c_pink, 'harga' => 160, 'sewa' => 12],
    15 => ['tipe' => 'bandara', 'nama' => 'Band. Juanda', 'warna' => '#ccc', 'harga' => 200],
    16 => ['tipe' => 'properti', 'nama' => 'Semarang', 'grup' => $c_orange, 'harga' => 180, 'sewa' => 14],
    17 => ['tipe' => 'dana_umum', 'nama' => 'Dana Umum', 'warna' => '#fff', 'harga' => 0],
    18 => ['tipe' => 'properti', 'nama' => 'Yogyakarta', 'grup' => $c_orange, 'harga' => 180, 'sewa' => 14],
    19 => ['tipe' => 'properti', 'nama' => 'Bandung', 'grup' => $c_orange, 'harga' => 200, 'sewa' => 16],
    20 => ['tipe' => 'corner', 'nama' => 'Bebas Parkir', 'warna' => '#e8e8eb', 'harga' => 0],
    21 => ['tipe' => 'properti', 'nama' => 'Surabaya', 'grup' => $c_merah, 'harga' => 220, 'sewa' => 18],
    22 => ['tipe' => 'kesempatan', 'nama' => 'Kesempatan', 'warna' => '#fff', 'harga' => 0],
    23 => ['tipe' => 'properti', 'nama' => 'Denpasar', 'grup' => $c_merah, 'harga' => 220, 'sewa' => 18],
    24 => ['tipe' => 'properti', 'nama' => 'Mataram', 'grup' => $c_merah, 'harga' => 240, 'sewa' => 20],
    25 => ['tipe' => 'bandara', 'nama' => 'B. Ngurah Rai', 'warna' => '#ccc', 'harga' => 200],
    26 => ['tipe' => 'properti', 'nama' => 'Makassar', 'grup' => $c_kuning, 'harga' => 260, 'sewa' => 22],
    27 => ['tipe' => 'properti', 'nama' => 'Manado', 'grup' => $c_kuning, 'harga' => 260, 'sewa' => 22],
    28 => ['tipe' => 'utilitas', 'nama' => 'Lab Komputer', 'warna' => '#fff', 'harga' => 150],
    29 => ['tipe' => 'properti', 'nama' => 'Balikpapan', 'grup' => $c_kuning, 'harga' => 280, 'sewa' => 24],
    30 => ['tipe' => 'corner', 'nama' => 'Masuk Penjara!', 'warna' => '#e8e8eb', 'harga' => 0],
    31 => ['tipe' => 'properti', 'nama' => 'Samarinda', 'grup' => $c_hijau, 'harga' => 300, 'sewa' => 26],
    32 => ['tipe' => 'properti', 'nama' => 'Pontianak', 'grup' => $c_hijau, 'harga' => 300, 'sewa' => 26],
    33 => ['tipe' => 'dana_umum', 'nama' => 'Dana Umum', 'warna' => '#fff', 'harga' => 0],
    34 => ['tipe' => 'properti', 'nama' => 'Banjarmasin', 'grup' => $c_hijau, 'harga' => 320, 'sewa' => 28],
    35 => ['tipe' => 'bandara', 'nama' => 'B. Kualanamu', 'warna' => '#ccc', 'harga' => 200],
    36 => ['tipe' => 'kesempatan', 'nama' => 'Kesempatan', 'warna' => '#fff', 'harga' => 0],
    37 => ['tipe' => 'properti', 'nama' => 'Palembang', 'grup' => $c_biru_tua, 'harga' => 350, 'sewa' => 35],
    38 => ['tipe' => 'pajak', 'nama' => 'Bayar PBB', 'warna' => '#fff', 'harga' => 100], 
    39 => ['tipe' => 'properti', 'nama' => 'Medan', 'grup' => $c_biru_tua, 'harga' => 400, 'sewa' => 50]
];

function formatRp($val) { return 'Rp ' . number_format($val * 1000, 0, ',', '.'); }
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MONOPOLI - Statistika</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <style>
        body { background-color: #eef2f5; font-family: 'Poppins', sans-serif; margin: 0; padding: 20px; overflow-x: hidden; color: #333; }

        /* GRID PAPAN */
        .monopoly-board { 
            display: grid; grid-template-columns: 120px repeat(9, 80px) 120px; grid-template-rows: 120px repeat(9, 80px) 120px; 
            gap: 2px; background: #2c3e50; border: 8px solid #2c3e50; border-radius: 15px; width: fit-content; margin: 20px auto; 
            box-shadow: 0 20px 40px rgba(0,0,0,0.15); position: relative; overflow: hidden;
        }
        .tile { background: #fdfdfd; position: relative; display: flex; flex-direction: column; align-items: center; justify-content: space-between; text-align: center; padding: 2px; cursor: pointer; transition: 0.2s; }
        .tile:hover { filter: brightness(0.9); }
        .tile-color-bar { width: 100%; height: 16px; border-bottom: 2px solid rgba(0,0,0,0.1); }
        .tile.corner { background: #e8eaed; font-size: 0.9rem; font-weight: bold; justify-content: center; }

        .tile-0 { grid-column: 11; grid-row: 11; } .tile-1 { grid-column: 10; grid-row: 11; } .tile-2 { grid-column: 9; grid-row: 11; } .tile-3 { grid-column: 8; grid-row: 11; } .tile-4 { grid-column: 7; grid-row: 11; } .tile-5 { grid-column: 6; grid-row: 11; } .tile-6 { grid-column: 5; grid-row: 11; } .tile-7 { grid-column: 4; grid-row: 11; } .tile-8 { grid-column: 3; grid-row: 11; } .tile-9 { grid-column: 2; grid-row: 11; } .tile-10 { grid-column: 1; grid-row: 11; }
        .tile-11 { grid-column: 1; grid-row: 10; } .tile-12 { grid-column: 1; grid-row: 9; } .tile-13 { grid-column: 1; grid-row: 8; } .tile-14 { grid-column: 1; grid-row: 7; } .tile-15 { grid-column: 1; grid-row: 6; } .tile-16 { grid-column: 1; grid-row: 5; } .tile-17 { grid-column: 1; grid-row: 4; } .tile-18 { grid-column: 1; grid-row: 3; } .tile-19 { grid-column: 1; grid-row: 2; }
        .tile-20 { grid-column: 1; grid-row: 1; } .tile-21 { grid-column: 2; grid-row: 1; } .tile-22 { grid-column: 3; grid-row: 1; } .tile-23 { grid-column: 4; grid-row: 1; } .tile-24 { grid-column: 5; grid-row: 1; } .tile-25 { grid-column: 6; grid-row: 1; } .tile-26 { grid-column: 7; grid-row: 1; } .tile-27 { grid-column: 8; grid-row: 1; } .tile-28 { grid-column: 9; grid-row: 1; } .tile-29 { grid-column: 10; grid-row: 1; } .tile-30 { grid-column: 11; grid-row: 1; }
        .tile-31 { grid-column: 11; grid-row: 2; } .tile-32 { grid-column: 11; grid-row: 3; } .tile-33 { grid-column: 11; grid-row: 4; } .tile-34 { grid-column: 11; grid-row: 5; } .tile-35 { grid-column: 11; grid-row: 6; } .tile-36 { grid-column: 11; grid-row: 7; } .tile-37 { grid-column: 11; grid-row: 8; } .tile-38 { grid-column: 11; grid-row: 9; } .tile-39 { grid-column: 11; grid-row: 10; }

        /* AREA TENGAH */
        .center-board { 
            grid-column: 2 / 11; grid-row: 2 / 11; background: radial-gradient(circle at center, #ffffff 0%, #eef2f5 100%);
            display: flex; flex-direction: column; align-items: center; justify-content: space-between; 
            padding: 30px 20px; box-shadow: inset 0 0 40px rgba(0,0,0,0.03); position: relative;
        }

        /* Player Cards */
        .players-container { display: flex; width: 100%; background: #fff; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.04); overflow: hidden; border: 1px solid #eaeaea; }
        .player-section { flex: 1; padding: 15px 10px; border-right: 1px solid #eaeaea; border-top: 4px solid #f8f9fa; transition: 0.4s ease; display: flex; flex-direction: column; gap: 10px; height: 260px; }
        .player-section:last-child { border-right: none; }
        .player-section.active-turn.p-indicator-0 { border-top-color: #ef4444; background-color: #fef2f2; }
        .player-section.active-turn.p-indicator-1 { border-top-color: #3b82f6; background-color: #eff6ff; }
        .player-section.active-turn.p-indicator-2 { border-top-color: #10b981; background-color: #ecfdf5; }
        .player-section.active-turn.p-indicator-3 { border-top-color: #f59e0b; background-color: #fffbeb; }

        .player-header { display: flex; align-items: center; gap: 10px; }
        .player-icon { width: 35px; height: 35px; border-radius: 50%; color: white; display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0; }
        .bg-player-0 { background: #ef4444; } .bg-player-1 { background: #3b82f6; } .bg-player-2 { background: #10b981; } .bg-player-3 { background: #f59e0b; }
        
        .player-info { display: flex; flex-direction: column; text-align: left; }
        .player-name { font-weight: 700; font-size: 0.8rem; color: #333; line-height: 1; margin-bottom: 4px; }
        .player-money { font-weight: 600; font-size: 0.75rem; color: #10b981; }

        .inventory-flat { display: flex; flex-direction: column; gap: 5px; flex-grow: 1; overflow-y: auto; padding-right: 5px; }
        .inventory-flat::-webkit-scrollbar { width: 4px; } .inventory-flat::-webkit-scrollbar-thumb { background: #ddd; border-radius: 4px; }
        .inv-item { display: flex; justify-content: space-between; align-items: center; background: #fafafa; border: 1px solid #eee; border-radius: 4px; padding: 4px 8px; border-left: 4px solid; cursor: pointer; transition: 0.2s; }
        .inv-item:hover { background: #f0f0f0; }
        .inv-top { font-size: 0.65rem; font-weight: 600; color: #444; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;}
        .badge-status { font-size: 0.6rem; font-weight: 800; background: #e0e0e0; padding: 2px 6px; border-radius: 4px; }
        
        /* Action Panel */
        .action-panel { display: flex; align-items: center; justify-content: center; gap: 40px; background: white; padding: 15px 30px; border-radius: 50px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid #f0f0f0; margin-top: 10px; }
        .stock-minimal { text-align: center; font-weight: 600; display: flex; flex-direction: column; align-items: center;}
        .stock-minimal i { font-size: 1.5rem; margin-bottom: 5px; }
        .stock-label { font-size: 0.7rem; color: #888; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 2px; }
        
        .dice-area { text-align: center; padding: 0 30px; border-left: 2px dashed #eee; border-right: 2px dashed #eee; min-width: 250px;}
        .btn-action { color: white; border: none; font-weight: 700; letter-spacing: 1px; padding: 10px 30px; border-radius: 30px; transition: 0.3s; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: 100%; margin-top: 5px;}
        .btn-roll { background: #2c3e50; } .btn-roll:hover { background: #1a252f; transform: scale(1.05); }
        .btn-end { background: #ef4444; } .btn-end:hover { background: #dc2626; transform: scale(1.05); }
        .btn-end:disabled { background: #9ca3af; transform: none; cursor: not-allowed; }

        /* Tumpukan Kartu Board */
        .card-decks { display: flex; gap: 30px; }
        .deck { 
            width: 120px; height: 160px; border-radius: 12px; display: flex; flex-direction: column; align-items: center; justify-content: center; 
            font-weight: 700; text-align: center; color: white; cursor: pointer; font-size: 0.8rem; letter-spacing: 0.5px; padding: 10px;
            background-image: url('src/images/board/back.png'); background-size: cover; background-blend-mode: overlay;
            box-shadow: -2px 2px 0 #fff, -4px 4px 0 #ddd, -6px 6px 15px rgba(0,0,0,0.2); transition: transform 0.2s;
        }
        .deck:active { transform: translate(-2px, 2px); box-shadow: 0 0 5px rgba(0,0,0,0.3); }
        .deck i { font-size: 2.5rem; margin-bottom: 15px; }
        .deck-chance { background-color: #f39c12; } .deck-chest { background-color: #3498db; } .deck-stats { background-color: #e74c3c; }

        /* Bidak & Bangkrut Styling */
        .pawn-container { width: 25px; height: 25px; position: absolute; z-index: 50; display: flex; align-items: center; justify-content: center; transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1); text-shadow: 1px 1px 3px rgba(0,0,0,0.6); }
        .text-player-0 { color: #ef4444 !important; } .text-player-1 { color: #3b82f6 !important; } .text-player-2 { color: #10b981 !important; } .text-player-3 { color: #f59e0b !important; }
        
        .bankrupt-ui { opacity: 0.4; filter: grayscale(100%); pointer-events: none; }
        .bankrupt-pawn { top: 75% !important; left: 85% !important; transform: scale(1.5) !important; opacity: 0.4; filter: grayscale(100%); }

        /* POPUP KARTU SOLID & RAPI (Revisi Tampilan Solid Putih) */
        .swal-custom-card { background: transparent !important; box-shadow: none !important; }
        .swal-card-stats { border-top: 15px solid #e74c3c !important; border-radius: 15px !important; background: #fff !important; box-shadow: 0 20px 50px rgba(0,0,0,0.5) !important; padding: 20px !important;}
        .swal-card-chance { border-top: 15px solid #f39c12 !important; border-radius: 15px !important; background: #fff !important; box-shadow: 0 20px 50px rgba(0,0,0,0.5) !important; padding: 20px !important;}
        .swal-card-chest { border-top: 15px solid #3498db !important; border-radius: 15px !important; background: #fff !important; box-shadow: 0 20px 50px rgba(0,0,0,0.5) !important; padding: 20px !important;}
        
        /* Tampilan Kartu Depan - Tetap seperti Kartu */
        .swal-card-front { 
            background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%) !important; 
            border: 2px solid #ddd !important; 
            border-radius: 12px !important; 
            padding: 30px 20px !important; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.3), inset 0 1px 0 rgba(255,255,255,0.8) !important; 
            min-height: 200px; 
            display: flex; 
            flex-direction: column; 
            justify-content: center; 
            align-items: center;
            position: relative;
        }
        
        .swal-card-front::before {
            content: '';
            position: absolute;
            top: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 90%;
            height: 20px;
            background: #fff;
            border-radius: 50%;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        /* Container untuk jawaban/buttons di bawah kartu */
        .swal-card-answers {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 20px;
            width: 100%;
        }
        
        /* Background putih monoton menjadi gradient */
        .swal2-popup { background: linear-gradient(135deg, #f5f7fa 0%, #ffffff 50%, #f5f7fa 100%) !important; }
        
        /* Input Jawaban Jelas Terbaca */
        .swal2-input { background: #fdfdfd !important; color: #111 !important; border: 3px solid #ccc !important; border-radius: 8px !important; font-weight: bold; text-align: center; margin: 20px auto !important; box-shadow: inset 0 2px 5px rgba(0,0,0,0.05) !important;}
        .swal2-input:focus { border-color: #e74c3c !important; box-shadow: 0 0 10px rgba(231,76,60,0.3) !important; }
    </style>
</head>
<body>

<script>
    const BOARD = <?= json_encode($board) ?>;
    const QUESTIONS = <?= json_encode($questions) ?>;
</script>

<div class="monopoly-board" id="board">
    <?php foreach ($board as $index => $petak): ?>
        <div class="tile tile-<?= $index ?> <?= $petak['tipe'] ?>" id="tile-<?= $index ?>" onclick="handleTileClick(<?= $index ?>)">
            <div class="house-indicator" id="houses-<?= $index ?>" style="top:20px;"></div>
            <?php if(isset($petak['grup'])): ?>
                <div class="tile-color-bar" style="background-color: <?= $petak['grup'] ?>"></div>
            <?php endif; ?>
            <div class="fw-bold mt-1 px-1" style="font-size: 0.6rem; line-height: 1.2; letter-spacing: 0.3px; position:relative; width:100%;">
                <?= $petak['nama'] ?>
            </div>
            <?php if($petak['harga'] > 0): ?>
                <div class="text-danger fw-bold pb-1" style="font-size: 0.55rem;"><?= formatRp($petak['harga']) ?></div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

    <div class="center-board">
        <div class="players-container">
            <?php for($i=0; $i<4; $i++): ?>
            <div id="ui-p<?= $i ?>" class="player-section p-indicator-<?= $i ?>">
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

        <div class="card-decks">
            <div class="deck deck-chance" onclick="drawCard('chance')"><i class="fa-solid fa-question"></i><span>KESEMPATAN</span></div>
            <div class="deck deck-stats" onclick="Swal.fire('Info', 'Klik tanah kosong di papan untuk membelinya (Akan menarik kartu ini).', 'info')"><i class="fa-solid fa-brain"></i><span>STATISTIKA</span></div>
            <div class="deck deck-chest" onclick="drawCard('chest')"><i class="fa-solid fa-treasure-chest"></i><span>DANA UMUM</span></div>
        </div>
    </div>
    
    <div id="pawn-0" class="pawn-container text-player-0 fs-4"><i class="fa-solid fa-chess-pawn"></i></div>
    <div id="pawn-1" class="pawn-container text-player-1 fs-4"><i class="fa-solid fa-chess-knight"></i></div>
    <div id="pawn-2" class="pawn-container text-player-2 fs-4"><i class="fa-solid fa-chess-rook"></i></div>
    <div id="pawn-3" class="pawn-container text-player-3 fs-4"><i class="fa-solid fa-chess-queen"></i></div>
</div>

<script>
    function formatRp(val) { return 'Rp ' + (val * 1000).toLocaleString('id-ID'); }

    const sfx = {
        move: new Audio('src/sfx/place.mp3'), door: new Audio('src/sfx/door-opening.mp3'), 
        jail: new Audio('src/sfx/jail.mp3'), build: new Audio('src/sfx/building.mp3'),
        bell: new Audio('src/sfx/bell.mp3'), dice: new Audio('src/sfx/roll-dice.mp3'),
        card: new Audio('src/sfx/taking-card.mp3'), buy: new Audio('src/sfx/purchase.mp3')
    };

    const pColors = ['text-player-0', 'text-player-1', 'text-player-2', 'text-player-3'];
    const pBgSoft = ['#fef2f2', '#eff6ff', '#ecfdf5', '#fffbeb'];

    function playSfx(sound) { if(sound){ sound.currentTime = 0; sound.play().catch(e => console.log('SFX Blocked:', e)); } }

    let players = [
        { id: 0, pos: 0, money: 1500, properties: [], cards: [], wrongAnswers: 0, inJail: false, jailTurns: 0, isBankrupt: false },
        { id: 1, pos: 0, money: 1500, properties: [], cards: [], wrongAnswers: 0, inJail: false, jailTurns: 0, isBankrupt: false },
        { id: 2, pos: 0, money: 1500, properties: [], cards: [], wrongAnswers: 0, inJail: false, jailTurns: 0, isBankrupt: false },
        { id: 3, pos: 0, money: 1500, properties: [], cards: [], wrongAnswers: 0, inJail: false, jailTurns: 0, isBankrupt: false }
    ];
    let currentTurn = 0; let stockRumah = 32; let stockHotel = 11;
    let isActionPhase = true; let hasRolled = false;
    let autoRollInterval; let rollCountdown = 3;
    
    window.onload = () => { updatePawnPositions(); startAutoRoll(); };

    // --- AUTO ROLL 3 DETIK ---
    function startAutoRoll() {
        rollCountdown = 3;
        document.getElementById('btn-roll').innerText = `KOCOK DADU (${rollCountdown})`;
        autoRollInterval = setInterval(() => {
            rollCountdown--;
            if(rollCountdown > 0) {
                document.getElementById('btn-roll').innerText = `KOCOK DADU (${rollCountdown})`;
            } else {
                clearInterval(autoRollInterval);
                processTurn();
            }
        }, 1000);
    }
    function clearAutoRoll() {
        clearInterval(autoRollInterval);
        document.getElementById('btn-roll').innerText = `KOCOK DADU`;
    }

    // --- UPDATE UI INVENTORY & BOARD STATUS ---
    function updateUI() {
        document.getElementById('stock-rumah').innerText = stockRumah; 
        document.getElementById('stock-hotel').innerText = stockHotel;
        
        let p = players[currentTurn];

        players.forEach(player => {
            if(player.isBankrupt) return;
            let mEl = document.getElementById(`money-p${player.id}`);
            mEl.innerText = formatRp(player.money);
            mEl.className = player.money < 0 ? 'player-money text-danger' : 'player-money text-success';
            
            document.getElementById(`jail-badge-${player.id}`).classList.toggle('d-none', !player.inJail);
            const invDiv = document.getElementById(`inv-p${player.id}`);
            invDiv.innerHTML = ''; 
            
            player.properties.forEach((prop) => { 
                let tileIndex = BOARD.findIndex(t => t.nama === prop.nama);
                let tile = BOARD[tileIndex];
                let status = '0';
                if (tile.mortgaged) status = '🔒';
                else if (tile.houses > 0) status = tile.houses;
                
                invDiv.innerHTML += `
                    <div class="inv-item" style="border-left-color: ${prop.grup}">
                        <div class="inv-top">${prop.nama}</div>
                        <div style="display: flex; gap: 5px; align-items: center;">
                            <div class="badge-status">${status}</div>
                            <button class="btn btn-sm btn-info" style="padding: 2px 8px; font-size: 0.6rem;" onclick="handleTileClick(${tileIndex})"><i class="fa-solid fa-eye"></i></button>
                        </div>
                    </div>`; 
            });
            player.cards.forEach((c) => { 
                invDiv.innerHTML += `<div class="inv-item" style="border-left-color: #f39c12" onclick="useJailCard(${player.id})"><div class="inv-top">${c.nama}</div></div>`; 
            });
        });

        // Board Indicators Ikon Rumah
        BOARD.forEach((t, idx) => {
            let st = document.getElementById(`houses-${idx}`);
            if(!st) return;
            if(t.owner === undefined) { st.innerHTML = ''; return; }
            if(t.mortgaged) st.innerHTML = '<i class="fa-solid fa-lock text-secondary"></i>';
            else {
                st.innerHTML = '';
                if(t.houses > 0 && t.houses < 5) {
                    for(let i=0; i<t.houses; i++) st.innerHTML += `<i class="fa-solid fa-house ${pColors[t.owner]}"></i>`;
                } else if(t.houses === 5) {
                    st.innerHTML = `<i class="fa-solid fa-building ${pColors[t.owner]} fs-6"></i>`;
                }
            }
        });

        // Action Phase Button Logic (Debt Enforcement)
        if (isActionPhase && !p.isBankrupt) {
            let btnRoll = document.getElementById('btn-roll');
            let btnEnd = document.getElementById('btn-end');
            let btnBank = document.getElementById('btn-bankrupt');
            
            if (!hasRolled) {
                btnRoll.classList.remove('d-none'); btnEnd.classList.add('d-none'); btnBank.classList.add('d-none');
            } else {
                btnRoll.classList.add('d-none');
                if (p.money < 0) {
                    let totalAssets = calculateAssets(p.id);
                    if (p.money + totalAssets < 0) {
                        btnEnd.classList.add('d-none'); btnBank.classList.remove('d-none');
                    } else {
                        btnEnd.disabled = true; btnEnd.innerText = 'LUNASI HUTANG!';
                        btnEnd.classList.remove('d-none'); btnBank.classList.add('d-none');
                    }
                } else {
                    btnEnd.disabled = false; btnEnd.innerText = 'AKHIRI GILIRAN';
                    btnEnd.classList.remove('d-none'); btnBank.classList.add('d-none');
                }
            }
        }
    }

    function calculateAssets(id) {
        let val = 0;
        players[id].properties.forEach(prop => {
            let t = BOARD.find(x => x.nama === prop.nama);
            if (!t.mortgaged) val += t.harga / 2;
            if (t.houses > 0 && t.houses < 5) val += t.houses * 25;
            if (t.houses === 5) val += 125;
        });
        return val;
    }

    // --- ALUR GILIRAN ---
    function processTurn() {
        clearAutoRoll();
        let p = players[currentTurn];
        document.getElementById('btn-roll').disabled = true;

        if (p.inJail) {
            let hasJailCard = p.cards.find(c => c.type === 'free_jail');
            let jailOpts = `<button class="btn btn-warning m-1 fw-bold" onclick="payJail(${p.id})">Bayar ${formatRp(50)}</button>`;
            if(hasJailCard) jailOpts += `<button class="btn btn-info m-1 fw-bold" onclick="useJailCard(${p.id})">Pakai Kartu</button>`;
            jailOpts += `<br><button class="btn btn-danger m-1 mt-2 fw-bold" onclick="skipJailTurn(${p.id})">Lewati Giliran</button>`;
            Swal.fire({ title: 'Terkurung di Penjara!', html: jailOpts, showConfirmButton: false, allowOutsideClick: false });
        } else {
            rollAndMove(p);
        }
    }

    function payJail(id) {
        let p = players[id]; p.money -= 50; p.inJail = false; p.wrongAnswers = 0; playSfx(sfx.door); updateUI();
        if(p.money < 0) { hasRolled = true; updateUI(); Swal.fire('Hutang!', 'Jual aset untuk membayar denda penjara.', 'warning'); }
        else { Swal.fire('Bebas!', 'Denda dibayar.', 'success').then(() => rollAndMove(p)); }
    }
    function skipJailTurn(id) {
        players[id].jailTurns++; if(players[id].jailTurns >= 2) { players[id].inJail = false; players[id].wrongAnswers = 0; playSfx(sfx.door); }
        hasRolled = true; updateUI(); Swal.close(); 
    }
    function useJailCard(id) {
        if(!isActionPhase && id !== currentTurn) return;
        let p = players[id];
        if(!p.inJail) return Swal.fire('Tidak Bisa', 'Anda tidak di penjara, kartu tidak bisa digunakan sekarang.', 'warning');
        let cIdx = p.cards.findIndex(c => c.type === 'free_jail');
        if(cIdx > -1) { p.cards.splice(cIdx, 1); p.inJail = false; p.wrongAnswers = 0; playSfx(sfx.door); updateUI(); Swal.fire('Bebas!', 'Kartu digunakan.', 'success').then(() => { if(!hasRolled) rollAndMove(p); }); }
    }

    function rollAndMove(p) {
        let diceIcon = document.getElementById('dice-css-icon');
        const dCls = ['fa-dice-one', 'fa-dice-two', 'fa-dice-three', 'fa-dice-four', 'fa-dice-five', 'fa-dice-six'];
        let rollCount = 0; playSfx(sfx.dice); 

        let rollInterval = setInterval(() => {
            diceIcon.className = `fa-solid ${dCls[Math.floor(Math.random() * 6)]} text-secondary`; rollCount++;
            if (rollCount > 10) {
                clearInterval(rollInterval);
                let finalDice = Math.floor(Math.random() * 6) + 1;
                diceIcon.className = `fa-solid ${dCls[finalDice - 1]} ${pColors[p.id]} animate__animated animate__bounceIn`;
                
                let step = 0;
                let stepInterval = setInterval(() => {
                    step++; p.pos = (p.pos + 1) % 40; playSfx(sfx.move);
                    if (p.pos === 0) { p.money += 200; updateUI(); Swal.fire({toast:true, position:'top-end', title:`START! +${formatRp(200)}`, icon:'success', showConfirmButton:false, timer:2000}); } 
                    updatePawnPositions();
                    if(step === finalDice) { clearInterval(stepInterval); hasRolled = true; setTimeout(() => handleTileLogic(p, BOARD[p.pos]), 400); }
                }, 300);
            }
        }, 80);
    }

    function handleTileLogic(p, tile) {
        if (p.pos === 30) { p.pos = 10; p.inJail = true; p.jailTurns = 0; playSfx(sfx.jail); updatePawnPositions(); updateUI(); } 
        else if (tile.tipe === 'pajak') { p.money -= tile.harga; playSfx(sfx.bell); updateUI(); Swal.fire('Denda!', `Kamu bayar ${formatRp(tile.harga)}`, 'error'); } 
        else if (tile.tipe === 'kesempatan' || tile.tipe === 'dana_umum') { drawCard(tile.tipe, p); }
        else if (tile.tipe === 'properti' || tile.tipe === 'bandara' || tile.tipe === 'utilitas') {
            if (tile.owner !== undefined && tile.owner !== p.id) {
                if(tile.mortgaged) Swal.fire('Bebas Sewa', 'Properti sedang digadaikan.', 'info');
                else {
                    let rent = tile.sewa || 25; if(tile.houses) rent += (tile.houses * 20); 
                    p.money -= rent; players[tile.owner].money += rent; playSfx(sfx.bell); updateUI();
                    Swal.fire('Bayar Sewa!', `Membayar ${formatRp(rent)} ke Player ${tile.owner + 1}`, 'warning');
                }
            } else if (tile.owner === undefined && p.money >= tile.harga) {
                triggerStatsQuestion(p, BOARD.indexOf(tile));
            }
        }
        updateUI();
    }

    // --- FASE AKSI TERBUKA (Bisa Klik Papan) ---
    function handleTileClick(tileIndex) {
        if (!isActionPhase) return;
        let p = players[currentTurn]; let tile = BOARD[tileIndex];

        if (tile.owner === p.id) { openPropertyMenu(p.id, tileIndex); } 
        else if (tile.owner !== undefined && tile.owner !== p.id && !tile.mortgaged) { viewOtherProperty(tileIndex, p.id); }
        else if (tile.owner === undefined && tile.harga > 0) {
            if(p.pos === tileIndex && p.money >= tile.harga) triggerStatsQuestion(p, tileIndex);
            else Swal.fire({ title: tile.nama, html: `Harga: <b>${formatRp(tile.harga)}</b><br>Sewa: <b>${formatRp(tile.sewa)}</b><br><br><i>Tanah ini belum dimiliki siapa pun.</i>`, icon: 'info' });
        }
    }

    function viewOtherProperty(tileIndex, buyerId) {
        let tile = BOARD[tileIndex]; let sellerId = tile.owner;
        let html = `Milik: <b>Player ${sellerId + 1}</b><br>Sewa Dasar: <b>${formatRp(tile.sewa)}</b><br>
                    Rumah: <b>${tile.houses < 5 ? (tile.houses||0) : 0}</b> | Hotel: <b>${tile.houses === 5 ? 1 : 0}</b><br><br>
                    <button class="btn btn-info w-100 fw-bold text-white" onclick="offerProperty(${buyerId}, ${sellerId}, ${tileIndex})"><i class="fa-solid fa-handshake"></i> Tawar Tanah</button>`;
        Swal.fire({ title: tile.nama, html: html, showCancelButton: true, cancelButtonText: 'Tutup', showConfirmButton: false });
    }

    // --- MANAJEMEN ASET ---
    function openPropertyMenu(ownerId, tileIndex) {
        let p = players[ownerId]; let tile = BOARD[tileIndex]; let htmlOpts = '';

        if (tile.mortgaged) {
            let tebusCost = (tile.harga / 2) * 1.1; 
            htmlOpts += `<button class="btn btn-success m-2 w-100 fw-bold" onclick="unmortgage(${ownerId}, ${tileIndex}, ${tebusCost})"><i class="fa-solid fa-unlock"></i> Tebus Sertifikat (${formatRp(tebusCost)})</button>`;
        } else {
            if (tile.houses > 0) {
                if(tile.houses === 5) htmlOpts += `<button class="btn btn-danger m-2 w-100 fw-bold" onclick="sellHotel(${ownerId}, ${tileIndex})"><i class="fa-solid fa-building"></i> Jual Hotel (+${formatRp(25)})</button>`;
                else htmlOpts += `<button class="btn btn-warning m-2 w-100 fw-bold" onclick="sellHouse(${ownerId}, ${tileIndex})"><i class="fa-solid fa-house"></i> Jual Rumah (+${formatRp(25)})</button>`;
            } else {
                if (checkMonopolyGroup(ownerId, tile.grup) && tile.tipe === 'properti') {
                    htmlOpts += `<button class="btn btn-primary m-2 w-100 fw-bold" onclick="buildHouse(${ownerId}, ${tileIndex})"><i class="fa-solid fa-hammer"></i> Bangun Properti (${formatRp(50)})</button>`;
                }
                let gadaiGain = tile.harga / 2;
                htmlOpts += `<button class="btn btn-danger m-2 w-100 fw-bold" onclick="mortgage(${ownerId}, ${tileIndex}, ${gadaiGain})"><i class="fa-solid fa-lock"></i> Gadaikan (+${formatRp(gadaiGain)})</button>`;
            }
        }
        Swal.fire({ title: tile.nama, html: htmlOpts, showCancelButton: true, cancelButtonText: 'Tutup', showConfirmButton: false });
    }

    function checkMonopolyGroup(playerId, groupColor) {
        if(!groupColor) return false;
        return BOARD.filter(t => t.grup === groupColor).length === players[playerId].properties.filter(t => t.grup === groupColor).length;
    }
    function checkEvenBuildRule(tile) {
        let groupTiles = BOARD.filter(t => t.grup === tile.grup && t.tipe === 'properti');
        let minHouses = Math.min(...groupTiles.map(t => t.houses || 0));
        let maxHouses = Math.max(...groupTiles.map(t => t.houses || 0));
        let currentHouses = tile.houses || 0;
        // Boleh membangun jika: rumah ini = minimum, atau jika punya rumah dan ingin upgrade (maksimal 1 lebih banyak dari minimum)
        return currentHouses === minHouses || (currentHouses > minHouses && currentHouses <= maxHouses);
    }

    function buildHouse(playerId, tileIndex) {
        let p = players[playerId]; let tile = BOARD[tileIndex];
        if (p.money < 50) return Swal.fire('Gagal', 'Uang tidak cukup.', 'error');
        if (!checkEvenBuildRule(tile)) return Swal.fire('Gagal', 'Pembangunan di grup ini harus merata!', 'error');

        if (!tile.houses) tile.houses = 0;
        if (tile.houses < 4 && stockRumah > 0) { tile.houses++; stockRumah--; p.money -= 50; playSfx(sfx.build); updateUI(); Swal.fire('Sukses', '1 Rumah dibangun.', 'success'); }
        else if (tile.houses === 4 && stockHotel > 0) { tile.houses = 5; stockRumah += 4; stockHotel--; p.money -= 50; playSfx(sfx.build); updateUI(); Swal.fire('Megah!', 'Hotel dibangun.', 'success'); }
        else Swal.fire('Gagal', 'Stok habis / Level Maksimal.', 'error');
    }

    function mortgage(pId, tIdx, gain) { BOARD[tIdx].mortgaged = true; players[pId].money += gain; playSfx(sfx.buy); updateUI(); Swal.close(); }
    function unmortgage(pId, tIdx, cost) { if(players[pId].money < cost) return Swal.fire('Gagal','Uang kurang','error'); BOARD[tIdx].mortgaged = false; players[pId].money -= cost; playSfx(sfx.buy); updateUI(); Swal.close(); }
    function sellHouse(pId, tIdx) { BOARD[tIdx].houses--; stockRumah++; players[pId].money += 25; playSfx(sfx.buy); updateUI(); Swal.close(); }
    function sellHotel(pId, tIdx) { BOARD[tIdx].houses=4; stockHotel++; stockRumah-=4; players[pId].money += 25; playSfx(sfx.buy); updateUI(); Swal.close(); }

    // --- TRADING / TAWAR ---
    function offerProperty(buyerId, sellerId, tileIndex) {
        let buyer = players[buyerId]; let seller = players[sellerId]; let tile = BOARD[tileIndex];
        Swal.fire({
            title: `Tawar ${tile.nama}`, text: `Maksimal: ${formatRp(buyer.money)}`, input: 'number', inputAttributes: { min: 1, max: buyer.money }, 
            showCancelButton: true, confirmButtonText: 'Tawar', customClass: { popup: 'swal-card-stats animate__animated animate__fadeIn' }
        }).then((res) => {
            if(res.isConfirmed && res.value > 0) {
                let amount = parseInt(res.value); if(amount > buyer.money) return Swal.fire('Gagal', 'Uang tidak cukup', 'error');
                Swal.fire({
                    title: `<span class="${pColors[sellerId]}">Tawaran (Player ${sellerId+1})</span>`,
                    html: `Player ${buyerId+1} menawar <b>${tile.nama}</b> seharga <b>${formatRp(amount)}</b>. Terima?`,
                    icon: 'question', showCancelButton: true, confirmButtonText: 'Terima', cancelButtonText: 'Tolak', confirmButtonColor: '#10b981', cancelButtonColor: '#ef4444'
                }).then((sellRes) => {
                    if(sellRes.isConfirmed) {
                        buyer.money -= amount; seller.money += amount; tile.owner = buyerId;
                        seller.properties = seller.properties.filter(x => x.nama !== tile.nama); buyer.properties.push(tile);
                        playSfx(sfx.buy); updateUI(); Swal.fire('Deal!', 'Properti berpindah tangan.', 'success');
                    } else Swal.fire('Ditolak', 'Tawaran ditolak.', 'error');
                });
            }
        });
    }

    // --- KARTU POPUP SOLID ---
    function triggerStatsQuestion(p, tileIndex) {
        playSfx(sfx.card); let tile = BOARD[tileIndex];
        const qTypes = ['K', 'M', 'D', 'J', 'R']; const q = QUESTIONS[qTypes[Math.floor(Math.random() * qTypes.length)]][Math.floor(Math.random() * QUESTIONS[qTypes[0]].length)];
        
        Swal.fire({ 
            title: '<i class="fa-solid fa-brain" style="font-size:3rem; color:#e74c3c;"></i><br><span style="font-size:1.2rem; letter-spacing:2px; font-weight:800; color:#c0392b;">STATISTIKA</span>',
            text: q.soal, footer: `Reward: ${formatRp(q.poin)}`, input: 'text', 
            customClass: { popup: 'swal-card-stats animate__animated animate__flipInY' }, 
            showCancelButton: false, allowOutsideClick: false, confirmButtonText: 'Kunci Jawaban' 
        }).then((res) => {
            if (res.value.toLowerCase() == q.jawaban_kunci.toLowerCase()) {
                p.wrongAnswers = 0;
                Swal.fire({ title:'Tepat!', text:`Reward ${formatRp(q.poin)} akan masuk saldo. Beli ${tile.nama} seharga ${formatRp(tile.harga)}?`, showCancelButton: true, confirmButtonText: `Beli`, cancelButtonText: 'Lewati' }).then((b) => {
                    if(b.isConfirmed) { 
                        p.money += (q.poin * 1000); // Reward ditambah SETELAH konfirmasi
                        if(p.money >= tile.harga) { 
                            p.money -= tile.harga; tile.owner = p.id; p.properties.push(tile); playSfx(sfx.buy); 
                            Swal.fire('Sukses!', `${tile.nama} berhasil dibeli!`, 'success').then(() => updateUI());
                        } else { 
                            Swal.fire('Uang Kurang', `Reward diterima tapi uang kurang untuk beli tanah. Sekarang punya ${formatRp(p.money)}.`, 'info').then(() => updateUI());
                        }
                    } else {
                        p.money += (q.poin * 1000); // Reward tetap ditambah meski tidak beli
                        Swal.fire('OK', `Reward ${formatRp(q.poin)} masuk saldo.`, 'info').then(() => updateUI());
                    }
                });
            } else {
                p.wrongAnswers++; if (p.wrongAnswers >= 3) { Swal.fire('Salah 3x!', 'Masuk Penjara!', 'error').then(() => { p.pos=10; p.inJail=true; p.jailTurns=0; playSfx(sfx.jail); updatePawnPositions(); updateUI(); }); } 
                else { Swal.fire('Salah!', `Jawaban: ${q.jawaban_kunci}. Peringatan: ${p.wrongAnswers}/3.`, 'warning').then(()=>updateUI()); }
            }
        });
    }

    function drawCard(type, p) {
        playSfx(sfx.card); let isChance = (type === 'chance'); let isGetOutJail = Math.random() > 0.8; 
        
        Swal.fire({ 
            title: `<i class="fa-solid ${isChance?'fa-question':'fa-treasure-chest'}" style="font-size:3rem; color:${isChance?'#f39c12':'#3498db'};"></i><br><span style="font-size:1.2rem; letter-spacing:2px; font-weight:800; color:${isChance?'#d35400':'#2980b9'};">${isChance?'KESEMPATAN':'DANA UMUM'}</span>`,
            text: isGetOutJail ? "Dapat Kartu Bebas Penjara." : "Bonus Rp 150.000.",
            customClass: { popup: `${isChance?'swal-card-chance':'swal-card-chest'} animate__animated animate__zoomIn` }, 
            confirmButtonText: 'Ambil' 
        }).then(() => {
            if (isGetOutJail) p.cards.push({ nama: 'Bebas Penjara', type: 'free_jail' }); else p.money += 150;
            updateUI();
        });
    }

    // --- BANGKRUT & END TURN ---
    function declareBankrupt() {
        let p = players[currentTurn]; p.isBankrupt = true;
        p.properties.forEach(prop => {
            let t = BOARD.find(x => x.nama === prop.nama);
            t.owner = undefined; t.mortgaged = false;
            if (t.houses > 0 && t.houses < 5) stockRumah += t.houses;
            if (t.houses === 5) { stockRumah += 4; stockHotel += 1; }
            t.houses = 0;
        });
        p.properties = []; p.money = 0;
        document.getElementById('pawn-'+p.id).classList.add('bankrupt-pawn');
        document.getElementById('ui-p'+p.id).classList.add('bankrupt-ui');
        Swal.fire('Bangkrut!', `Player ${p.id+1} telah bangkrut dan keluar dari permainan.`, 'error').then(() => endTurn());
    }

    function promptEndTurn() {
        Swal.fire({
            title: 'Akhiri Giliran?', text: "Yakin ingin mengakhiri giliranmu?", icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#6b7280', confirmButtonText: 'Ya, Akhiri!'
        }).then((result) => { if (result.isConfirmed) endTurn(); });
    }

    function endTurn() {
        document.getElementById(`ui-p${currentTurn}`).classList.remove('active-turn');
        do { currentTurn = (currentTurn + 1) % 4; } while (players[currentTurn].isBankrupt);

        document.getElementById(`ui-p${currentTurn}`).classList.add('active-turn');
        document.getElementById('turn-indicator').innerText = `GILIRAN PLAYER ${currentTurn + 1}`; 
        document.getElementById('turn-indicator').className = `fw-bold mb-1 ${pColors[currentTurn]}`;
        
        hasRolled = false; updateUI(); startAutoRoll(); // Timer mulai lagi untuk player baru

        Swal.fire({
            title: `<span class="${pColors[currentTurn]}">Giliran Player ${currentTurn + 1}</span>`,
            background: pBgSoft[currentTurn], allowOutsideClick: false, confirmButtonText: 'OK',
            didOpen: () => { Swal.disableButtons(); setTimeout(() => { Swal.enableButtons(); }, 2000); }
        });
    }

    function updatePawnPositions() {
        players.forEach(p => {
            if(p.isBankrupt) return;
            const tileEl = document.getElementById(`tile-${p.pos}`); const pawnEl = document.getElementById(`pawn-${p.id}`);
            if (tileEl && pawnEl) {
                const rect = tileEl.getBoundingClientRect(); const boardRect = document.getElementById('board').getBoundingClientRect();
                const top = rect.top - boardRect.top + (rect.height/2) - 15; const left = rect.left - boardRect.left + (rect.width/2) - 15;
                const offsetX = p.id % 2 === 0 ? -12 : 12; const offsetY = p.id < 2 ? -12 : 12;
                pawnEl.style.transform = `translate(${left + offsetX}px, ${top + offsetY}px)`;
            }
        });
    }
</script>

</body>
</html>