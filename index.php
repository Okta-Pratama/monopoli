<?php
// File: index.php
session_start();
@include 'questions.php'; 

// Jika tidak ada soal, beri default array kosong agar JS tidak error
if (!isset($questions)) {
    $questions = ['K'=>[], 'M'=>[], 'D'=>[], 'J'=>[], 'R'=>[]];
}

// 1 & 2. Penambahan Landmark dan Ikon Negara (Emoji Bendera + FontAwesome)
$board = [
    ['nama' => 'START', 'tipe' => 'aman', 'ikon' => 'fa-plane-departure', 'bendera' => '🛫', 'warna' => '#6c757d', 'desc' => 'Titik Awal'],
    ['nama' => 'Tokyo', 'tipe' => 'K', 'ikon' => 'fa-torii-gate', 'bendera' => '🇯🇵', 'warna' => '#0dcaf0', 'desc' => 'Kuartil'],
    ['nama' => 'Seoul', 'tipe' => 'K', 'ikon' => 'fa-yin-yang', 'bendera' => '🇰🇷', 'warna' => '#0dcaf0', 'desc' => 'Kuartil'],
    ['nama' => 'Dana Umum', 'tipe' => 'bonus_uang', 'ikon' => 'fa-sack-dollar', 'bendera' => '💰', 'warna' => '#ffc107', 'desc' => 'Gratis Koin'],
    ['nama' => 'Beijing', 'tipe' => 'K', 'ikon' => 'fa-dragon', 'bendera' => '🇨🇳', 'warna' => '#0dcaf0', 'desc' => 'Kuartil'],
    ['nama' => 'Paris', 'tipe' => 'M', 'ikon' => 'fa-archway', 'bendera' => '🇫🇷', 'warna' => '#198754', 'desc' => 'Median'],
    ['nama' => 'London', 'tipe' => 'M', 'ikon' => 'fa-bus-simple', 'bendera' => '🇬🇧', 'warna' => '#198754', 'desc' => 'Median'],
    ['nama' => 'Remedial', 'tipe' => 'aman', 'ikon' => 'fa-bed', 'bendera' => '🛌', 'warna' => '#dc3545', 'desc' => 'Istirahat (Aman)'],
    ['nama' => 'Berlin', 'tipe' => 'M', 'ikon' => 'fa-beer-mug-empty', 'bendera' => '🇩🇪', 'warna' => '#198754', 'desc' => 'Median'],
    ['nama' => 'New York', 'tipe' => 'D', 'ikon' => 'fa-city', 'bendera' => '🇺🇸', 'warna' => '#0d6efd', 'desc' => 'Modus'],
    ['nama' => 'Jackpot', 'tipe' => 'bonus_x2', 'ikon' => 'fa-star', 'bendera' => '🌟', 'warna' => '#ffc107', 'desc' => '+100 Poin Instan!'],
    ['nama' => 'Rio de Janeiro', 'tipe' => 'D', 'ikon' => 'fa-umbrella-beach', 'bendera' => '🇧🇷', 'warna' => '#0d6efd', 'desc' => 'Modus'],
    ['nama' => 'Toronto', 'tipe' => 'D', 'ikon' => 'fa-leaf', 'bendera' => '🇨🇦', 'warna' => '#0d6efd', 'desc' => 'Modus'],
    ['nama' => 'Bebas Parkir', 'tipe' => 'aman', 'ikon' => 'fa-square-parking', 'bendera' => '🅿️', 'warna' => '#6c757d', 'desc' => 'Parkir Aman'],
    ['nama' => 'Kairo', 'tipe' => 'J', 'ikon' => 'fa-monument', 'bendera' => '🇪🇬', 'warna' => '#dc3545', 'desc' => 'Jangkauan'],
    ['nama' => 'Cape Town', 'tipe' => 'J', 'ikon' => 'fa-mountain-sun', 'bendera' => '🇿🇦', 'warna' => '#dc3545', 'desc' => 'Jangkauan'],
    ['nama' => 'Subsidi Pemerintah', 'tipe' => 'bonus_uang', 'ikon' => 'fa-hand-holding-dollar', 'bendera' => '💸', 'warna' => '#ffc107', 'desc' => 'Gratis Koin'],
    ['nama' => 'Sydney', 'tipe' => 'R', 'ikon' => 'fa-bridge-water', 'bendera' => '🇦🇺', 'warna' => '#0dcaf0', 'desc' => 'Rata-rata'],
    ['nama' => 'Auckland', 'tipe' => 'R', 'ikon' => 'fa-sailboat', 'bendera' => '🇳🇿', 'warna' => '#0dcaf0', 'desc' => 'Rata-rata'],
    ['nama' => 'Bandara', 'tipe' => 'aman', 'ikon' => 'fa-plane', 'bendera' => '✈️', 'warna' => '#6c757d', 'desc' => 'Persiapan Terbang']
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monopoli Statistika</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        body {
            background-color: #e9ecef;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* 5. UI 4 Pemain di Pojok Layar */
        .player-card {
            position: fixed;
            width: 160px;
            z-index: 100;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            padding: 10px;
            text-align: center;
            border: 3px solid transparent;
            transition: all 0.3s ease;
        }
        .player-card.active-turn { transform: scale(1.1); box-shadow: 0 0 20px rgba(0,0,0,0.5); }
        .p0 { top: 20px; left: 20px; border-color: #dc3545; } /* Merah */
        .p1 { top: 20px; right: 20px; border-color: #0d6efd; } /* Biru */
        .p2 { bottom: 80px; left: 20px; border-color: #198754; } /* Hijau */
        .p3 { bottom: 80px; right: 20px; border-color: #ffc107; } /* Kuning */

        .score-display { font-size: 1.2rem; font-weight: bold; }

        /* Area Papan Utama */
        .game-area {
            margin: auto;
            max-width: 900px;
            padding: 120px 20px; /* Jarak agar tidak tertutup player card */
            position: relative;
        }

        .board-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 8px;
            position: relative;
            background: #fff;
            padding: 15px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        @media (max-width: 768px) {
            .board-grid { grid-template-columns: repeat(4, 1fr); }
            .player-card { width: 120px; padding: 5px; }
            .p2, .p3 { bottom: 120px; } /* Naik sedikit di mobile */
            .score-display { font-size: 1rem; }
        }

        .tile {
            position: relative;
            height: 110px;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            background-color: #f8f9fa;
        }
        .tile-header { font-size: 1.5rem; margin-bottom: 2px; }
        .tile-name { font-size: 0.8rem; font-weight: bold; line-height: 1.1; }
        .tile-desc { font-size: 0.65rem; color: #6c757d; }

        /* 8. Animasi Bidak */
        .pawn-container {
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            pointer-events: none; /* Agar tidak menghalangi klik */
        }
        .pawn {
            width: 25px; height: 25px;
            border-radius: 50%;
            position: absolute;
            border: 2px solid white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.5);
            transition: all 0.5s cubic-bezier(0.25, 0.8, 0.25, 1); /* Animasi mulus */
            z-index: 50;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; color: white; font-weight: bold;
        }
        .bg-p0 { background-color: #dc3545; }
        .bg-p1 { background-color: #0d6efd; }
        .bg-p2 { background-color: #198754; }
        .bg-p3 { background-color: #ffc107; color: black; }

        /* Area Kontrol Tengah */
        .dice-area {
            text-align: center;
            margin-bottom: 20px;
        }
        .btn-dice {
            border-radius: 50px;
            padding: 15px 40px;
            font-size: 1.2rem;
            font-weight: bold;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }

        /* 9. Tombol Reset di bawah */
        .reset-area {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1000;
        }
    </style>
</head>
<body>

<script>
    const BOARD_DATA = <?= json_encode($board) ?>;
    const QUESTIONS_DATA = <?= json_encode($questions) ?>;
</script>

<div id="ui-p0" class="player-card p0 active-turn">
    <div class="fw-bold text-danger">Player 1</div>
    <div class="score-display text-danger">💰 <span id="score-p0">0</span></div>
</div>
<div id="ui-p1" class="player-card p1">
    <div class="fw-bold text-primary">Player 2</div>
    <div class="score-display text-primary">💰 <span id="score-p1">0</span></div>
</div>
<div id="ui-p2" class="player-card p2">
    <div class="fw-bold text-success">Player 3</div>
    <div class="score-display text-success">💰 <span id="score-p2">0</span></div>
</div>
<div id="ui-p3" class="player-card p3">
    <div class="fw-bold text-warning">Player 4</div>
    <div class="score-display text-warning">💰 <span id="score-p3">0</span></div>
</div>

<div class="game-area">
    <div class="dice-area">
        <h4 id="turn-indicator" class="fw-bold mb-3">Giliran Player 1</h4>
        <p class="text-danger fw-bold mb-2">Waktu Lempar: <span id="dice-timer">15</span>s</p>
        <button id="btn-roll" class="btn btn-primary btn-dice" onclick="rollDice()">
            <i class="fa-solid fa-dice"></i> Lempar Dadu
        </button>
    </div>

    <div class="board-grid" id="board">
        <?php foreach ($board as $index => $petak): ?>
            <div class="tile" id="tile-<?= $index ?>" style="border-top: 5px solid <?= $petak['warna'] ?>">
                <div class="tile-header">
                    <?= $petak['bendera'] ?> <i class="fa-solid <?= $petak['ikon'] ?> fs-6 ms-1" style="color: <?= $petak['warna'] ?>"></i>
                </div>
                <div class="tile-name"><?= $petak['nama'] ?></div>
                <div class="tile-desc"><?= $petak['desc'] ?></div>
            </div>
        <?php endforeach; ?>
        
        <div class="pawn-container" id="pawns">
            <div id="pawn-0" class="pawn bg-p0">P1</div>
            <div id="pawn-1" class="pawn bg-p1">P2</div>
            <div id="pawn-2" class="pawn bg-p2">P3</div>
            <div id="pawn-3" class="pawn bg-p3">P4</div>
        </div>
    </div>
</div>

<div class="reset-area">
    <button class="btn btn-outline-danger btn-sm rounded-pill px-4 bg-white" onclick="location.reload()">Mulai Ulang Game</button>
</div>

<script>
    // State Game
    const players = [
        { id: 0, pos: 0, score: 0, name: 'Player 1', colorClass: 'text-danger' },
        { id: 1, pos: 0, score: 0, name: 'Player 2', colorClass: 'text-primary' },
        { id: 2, pos: 0, score: 0, name: 'Player 3', colorClass: 'text-success' },
        { id: 3, pos: 0, score: 0, name: 'Player 4', colorClass: 'text-warning' }
    ];
    let currentTurn = 0;
    
    // Konfigurasi Timer
    let diceTimerVal = 15;
    let diceInterval;

    // Inisialisasi awal
    window.onload = () => {
        updatePawnPositions();
        startDiceTimer();
        window.addEventListener('resize', updatePawnPositions); // Update animasi jika layar diresize
    };

    function startDiceTimer() {
        clearInterval(diceInterval);
        diceTimerVal = 15;
        document.getElementById('dice-timer').innerText = diceTimerVal;
        
        diceInterval = setInterval(() => {
            diceTimerVal--;
            document.getElementById('dice-timer').innerText = diceTimerVal;
            if (diceTimerVal <= 0) {
                clearInterval(diceInterval);
                Swal.fire({ icon: 'warning', title: 'Waktu Habis!', text: 'Giliran dilewati.', timer: 2000 });
                nextTurn();
            }
        }, 1000);
    }

    function rollDice() {
        clearInterval(diceInterval);
        document.getElementById('btn-roll').disabled = true;
        
        const diceNum = Math.floor(Math.random() * 6) + 1;
        const player = players[currentTurn];
        
        // Pindah Posisi
        player.pos += diceNum;
        if (player.pos >= BOARD_DATA.length) {
            player.pos = player.pos % BOARD_DATA.length;
            player.score += 50; // Bonus melewati start
            updateScoreUI(player);
        }

        // Animasi pergerakan bidak
        updatePawnPositions();

        // 7. Alert 5 Detik sebelum aksi
        const tile = BOARD_DATA[player.pos];
        Swal.fire({
            title: `Dadu: ${diceNum}`,
            html: `Mendarat di <b>${tile.bendera} ${tile.nama}</b>!<br>Bersiaplah...`,
            timer: 5000,
            timerProgressBar: true,
            allowOutsideClick: false,
            showConfirmButton: false
        }).then(() => {
            handleTileAction(player, tile);
        });
    }

    function handleTileAction(player, tile) {
        // 3. Logika Tantangan vs Boosting
        if (tile.tipe === 'aman') {
            Swal.fire({ icon: 'info', title: 'Aman!', text: 'Tidak ada tantangan di sini.', timer: 2000 });
            nextTurn();
        } else if (tile.tipe === 'bonus_uang') {
            player.score += 30;
            updateScoreUI(player);
            Swal.fire({ icon: 'success', title: 'Rezeki Nomplok!', text: '+30 Koin Gratis!', timer: 2000 });
            nextTurn();
        } else if (tile.tipe === 'bonus_x2') {
            player.score += 100;
            updateScoreUI(player);
            Swal.fire({ icon: 'success', title: 'JACKPOT!', text: '+100 Koin Instan!', timer: 2000 });
            nextTurn();
        } else {
            // Tipe Soal (K, M, D, J, R)
            triggerQuestion(player, tile.tipe);
        }
    }

    function triggerQuestion(player, tipeSoal) {
        const questionPool = QUESTIONS_DATA[tipeSoal];
        if (!questionPool || questionPool.length === 0) {
            Swal.fire('Error', 'Bank soal untuk kategori ini belum tersedia.', 'error');
            return nextTurn();
        }

        const q = questionPool[Math.floor(Math.random() * questionPool.length)];

        // 4 & 10. Popup Tantangan Matematika dengan Timer
        Swal.fire({
            title: `<i class="fa-solid fa-brain"></i> Tantangan Level ${q.level}`,
            text: q.soal,
            input: 'text',
            inputPlaceholder: 'Ketik jawaban berupa angka...',
            showCancelButton: false,
            confirmButtonText: 'Kunci Jawaban',
            allowOutsideClick: false,
            timer: 30000, // Waktu jawab 30 detik
            timerProgressBar: true
        }).then((result) => {
            if (result.isConfirmed) {
                const answer = result.value.trim();
                if (answer.toLowerCase() === q.jawaban_kunci.toLowerCase()) {
                    player.score += parseInt(q.poin);
                    updateScoreUI(player);
                    Swal.fire({ icon: 'success', title: 'BENAR!', text: `Mendapatkan +${q.poin} Koin!`, timer: 2000 });
                } else {
                    Swal.fire({ icon: 'error', title: 'SALAH!', text: `Jawaban yang benar adalah: ${q.jawaban_kunci}`, timer: 3000 });
                }
            } else if (result.dismiss === Swal.DismissReason.timer) {
                Swal.fire({ icon: 'error', title: 'Waktu Habis!', text: 'Kamu terlalu lama menjawab.', timer: 2000 });
            }
            setTimeout(nextTurn, 2500); // Jeda sebelum ganti giliran
        });
    }

    function nextTurn() {
        document.getElementById(`ui-p${currentTurn}`).classList.remove('active-turn');
        
        currentTurn++;
        if (currentTurn > 3) currentTurn = 0;
        
        document.getElementById(`ui-p${currentTurn}`).classList.add('active-turn');
        
        const nextPlayer = players[currentTurn];
        const indicator = document.getElementById('turn-indicator');
        indicator.innerText = `Giliran ${nextPlayer.name}`;
        indicator.className = `fw-bold mb-3 ${nextPlayer.colorClass}`;
        
        document.getElementById('btn-roll').disabled = false;
        startDiceTimer();
    }

    function updateScoreUI(player) {
        document.getElementById(`score-p${player.id}`).innerText = player.score;
    }

    // Sistem Animasi Bidak Relatif terhadap Grid
    function updatePawnPositions() {
        players.forEach((p, idx) => {
            const tileElement = document.getElementById(`tile-${p.pos}`);
            const pawnElement = document.getElementById(`pawn-${idx}`);
            
            if (tileElement && pawnElement) {
                // Kalkulasi offset bidak agar tidak menumpuk sempurna di tengah (memberi jarak tiap bidak)
                const offsetX = (idx % 2 === 0 ? -10 : 10);
                const offsetY = (idx < 2 ? -10 : 10);
                
                const topPos = tileElement.offsetTop + (tileElement.offsetHeight / 2) - 12 + offsetY;
                const leftPos = tileElement.offsetLeft + (tileElement.offsetWidth / 2) - 12 + offsetX;

                pawnElement.style.transform = `translate(${leftPos}px, ${topPos}px)`;
            }
        });
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>