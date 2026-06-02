/**
 * MONOPOLI - STATISTIKA GAME
 * Turn & Movement Logic
 */

function processTurn() {
    clearAutoRoll();
    hasRolled = true;
    canEndTurn = false;
    updateUI();
    let p = players[currentTurn];
    rollAndMove(p);
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
            logGameEvent(`Player ${p.id + 1} mengocok dadu dan mendapat angka <b>${finalDice}</b>.`, 'dice', p.id);

            let step = 0;
            let stepInterval = setInterval(() => {
                step++;
                p.pos = (p.pos + 1) % 40;
                playSfx(sfx.move);
                if (p.pos === 0) {
                    p.money += 200;
                    updateUI();
                    logGameEvent(`Player ${p.id + 1} melewati START dan mendapat bonus ${formatRp(200)}.`, 'buy', p.id);
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

function movePlayerAnimate(p, steps, onComplete) {
    let step = 0;
    let dir = steps > 0 ? 1 : -1;
    let absSteps = Math.abs(steps);
    
    if (absSteps === 0) {
        if (typeof onComplete === 'function') onComplete();
        return;
    }
    
    let stepInterval = setInterval(() => {
        step++;
        p.pos = (p.pos + dir + 40) % 40;
        playSfx(sfx.move);
        
        if (dir === 1 && p.pos === 0) {
            p.money += 200;
            updateUI();
            logGameEvent(`Player ${p.id + 1} melewati START dan mendapat bonus ${formatRp(200)}.`, 'buy', p.id);
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
        
        if (step === absSteps) {
            clearInterval(stepInterval);
            setTimeout(() => {
                if (typeof onComplete === 'function') onComplete();
            }, 400);
        }
    }, 300);
}

function teleportPlayer(p, targetPos, onComplete) {
    p.pos = targetPos;
    playSfx(sfx.move);
    updatePawnPositions();
    setTimeout(() => {
        if (typeof onComplete === 'function') onComplete();
    }, 400);
}

function handleTileLogic(p, tile) {
    let tileIndex = BOARD.indexOf(tile);
    if (tileIndex !== 0 && tileIndex !== 20) {
        triggerStatsQuestion(p, tileIndex, () => {
            executeActualTileLogic(p, tile);
        });
    } else {
        executeActualTileLogic(p, tile);
    }
}

function executeActualTileLogic(p, tile) {
    let tileIndex = BOARD.indexOf(tile);
    if (tile.tipe === 'pajak') {
        p.money -= tile.harga;
        playSfx(sfx.bell);
        updateUI();
        logGameEvent(`Player ${p.id + 1} membayar Pajak sebesar ${formatRp(tile.harga)}.`, 'rent', p.id);
        Swal.fire('Denda!', `Kamu bayar ${formatRp(tile.harga)}`, 'error').then(() => {
            canEndTurn = true;
            updateUI();
        });
    } else if (tile.tipe === 'kesempatan' || tile.tipe === 'dana_umum') {
        if (tile.tipe === 'kesempatan' && p.blockTamanBunga) {
            p.blockTamanBunga = false; // Reset flag
            logGameEvent(`Player ${p.id + 1} kehilangan kesempatan mengambil Kartu Taman Bunga!`, 'system', p.id);
            Swal.fire({
                title: 'Kesempatan Diblokir!',
                text: 'Kamu kehilangan kesempatan mengambil Kartu Taman Bunga akibat efek Kartu Bencana Alam!',
                icon: 'warning',
                confirmButtonText: 'Lanjutkan'
            }).then(() => {
                canEndTurn = true;
                updateUI();
            });
        } else if (tile.tipe === 'kesempatan' && p.doubleTamanBunga) {
            p.doubleTamanBunga = false; // Reset flag
            logGameEvent(`Player ${p.id + 1} mengambil 2x Kartu Taman Bunga!`, 'card', p.id);
            Swal.fire({
                title: 'Double Draw!',
                text: 'Kamu mendapat bonus mengambil 2 Kartu Taman Bunga sekaligus!',
                icon: 'success',
                confirmButtonText: 'Ambil Kartu 1'
            }).then(() => {
                drawCard('kesempatan', p, () => {
                    setTimeout(() => {
                        Swal.fire({
                            title: 'Double Draw!',
                            text: 'Sekarang ambil Kartu Taman Bunga kedua!',
                            icon: 'success',
                            confirmButtonText: 'Ambil Kartu 2'
                        }).then(() => {
                            drawCard('kesempatan', p);
                        });
                    }, 1000);
                });
            });
        } else if (tile.tipe === 'dana_umum' && p.skipPeristiwaAlam) {
            p.skipPeristiwaAlam = false; // Reset flag
            logGameEvent(`Player ${p.id + 1} melewati Peristiwa Alam berkat imunitas Kartu Taman Bunga!`, 'system', p.id);
            Swal.fire({
                title: 'Bencana Terlewati!',
                text: 'Kamu selamat dari Peristiwa Alam berkat imunitas Kartu Taman Bunga!',
                icon: 'success',
                confirmButtonText: 'Lanjutkan'
            }).then(() => {
                canEndTurn = true;
                updateUI();
            });
        } else {
            drawCard(tile.tipe, p);
        }
    } else if (tile.tipe === 'properti' || tile.tipe === 'bandara' || tile.tipe === 'utilitas') {
        if (tile.owner !== undefined && tile.owner !== p.id) {
            if (tile.mortgaged) {
                Swal.fire('Bebas Sewa', 'Properti sedang digadaikan.', 'info').then(() => {
                    canEndTurn = true;
                    updateUI();
                });
            } else {
                let rent = tile.sewa || 25;
                if (tile.houses) rent += (tile.houses * 20);
                p.money -= rent;
                players[tile.owner].money += rent;
                playSfx(sfx.bell);
                updateUI();
                logGameEvent(`Player ${p.id + 1} membayar sewa ${formatRp(rent)} ke Player ${tile.owner + 1} di <b>${tile.nama}</b>.`, 'rent', p.id);
                Swal.fire('Bayar Sewa!', `Membayar ${formatRp(rent)} ke Player ${tile.owner + 1}`, 'warning').then(() => {
                    canEndTurn = true;
                    updateUI();
                });
            }
        } else if (tile.owner === undefined) {
            promptBuyProperty(p, tileIndex, () => {
                canEndTurn = true;
                updateUI();
            });
        } else {
            canEndTurn = true;
            updateUI();
        }
    } else {
        // Start or other corners (jail, free parking)
        canEndTurn = true;
        updateUI();
    }
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
    logGameEvent(`Player ${p.id + 1} dinyatakan bangkrut! Semua aset disita oleh bank.`, 'system', p.id);
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
    
    let p = players[currentTurn];
    if (p.extraTurn && !p.isBankrupt) {
        p.extraTurn = false; // Reset flag
        logGameEvent(`Player ${p.id + 1} mendapatkan Bonus Jalan 2x!`, 'system', p.id);
        
        hasRolled = false;
        canEndTurn = false;
        updateUI();
        
        Swal.fire({
            title: `🎲 BONUS JALAN 2x!`,
            text: `Kamu mendapat giliran tambahan!`,
            icon: 'success',
            background: pBgSoft[currentTurn],
            confirmButtonText: 'Mulai Main'
        }).then(() => {
            startAutoRoll();
        });
        return;
    }

    document.getElementById(`ui-p${currentTurn}`).classList.remove('active-turn');
    
    let loopCount = 0;
    do {
        currentTurn = (currentTurn + 1) % 4;
        loopCount++;
        
        if (players[currentTurn].skipNextTurn && !players[currentTurn].isBankrupt) {
            players[currentTurn].skipNextTurn = false; // Reset flag
            logGameEvent(`Giliran Player ${currentTurn + 1} dilewati karena efek Kartu Bencana Alam!`, 'system', currentTurn);
            Swal.fire({
                toast: true,
                position: 'top-end',
                title: `🔇 Player ${currentTurn + 1} Lewat Giliran!`,
                icon: 'warning',
                showConfirmButton: false,
                timer: 3000
            });
        } else {
            if (!players[currentTurn].isBankrupt) {
                break;
            }
        }
    } while (loopCount < 8);

    document.getElementById(`ui-p${currentTurn}`).classList.add('active-turn');
    document.getElementById('turn-indicator').innerText = `GILIRAN PLAYER ${currentTurn + 1}`;
    document.getElementById('turn-indicator').className = `turn-indicator-text fw-bold mb-1 ${pColors[currentTurn]}`;

    hasRolled = false;
    canEndTurn = false;
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
