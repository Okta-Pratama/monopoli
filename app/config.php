<?php
/**
 * KONFIGURASI MONOPOLI - STATISTIKA
 * File ini berisi semua konfigurasi, variable, dan konstanta untuk game Monopoli
 */

// ========== KONFIGURASI UMUM ==========
$startMoney = 3000;

// ========== PERTANYAAN & SOAL ==========
$questions = [
    'K' => [
        // Level 1
        ['soal' => 'Tentukan kuartil bawah (Q1) dari data berikut: 2, 4, 6, 8, 10, 12', 'jawaban_kunci' => 'Q1 = 4', 'poin' => 10, 'level' => 1],
        ['soal' => 'Sebutkan pengertian kuartil dalam statistika', 'jawaban_kunci' => 'Kuartil adalah nilai yang membagi data menjadi 4 bagian sama banyak', 'poin' => 10, 'level' => 1],
        // Level 2
        ['soal' => 'Tentukan Q1, Q2, dan Q3 dari data: 3, 5, 7, 9, 11, 13, 15', 'jawaban_kunci' => 'Q1=5, Q2=9, Q3=13', 'poin' => 10, 'level' => 2],
        ['soal' => 'Jelaskan perbedaan antara Q1, Q2, dan Q3', 'jawaban_kunci' => 'Q1 = kuartil bawah, Q2 = median, Q3 = kuartil atas', 'poin' => 10, 'level' => 2],
        ['soal' => 'Data: 4, 6, 8, 10, 12, 14, 16, 18 Tentukan posisi kuartil atas (Q3)', 'jawaban_kunci' => 'Posisi Q3 = 3/4(n+1) = 3/4(9) = 6,75', 'poin' => 10, 'level' => 2],
        // Level 3
        ['soal' => 'Data nilai: 60, 65, 70, 75, 80, 85, 90 Hitung Q1, Q2, dan Q3', 'jawaban_kunci' => 'Q1=65, Q2=75, Q3=85', 'poin' => 10, 'level' => 3],
        // Level 4
        ['soal' => 'Data: 5, 7, 9, 11, 13, 15, 17, 19, 21 Analisis apakah data tersebut simetris berdasarkan nilai kuartil', 'jawaban_kunci' => 'Simetris karena jarak antar kuartil sama', 'poin' => 10, 'level' => 4],
    ],
    'M' => [
        // Level 1
        ['soal' => 'Tentukan median dari data: 2, 4, 6, 8, 10', 'jawaban_kunci' => 'Median = 6', 'poin' => 10, 'level' => 1],
        ['soal' => 'Apa yang dimaksud dengan median?', 'jawaban_kunci' => 'Median adalah nilai tengah data', 'poin' => 10, 'level' => 1],
        // Level 2
        ['soal' => 'Tentukan median dari data: 3, 5, 7, 9, 11, 13', 'jawaban_kunci' => 'Median = 7+9 /2 =8', 'poin' => 10, 'level' => 2],
        ['soal' => 'Jelaskan cara menentukan median pada jumlah data genap', 'jawaban_kunci' => 'Median = rata-rata dua nilai tengah', 'poin' => 10, 'level' => 2],
        ['soal' => 'Data: 10, 20, 30, 40, 50, 60 Tentukan median', 'jawaban_kunci' => 'Median = 30+40 /2 =35', 'poin' => 10, 'level' => 2],
        // Level 3
        ['soal' => 'Data nilai: 65, 70, 75, 80, 85, 90 Hitung median', 'jawaban_kunci' => 'Median = 75+80 /2 = 77,5', 'poin' => 10, 'level' => 3],
        ['soal' => 'Data: 12, 15, 18, 20, 22 Hitung median', 'jawaban_kunci' => 'Median = 18', 'poin' => 10, 'level' => 3],
        // Level 4
        ['soal' => 'Data: 5, 10, 15, 20, 25, 30, 100 Analisis pengaruh nilai ekstrem terhadap median', 'jawaban_kunci' => 'Median tidak teralu terpengaruh nilai ekstrem', 'poin' => 10, 'level' => 4],
    ],
    'D' => [
        // Level 1
        ['soal' => 'Tentukan modus dari data: 2, 3, 3, 4, 5', 'jawaban_kunci' => 'Modus = 3', 'poin' => 10, 'level' => 1],
        // Level 2
        ['soal' => 'Jelaskan apa itu modus', 'jawaban_kunci' => 'Modus adalah nilai yang paling sering muncul', 'poin' => 10, 'level' => 2],
        ['soal' => 'Tentukan modus dari data: 5, 6, 6, 7, 7, 7, 8', 'jawaban_kunci' => 'Modus = 7', 'poin' => 10, 'level' => 2],
        ['soal' => 'Apakah data berikut memiliki modus? 1, 2, 3, 4, 5', 'jawaban_kunci' => 'Tidak memiliki modus', 'poin' => 10, 'level' => 2],
        // Level 3
        ['soal' => 'Data nilai: 70, 75, 75, 80, 85, 85, 85 Tentukan modus', 'jawaban_kunci' => 'Modus = 85', 'poin' => 10, 'level' => 3],
        ['soal' => 'Data: 10, 10, 20, 20, 30, 30 Tentukan jenis modusnya', 'jawaban_kunci' => 'Modus ganda', 'poin' => 10, 'level' => 3],
        // Level 4
        ['soal' => 'Bandingkan dua data berikut dan tentukan mana yang lebih representatif berdasarkan modus: A: 2, 2, 3, 4, 5 B: 1, 2, 3, 4, 5', 'jawaban_kunci' => 'Data A lebih representatif', 'poin' => 10, 'level' => 4],
    ],
    'J' => [
        // Level 1
        ['soal' => 'Tentukan jangkauan dari data: 2, 5, 8, 10', 'jawaban_kunci' => 'Jangkauan = 10 – 2 = 8', 'poin' => 10, 'level' => 1],
        // Level 2
        ['soal' => 'Jelaskan pengertian jangkauan', 'jawaban_kunci' => 'Jangkauan = nilai terbesar – terkecil', 'poin' => 10, 'level' => 2],
        ['soal' => 'Tentukan jangkauan data: 10, 20, 30, 40, 50', 'jawaban_kunci' => 'Jangkauan = 50 – 10 = 40', 'poin' => 10, 'level' => 2],
        ['soal' => 'Mengapa jangkauan penting dalam statistika', 'jawaban_kunci' => 'Untuk mengetahui penyebaran data', 'poin' => 10, 'level' => 2],
        // Level 3
        ['soal' => 'Data nilai: 60, 65, 70, 75, 80 Hitung jangkauan', 'jawaban_kunci' => 'Jangkauan = 80 – 60 = 20', 'poin' => 10, 'level' => 3],
        ['soal' => 'Data: 15, 18, 20, 22, 25 Hitung jangkauan', 'jawaban_kunci' => 'Jangkauan = 25 – 15 = 10', 'poin' => 10, 'level' => 3],
        // Level 4
        ['soal' => 'Data A: 10, 20, 30, 40, 50 Data B: 25, 26, 27, 28, 29 Analisis mana yang lebih menyebar', 'jawaban_kunci' => 'Data A lebih menyebar', 'poin' => 10, 'level' => 4],
    ],
    'R' => [
        // Level 1
        ['soal' => 'Tentukan rata-rata dari data: 2, 4, 6, 8 9', 'jawaban_kunci' => 'Rata-rata = 5', 'poin' => 10, 'level' => 1],
        ['soal' => 'Apa yang dimaksud dengan rata-rata?', 'jawaban_kunci' => 'Rata-rata adalah jumlah data dibagi banyak data', 'poin' => 10, 'level' => 1],
        // Level 2
        ['soal' => 'Tentukan rata-rata dari data: 10, 20, 30, 40, 50', 'jawaban_kunci' => 'Rata rata = 30', 'poin' => 10, 'level' => 2],
        ['soal' => 'Jelaskan langkah-langkah menghitung rata-rata', 'jawaban_kunci' => 'Jumlahkan lalu bagi banyak data', 'poin' => 10, 'level' => 2],
        ['soal' => 'Data: 5, 10, 15, 20 Hitung rata-rata', 'jawaban_kunci' => 'Rata-rata = 12,5', 'poin' => 10, 'level' => 2],
        // Level 3
        ['soal' => 'Data nilai: 70, 75, 80, 85, 90 Hitung rata-rata', 'jawaban_kunci' => 'Rata-rata = 80', 'poin' => 10, 'level' => 3],
        // Level 4
        ['soal' => 'Data: 10, 20, 30, 40, 100 Analisis pengaruh nilai ekstrem terhadap rata-rata', 'jawaban_kunci' => 'Rata-rata sangat terpengaruh nilai ekstrem', 'poin' => 10, 'level' => 4],
    ]
];

// ========== WARNA & STYLING ==========
$c_coklat = '#964B00'; 
$c_biru_muda = '#14b8a6'; /* teal — distinct from Dana Umum blue */
$c_pink = '#d946ef'; 
$c_orange = '#f43f5e'; /* rose — distinct from Kesempatan amber */
$c_merah = '#e11d48'; /* deep rose-red */
$c_kuning = '#84cc16'; /* lime green — distinct from Kesempatan gold */
$c_hijau = '#10b981'; 
$c_biru_tua = '#7c3aed'; /* violet — distinct from Dana Umum blue */

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
    1 => ['tipe' => 'properti', 'nama' => 'Bekasi', 'grup' => $c_coklat, 'harga' => 80, 'sewa' => 4],
    2 => ['tipe' => 'dana_umum', 'nama' => 'Peristiwa Alam', 'warna' => '#fff', 'harga' => 0],
    3 => ['tipe' => 'properti', 'nama' => 'Tangerang', 'grup' => $c_coklat, 'harga' => 80, 'sewa' => 6],
    4 => ['tipe' => 'pajak', 'nama' => 'Kena Tilang', 'warna' => '#fff', 'harga' => 200], 
    5 => ['tipe' => 'bandara', 'nama' => 'Stasiun Gambir', 'warna' => '#ccc', 'harga' => 250],
    6 => ['tipe' => 'properti', 'nama' => 'Bogor', 'grup' => $c_biru_muda, 'harga' => 120, 'sewa' => 8],
    7 => ['tipe' => 'kesempatan', 'nama' => 'Taman Bunga', 'warna' => '#fff', 'harga' => 0],
    8 => ['tipe' => 'properti', 'nama' => 'Depok', 'grup' => $c_biru_muda, 'harga' => 120, 'sewa' => 8],
    9 => ['tipe' => 'properti', 'nama' => 'Gresik', 'grup' => $c_biru_muda, 'harga' => 140, 'sewa' => 10],
    10 => ['tipe' => 'corner', 'nama' => 'Penjara', 'warna' => '#e8e8eb', 'harga' => 0],
    11 => ['tipe' => 'properti', 'nama' => 'Sidoarjo', 'grup' => $c_pink, 'harga' => 160, 'sewa' => 12],
    12 => ['tipe' => 'utilitas', 'nama' => 'Perpustakaan', 'warna' => '#fff', 'harga' => 180],
    13 => ['tipe' => 'properti', 'nama' => 'Malang', 'grup' => $c_pink, 'harga' => 160, 'sewa' => 12],
    14 => ['tipe' => 'properti', 'nama' => 'Solo', 'grup' => $c_pink, 'harga' => 180, 'sewa' => 14],
    15 => ['tipe' => 'bandara', 'nama' => 'Band. Juanda', 'warna' => '#ccc', 'harga' => 250],
    16 => ['tipe' => 'properti', 'nama' => 'Semarang', 'grup' => $c_orange, 'harga' => 200, 'sewa' => 16],
    17 => ['tipe' => 'dana_umum', 'nama' => 'Peristiwa Alam', 'warna' => '#fff', 'harga' => 0],
    18 => ['tipe' => 'properti', 'nama' => 'Yogyakarta', 'grup' => $c_orange, 'harga' => 200, 'sewa' => 16],
    19 => ['tipe' => 'properti', 'nama' => 'Bandung', 'grup' => $c_orange, 'harga' => 220, 'sewa' => 18],
    20 => ['tipe' => 'corner', 'nama' => 'Bebas Parkir', 'warna' => '#e8e8eb', 'harga' => 0],
    21 => ['tipe' => 'properti', 'nama' => 'Surabaya', 'grup' => $c_merah, 'harga' => 240, 'sewa' => 20],
    22 => ['tipe' => 'kesempatan', 'nama' => 'Taman Bunga', 'warna' => '#fff', 'harga' => 0],
    23 => ['tipe' => 'properti', 'nama' => 'Denpasar', 'grup' => $c_merah, 'harga' => 240, 'sewa' => 20],
    24 => ['tipe' => 'properti', 'nama' => 'Mataram', 'grup' => $c_merah, 'harga' => 260, 'sewa' => 22],
    25 => ['tipe' => 'bandara', 'nama' => 'B. Ngurah Rai', 'warna' => '#ccc', 'harga' => 250],
    26 => ['tipe' => 'properti', 'nama' => 'Makassar', 'grup' => $c_kuning, 'harga' => 280, 'sewa' => 24],
    27 => ['tipe' => 'properti', 'nama' => 'Manado', 'grup' => $c_kuning, 'harga' => 280, 'sewa' => 24],
    28 => ['tipe' => 'utilitas', 'nama' => 'Lab Komputer', 'warna' => '#fff', 'harga' => 180],
    29 => ['tipe' => 'properti', 'nama' => 'Balikpapan', 'grup' => $c_kuning, 'harga' => 300, 'sewa' => 26],
    30 => ['tipe' => 'corner', 'nama' => 'Masuk Penjara!', 'warna' => '#e8e8eb', 'harga' => 0],
    31 => ['tipe' => 'properti', 'nama' => 'Samarinda', 'grup' => $c_hijau, 'harga' => 320, 'sewa' => 28],
    32 => ['tipe' => 'properti', 'nama' => 'Pontianak', 'grup' => $c_hijau, 'harga' => 320, 'sewa' => 28],
    33 => ['tipe' => 'dana_umum', 'nama' => 'Peristiwa Alam', 'warna' => '#fff', 'harga' => 0],
    34 => ['tipe' => 'properti', 'nama' => 'Banjarmasin', 'grup' => $c_hijau, 'harga' => 340, 'sewa' => 30],
    35 => ['tipe' => 'bandara', 'nama' => 'B. Kualanamu', 'warna' => '#ccc', 'harga' => 250],
    36 => ['tipe' => 'kesempatan', 'nama' => 'Taman Bunga', 'warna' => '#fff', 'harga' => 0],
    37 => ['tipe' => 'properti', 'nama' => 'Palembang', 'grup' => $c_biru_tua, 'harga' => 380, 'sewa' => 35],
    38 => ['tipe' => 'pajak', 'nama' => 'Bayar PBB', 'warna' => '#fff', 'harga' => 100], 
    39 => ['tipe' => 'properti', 'nama' => 'Medan', 'grup' => $c_biru_tua, 'harga' => 450, 'sewa' => 50]
];

?>
