<?php
/**
 * KONFIGURASI MONOPOLI - STATISTIKA
 * File ini berisi semua konfigurasi, variable, dan konstanta untuk game Monopoli
 */


// ========== PERTANYAAN & SOAL ==========
$questions = [
    'K' => [
        ['soal' => 'Q1 dari 2,4,6?', 'jawaban_kunci' => '2', 'poin' => 10],
        ['soal' => 'Kuartil 1 dari 2,4,6?', 'jawaban_kunci' => '2', 'poin' => 10],
    ],
    'M' => [
        ['soal' => 'Median 2,4,6?', 'jawaban_kunci' => '4', 'poin' => 10],
        ['soal' => 'Median 1,3,5,7?', 'jawaban_kunci' => '4', 'poin' => 10],
    ],
    'D' => [
        ['soal' => 'Modus 2,2,3?', 'jawaban_kunci' => '2', 'poin' => 10],
        ['soal' => 'Modus 1,1,2,3?', 'jawaban_kunci' => '1', 'poin' => 10],
    ],
    'J' => [
        ['soal' => 'Jangkauan 2,10?', 'jawaban_kunci' => '8', 'poin' => 10],
        ['soal' => 'Range 5,15?', 'jawaban_kunci' => '10', 'poin' => 10],
    ],
    'R' => [
        ['soal' => 'Rata-rata 2,4,6?', 'jawaban_kunci' => '4', 'poin' => 10],
        ['soal' => 'Mean 1,2,3?', 'jawaban_kunci' => '2', 'poin' => 10],
    ]
];

// ========== WARNA & STYLING ==========
$c_coklat = '#964B00'; 
$c_biru_muda = '#0ea5e9'; 
$c_pink = '#d946ef'; 
$c_orange = '#f97316'; 
$c_merah = '#ef4444'; 
$c_kuning = '#eab308'; 
$c_hijau = '#10b981'; 
$c_biru_tua = '#2563eb';

// ========== WARNA PEMAIN ==========
$playerColors = [
    ['name' => 'Merah', 'bg' => '#ef4444', 'bgSoft' => '#fef2f2'],
    ['name' => 'Biru', 'bg' => '#3b82f6', 'bgSoft' => '#eff6ff'],
    ['name' => 'Hijau', 'bg' => '#10b981', 'bgSoft' => '#ecfdf5'],
    ['name' => 'Kuning', 'bg' => '#f59e0b', 'bgSoft' => '#fffbeb']
];

// ========== FUNGSI UTILITY ==========
function formatRp($val) { 
    return 'Rp ' . number_format($val * 1000, 0, ',', '.'); 
}

// ========== BOARD DATA ==========
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

?>
