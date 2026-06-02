<?php
/**
 * KONFIGURASI MONOPOLI - STATISTIKA
 * File ini berisi semua konfigurasi, variable, dan konstanta untuk game Monopoli
 */

// ========== KONFIGURASI UMUM ==========
$actionCountdown = 30;          // Batas waktu berfikir pemain per giliran (detik)

// ========== PERTANYAAN & SOAL ==========
$questions = [
    'K' => [
        // Level 1
        ['soal' => 'Tentukan kuartil bawah (Q1) dari data berikut: 2, 4, 6, 8, 10, 12', 'jawaban_kunci' => 'Q1 = 4', 'poin' => 1, 'level' => 1],
        ['soal' => 'Sebutkan pengertian kuartil dalam statistika', 'jawaban_kunci' => 'Kuartil adalah nilai yang membagi data menjadi 4 bagian sama banyak', 'poin' => 1, 'level' => 1],
        // Level 2
        ['soal' => 'Tentukan Q1, Q2, dan Q3 dari data: 3, 5, 7, 9, 11, 13, 15', 'jawaban_kunci' => 'Q1=5, Q2=9, Q3=13', 'poin' => 1, 'level' => 2],
        ['soal' => 'Jelaskan perbedaan antara Q1, Q2, dan Q3', 'jawaban_kunci' => 'Q1 = kuartil bawah, Q2 = median, Q3 = kuartil atas', 'poin' => 1, 'level' => 2],
        ['soal' => 'Data: 4, 6, 8, 10, 12, 14, 16, 18 Tentukan posisi kuartil atas (Q3)', 'jawaban_kunci' => 'Posisi Q3 = 3/4(n+1) = 3/4(9) = 6,75', 'poin' => 1, 'level' => 2],
        // Level 3
        ['soal' => 'Data nilai: 60, 65, 70, 75, 80, 85, 90 Hitung Q1, Q2, dan Q3', 'jawaban_kunci' => 'Q1=65, Q2=75, Q3=85', 'poin' => 1, 'level' => 3],
        // Level 4
        ['soal' => 'Data: 5, 7, 9, 11, 13, 15, 17, 19, 21 Analisis apakah data tersebut simetris berdasarkan nilai kuartil', 'jawaban_kunci' => 'Simetris karena jarak antar kuartil sama', 'poin' => 1, 'level' => 4],
    ],
    'M' => [
        // Level 1
        ['soal' => 'Tentukan median dari data: 2, 4, 6, 8, 10', 'jawaban_kunci' => 'Median = 6', 'poin' => 1, 'level' => 1],
        ['soal' => 'Apa yang dimaksud dengan median?', 'jawaban_kunci' => 'Median adalah nilai tengah data', 'poin' => 1, 'level' => 1],
        // Level 2
        ['soal' => 'Tentukan median dari data: 3, 5, 7, 9, 11, 13', 'jawaban_kunci' => 'Median = 7+9 /2 =8', 'poin' => 1, 'level' => 2],
        ['soal' => 'Jelaskan cara menentukan median pada jumlah data genap', 'jawaban_kunci' => 'Median = rata-rata dua nilai tengah', 'poin' => 1, 'level' => 2],
        ['soal' => 'Data: 10, 20, 30, 40, 50, 60 Tentukan median', 'jawaban_kunci' => 'Median = 30+40 /2 =35', 'poin' => 1, 'level' => 2],
        // Level 3
        ['soal' => 'Data nilai: 65, 70, 75, 80, 85, 90 Hitung median', 'jawaban_kunci' => 'Median = 75+80 /2 = 77,5', 'poin' => 1, 'level' => 3],
        ['soal' => 'Data: 12, 15, 18, 20, 22 Hitung median', 'jawaban_kunci' => 'Median = 18', 'poin' => 1, 'level' => 3],
        // Level 4
        ['soal' => 'Data: 5, 10, 15, 20, 25, 30, 100 Analisis pengaruh nilai ekstrem terhadap median', 'jawaban_kunci' => 'Median tidak teralu terpengaruh nilai ekstrem', 'poin' => 1, 'level' => 4],
    ],
    'D' => [
        // Level 1
        ['soal' => 'Tentukan modus dari data: 2, 3, 3, 4, 5', 'jawaban_kunci' => 'Modus = 3', 'poin' => 1, 'level' => 1],
        // Level 2
        ['soal' => 'Jelaskan apa itu modus', 'jawaban_kunci' => 'Modus adalah nilai yang paling sering muncul', 'poin' => 1, 'level' => 2],
        ['soal' => 'Tentukan modus dari data: 5, 6, 6, 7, 7, 7, 8', 'jawaban_kunci' => 'Modus = 7', 'poin' => 1, 'level' => 2],
        ['soal' => 'Apakah data berikut memiliki modus? 1, 2, 3, 4, 5', 'jawaban_kunci' => 'Tidak memiliki modus', 'poin' => 1, 'level' => 2],
        // Level 3
        ['soal' => 'Data nilai: 70, 75, 75, 80, 85, 85, 85 Tentukan modus', 'jawaban_kunci' => 'Modus = 85', 'poin' => 1, 'level' => 3],
        ['soal' => 'Data: 10, 10, 20, 20, 30, 30 Tentukan jenis modusnya', 'jawaban_kunci' => 'Modus ganda', 'poin' => 1, 'level' => 3],
        // Level 4
        ['soal' => 'Bandingkan dua data berikut and tentukan mana yang lebih representatif berdasarkan modus: A: 2, 2, 3, 4, 5 B: 1, 2, 3, 4, 5', 'jawaban_kunci' => 'Data A lebih representatif', 'poin' => 1, 'level' => 4],
    ],
    'J' => [
        // Level 1
        ['soal' => 'Tentukan jangkauan dari data: 2, 5, 8, 10', 'jawaban_kunci' => 'Jangkauan = 10 – 2 = 8', 'poin' => 1, 'level' => 1],
        // Level 2
        ['soal' => 'Jelaskan pengertian jangkauan', 'jawaban_kunci' => 'Jangkauan = nilai terbesar – terkecil', 'poin' => 1, 'level' => 2],
        ['soal' => 'Tentukan jangkauan data: 10, 20, 30, 40, 50', 'jawaban_kunci' => 'Jangkauan = 50 – 10 = 40', 'poin' => 1, 'level' => 2],
        ['soal' => 'Mengapa jangkauan penting dalam statistika', 'jawaban_kunci' => 'Untuk mengetahui penyebaran data', 'poin' => 1, 'level' => 2],
        // Level 3
        ['soal' => 'Data nilai: 60, 65, 70, 75, 80 Hitung jangkauan', 'jawaban_kunci' => 'Jangkauan = 80 – 60 = 20', 'poin' => 1, 'level' => 3],
        ['soal' => 'Data: 15, 18, 20, 22, 25 Hitung jangkauan', 'jawaban_kunci' => 'Jangkauan = 25 – 15 = 10', 'poin' => 1, 'level' => 3],
        // Level 4
        ['soal' => 'Data A: 10, 20, 30, 40, 50 Data B: 25, 26, 27, 28, 29 Analisis mana yang lebih menyebar', 'jawaban_kunci' => 'Data A lebih menyebar', 'poin' => 1, 'level' => 4],
    ],
    'R' => [
        // Level 1
        ['soal' => 'Tentukan rata-rata dari data: 2, 4, 6, 8 9', 'jawaban_kunci' => 'Rata-rata = 5', 'poin' => 1, 'level' => 1],
        ['soal' => 'Apa yang dimaksud dengan rata-rata?', 'jawaban_kunci' => 'Rata-rata adalah jumlah data dibagi banyak data', 'poin' => 1, 'level' => 1],
        // Level 2
        ['soal' => 'Tentukan rata-rata dari data: 10, 20, 30, 40, 50', 'jawaban_kunci' => 'Rata rata = 30', 'poin' => 1, 'level' => 2],
        ['soal' => 'Jelaskan langkah-langkah menghitung rata-rata', 'jawaban_kunci' => 'Jumlahkan lalu bagi banyak data', 'poin' => 1, 'level' => 2],
        ['soal' => 'Data: 5, 10, 15, 20 Hitung rata-rata', 'jawaban_kunci' => 'Rata-rata = 12,5', 'poin' => 1, 'level' => 2],
        // Level 3
        ['soal' => 'Data nilai: 70, 75, 80, 85, 90 Hitung rata-rata', 'jawaban_kunci' => 'Rata-rata = 80', 'poin' => 1, 'level' => 3],
        // Level 4
        ['soal' => 'Data: 10, 20, 30, 40, 100 Analisis pengaruh nilai ekstrem terhadap rata-rata', 'jawaban_kunci' => 'Rata-rata sangat terpengaruh nilai ekstrem', 'poin' => 1, 'level' => 4],
    ]
];

// ========== WARNA & STYLING (TEMA MATEMATIKA) ==========
$c_kuartil = '#7c3aed';    // Violet
$c_median = '#14b8a6';     // Teal
$c_modus = '#d946ef';      // Pink
$c_jangkauan = '#f43f5e';  // Orange/Rose
$c_rata2 = '#10b981';      // Green

// ========== WARNA PEMAIN ==========
$playerColors = [
    ['name' => 'Merah', 'bg' => '#ef4444', 'bgSoft' => '#fef2f2'],
    ['name' => 'Biru', 'bg' => '#3b82f6', 'bgSoft' => '#eff6ff'],
    ['name' => 'Hijau', 'bg' => '#10b981', 'bgSoft' => '#ecfdf5'],
    ['name' => 'Kuning', 'bg' => '#f59e0b', 'bgSoft' => '#fffbeb']
];

// ========== BOARD DATA ==========
$board = [
    0 => ['tipe' => 'corner', 'nama' => 'START', 'warna' => '#e8e8eb'],
    1 => ['tipe' => 'kuis', 'nama' => 'Rata-rata (R1)', 'grup' => $c_rata2],
    2 => ['tipe' => 'kuis', 'nama' => 'Median (M1)', 'grup' => $c_median],
    3 => ['tipe' => 'kuis', 'nama' => 'Jangkauan (J2)', 'grup' => $c_jangkauan],
    4 => ['tipe' => 'kuis', 'nama' => 'Modus (D2)', 'grup' => $c_modus], 
    5 => ['tipe' => 'kuis', 'nama' => 'Rata-rata (R1)', 'grup' => $c_rata2],
    6 => ['tipe' => 'kuis', 'nama' => 'Kuartil (K2)', 'grup' => $c_kuartil],
    7 => ['tipe' => 'kuis', 'nama' => 'Jangkauan (J1)', 'grup' => $c_jangkauan],
    8 => ['tipe' => 'kuis', 'nama' => 'Modus (D2)', 'grup' => $c_modus],
    9 => ['tipe' => 'kuis', 'nama' => 'Median (M1)', 'grup' => $c_median],
    10 => ['tipe' => 'corner', 'nama' => 'Taman Bunga', 'warna' => '#e8e8eb'],
    11 => ['tipe' => 'kuis', 'nama' => 'Rata-rata (R2)', 'grup' => $c_rata2],
    12 => ['tipe' => 'kuis', 'nama' => 'Kuartil (K3)', 'grup' => $c_kuartil],
    13 => ['tipe' => 'kuis', 'nama' => 'Median (M2)', 'grup' => $c_median],
    14 => ['tipe' => 'kuis', 'nama' => 'Modus (D4)', 'grup' => $c_modus],
    15 => ['tipe' => 'kuis', 'nama' => 'Jangkauan (J3)', 'grup' => $c_jangkauan],
    16 => ['tipe' => 'kuis', 'nama' => 'Rata-rata (R2)', 'grup' => $c_rata2],
    17 => ['tipe' => 'kuis', 'nama' => 'Median (M4)', 'grup' => $c_median],
    18 => ['tipe' => 'kuis', 'nama' => 'Modus (D3)', 'grup' => $c_modus],
    19 => ['tipe' => 'kuis', 'nama' => 'Kuartil (K2)', 'grup' => $c_kuartil],
    20 => ['tipe' => 'corner', 'nama' => 'Kesempatan', 'warna' => '#e8e8eb'],
    21 => ['tipe' => 'kuis', 'nama' => 'Median (M3)', 'grup' => $c_median],
    22 => ['tipe' => 'kuis', 'nama' => 'Jangkauan (J2)', 'grup' => $c_jangkauan],
    23 => ['tipe' => 'kuis', 'nama' => 'Kuartil (K1)', 'grup' => $c_kuartil],
    24 => ['tipe' => 'kuis', 'nama' => 'Modus (D2)', 'grup' => $c_modus],
    25 => ['tipe' => 'kuis', 'nama' => 'Median (M2)', 'grup' => $c_median],
    26 => ['tipe' => 'kuis', 'nama' => 'Jangkauan (J2)', 'grup' => $c_jangkauan],
    27 => ['tipe' => 'kuis', 'nama' => 'Rata-rata (R2)', 'grup' => $c_rata2],
    28 => ['tipe' => 'kuis', 'nama' => 'Jangkauan (J3)', 'grup' => $c_jangkauan],
    29 => ['tipe' => 'kuis', 'nama' => 'Kuartil (K2)', 'grup' => $c_kuartil],
    30 => ['tipe' => 'corner', 'nama' => 'Bencana Alam', 'warna' => '#e8e8eb'],
    31 => ['tipe' => 'kuis', 'nama' => 'Median (M2)', 'grup' => $c_median],
    32 => ['tipe' => 'kuis', 'nama' => 'Modus (D3)', 'grup' => $c_modus],
    33 => ['tipe' => 'kuis', 'nama' => 'Rata-rata (R3)', 'grup' => $c_rata2],
    34 => ['tipe' => 'kuis', 'nama' => 'Kuartil (K4)', 'grup' => $c_kuartil],
    35 => ['tipe' => 'kuis', 'nama' => 'Median (M3)', 'grup' => $c_median],
    36 => ['tipe' => 'kuis', 'nama' => 'Rata-rata (R4)', 'grup' => $c_rata2],
    37 => ['tipe' => 'kuis', 'nama' => 'Modus (D1)', 'grup' => $c_modus],
    38 => ['tipe' => 'kuis', 'nama' => 'Jangkauan (J4)', 'grup' => $c_jangkauan],
    39 => ['tipe' => 'kuis', 'nama' => 'Kuartil (K1)', 'grup' => $c_kuartil]
];

?>
