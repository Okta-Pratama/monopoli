/**
 * MONOPOLI - STATISTIKA GAME
 * Turn & Movement Logic
 */

function processTurn() {
    clearAutoRoll();
    let p = players[currentTurn];
    document.getElementById('btn-roll').disabled = true;

    if (p.inJail) {
        let hasJailCard = p.cards.find(c => c.type === 'free_jail');
        let jailOpts = `
            <div style="display:flex; flex-direction:column; gap:10px; padding:10px;">
                <button class="btn btn-warning fw-bold" onclick="payJail(${p.id})" style="border-radius:12px; padding:12px;">
                    <i class="fa-solid fa-money-bill-wave me-2"></i>Bayar Denda ${formatRp(50)}
                </button>
                <button class="btn btn-primary fw-bold" onclick="rollDiceJail(${p.id})" style="border-radius:12px; padding:12px;">
                    <i class="fa-solid fa-dice me-2"></i>Kocok Dadu (Butuh angka 6!)
                </button>
                ${hasJailCard ? `<button class="btn btn-info fw-bold text-white" onclick="useJailCard(${p.id})" style="border-radius:12px; padding:12px;"><i class="fa-solid fa-ticket me-2"></i>Pakai Kartu Bebas</button>` : ''}
                <button class="btn btn-outline-danger fw-bold mt-2" onclick="skipJailTurn(${p.id})" style="border-radius:12px; padding:12px;">
                    <i class="fa-solid fa-forward me-2"></i>Lewati Giliran (${p.jailTurns}/2)
                </button>
            </div>`;
        Swal.fire({
            title: '<i class="fa-solid fa-bars-staggered me-2"></i>Terkurung di Penjara!',
            html: jailOpts,
            showConfirmButton: false,
            allowOutsideClick: false,
            customClass: { popup: 'swal-jail-popup' }
        });
    } else {
        rollAndMove(p);
    }
}

function rollAndMove(p) {
    let diceIcon = document.getElementById('dice-css-icon');
    const dCls = ['fa-dice-one', 'fa-dice-two', 'fa-dice-three', 'fa-dice-four', 'fa-dice-five', 'fa-dice-six'];
    let rollCount = 0;
    playSfx(sfx.dice);

    let rollInterval = setInterval(() => {
        diceIcon.className = `fa-solid ${dCls[Math.floor(Math.random() * 6)]} text-secondary`;
        rollCount++;
        if (rollCount > 10) {
            clearInterval(rollInterval);
            let finalDice = Math.floor(Math.random() * 6) + 1;
            diceIcon.className = `fa-solid ${dCls[finalDice - 1]} ${pColors[p.id]} animate__animated animate__bounceIn`;

            let step = 0;
            let stepInterval = setInterval(() => {
                step++;
                p.pos = (p.pos + 1) % 40;
                playSfx(sfx.move);
                if (p.pos === 0) {
                    p.money += 200;
                    updateUI();
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        title: `🎉 Lewat START! +${formatRp(200)}`,
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 2500
                    });
                }
                updatePawnPositions();
                if (step === finalDice) {
                    clearInterval(stepInterval);
                    hasRolled = true;
                    startActionTimer();
                    setTimeout(() => handleTileLogic(p, BOARD[p.pos]), 400);
                }
            }, 300);
        }
    }, 80);
}

function handleTileLogic(p, tile) {
    if (p.pos === 30) {
        p.pos = 10;
        p.inJail = true;
        p.jailTurns = 0;
        playSfx(sfx.jail);
        updatePawnPositions();
        updateUI();
        Swal.fire({
            title: '🚔 Masuk Penjara!',
            text: 'Kamu langsung ditangkap dan masuk penjara!',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    } else if (tile.tipe === 'pajak') {
        p.money -= tile.harga;
        playSfx(sfx.bell);
        updateUI();
        Swal.fire('Denda!', `Kamu bayar ${formatRp(tile.harga)}`, 'error');
    } else if (tile.tipe === 'kesempatan' || tile.tipe === 'dana_umum') {
        drawCard(tile.tipe, p);
    } else if (tile.tipe === 'properti' || tile.tipe === 'bandara' || tile.tipe === 'utilitas') {
        if (tile.owner !== undefined && tile.owner !== p.id) {
            if (tile.mortgaged) {
                Swal.fire('Bebas Sewa', 'Properti sedang digadaikan.', 'info');
            } else {
                let rent = tile.sewa || 25;
                if (tile.houses) rent += (tile.houses * 20);
                p.money -= rent;
                players[tile.owner].money += rent;
                playSfx(sfx.bell);
                updateUI();
                Swal.fire('Bayar Sewa!', `Membayar ${formatRp(rent)} ke Player ${tile.owner + 1}`, 'warning');
            }
        } else if (tile.owner === undefined && p.money >= tile.harga) {
            triggerStatsQuestion(p, BOARD.indexOf(tile));
        }
    }
    updateUI();
}

function declareBankrupt() {
    clearActionTimer();
    let p = players[currentTurn];
    p.isBankrupt = true;
    p.properties.forEach(prop => {
        let t = BOARD.find(x => x.nama === prop.nama);
        t.owner = undefined;
        t.mortgaged = false;
        if (t.houses > 0 && t.houses < 5) stockRumah += t.houses;
        if (t.houses === 5) { stockRumah += 4; stockHotel += 1; }
        t.houses = 0;
    });
    p.properties = [];
    p.money = 0;
    document.getElementById('pawn-' + p.id).classList.add('bankrupt-pawn');
    document.getElementById('ui-p' + p.id).classList.add('bankrupt-ui');
    Swal.fire('Bangkrut!', `Player ${p.id + 1} telah bangkrut dan keluar dari permainan.`, 'error').then(() => endTurn());
}

function promptEndTurn() {
    Swal.fire({
        title: 'Akhiri Giliran?',
        text: "Yakin ingin mengakhiri giliranmu?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Akhiri!'
    }).then((result) => { if (result.isConfirmed) endTurn(); });
}

function endTurn() {
    clearActionTimer();
    document.getElementById(`ui-p${currentTurn}`).classList.remove('active-turn');
    do { currentTurn = (currentTurn + 1) % 4; } while (players[currentTurn].isBankrupt);

    document.getElementById(`ui-p${currentTurn}`).classList.add('active-turn');
    document.getElementById('turn-indicator').innerText = `GILIRAN PLAYER ${currentTurn + 1}`;
    document.getElementById('turn-indicator').className = `turn-indicator-text fw-bold mb-1 ${pColors[currentTurn]}`;

    hasRolled = false;
    updateUI();

    const playerIcons = ['fa-chess-pawn', 'fa-chess-knight', 'fa-chess-rook', 'fa-chess-queen'];
    const playerColorNames = ['#ef4444', '#3b82f6', '#10b981', '#f59e0b'];

    let countdown = 3;
    Swal.fire({
        title: `<i class="fa-solid ${playerIcons[currentTurn]}" style="color:${playerColorNames[currentTurn]}; font-size:2rem;"></i><br><span style="color:${playerColorNames[currentTurn]}; font-size:1.3rem;">Giliran Player ${currentTurn + 1}!</span>`,
        html: `<div style="padding:10px 0; color:#555;">Bersiap dalam <b id="swal-countdown">${countdown}</b> detik...</div>`,
        background: pBgSoft[currentTurn],
        allowOutsideClick: false,
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: () => {
            const countdownEl = document.getElementById('swal-countdown');
            const interval = setInterval(() => {
                countdown--;
                if (countdownEl) countdownEl.textContent = countdown;
                if (countdown <= 0) clearInterval(interval);
            }, 1000);
        }
    }).then(() => {
        startAutoRoll();
    });
}
