<?php
// File: index.php
session_start();
require 'questions.php';

// Reset Game
if (isset($_POST['reset'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

// Inisialisasi State Game jika belum ada
if (!isset($_SESSION['player_pos'])) {
    $_SESSION['player_pos'] = 0;
    $_SESSION['player_score'] = 0;
    $_SESSION['pesan'] = "Selamat datang di MONIKA! Silakan lempar dadu untuk mulai.";
    $_SESSION['pertanyaan_aktif'] = null;
}

// Layout Papan (10 Petak sederhana)
// START, K, M, ZONK, D, J, START, R, BONUS, K
$board = [
    0 => ['nama' => 'START', 'tipe' => 'aman'],
    1 => ['nama' => 'Petak Kuartil (K)', 'tipe' => 'K'],
    2 => ['nama' => 'Petak Median (M)', 'tipe' => 'M'],
    3 => ['nama' => 'ZONK (Istirahat)', 'tipe' => 'aman'],
    4 => ['nama' => 'Petak Modus (D)', 'tipe' => 'D'],
    5 => ['nama' => 'Petak Jangkauan (J)', 'tipe' => 'J'],
    6 => ['nama' => 'Petak Rata-rata (R)', 'tipe' => 'R'],
    7 => ['nama' => 'DANA UMUM (+20 Poin)', 'tipe' => 'bonus'],
];

// Logika Lempar Dadu
if (isset($_POST['roll_dice']) && $_SESSION['pertanyaan_aktif'] === null) {
    $dadu = rand(1, 3); // Dadu 1-3 agar tidak terlalu cepat habis petaknya
    $_SESSION['player_pos'] += $dadu;
    
    // Loop kembali ke 0 jika melewati papan
    if ($_SESSION['player_pos'] >= count($board)) {
        $_SESSION['player_pos'] = $_SESSION['player_pos'] % count($board);
        $_SESSION['player_score'] += 20; // Bonus melewati START
        $_SESSION['pesan'] = "Melewati START! Dapat +20 Koin Poin. ";
    } else {
        $_SESSION['pesan'] = "";
    }

    $petak_sekarang = $board[$_SESSION['player_pos']];
    $_SESSION['pesan'] .= "Kamu melempar angka $dadu dan mendarat di: " . $petak_sekarang['nama'] . ".";

    // Cek aksi berdasarkan petak
    if ($petak_sekarang['tipe'] == 'bonus') {
        $_SESSION['player_score'] += 20;
        $_SESSION['pesan'] .= " Hore! Dapat bonus 20 poin!";
    } elseif ($petak_sekarang['tipe'] != 'aman') {
        // Ambil soal secara acak berdasarkan tipe petak (K, M, D, J, R)
        $tipe = $petak_sekarang['tipe'];
        $soal_acak = $questions[$tipe][array_rand($questions[$tipe])];
        $_SESSION['pertanyaan_aktif'] = $soal_acak;
    }
}

// Logika Cek Jawaban
if (isset($_POST['jawab'])) {
    $jawaban_user = trim($_POST['jawaban_user']);
    $jawaban_kunci = trim($_SESSION['pertanyaan_aktif']['jawaban_kunci']);
    $poin = $_SESSION['pertanyaan_aktif']['poin'];

    if (strtolower($jawaban_user) == strtolower($jawaban_kunci)) {
        $_SESSION['player_score'] += $poin;
        $_SESSION['pesan'] = "BENAR! Kamu mendapatkan +$poin Poin.";
    } else {
        $_SESSION['pesan'] = "SALAH! Jawaban yang benar adalah $jawaban_kunci.";
    }
    $_SESSION['pertanyaan_aktif'] = null; // Reset soal
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Game MONIKA - Monopoli Statistika</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f7f6; padding: 20px; }
        .board-container { display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; }
        .tile { width: 100px; height: 100px; border: 2px solid #ccc; display: flex; align-items: center; justify-content: center; text-align: center; background: white; border-radius: 8px; font-weight: bold; position: relative; }
        .tile.active { border-color: #0d6efd; background-color: #e9ecef; box-shadow: 0 0 10px rgba(13,110,253,0.5); }
        .player-pin { width: 20px; height: 20px; background: red; border-radius: 50%; position: absolute; bottom: 5px; }
    </style>
</head>
<body>

<div class="container">
    <h1 class="text-center text-primary mb-4">🎲 MONIKA (Monopoli Statistika)</h1>
    
    <div class="card mb-4 shadow-sm">
        <div class="card-body text-center">
            <h3>Skor Kamu: <span class="badge bg-success"><?= $_SESSION['player_score'] ?> Poin</span></h3>
            <p class="text-muted"><?= $_SESSION['pesan'] ?></p>
        </div>
    </div>

    <div class="board-container justify-content-center">
        <?php foreach ($board as $index => $petak): ?>
            <div class="tile <?= ($index == $_SESSION['player_pos']) ? 'active' : '' ?>">
                <?= $petak['nama'] ?>
                <?php if ($index == $_SESSION['player_pos']): ?>
                    <div class="player-pin"></div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="text-center">
        <?php if ($_SESSION['pertanyaan_aktif'] === null): ?>
            <form method="POST">
                <button type="submit" name="roll_dice" class="btn btn-primary btn-lg px-5">🎲 Lempar Dadu</button>
            </form>
        <?php else: ?>
            <div class="card border-warning mb-4 shadow-sm w-75 mx-auto">
                <div class="card-header bg-warning text-dark font-weight-bold">
                    ⚠️ TANTANGAN! (Level <?= $_SESSION['pertanyaan_aktif']['level'] ?> - <?= $_SESSION['pertanyaan_aktif']['poin'] ?> Poin)
                </div>
                <div class="card-body">
                    <p class="lead"><?= $_SESSION['pertanyaan_aktif']['soal'] ?></p>
                    <form method="POST">
                        <div class="input-group mb-3">
                            <input type="text" name="jawaban_user" class="form-control form-control-lg" placeholder="Masukkan angka jawaban..." required autocomplete="off">
                            <button type="submit" name="jawab" class="btn btn-success btn-lg">Jawab!</button>
                        </div>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <form method="POST" class="mt-4">
            <button type="submit" name="reset" class="btn btn-outline-danger btn-sm">Reset Permainan</button>
        </form>
    </div>
</div>

</body>
</html>