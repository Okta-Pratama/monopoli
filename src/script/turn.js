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
        let jailOpts = `<button class="btn btn-warning m-1 fw-bold" onclick="payJail(${p.id})">Bayar ${formatRp(50)}</button>`;
        if (hasJailCard) jailOpts += `<button class="btn btn-info m-1 fw-bold" onclick="useJailCard(${p.id})">Pakai Kartu</button>`;
        jailOpts += `<br><button class="btn btn-danger m-1 mt-2 fw-bold" onclick="skipJailTurn(${p.id})">Lewati Giliran</button>`;
        Swal.fire({ title: 'Terkurung di Penjara!', html: jailOpts, showConfirmButton: false, allowOutsideClick: false });
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
                        title: `START! +${formatRp(200)}`,
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
                updatePawnPositions();
                if (step === finalDice) {
                    clearInterval(stepInterval);
                    hasRolled = true;
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
    document.getElementById(`ui-p${currentTurn}`).classList.remove('active-turn');
    do { currentTurn = (currentTurn + 1) % 4; } while (players[currentTurn].isBankrupt);

    document.getElementById(`ui-p${currentTurn}`).classList.add('active-turn');
    document.getElementById('turn-indicator').innerText = `GILIRAN PLAYER ${currentTurn + 1}`;
    document.getElementById('turn-indicator').className = `fw-bold mb-1 ${pColors[currentTurn]}`;

    hasRolled = false;
    updateUI();

    Swal.fire({
        title: `<span class="${pColors[currentTurn]}">Giliran Player ${currentTurn + 1}</span>`,
        background: pBgSoft[currentTurn],
        allowOutsideClick: false,
        confirmButtonText: 'OK',
        didOpen: () => { Swal.disableButtons(); setTimeout(() => { Swal.enableButtons(); }, 2000); }
    }).then(() => {
        startAutoRoll();
    });
}
