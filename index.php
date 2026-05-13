<?php
session_start();

// Data 40 Petak Papan MONOPOLI
$board = [
    0 => ['tipe' => 'corner', 'nama' => 'START', 'warna' => '#e8e8eb', 'harga' => 0],
    1 => ['tipe' => 'properti', 'nama' => 'Jakarta', 'grup' => '#8b4513', 'harga' => 60, 'sewa' => 2],
    2 => ['tipe' => 'dana_umum', 'nama' => 'Dana Umum', 'warna' => '#fff', 'harga' => 0],
    3 => ['tipe' => 'properti', 'nama' => 'Bali', 'grup' => '#8b4513', 'harga' => 60, 'sewa' => 4],
    4 => ['tipe' => 'pajak', 'nama' => 'Pajak Studi', 'warna' => '#fff', 'harga' => 200],
    5 => ['tipe' => 'bandara', 'nama' => 'Band. Soetta', 'warna' => '#ccc', 'harga' => 200],
    6 => ['tipe' => 'properti', 'nama' => 'Tokyo', 'grup' => '#87cefa', 'harga' => 100, 'sewa' => 6],
    7 => ['tipe' => 'kesempatan', 'nama' => 'Kesempatan', 'warna' => '#fff', 'harga' => 0],
    8 => ['tipe' => 'properti', 'nama' => 'Seoul', 'grup' => '#87cefa', 'harga' => 100, 'sewa' => 6],
    9 => ['tipe' => 'properti', 'nama' => 'Beijing', 'grup' => '#87cefa', 'harga' => 120, 'sewa' => 8],
    10 => ['tipe' => 'corner', 'nama' => 'Penjara', 'warna' => '#e8e8eb', 'harga' => 0],
    11 => ['tipe' => 'properti', 'nama' => 'Paris', 'grup' => '#da70d6', 'harga' => 140, 'sewa' => 10],
    12 => ['tipe' => 'utilitas', 'nama' => 'Perpustakaan', 'warna' => '#fff', 'harga' => 150],
    13 => ['tipe' => 'properti', 'nama' => 'London', 'grup' => '#da70d6', 'harga' => 140, 'sewa' => 10],
    14 => ['tipe' => 'properti', 'nama' => 'Berlin', 'grup' => '#da70d6', 'harga' => 160, 'sewa' => 12],
    15 => ['tipe' => 'bandara', 'nama' => 'Band. Heathrow', 'warna' => '#ccc', 'harga' => 200],
    16 => ['tipe' => 'properti', 'nama' => 'New York', 'grup' => '#ffa500', 'harga' => 180, 'sewa' => 14],
    17 => ['tipe' => 'dana_umum', 'nama' => 'Dana Umum', 'warna' => '#fff', 'harga' => 0],
    18 => ['tipe' => 'properti', 'nama' => 'Los Angeles', 'grup' => '#ffa500', 'harga' => 180, 'sewa' => 14],
    19 => ['tipe' => 'properti', 'nama' => 'Rio', 'grup' => '#ffa500', 'harga' => 200, 'sewa' => 16],
    20 => ['tipe' => 'corner', 'nama' => 'Bebas Parkir', 'warna' => '#e8e8eb', 'harga' => 0],
    21 => ['tipe' => 'properti', 'nama' => 'Toronto', 'grup' => '#ff0000', 'harga' => 220, 'sewa' => 18],
    22 => ['tipe' => 'kesempatan', 'nama' => 'Kesempatan', 'warna' => '#fff', 'harga' => 0],
    23 => ['tipe' => 'properti', 'nama' => 'Mexico City', 'grup' => '#ff0000', 'harga' => 220, 'sewa' => 18],
    24 => ['tipe' => 'properti', 'nama' => 'Buenos Aires', 'grup' => '#ff0000', 'harga' => 240, 'sewa' => 20],
    25 => ['tipe' => 'bandara', 'nama' => 'Band. JFK', 'warna' => '#ccc', 'harga' => 200],
    26 => ['tipe' => 'properti', 'nama' => 'Kairo', 'grup' => '#ffff00', 'harga' => 260, 'sewa' => 22],
    27 => ['tipe' => 'properti', 'nama' => 'Cape Town', 'grup' => '#ffff00', 'harga' => 260, 'sewa' => 22],
    28 => ['tipe' => 'utilitas', 'nama' => 'Lab Komputer', 'warna' => '#fff', 'harga' => 150],
    29 => ['tipe' => 'properti', 'nama' => 'Nairobi', 'grup' => '#ffff00', 'harga' => 280, 'sewa' => 24],
    30 => ['tipe' => 'corner', 'nama' => 'Masuk Penjara!', 'warna' => '#e8e8eb', 'harga' => 0],
    31 => ['tipe' => 'properti', 'nama' => 'Sydney', 'grup' => '#008000', 'harga' => 300, 'sewa' => 26],
    32 => ['tipe' => 'properti', 'nama' => 'Melbourne', 'grup' => '#008000', 'harga' => 300, 'sewa' => 26],
    33 => ['tipe' => 'dana_umum', 'nama' => 'Dana Umum', 'warna' => '#fff', 'harga' => 0],
    34 => ['tipe' => 'properti', 'nama' => 'Auckland', 'grup' => '#008000', 'harga' => 320, 'sewa' => 28],
    35 => ['tipe' => 'bandara', 'nama' => 'Band. Sydney', 'warna' => '#ccc', 'harga' => 200],
    36 => ['tipe' => 'kesempatan', 'nama' => 'Kesempatan', 'warna' => '#fff', 'harga' => 0],
    37 => ['tipe' => 'properti', 'nama' => 'Amsterdam', 'grup' => '#0000cd', 'harga' => 350, 'sewa' => 35],
    38 => ['tipe' => 'pajak', 'nama' => 'Pajak Mewah', 'warna' => '#fff', 'harga' => 100],
    39 => ['tipe' => 'properti', 'nama' => 'Roma', 'grup' => '#0000cd', 'harga' => 400, 'sewa' => 50]
];

// Integrasi Bank Soal dari PDF Monika Statistika
$questions = [
    'K' => [
        ['level' => 1, 'soal' => 'Tentukan kuartil bawah (Q1) dari data berikut: 2, 4, 6, 8, 10, 12', 'jawaban_kunci' => '4', 'poin' => 10],
        ['level' => 1, 'soal' => 'Sebutkan pengertian kuartil dalam statistika!', 'jawaban_kunci' => 'nilai yang membagi data menjadi 4 bagian sama banyak', 'poin' => 10],
        ['level' => 2, 'soal' => 'Tentukan Q1, Q2, dan Q3 dari data: 3, 5, 7, 9, 11, 13, 15. (Format: Q1, Q2, Q3)', 'jawaban_kunci' => '5, 9, 13', 'poin' => 20],
        ['level' => 2, 'soal' => 'Jelaskan perbedaan antara Q1, Q2, dan Q3!', 'jawaban_kunci' => 'Q1 kuartil bawah, Q2 median, Q3 kuartil atas', 'poin' => 20],
        ['level' => 2, 'soal' => 'Data: 4, 6, 8, 10, 12, 14, 16, 18. Tentukan posisi kuartil atas (Q3)!', 'jawaban_kunci' => '6,75', 'poin' => 20],
        ['level' => 3, 'soal' => 'Data nilai: 60, 65, 70, 75, 80, 85, 90. Hitung Q1, Q2, dan Q3! (Format: Q1, Q2, Q3)', 'jawaban_kunci' => '65, 75, 85', 'poin' => 30],
        ['level' => 4, 'soal' => 'Data: 5, 7, 9, 11, 13, 15, 17, 19, 21. Analisis apakah data tersebut simetris berdasarkan nilai kuartil? (Jawab: Simetris / Tidak)', 'jawaban_kunci' => 'Simetris', 'poin' => 50]
    ],
    'M' => [
        ['level' => 1, 'soal' => 'Tentukan median dari data: 2, 4, 6, 8, 10', 'jawaban_kunci' => '6', 'poin' => 10],
        ['level' => 1, 'soal' => 'Apa yang dimaksud dengan median?', 'jawaban_kunci' => 'nilai tengah data', 'poin' => 10],
        ['level' => 2, 'soal' => 'Tentukan median dari data: 3, 5, 7, 9, 11, 13', 'jawaban_kunci' => '8', 'poin' => 20],
        ['level' => 2, 'soal' => 'Jelaskan cara menentukan median pada jumlah data genap!', 'jawaban_kunci' => 'rata-rata dua nilai tengah', 'poin' => 20],
        ['level' => 2, 'soal' => 'Data: 10, 20, 30, 40, 50, 60. Tentukan median!', 'jawaban_kunci' => '35', 'poin' => 20],
        ['level' => 3, 'soal' => 'Data nilai: 65, 70, 75, 80, 85, 90. Hitung median!', 'jawaban_kunci' => '77,5', 'poin' => 30],
        ['level' => 3, 'soal' => 'Data: 12, 15, 18, 20, 22. Hitung median!', 'jawaban_kunci' => '18', 'poin' => 30],
        ['level' => 4, 'soal' => 'Data: 5, 10, 15, 20, 25, 30, 100. Analisis pengaruh nilai ekstrem terhadap median! (Apakah: terpengaruh / tidak terlalu terpengaruh)', 'jawaban_kunci' => 'tidak terlalu terpengaruh', 'poin' => 50]
    ],
    'D' => [
        ['level' => 1, 'soal' => 'Tentukan modus dari data: 2, 3, 3, 4, 5', 'jawaban_kunci' => '3', 'poin' => 10],
        ['level' => 2, 'soal' => 'Jelaskan apa itu modus!', 'jawaban_kunci' => 'nilai yang paling sering muncul', 'poin' => 20],
        ['level' => 2, 'soal' => 'Tentukan modus dari data: 5, 6, 6, 7, 7, 7, 8', 'jawaban_kunci' => '7', 'poin' => 20],
        ['level' => 2, 'soal' => 'Apakah data berikut memiliki modus? 1, 2, 3, 4, 5', 'jawaban_kunci' => 'Tidak memiliki modus', 'poin' => 20],
        ['level' => 3, 'soal' => 'Data nilai: 70, 75, 75, 80, 85, 85, 85. Tentukan modus!', 'jawaban_kunci' => '85', 'poin' => 30],
        ['level' => 3, 'soal' => 'Data: 10, 10, 20, 20, 30, 30. Tentukan jenis modusnya!', 'jawaban_kunci' => 'Modus ganda', 'poin' => 30],
        ['level' => 4, 'soal' => 'Data A: 2, 2, 3, 4, 5. Data B: 1, 2, 3, 4, 5. Mana yang lebih representatif berdasarkan modus? (Jawab: Data A / Data B)', 'jawaban_kunci' => 'Data A', 'poin' => 50]
    ],
    'J' => [
        ['level' => 1, 'soal' => 'Tentukan jangkauan dari data: 2, 5, 8, 10', 'jawaban_kunci' => '8', 'poin' => 10],
        ['level' => 2, 'soal' => 'Jelaskan pengertian jangkauan!', 'jawaban_kunci' => 'nilai terbesar - terkecil', 'poin' => 20],
        ['level' => 2, 'soal' => 'Tentukan jangkauan data: 10, 20, 30, 40, 50', 'jawaban_kunci' => '40', 'poin' => 20],
        ['level' => 2, 'soal' => 'Mengapa jangkauan penting dalam statistika?', 'jawaban_kunci' => 'Untuk mengetahui penyebaran data', 'poin' => 20],
        ['level' => 3, 'soal' => 'Data nilai: 60, 65, 70, 75, 80. Hitung jangkauan!', 'jawaban_kunci' => '20', 'poin' => 30],
        ['level' => 3, 'soal' => 'Data: 15, 18, 20, 22, 25. Hitung jangkauan!', 'jawaban_kunci' => '10', 'poin' => 30],
        ['level' => 4, 'soal' => 'Data A: 10, 20, 30, 40, 50. Data B: 25, 26, 27, 28, 29. Analisis mana yang lebih menyebar! (Jawab: Data A / Data B)', 'jawaban_kunci' => 'Data A', 'poin' => 50]
    ],
    'R' => [
        ['level' => 1, 'soal' => 'Tentukan rata-rata dari data: 2, 4, 6, 8', 'jawaban_kunci' => '5', 'poin' => 10],
        ['level' => 1, 'soal' => 'Apa yang dimaksud dengan rata-rata?', 'jawaban_kunci' => 'jumlah data dibagi banyak data', 'poin' => 10],
        ['level' => 2, 'soal' => 'Tentukan rata-rata dari data: 10, 20, 30, 40, 50', 'jawaban_kunci' => '30', 'poin' => 20],
        ['level' => 2, 'soal' => 'Jelaskan langkah-langkah menghitung rata-rata!', 'jawaban_kunci' => 'Jumlahkan lalu bagi banyak data', 'poin' => 20],
        ['level' => 2, 'soal' => 'Data: 5, 10, 15, 20. Hitung rata-rata!', 'jawaban_kunci' => '12,5', 'poin' => 20],
        ['level' => 3, 'soal' => 'Data nilai: 70, 75, 80, 85, 90. Hitung rata-rata!', 'jawaban_kunci' => '80', 'poin' => 30],
        ['level' => 4, 'soal' => 'Data: 10, 20, 30, 40, 100. Analisis pengaruh nilai ekstrem terhadap rata-rata! (Jawab: sangat terpengaruh / tidak terpengaruh)', 'jawaban_kunci' => 'sangat terpengaruh', 'poin' => 50]
    ]
];

function formatRp($val) { return 'Rp ' . number_format($val * 1000, 0, ',', '.'); }
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MONOPOLI - Statistika</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <style>
        body { background-color: #d1ebd5; font-family: 'Segoe UI', Tahoma, sans-serif; margin: 0; padding: 20px; overflow-x: hidden; }

        /* UI HUD Pemain & Inventori (Revisi 2: Hover tidak brutal) */
        .player-dashboard { position: fixed; width: 220px; z-index: 100; background: white; border-radius: 12px; box-shadow: 0 8px 16px rgba(0,0,0,0.1); padding: 15px; border-left: 8px solid; transition: transform 0.3s; }
        .player-dashboard.active-turn { transform: scale(1.05); box-shadow: 0 10px 25px rgba(0,0,0,0.2); }
        .p0 { top: 20px; left: 20px; border-color: #dc3545; }
        .p1 { top: 20px; right: 20px; border-color: #0d6efd; }
        .p2 { bottom: 20px; left: 20px; border-color: #198754; }
        .p3 { bottom: 20px; right: 20px; border-color: #ffc107; }

        .inventory-container { display: flex; flex-direction: column; gap: -20px; margin-top: 15px; height: 60px; position: relative; }
        .inventory-card { 
            background: #f8f9fa; border: 1px solid #ddd; border-radius: 6px; padding: 4px 8px; font-size: 0.75rem; 
            font-weight: bold; position: absolute; right: -75px; width: 120px; box-shadow: -2px 2px 5px rgba(0,0,0,0.1); 
            transition: transform 0.2s ease, right 0.2s ease; cursor: pointer; 
        }
        /* REVISI 2: Hover yang halus */
        .inventory-card:hover { right: -65px; transform: scale(1.02); z-index: 10 !important; background: #fff; }

        /* CSS Grid Papan */
        .monopoly-board { display: grid; grid-template-columns: 120px repeat(9, 80px) 120px; grid-template-rows: 120px repeat(9, 80px) 120px; gap: 2px; background: #222; border: 6px solid #222; border-radius: 10px; width: fit-content; margin: 40px auto; box-shadow: 0 15px 30px rgba(0,0,0,0.3); }
        .tile { background: #e3f2fd; position: relative; display: flex; flex-direction: column; align-items: center; justify-content: space-between; text-align: center; font-size: 0.65rem; padding: 2px; }
        /* REVISI 5: Tinggi color bar diubah ke 12px */
        .tile-color-bar { width: 100%; height: 12px; border-bottom: 2px solid #222; }
        .tile.corner { background: #f1f3f5; font-size: 0.9rem; font-weight: bold; justify-content: center; }
        .house-indicator { position: absolute; top: 15px; width: 100%; display: flex; justify-content: center; gap: 3px; z-index: 10; }

        /* Pemetaan Posisi Grid (40 Petak) */
        .tile-0 { grid-column: 11; grid-row: 11; } .tile-1 { grid-column: 10; grid-row: 11; } .tile-2 { grid-column: 9; grid-row: 11; } .tile-3 { grid-column: 8; grid-row: 11; } .tile-4 { grid-column: 7; grid-row: 11; } .tile-5 { grid-column: 6; grid-row: 11; } .tile-6 { grid-column: 5; grid-row: 11; } .tile-7 { grid-column: 4; grid-row: 11; } .tile-8 { grid-column: 3; grid-row: 11; } .tile-9 { grid-column: 2; grid-row: 11; } .tile-10 { grid-column: 1; grid-row: 11; }
        .tile-11 { grid-column: 1; grid-row: 10; } .tile-12 { grid-column: 1; grid-row: 9; } .tile-13 { grid-column: 1; grid-row: 8; } .tile-14 { grid-column: 1; grid-row: 7; } .tile-15 { grid-column: 1; grid-row: 6; } .tile-16 { grid-column: 1; grid-row: 5; } .tile-17 { grid-column: 1; grid-row: 4; } .tile-18 { grid-column: 1; grid-row: 3; } .tile-19 { grid-column: 1; grid-row: 2; }
        .tile-20 { grid-column: 1; grid-row: 1; } .tile-21 { grid-column: 2; grid-row: 1; } .tile-22 { grid-column: 3; grid-row: 1; } .tile-23 { grid-column: 4; grid-row: 1; } .tile-24 { grid-column: 5; grid-row: 1; } .tile-25 { grid-column: 6; grid-row: 1; } .tile-26 { grid-column: 7; grid-row: 1; } .tile-27 { grid-column: 8; grid-row: 1; } .tile-28 { grid-column: 9; grid-row: 1; } .tile-29 { grid-column: 10; grid-row: 1; } .tile-30 { grid-column: 11; grid-row: 1; }
        .tile-31 { grid-column: 11; grid-row: 2; } .tile-32 { grid-column: 11; grid-row: 3; } .tile-33 { grid-column: 11; grid-row: 4; } .tile-34 { grid-column: 11; grid-row: 5; } .tile-35 { grid-column: 11; grid-row: 6; } .tile-36 { grid-column: 11; grid-row: 7; } .tile-37 { grid-column: 11; grid-row: 8; } .tile-38 { grid-column: 11; grid-row: 9; } .tile-39 { grid-column: 11; grid-row: 10; }

        /* REVISI 4: Area Tengah (Deck Cards diperbaiki layutnya agar tidak overflow) */
        .center-board { grid-column: 2 / 11; grid-row: 2 / 11; background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%); display: flex; flex-direction: column; align-items: center; justify-content: space-around; padding: 20px; border-radius: 4px; }
        .card-decks { display: flex; gap: 40px; justify-content: center; width: 100%; }
        
        .deck { 
            width: 120px; height: 180px; border-radius: 12px; box-shadow: 3px 5px 15px rgba(0,0,0,0.2); 
            display: flex; flex-direction: column; align-items: center; justify-content: center; /* Flex Col untuk susun vertikal */
            font-weight: bold; text-align: center; color: white; cursor: pointer; border: 3px solid rgba(255,255,255,0.5);
            padding: 10px; font-size: 0.85rem; line-height: 1.2;
        }
        .deck i { font-size: 3rem; margin-bottom: 15px; } /* Ikon diperbesar, dikasih jarak ke teks */
        .deck-chance { background: linear-gradient(45deg, #f57c00, #ffb74d); }
        .deck-chest { background: linear-gradient(45deg, #1976d2, #64b5f6); }
        .deck-stats { background: linear-gradient(45deg, #c2185b, #f06292); }

        .pawn-container { width: 25px; height: 25px; position: absolute; z-index: 50; display: flex; align-items: center; justify-content: center; transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1); text-shadow: 1px 1px 3px rgba(0,0,0,0.6); }
        .dice-container { background: rgba(255,255,255,0.95); padding: 15px 40px; border-radius: 15px; text-align: center; box-shadow: 0 8px 20px rgba(0,0,0,0.1); }
        .dice-icon-wrapper { font-size: 3rem; color: #495057; margin: 10px 0; display: inline-block; }
    </style>
</head>
<body>

<script>
    const BOARD = <?= json_encode($board) ?>;
    const QUESTIONS = <?= json_encode($questions) ?>;
</script>

<?php for($i=0; $i<4; $i++): ?>
<div id="ui-p<?= $i ?>" class="player-dashboard p<?= $i ?>">
    <h5 class="mb-1 fw-bold">Player <?= $i+1 ?> <span id="jail-badge-<?= $i ?>" class="badge bg-dark d-none ms-1">PENJARA</span></h5>
    <div class="fw-bold text-success fs-6"><span id="money-p<?= $i ?>">Rp 1.500.000</span></div>
    <div class="inventory-container" id="inv-p<?= $i ?>"></div>
</div>
<?php endfor; ?>

<div class="monopoly-board" id="board">
    <?php foreach ($board as $index => $petak): ?>
        <div class="tile tile-<?= $index ?> <?= $petak['tipe'] ?>" id="tile-<?= $index ?>">
            <div class="house-indicator" id="houses-<?= $index ?>"></div> 
            <?php if(isset($petak['grup'])): ?>
                <div class="tile-color-bar" style="background-color: <?= $petak['grup'] ?>"></div>
            <?php endif; ?>
            <div class="fw-bold mt-1 px-1" style="font-size: 0.7rem; line-height: 1.1;"><?= $petak['nama'] ?></div>
            <?php if($petak['harga'] > 0): ?>
                <div class="text-danger fw-bold pb-1"><?= formatRp($petak['harga']) ?></div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

    <div class="center-board">
        <div class="text-center">
            <h1 class="fw-bold text-dark mb-0" style="font-size: 3.5rem; letter-spacing: 5px; text-shadow: 2px 2px 0 #fff;">MONOPOLI</h1>
            <h5 class="text-secondary fw-bold">EDISI STATISTIKA</h5>
        </div>
        
        <div class="card-decks">
            <div class="deck deck-chance" onclick="drawCard('chance')">
                <i class="fa-solid fa-question"></i>
                <span>KESEMPATAN</span>
            </div>
            <div class="deck deck-stats" onclick="Swal.fire('Kartu Statistika', 'Ditarik otomatis jika ingin membeli properti.', 'info')">
                <i class="fa-solid fa-brain"></i>
                <span>STATISTIKA</span>
            </div>
            <div class="deck deck-chest" onclick="drawCard('chest')">
                <i class="fa-solid fa-dollar-sign"></i>
                <span>DANA UMUM</span>
            </div>
        </div>

        <div class="d-flex justify-content-center gap-4 w-100 mt-2">
            <div class="bg-white px-3 py-1 rounded shadow-sm fw-bold border border-success"><i class="fa-solid fa-house text-success"></i> Sisa: <span id="stock-rumah">32</span></div>
            <div class="bg-white px-3 py-1 rounded shadow-sm fw-bold border border-danger"><i class="fa-solid fa-building text-danger"></i> Sisa: <span id="stock-hotel">11</span></div>
        </div>

        <div class="dice-container mt-2">
            <h5 id="turn-indicator" class="fw-bold text-danger mb-0">Giliran Player 1</h5>
            <div class="dice-icon-wrapper"><i id="dice-css-icon" class="fa-solid fa-dice-one"></i></div><br>
            <button id="btn-roll" class="btn btn-primary fw-bold px-5 rounded-pill shadow" onclick="processTurn()">Kocok Dadu</button>
        </div>
    </div>
    
    <div id="pawn-0" class="pawn-container text-danger fs-4"><i class="fa-solid fa-chess-pawn"></i></div>
    <div id="pawn-1" class="pawn-container text-primary fs-4"><i class="fa-solid fa-chess-knight"></i></div>
    <div id="pawn-2" class="pawn-container text-success fs-4"><i class="fa-solid fa-chess-rook"></i></div>
    <div id="pawn-3" class="pawn-container text-warning fs-4"><i class="fa-solid fa-chess-queen"></i></div>
</div>

<script>
    // --- FORMATTER ---
    function formatRp(val) { return 'Rp ' + (val * 1000).toLocaleString('id-ID'); }

    // --- REVISI 1: SFX INITIALIZATION ---
    // Pastikan path audio sama persis dengan yang ada di foldermu 'src/sfx/...'
    const sfx = {
        move: new Audio('src/sfx/place.mp3'),
        door: new Audio('src/sfx/open-door.mp3'), // Pastikan file open-door.mp3 ditambahkan ke foldermu
        build: new Audio('src/sfx/building.mp3'),
        bell: new Audio('src/sfx/bell.mp3'),
        dice: new Audio('src/sfx/roll-dice.mp3'),
        card: new Audio('src/sfx/taking-card.mp3'),
        buy: new Audio('src/sfx/purchase.mp3')   // Pastikan file purchase.mp3 ditambahkan ke foldermu
    };

    function playSfx(sound) {
        if(!sound) return;
        sound.currentTime = 0;
        sound.play().catch(e => console.log('SFX block by browser:', e));
    }

    // --- STATE MANAGEMENT ---
    let players = [
        { id: 0, pos: 0, money: 1500, properties: [], cards: [], wrongAnswers: 0, inJail: false, jailTurns: 0 },
        { id: 1, pos: 0, money: 1500, properties: [], cards: [], wrongAnswers: 0, inJail: false, jailTurns: 0 },
        { id: 2, pos: 0, money: 1500, properties: [], cards: [], wrongAnswers: 0, inJail: false, jailTurns: 0 },
        { id: 3, pos: 0, money: 1500, properties: [], cards: [], wrongAnswers: 0, inJail: false, jailTurns: 0 }
    ];
    let currentTurn = 0; let stockRumah = 32; let stockHotel = 11;
    
    window.onload = () => { updatePawnPositions(); document.getElementById(`ui-p0`).classList.add('active-turn'); };

    function updateUI() {
        document.getElementById('stock-rumah').innerText = stockRumah; document.getElementById('stock-hotel').innerText = stockHotel;
        players.forEach(p => {
            document.getElementById(`money-p${p.id}`).innerText = formatRp(p.money);
            document.getElementById(`jail-badge-${p.id}`).classList.toggle('d-none', !p.inJail);
            const invDiv = document.getElementById(`inv-p${p.id}`);
            invDiv.innerHTML = ''; let zIdx = 1;
            p.properties.forEach((prop, i) => { invDiv.innerHTML += `<div class="inventory-card" style="top: ${i*18}px; z-index: ${zIdx++}; border-left: 5px solid ${prop.grup}">${prop.nama}</div>`; });
            p.cards.forEach((c, i) => { invDiv.innerHTML += `<div class="inventory-card" style="top: ${(p.properties.length+i)*18}px; z-index: ${zIdx++}; border-left: 5px solid #ff9800">${c.nama}</div>`; });
        });
    }

    function processTurn() {
        let p = players[currentTurn];
        document.getElementById('btn-roll').disabled = true;

        if (p.inJail) {
            let hasJailCard = p.cards.find(c => c.type === 'free_jail');
            let jailOpts = `<button class="btn btn-warning m-1" onclick="payJail(${p.id})">Bayar ${formatRp(50)}</button>`;
            if(hasJailCard) jailOpts += `<button class="btn btn-info m-1" onclick="useJailCard(${p.id})">Pakai Kartu</button>`;
            jailOpts += `<br><button class="btn btn-danger m-1 mt-2" onclick="skipJailTurn(${p.id})">Lewati Giliran</button>`;
            Swal.fire({ title: 'Terkurung di Penjara!', html: jailOpts, showConfirmButton: false, allowOutsideClick: false });
        } else {
            rollAndMove(p);
        }
    }

    function payJail(id) {
        if(players[id].money >= 50) {
            players[id].money -= 50; players[id].inJail = false; players[id].wrongAnswers = 0;
            playSfx(sfx.door); // SFX KELUAR PENJARA
            Swal.fire('Bebas!', 'Denda dibayar.', 'success').then(() => rollAndMove(players[id]));
            updateUI();
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
        playSfx(sfx.door); // SFX KELUAR PENJARA
        Swal.fire('Bebas!', 'Kartu digunakan.', 'success').then(() => {
            if(p.pos === 10) rollAndMove(p); else actionPhase(p, BOARD[p.pos]);
        });
    }

    // --- REVISI 1: ANIMASI DADU DENGAN SFX & JALAN SATU PERSATU ---
    function rollAndMove(p) {
        let diceIcon = document.getElementById('dice-css-icon');
        const diceClasses = ['fa-dice-one', 'fa-dice-two', 'fa-dice-three', 'fa-dice-four', 'fa-dice-five', 'fa-dice-six'];
        let rollCount = 0;
        
        playSfx(sfx.dice); // SFX ROLL DICE

        let rollInterval = setInterval(() => {
            let rand = Math.floor(Math.random() * 6);
            diceIcon.className = `fa-solid ${diceClasses[rand]} text-primary`;
            rollCount++;
            
            if (rollCount > 10) {
                clearInterval(rollInterval);
                let finalDice = Math.floor(Math.random() * 6) + 1;
                diceIcon.className = `fa-solid ${diceClasses[finalDice - 1]} text-success animate__animated animate__bounceIn`;
                
                // Jalan satu per satu untuk membunyikan SFX berulang
                let step = 0;
                let stepInterval = setInterval(() => {
                    step++;
                    p.pos = (p.pos + 1) % 40;
                    playSfx(sfx.move); // SFX PLACE PER PETAK
                    
                    if (p.pos === 0) { 
                        p.money += 200; updateUI(); 
                        Swal.fire({toast:true, position:'top-end', title:`Melewati GO! +${formatRp(200)}`, icon:'success', showConfirmButton:false, timer:2000});
                    } 
                    updatePawnPositions();

                    if(step === finalDice) {
                        clearInterval(stepInterval);
                        setTimeout(() => handleTileLogic(p, BOARD[p.pos]), 400);
                    }
                }, 300); // Kecepatan bidak melangkah (300ms)
            }
        }, 80);
    }

    function handleTileLogic(p, tile) {
        if (p.pos === 30) { 
            goToJail(p);
        } else if (tile.tipe === 'pajak') {
            p.money -= tile.harga; updateUI();
            Swal.fire('Kena Pajak!', `Kamu membayar ${formatRp(tile.harga)}`, 'error').then(() => nextTurn());
        } else if (tile.tipe === 'properti' || tile.tipe === 'bandara' || tile.tipe === 'utilitas') {
            if (!tile.owner && tile.owner !== 0) { triggerStatsQuestion(p, tile); } 
            else if (tile.owner !== p.id) { payRent(p, tile); } 
            else { actionPhase(p, tile); }
        } else if (tile.tipe === 'kesempatan' || tile.tipe === 'dana_umum') {
            drawCard(tile.tipe, p);
        } else {
            actionPhase(p, tile); 
        }
    }

    function triggerStatsQuestion(p, tile) {
        playSfx(sfx.card); // SFX AMBIL KARTU
        const qTypes = ['K', 'M', 'D', 'J', 'R'];
        const randomType = qTypes[Math.floor(Math.random() * qTypes.length)];
        const qList = QUESTIONS[randomType];
        const q = qList[Math.floor(Math.random() * qList.length)];

        Swal.fire({
            title: 'KARTU STATISTIKA', text: q.soal, input: 'text',
            showCancelButton: false, allowOutsideClick: false, confirmButtonText: 'Jawab'
        }).then((res) => {
            if (res.value.toLowerCase() == q.jawaban_kunci.toLowerCase()) {
                p.wrongAnswers = 0; p.money += q.poin; updateUI();
                Swal.fire('Benar!', `Kamu mendapat ${q.poin} Koin. Silakan bertransaksi.`, 'success').then(() => actionPhase(p, tile));
            } else {
                p.wrongAnswers++;
                if (p.wrongAnswers >= 3) {
                    Swal.fire('Salah 3x!', 'Kamu dihukum masuk Penjara!', 'error').then(() => goToJail(p));
                } else {
                    Swal.fire('Salah!', `Jawaban yang benar: ${q.jawaban_kunci}. Peringatan: ${p.wrongAnswers}/3.`, 'warning').then(() => nextTurn());
                }
            }
        });
    }

    function actionPhase(p, tile) {
        let htmlOpts = '';
        if ((tile.tipe === 'properti' || tile.tipe === 'bandara' || tile.tipe === 'utilitas') && (!tile.owner && tile.owner !== 0) && p.money >= tile.harga) {
            htmlOpts += `<button class="btn btn-success m-2" onclick="buyProperty(${p.id}, ${p.pos})">Beli Sertifikat (${formatRp(tile.harga)})</button><br>`;
        }
        if (tile.owner === p.id && tile.tipe === 'properti') {
            htmlOpts += `<button class="btn btn-warning m-2 text-dark" onclick="buildHouse(${p.id}, ${p.pos})"><i class="fa-solid fa-hammer"></i> Bangun Rumah (${formatRp(50)})</button><br>`;
        }
        if (p.cards.find(c => c.type === 'free_jail') && p.inJail) {
            htmlOpts += `<button class="btn btn-info m-2" onclick="useJailCard(${p.id})">Gunakan Kartu Bebas</button><br>`;
        }
        htmlOpts += `<button class="btn btn-danger m-2 px-4" onclick="Swal.close(); nextTurn();">Akhiri Giliran</button>`;
        Swal.fire({ title: 'Fase Aksi', html: htmlOpts, showConfirmButton: false, allowOutsideClick: false });
    }

    function buyProperty(playerId, tileIndex) {
        let p = players[playerId]; let tile = BOARD[tileIndex];
        p.money -= tile.harga; tile.owner = p.id; p.properties.push(tile);
        
        playSfx(sfx.buy); // SFX BELI TANAH

        const colors = ['#dc3545', '#0d6efd', '#198754', '#ffc107'];
        document.getElementById(`tile-${tileIndex}`).style.border = `4px solid ${colors[p.id]}`; 
        updateUI();
        Swal.fire('Terbeli!', `${tile.nama} resmi menjadi milikmu.`, 'success').then(() => actionPhase(p, tile));
    }

    function buildHouse(playerId, tileIndex) {
        let p = players[playerId]; let tile = BOARD[tileIndex];
        if (p.money < 50) return Swal.fire('Dana Kurang', '', 'error').then(() => actionPhase(p, tile));
        if (!tile.houses) tile.houses = 0;

        if (tile.houses < 4) {
            if (stockRumah <= 0) return Swal.fire('Habis', 'Stok Rumah Bank Habis', 'error').then(() => actionPhase(p, tile));
            tile.houses++; stockRumah--; p.money -= 50;
            document.getElementById(`houses-${tileIndex}`).innerHTML += `<i class="fa-solid fa-house text-success mt-1" style="font-size:10px;"></i>`;
            playSfx(sfx.build); // SFX BANGUN RUMAH
            Swal.fire('Sukses', '1 Rumah dibangun.', 'success').then(() => actionPhase(p, tile));
        } else if (tile.houses === 4) {
            if (stockHotel <= 0) return Swal.fire('Habis', 'Stok Hotel Habis', 'error').then(() => actionPhase(p, tile));
            tile.houses = 5; stockRumah += 4; stockHotel--; p.money -= 50;
            document.getElementById(`houses-${tileIndex}`).innerHTML = `<i class="fa-solid fa-building text-danger mt-1" style="font-size:14px;"></i>`;
            playSfx(sfx.build); // SFX BANGUN HOTEL
            Swal.fire('Megah!', 'Hotel dibangun.', 'success').then(() => actionPhase(p, tile));
        } else {
            Swal.fire('Maksimal', 'Properti penuh.', 'info').then(() => actionPhase(p, tile));
        }
        updateUI();
    }

    function payRent(p, tile) {
        let rent = tile.sewa || 25; if(tile.houses) rent += (tile.houses * 20); 
        p.money -= rent; players[tile.owner].money += rent; updateUI();
        playSfx(sfx.bell); // SFX BAYAR SEWA/BEL
        Swal.fire('Bayar Sewa!', `Membayar ${formatRp(rent)} ke Player ${tile.owner + 1}`, 'warning').then(() => actionPhase(p, tile));
    }

    function goToJail(p) {
        p.pos = 10; p.inJail = true; p.jailTurns = 0;
        updatePawnPositions(); updateUI(); nextTurn();
    }

    function drawCard(type, p) {
        playSfx(sfx.card); // SFX AMBIL KARTU
        let isGetOutJail = Math.random() > 0.8; 
        if (isGetOutJail && p) {
            p.cards.push({ nama: 'Bebas Penjara', type: 'free_jail' }); updateUI();
            Swal.fire({ title: 'DISIMPAN!', text: 'Mendapat Kartu Bebas Penjara.', icon: 'info', showClass: { popup: 'animate__animated animate__flipInY' } }).then(() => nextTurn());
        } else {
            Swal.fire({ title: type === 'kesempatan' ? 'KESEMPATAN' : 'DANA UMUM', text: 'Bonus tak terduga! Dapat tambahan dana.', icon: 'success', showClass: { popup: 'animate__animated animate__zoomInDown' } }).then(() => {
                if(p) { p.money += 150; updateUI(); nextTurn(); }
            });
        }
    }

    function nextTurn() {
        document.getElementById(`ui-p${currentTurn}`).classList.remove('active-turn');
        currentTurn = (currentTurn + 1) % 4;
        document.getElementById(`ui-p${currentTurn}`).classList.add('active-turn');
        let colors = ['text-danger', 'text-primary', 'text-success', 'text-warning'];
        let ind = document.getElementById('turn-indicator');
        ind.innerText = `Giliran Player ${currentTurn + 1}`; ind.className = `fw-bold mb-0 ${colors[currentTurn]}`;
        document.getElementById('dice-css-icon').className = 'fa-solid fa-dice text-secondary animate__animated animate__fadeIn';
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