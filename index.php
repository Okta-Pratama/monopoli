<?php
session_start();

@include 'questions.php'; 
if (!isset($questions)) {
    $questions = ['K'=>[['soal'=>'Q1 dari 2,4,6?','jawaban_kunci'=>'2','poin'=>10]],'M'=>[['soal'=>'Median 2,4,6?','jawaban_kunci'=>'4','poin'=>10]],'D'=>[['soal'=>'Modus 2,2,3?','jawaban_kunci'=>'2','poin'=>10]],'J'=>[['soal'=>'Jangkauan 2,10?','jawaban_kunci'=>'8','poin'=>10]],'R'=>[['soal'=>'Rata-rata 2,4,6?','jawaban_kunci'=>'4','poin'=>10]]];
}

// Data 40 Petak Papan (Revisi Petak Denda Lokal)
$board = [
    // Baris Bawah (0 - 10)
    0 => ['tipe' => 'corner', 'nama' => 'START', 'warna' => '#e8e8eb', 'harga' => 0],
    1 => ['tipe' => 'properti', 'nama' => 'Bekasi', 'grup' => '#8b4513', 'harga' => 60, 'sewa' => 2],
    2 => ['tipe' => 'dana_umum', 'nama' => 'Dana Umum', 'warna' => '#fff', 'harga' => 0],
    3 => ['tipe' => 'properti', 'nama' => 'Tangerang', 'grup' => '#8b4513', 'harga' => 60, 'sewa' => 4],
    4 => ['tipe' => 'pajak', 'nama' => 'Kena Tilang', 'warna' => '#fff', 'harga' => 200], // Revisi Lokal
    5 => ['tipe' => 'bandara', 'nama' => 'Stasiun Gambir', 'warna' => '#ccc', 'harga' => 200],
    6 => ['tipe' => 'properti', 'nama' => 'Bogor', 'grup' => '#87cefa', 'harga' => 100, 'sewa' => 6],
    7 => ['tipe' => 'kesempatan', 'nama' => 'Kesempatan', 'warna' => '#fff', 'harga' => 0],
    8 => ['tipe' => 'properti', 'nama' => 'Depok', 'grup' => '#87cefa', 'harga' => 100, 'sewa' => 6],
    9 => ['tipe' => 'properti', 'nama' => 'Gresik', 'grup' => '#87cefa', 'harga' => 120, 'sewa' => 8],
    10 => ['tipe' => 'corner', 'nama' => 'Penjara', 'warna' => '#e8e8eb', 'harga' => 0],
    // Baris Kiri (11 - 19)
    11 => ['tipe' => 'properti', 'nama' => 'Sidoarjo', 'grup' => '#da70d6', 'harga' => 140, 'sewa' => 10],
    12 => ['tipe' => 'utilitas', 'nama' => 'Perpustakaan', 'warna' => '#fff', 'harga' => 150],
    13 => ['tipe' => 'properti', 'nama' => 'Malang', 'grup' => '#da70d6', 'harga' => 140, 'sewa' => 10],
    14 => ['tipe' => 'properti', 'nama' => 'Solo', 'grup' => '#da70d6', 'harga' => 160, 'sewa' => 12],
    15 => ['tipe' => 'bandara', 'nama' => 'Bandara Juanda', 'warna' => '#ccc', 'harga' => 200],
    16 => ['tipe' => 'properti', 'nama' => 'Semarang', 'grup' => '#ffa500', 'harga' => 180, 'sewa' => 14],
    17 => ['tipe' => 'dana_umum', 'nama' => 'Dana Umum', 'warna' => '#fff', 'harga' => 0],
    18 => ['tipe' => 'properti', 'nama' => 'Yogyakarta', 'grup' => '#ffa500', 'harga' => 180, 'sewa' => 14],
    19 => ['tipe' => 'properti', 'nama' => 'Bandung', 'grup' => '#ffa500', 'harga' => 200, 'sewa' => 16],
    // Baris Atas (20 - 30)
    20 => ['tipe' => 'corner', 'nama' => 'Bebas Parkir', 'warna' => '#e8e8eb', 'harga' => 0],
    21 => ['tipe' => 'properti', 'nama' => 'Surabaya', 'grup' => '#ff0000', 'harga' => 220, 'sewa' => 18],
    22 => ['tipe' => 'kesempatan', 'nama' => 'Kesempatan', 'warna' => '#fff', 'harga' => 0],
    23 => ['tipe' => 'properti', 'nama' => 'Denpasar', 'grup' => '#ff0000', 'harga' => 220, 'sewa' => 18],
    24 => ['tipe' => 'properti', 'nama' => 'Mataram', 'grup' => '#ff0000', 'harga' => 240, 'sewa' => 20],
    25 => ['tipe' => 'bandara', 'nama' => 'Bandara Ngurah Rai', 'warna' => '#ccc', 'harga' => 200],
    26 => ['tipe' => 'properti', 'nama' => 'Makassar', 'grup' => '#ffff00', 'harga' => 260, 'sewa' => 22],
    27 => ['tipe' => 'properti', 'nama' => 'Manado', 'grup' => '#ffff00', 'harga' => 260, 'sewa' => 22],
    28 => ['tipe' => 'utilitas', 'nama' => 'Lab Komputer', 'warna' => '#fff', 'harga' => 150],
    29 => ['tipe' => 'properti', 'nama' => 'Balikpapan', 'grup' => '#ffff00', 'harga' => 280, 'sewa' => 24],
    30 => ['tipe' => 'corner', 'nama' => 'Masuk Penjara!', 'warna' => '#e8e8eb', 'harga' => 0],
    // Baris Kanan (31 - 39)
    31 => ['tipe' => 'properti', 'nama' => 'Samarinda', 'grup' => '#008000', 'harga' => 300, 'sewa' => 26],
    32 => ['tipe' => 'properti', 'nama' => 'Pontianak', 'grup' => '#008000', 'harga' => 300, 'sewa' => 26],
    33 => ['tipe' => 'dana_umum', 'nama' => 'Dana Umum', 'warna' => '#fff', 'harga' => 0],
    34 => ['tipe' => 'properti', 'nama' => 'Banjarmasin', 'grup' => '#008000', 'harga' => 320, 'sewa' => 28],
    35 => ['tipe' => 'bandara', 'nama' => 'Bandara Kualanamu', 'warna' => '#ccc', 'harga' => 200],
    36 => ['tipe' => 'kesempatan', 'nama' => 'Kesempatan', 'warna' => '#fff', 'harga' => 0],
    37 => ['tipe' => 'properti', 'nama' => 'Palembang', 'grup' => '#0000cd', 'harga' => 350, 'sewa' => 35],
    38 => ['tipe' => 'pajak', 'nama' => 'Bayar PBB', 'warna' => '#fff', 'harga' => 100], // Revisi Lokal
    39 => ['tipe' => 'properti', 'nama' => 'Medan', 'grup' => '#0000cd', 'harga' => 400, 'sewa' => 50]
];

function formatRp($val) { return 'Rp ' . number_format($val * 1000, 0, ',', '.'); }
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MONOPOLI - Statistika</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <style>
        body { 
            background-color: #eef2f5; 
            font-family: 'Poppins', sans-serif; 
            margin: 0; padding: 20px; overflow-x: hidden; color: #333;
        }

        /* --- GRID PAPAN --- */
        .monopoly-board { 
            display: grid; grid-template-columns: 120px repeat(9, 80px) 120px; grid-template-rows: 120px repeat(9, 80px) 120px; 
            gap: 2px; background: #2c3e50; border: 8px solid #2c3e50; border-radius: 15px; width: fit-content; margin: 20px auto; 
            box-shadow: 0 20px 40px rgba(0,0,0,0.15); position: relative; overflow: hidden;
        }
        .tile { background: #fdfdfd; position: relative; display: flex; flex-direction: column; align-items: center; justify-content: space-between; text-align: center; font-size: 0.65rem; padding: 2px; }
        .tile-color-bar { width: 100%; height: 14px; border-bottom: 2px solid rgba(0,0,0,0.1); }
        .tile.corner { background: #e8eaed; font-size: 0.9rem; font-weight: bold; justify-content: center; }
        .house-indicator { position: absolute; top: 16px; width: 100%; display: flex; justify-content: center; gap: 3px; z-index: 10; font-size: 0.65rem;}

        /* Posisi 40 Petak */
        .tile-0 { grid-column: 11; grid-row: 11; } .tile-1 { grid-column: 10; grid-row: 11; } .tile-2 { grid-column: 9; grid-row: 11; } .tile-3 { grid-column: 8; grid-row: 11; } .tile-4 { grid-column: 7; grid-row: 11; } .tile-5 { grid-column: 6; grid-row: 11; } .tile-6 { grid-column: 5; grid-row: 11; } .tile-7 { grid-column: 4; grid-row: 11; } .tile-8 { grid-column: 3; grid-row: 11; } .tile-9 { grid-column: 2; grid-row: 11; } .tile-10 { grid-column: 1; grid-row: 11; }
        .tile-11 { grid-column: 1; grid-row: 10; } .tile-12 { grid-column: 1; grid-row: 9; } .tile-13 { grid-column: 1; grid-row: 8; } .tile-14 { grid-column: 1; grid-row: 7; } .tile-15 { grid-column: 1; grid-row: 6; } .tile-16 { grid-column: 1; grid-row: 5; } .tile-17 { grid-column: 1; grid-row: 4; } .tile-18 { grid-column: 1; grid-row: 3; } .tile-19 { grid-column: 1; grid-row: 2; }
        .tile-20 { grid-column: 1; grid-row: 1; } .tile-21 { grid-column: 2; grid-row: 1; } .tile-22 { grid-column: 3; grid-row: 1; } .tile-23 { grid-column: 4; grid-row: 1; } .tile-24 { grid-column: 5; grid-row: 1; } .tile-25 { grid-column: 6; grid-row: 1; } .tile-26 { grid-column: 7; grid-row: 1; } .tile-27 { grid-column: 8; grid-row: 1; } .tile-28 { grid-column: 9; grid-row: 1; } .tile-29 { grid-column: 10; grid-row: 1; } .tile-30 { grid-column: 11; grid-row: 1; }
        .tile-31 { grid-column: 11; grid-row: 2; } .tile-32 { grid-column: 11; grid-row: 3; } .tile-33 { grid-column: 11; grid-row: 4; } .tile-34 { grid-column: 11; grid-row: 5; } .tile-35 { grid-column: 11; grid-row: 6; } .tile-36 { grid-column: 11; grid-row: 7; } .tile-37 { grid-column: 11; grid-row: 8; } .tile-38 { grid-column: 11; grid-row: 9; } .tile-39 { grid-column: 11; grid-row: 10; }

        /* --- AREA TENGAH ELEGAN --- */
        .center-board { 
            grid-column: 2 / 11; grid-row: 2 / 11; 
            background: radial-gradient(circle at center, #ffffff 0%, #eef2f5 100%);
            display: flex; flex-direction: column; align-items: center; justify-content: space-between; 
            padding: 30px 20px; box-shadow: inset 0 0 40px rgba(0,0,0,0.03);
        }

        /* 1. Panel Pemain (Minimalis, Atas) */
        .players-wrapper { display: flex; gap: 20px; width: 100%; justify-content: center; }
        .player-card { 
            background: white; border-radius: 16px; width: 160px; padding: 15px 10px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.05); text-align: center; border: 1px solid #eaeaea;
            transition: transform 0.3s ease, box-shadow 0.3s ease; position: relative;
        }
        .player-card.active-turn { transform: translateY(-8px); box-shadow: 0 12px 25px rgba(0,0,0,0.1); border-color: #ddd; }
        .p-indicator-0 { background-color: #ef4444; } .p-indicator-1 { background-color: #3b82f6; }
        .p-indicator-2 { background-color: #10b981; } .p-indicator-3 { background-color: #f59e0b; }
        .p-dot { width: 12px; height: 12px; border-radius: 50%; display: inline-block; margin-right: 5px; }
        
        /* Inventori Kartu Tumpuk Ke Bawah (Revisi) */
        .inventory-list {
            display: flex; flex-direction: column; align-items: center; 
            margin-top: 15px; position: relative; height: 40px; /* Ruang untuk tumpukan */
        }
        .inv-card-item {
            background: #fff; border: 1px solid #ccc; border-radius: 4px; 
            padding: 2px 6px; font-size: 0.65rem; font-weight: 600; color: #444;
            position: absolute; width: 90%; box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: transform 0.2s; cursor: pointer;
        }
        .inv-card-item:hover { transform: translateX(10px) scale(1.05); z-index: 20 !important; }

        /* 2. Area Aksi (Tengah - Dadu & Stok) */
        .action-panel {
            display: flex; align-items: center; justify-content: center; gap: 40px;
            background: white; padding: 15px 30px; border-radius: 50px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid #f0f0f0;
        }
        .stock-minimal { text-align: center; color: #888; font-weight: 600; }
        .stock-minimal i { font-size: 1.5rem; margin-bottom: 5px; }
        .dice-area { text-align: center; padding: 0 20px; border-left: 2px dashed #eee; border-right: 2px dashed #eee; }
        .btn-roll { background: #2c3e50; color: white; border: none; font-weight: 700; letter-spacing: 1px; padding: 10px 30px; border-radius: 30px; transition: 0.3s; }
        .btn-roll:hover { background: #1a252f; transform: scale(1.05); }

        /* 3. Tumpukan Kartu Board (Bawah) */
        .card-decks { display: flex; gap: 30px; }
        .deck { 
            width: 120px; height: 160px; border-radius: 12px; 
            display: flex; flex-direction: column; align-items: center; justify-content: center; 
            font-weight: 700; text-align: center; color: white; cursor: pointer; 
            font-size: 0.8rem; letter-spacing: 0.5px; padding: 10px;
            background-image: url('src/images/board/back.png'); 
            background-size: cover; background-blend-mode: overlay;
            box-shadow: -2px 2px 0 #fff, -4px 4px 0 #ddd, -6px 6px 15px rgba(0,0,0,0.2);
            transition: transform 0.2s;
        }
        .deck:active { transform: translate(-2px, 2px); box-shadow: 0 0 5px rgba(0,0,0,0.3); }
        .deck i { font-size: 2.5rem; margin-bottom: 15px; }
        .deck-chance { background-color: #f39c12; }
        .deck-chest { background-color: #3498db; }
        .deck-stats { background-color: #e74c3c; }

        /* KOMPONEN POPUP KARTU REALISTIS (Revisi 3) */
        .swal-custom-card {
            background: transparent !important; box-shadow: none !important;
        }
        .real-card-popup {
            width: 260px; height: 380px; margin: 0 auto; border-radius: 15px;
            background: #fff; border: 12px solid #fff; box-shadow: 0 20px 50px rgba(0,0,0,0.5);
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            text-align: center; position: relative; overflow: hidden;
        }
        .real-card-chance { border-color: #f39c12; color: #d35400; background: #fffdf5; }
        .real-card-chest { border-color: #3498db; color: #2980b9; background: #f4f9fd; }
        .real-card-stats { border-color: #e74c3c; color: #c0392b; background: #fdf5f5; }
        .real-card-popup i { font-size: 4rem; margin-bottom: 20px; opacity: 0.9; }
        .real-card-popup h3 { font-weight: 800; font-size: 1.2rem; letter-spacing: 2px; border-bottom: 2px solid; padding-bottom: 10px; width: 80%; margin: 0 auto 20px; }
        .real-card-popup p { font-size: 0.95rem; font-weight: 600; padding: 0 15px; line-height: 1.5; color: #444; }

        /* Bidak */
        .pawn-container { width: 25px; height: 25px; position: absolute; z-index: 50; display: flex; align-items: center; justify-content: center; transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1); text-shadow: 1px 1px 3px rgba(0,0,0,0.6); }
        .text-player-0 { color: #ef4444 !important; } .text-player-1 { color: #3b82f6 !important; }
        .text-player-2 { color: #10b981 !important; } .text-player-3 { color: #f59e0b !important; }
    </style>
</head>
<body>

<script>
    const BOARD = <?= json_encode($board) ?>;
    const QUESTIONS = <?= json_encode($questions) ?>;
</script>

<div class="monopoly-board" id="board">
    <?php foreach ($board as $index => $petak): ?>
        <div class="tile tile-<?= $index ?> <?= $petak['tipe'] ?>" id="tile-<?= $index ?>">
            <div class="house-indicator" id="houses-<?= $index ?>"></div> 
            <?php if(isset($petak['grup'])): ?>
                <div class="tile-color-bar" style="background-color: <?= $petak['grup'] ?>"></div>
            <?php endif; ?>
            <div class="fw-bold mt-1 px-1" style="font-size: 0.6rem; line-height: 1.2; letter-spacing: 0.3px;"><?= $petak['nama'] ?></div>
            <?php if($petak['harga'] > 0): ?>
                <div class="text-danger fw-bold pb-1" style="font-size: 0.55rem;"><?= formatRp($petak['harga']) ?></div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

    <div class="center-board">
        
        <div class="players-wrapper">
            <?php for($i=0; $i<4; $i++): ?>
            <div id="ui-p<?= $i ?>" class="player-card">
                <div class="fw-bold text-dark" style="font-size: 0.9rem;">
                    <span class="p-dot p-indicator-<?= $i ?>"></span>Player <?= $i+1 ?>
                </div>
                <div class="fw-bold text-success mt-2 mb-2 fs-6" id="money-p<?= $i ?>">Rp 1.500.000</div>
                <span id="jail-badge-<?= $i ?>" class="badge bg-dark w-100 mb-2 d-none">DI PENJARA</span>
                
                <div class="inventory-list" id="inv-p<?= $i ?>"></div>
            </div>
            <?php endfor; ?>
        </div>

        <div class="action-panel">
            <div class="stock-minimal" style="color: #10b981;">
                <i class="fa-solid fa-house"></i><br>
                <span class="fs-4" id="stock-rumah">32</span>
            </div>

            <div class="dice-area">
                <div id="turn-indicator" class="fw-bold text-player-0 mb-1" style="font-size: 0.85rem; letter-spacing: 1px;">GILIRAN PLAYER 1</div>
                <i id="dice-css-icon" class="fa-solid fa-dice-one text-secondary" style="font-size: 3rem; margin: 10px 0;"></i><br>
                <button id="btn-roll" class="btn-roll mt-2" onclick="processTurn()">KOCOK DADU</button>
            </div>

            <div class="stock-minimal" style="color: #ef4444;">
                <i class="fa-solid fa-building"></i><br>
                <span class="fs-4" id="stock-hotel">11</span>
            </div>
        </div>

        <div class="card-decks">
            <div class="deck deck-chance" onclick="drawCard('chance')">
                <i class="fa-solid fa-question"></i><span>KESEMPATAN</span>
            </div>
            <div class="deck deck-stats" onclick="Swal.fire('Info', 'Terbuka saat membeli properti.', 'info')">
                <i class="fa-solid fa-brain"></i><span>STATISTIKA</span>
            </div>
            <div class="deck deck-chest" onclick="drawCard('chest')">
                <i class="fa-solid fa-dollar"></i><span>DANA UMUM</span>
            </div>
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
        move: new Audio('src/sfx/place.mp3'),
        door: new Audio('src/sfx/door-opening.mp3'), 
        jail: new Audio('src/sfx/jail.mp3'),         
        build: new Audio('src/sfx/building.mp3'),
        bell: new Audio('src/sfx/bell.mp3'),
        dice: new Audio('src/sfx/roll-dice.mp3'),
        card: new Audio('src/sfx/taking-card.mp3'),
        buy: new Audio('src/sfx/purchase.mp3')
    };

    // Palet warna khusus pemain (Revisi Ikon Rumah & Bidak)
    const pColors = ['text-player-0', 'text-player-1', 'text-player-2', 'text-player-3'];

    function playSfx(sound) {
        if(!sound) return;
        sound.currentTime = 0; sound.play().catch(e => console.log('SFX Blocked:', e));
    }

    let players = [
        { id: 0, pos: 0, money: 1500, properties: [], cards: [], wrongAnswers: 0, inJail: false, jailTurns: 0 },
        { id: 1, pos: 0, money: 1500, properties: [], cards: [], wrongAnswers: 0, inJail: false, jailTurns: 0 },
        { id: 2, pos: 0, money: 1500, properties: [], cards: [], wrongAnswers: 0, inJail: false, jailTurns: 0 },
        { id: 3, pos: 0, money: 1500, properties: [], cards: [], wrongAnswers: 0, inJail: false, jailTurns: 0 }
    ];
    let currentTurn = 0; let stockRumah = 32; let stockHotel = 11;
    
    window.onload = () => { updatePawnPositions(); document.getElementById(`ui-p0`).classList.add('active-turn'); };

    // --- REVISI 2: Update UI Inventori List Ke Bawah ---
    function updateUI() {
        document.getElementById('stock-rumah').innerText = stockRumah; 
        document.getElementById('stock-hotel').innerText = stockHotel;
        
        players.forEach(p => {
            document.getElementById(`money-p${p.id}`).innerText = formatRp(p.money);
            document.getElementById(`jail-badge-${p.id}`).classList.toggle('d-none', !p.inJail);
            
            const invDiv = document.getElementById(`inv-p${p.id}`);
            invDiv.innerHTML = ''; 
            let zIdx = 1;
            
            // Tumpukan Properti
            p.properties.forEach((prop, i) => { 
                invDiv.innerHTML += `<div class="inv-card-item" style="top: ${i*18}px; z-index: ${zIdx++}; border-top: 3px solid ${prop.grup}">${prop.nama}</div>`; 
            });
            // Tumpukan Kartu Khusus
            p.cards.forEach((c, i) => { 
                invDiv.innerHTML += `<div class="inv-card-item" style="top: ${(p.properties.length+i)*18}px; z-index: ${zIdx++}; border-top: 3px solid #f39c12">${c.nama}</div>`; 
            });
            // Sesuaikan tinggi kontainer agar list tidak menabrak elemen lain
            invDiv.style.height = `${(p.properties.length + p.cards.length) * 18 + 20}px`;
        });
    }

    function processTurn() {
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
        if(players[id].money >= 50) {
            players[id].money -= 50; players[id].inJail = false; players[id].wrongAnswers = 0;
            playSfx(sfx.door); Swal.fire('Bebas!', 'Denda dibayar.', 'success').then(() => rollAndMove(players[id])); updateUI();
        } else { Swal.fire('Uang Kurang', '', 'error'); }
    }
    function skipJailTurn(id) {
        players[id].jailTurns++;
        if(players[id].jailTurns >= 2) { players[id].inJail = false; players[id].wrongAnswers = 0; playSfx(sfx.door); }
        Swal.close(); nextTurn();
    }
    function useJailCard(id) {
        let p = players[id]; let cIdx = p.cards.findIndex(c => c.type === 'free_jail');
        p.cards.splice(cIdx, 1); p.inJail = false; p.wrongAnswers = 0; updateUI();
        playSfx(sfx.door); Swal.fire('Bebas!', 'Kartu digunakan.', 'success').then(() => { if(p.pos === 10) rollAndMove(p); else actionPhase(p, BOARD[p.pos]); });
    }

    function rollAndMove(p) {
        let diceIcon = document.getElementById('dice-css-icon');
        const dCls = ['fa-dice-one', 'fa-dice-two', 'fa-dice-three', 'fa-dice-four', 'fa-dice-five', 'fa-dice-six'];
        let rollCount = 0; playSfx(sfx.dice); 

        let rollInterval = setInterval(() => {
            diceIcon.className = `fa-solid ${dCls[Math.floor(Math.random() * 6)]} text-secondary`;
            rollCount++;
            
            if (rollCount > 10) {
                clearInterval(rollInterval);
                let finalDice = Math.floor(Math.random() * 6) + 1;
                diceIcon.className = `fa-solid ${dCls[finalDice - 1]} ${pColors[p.id]} animate__animated animate__bounceIn`;
                
                let step = 0;
                let stepInterval = setInterval(() => {
                    step++; p.pos = (p.pos + 1) % 40; playSfx(sfx.move);
                    
                    if (p.pos === 0) { 
                        p.money += 200; updateUI(); 
                        Swal.fire({toast:true, position:'top-end', title:`Melewati START! +${formatRp(200)}`, icon:'success', showConfirmButton:false, timer:2000});
                    } 
                    updatePawnPositions();

                    if(step === finalDice) {
                        clearInterval(stepInterval);
                        setTimeout(() => handleTileLogic(p, BOARD[p.pos]), 400);
                    }
                }, 300);
            }
        }, 80);
    }

    function handleTileLogic(p, tile) {
        if (p.pos === 30) { goToJail(p); } 
        else if (tile.tipe === 'pajak') {
            p.money -= tile.harga; updateUI();
            Swal.fire('Kena Denda!', `Kamu ${tile.nama} sebesar ${formatRp(tile.harga)}`, 'error').then(() => nextTurn());
        } 
        else if (tile.tipe === 'properti' || tile.tipe === 'bandara' || tile.tipe === 'utilitas') {
            if (!tile.owner && tile.owner !== 0) { triggerStatsQuestion(p, tile); } 
            else if (tile.owner !== p.id) { payRent(p, tile); } 
            else { actionPhase(p, tile); }
        } 
        else if (tile.tipe === 'kesempatan' || tile.tipe === 'dana_umum') {
            drawCard(tile.tipe, p);
        } else { actionPhase(p, tile); }
    }

    // --- REVISI 3: Komponen Kartu Statistika (Popup Realistis) ---
    function triggerStatsQuestion(p, tile) {
        playSfx(sfx.card);
        const qTypes = ['K', 'M', 'D', 'J', 'R'];
        const randT = qTypes[Math.floor(Math.random() * qTypes.length)];
        const qList = QUESTIONS[randT]; const q = qList[Math.floor(Math.random() * qList.length)];

        let cardHTML = `
            <div class="real-card-popup real-card-stats animate__animated animate__flipInY">
                <i class="fa-solid fa-brain"></i>
                <h3>STATISTIKA</h3>
                <p>${q.soal}</p>
                <div style="font-size:0.8rem; margin-top:20px; color:#888;">(Reward: ${q.poin} Koin)</div>
            </div>
        `;

        Swal.fire({
            html: cardHTML, input: 'text', inputPlaceholder: 'Jawaban Anda...',
            customClass: { popup: 'swal-custom-card' },
            showCancelButton: false, allowOutsideClick: false, confirmButtonText: 'Kunci Jawaban'
        }).then((res) => {
            if (res.value.toLowerCase() == q.jawaban_kunci.toLowerCase()) {
                p.wrongAnswers = 0; p.money += q.poin; updateUI();
                Swal.fire('Tepat Sekali!', `Jawaban Benar. Silakan bertransaksi.`, 'success').then(() => actionPhase(p, tile));
            } else {
                p.wrongAnswers++;
                if (p.wrongAnswers >= 3) {
                    Swal.fire('Salah 3x!', 'Kamu dihukum masuk Penjara!', 'error').then(() => goToJail(p));
                } else {
                    Swal.fire('Jawaban Keliru!', `Jawaban yang benar: ${q.jawaban_kunci}. Peringatan: ${p.wrongAnswers}/3.`, 'warning').then(() => nextTurn());
                }
            }
        });
    }

    function actionPhase(p, tile) {
        let htmlOpts = '';
        if ((tile.tipe === 'properti' || tile.tipe === 'bandara' || tile.tipe === 'utilitas') && (!tile.owner && tile.owner !== 0) && p.money >= tile.harga) {
            htmlOpts += `<button class="btn btn-success m-2 fw-bold" onclick="buyProperty(${p.id}, ${p.pos})">Beli Tanah (${formatRp(tile.harga)})</button><br>`;
        }
        if (tile.owner === p.id && tile.tipe === 'properti') {
            htmlOpts += `<button class="btn btn-warning m-2 fw-bold text-dark" onclick="buildHouse(${p.id}, ${p.pos})"><i class="fa-solid fa-hammer"></i> Bangun Properti (${formatRp(50)})</button><br>`;
        }
        if (p.cards.find(c => c.type === 'free_jail') && p.inJail) {
            htmlOpts += `<button class="btn btn-info m-2 fw-bold" onclick="useJailCard(${p.id})">Gunakan Kartu Bebas</button><br>`;
        }
        htmlOpts += `<button class="btn btn-danger m-2 px-4 fw-bold" onclick="Swal.close(); nextTurn();">Akhiri Giliran</button>`;
        Swal.fire({ title: 'Fase Aksi', html: htmlOpts, showConfirmButton: false, allowOutsideClick: false });
    }

    function buyProperty(playerId, tileIndex) {
        let p = players[playerId]; let tile = BOARD[tileIndex];
        p.money -= tile.harga; tile.owner = p.id; p.properties.push(tile);
        playSfx(sfx.buy);
        
        // Tandai petak dengan warna pemain
        const colorHex = ['#ef4444', '#3b82f6', '#10b981', '#f59e0b'];
        document.getElementById(`tile-${tileIndex}`).style.border = `4px solid ${colorHex[p.id]}`; 
        
        updateUI();
        Swal.fire('Terbeli!', `${tile.nama} resmi menjadi milikmu.`, 'success').then(() => actionPhase(p, tile));
    }

    // --- REVISI 4: Warna Ikon Rumah Sesuai Pemain ---
    function buildHouse(playerId, tileIndex) {
        let p = players[playerId]; let tile = BOARD[tileIndex];
        if (p.money < 50) return Swal.fire('Dana Kurang', '', 'error').then(() => actionPhase(p, tile));
        if (!tile.houses) tile.houses = 0;

        // Gunakan pColors[playerId] agar warna ikon mengikuti warna pemain
        let pColorClass = pColors[playerId]; 

        if (tile.houses < 4) {
            if (stockRumah <= 0) return Swal.fire('Habis', 'Stok Rumah Bank Habis', 'error').then(() => actionPhase(p, tile));
            tile.houses++; stockRumah--; p.money -= 50;
            document.getElementById(`houses-${tileIndex}`).innerHTML += `<i class="fa-solid fa-house ${pColorClass}"></i>`;
            playSfx(sfx.build); updateUI();
            Swal.fire('Sukses', '1 Rumah dibangun.', 'success').then(() => actionPhase(p, tile));
        } else if (tile.houses === 4) {
            if (stockHotel <= 0) return Swal.fire('Habis', 'Stok Hotel Habis', 'error').then(() => actionPhase(p, tile));
            tile.houses = 5; stockRumah += 4; stockHotel--; p.money -= 50;
            document.getElementById(`houses-${tileIndex}`).innerHTML = `<i class="fa-solid fa-building ${pColorClass} fs-6"></i>`;
            playSfx(sfx.build); updateUI();
            Swal.fire('Megah!', 'Hotel dibangun.', 'success').then(() => actionPhase(p, tile));
        } else {
            Swal.fire('Maksimal', 'Properti penuh.', 'info').then(() => actionPhase(p, tile));
        }
    }

    function payRent(p, tile) {
        let rent = tile.sewa || 25; if(tile.houses) rent += (tile.houses * 20); 
        p.money -= rent; players[tile.owner].money += rent; updateUI(); playSfx(sfx.bell);
        Swal.fire('Bayar Sewa!', `Membayar ${formatRp(rent)} ke Player ${tile.owner + 1}`, 'warning').then(() => actionPhase(p, tile));
    }

    function goToJail(p) {
        p.pos = 10; p.inJail = true; p.jailTurns = 0; playSfx(sfx.jail); 
        updatePawnPositions(); updateUI(); nextTurn();
    }

    // --- REVISI 3: Komponen Kartu Kesempatan/Dana Umum (Popup Realistis) ---
    function drawCard(type, p) {
        playSfx(sfx.card);
        let isChance = (type === 'chance');
        let cardTypeClass = isChance ? 'real-card-chance' : 'real-card-chest';
        let cardTitle = isChance ? 'KESEMPATAN' : 'DANA UMUM';
        let cardIcon = isChance ? 'fa-question' : 'fa-dollar';
        let isGetOutJail = Math.random() > 0.8; 

        let msg = isGetOutJail ? "Selamat! Kamu mendapatkan Kartu Bebas Penjara yang bisa disimpan." : "Bonus rezeki tak terduga! Bank memberikanmu dana tambahan sebesar Rp 150.000.";

        let cardHTML = `
            <div class="real-card-popup ${cardTypeClass} animate__animated animate__zoomIn">
                <i class="fa-solid ${cardIcon}"></i>
                <h3>${cardTitle}</h3>
                <p>${msg}</p>
            </div>
        `;

        Swal.fire({
            html: cardHTML,
            customClass: { popup: 'swal-custom-card' },
            showConfirmButton: true, confirmButtonText: 'Ambil'
        }).then(() => {
            if (isGetOutJail && p) {
                p.cards.push({ nama: 'Bebas Penjara', type: 'free_jail' }); 
            } else if (p) {
                p.money += 150; 
            }
            updateUI(); nextTurn();
        });
    }

    function nextTurn() {
        document.getElementById(`ui-p${currentTurn}`).classList.remove('active-turn');
        currentTurn = (currentTurn + 1) % 4;
        document.getElementById(`ui-p${currentTurn}`).classList.add('active-turn');
        
        document.getElementById('turn-indicator').innerText = `GILIRAN PLAYER ${currentTurn + 1}`; 
        document.getElementById('turn-indicator').className = `fw-bold mb-1 ${pColors[currentTurn]}`;
        
        document.getElementById('dice-css-icon').className = 'fa-solid fa-dice-one text-secondary animate__animated animate__fadeIn';
        document.getElementById('btn-roll').disabled = false; updateUI();
    }

    function updatePawnPositions() {
        players.forEach(p => {
            const tileEl = document.getElementById(`tile-${p.pos}`);
            const pawnEl = document.getElementById(`pawn-${p.id}`);
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