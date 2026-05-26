/**
 * MONOPOLI - STATISTIKA GAME
 * Jail System
 */

function payJail(id) {
    let p = players[id];
    p.money -= 50;
    p.inJail = false;
    p.stars = 0;
    p.jailTurns = 0;
    playSfx(sfx.door);
    logGameEvent(`Player ${p.id + 1} membayar denda bebas penjara sebesar ${formatRp(50)}.`, 'jail', p.id);
    updateUI();
    Swal.close();
    if (p.money < 0) {
        hasRolled = true;
        updateUI();
        Swal.fire('Hutang!', 'Jual aset untuk membayar denda penjara.', 'warning');
    } else {
        Swal.fire('Bebas!', 'Denda Rp 50.000 dibayar. Silakan kocok dadu!', 'success').then(() => rollAndMove(p));
    }
}

function skipJailTurn(id) {
    let p = players[id];
    p.jailTurns++;
    if (p.jailTurns >= 2) {
        p.inJail = false;
        p.stars = 0;
        playSfx(sfx.door);
        logGameEvent(`Player ${p.id + 1} dibebaskan secara otomatis setelah melewati giliran ke-2 di penjara.`, 'jail', p.id);
    } else {
        logGameEvent(`Player ${p.id + 1} melewatkan giliran di penjara (Tahanan: ${p.jailTurns}/2).`, 'jail', p.id);
    }
    hasRolled = true;
    updateUI();
    Swal.close();
}

function rollDiceJail(id) {
    let p = players[id];
    Swal.close();

    // Animasi dadu penjara
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

            if (finalDice === 6) {
                // Bebas dari penjara!
                p.inJail = false;
                p.stars = 0;
                p.jailTurns = 0;
                playSfx(sfx.door);
                logGameEvent(`Player ${p.id + 1} mengocok dadu di penjara dan mendapatkan angka 6! Bebas dari penjara!`, 'jail', p.id);
                updateUI();
                Swal.fire({
                    title: '🎲 Dadu 6! Bebas!',
                    html: `<div style="font-size:1.1rem;">Kamu berhasil melempar angka <b>6</b>!<br>Kamu <b>bebas dari penjara</b> dan boleh melangkah 6 langkah!</div>`,
                    icon: 'success',
                    confirmButtonText: 'Maju!'
                }).then(() => {
                    // Jalankan 6 langkah
                    let step = 0;
                    let stepInterval = setInterval(() => {
                        step++;
                        p.pos = (p.pos + 1) % 40;
                        playSfx(sfx.move);
                        if (p.pos === 0) {
                            p.money += 200;
                            updateUI();
                            Swal.fire({
                                toast: true, position: 'top-end',
                                title: `START! +${formatRp(200)}`, icon: 'success',
                                showConfirmButton: false, timer: 2000
                            });
                        }
                        updatePawnPositions();
                        if (step === 6) {
                            clearInterval(stepInterval);
                            hasRolled = true;
                            setTimeout(() => handleTileLogic(p, BOARD[p.pos]), 400);
                        }
                    }, 300);
                });
            } else {
                // Gagal bebas
                p.jailTurns++;
                hasRolled = true;
                logGameEvent(`Player ${p.id + 1} mengocok dadu di penjara dan mendapatkan angka ${finalDice} (Gagal bebas!).`, 'jail', p.id);
                updateUI();
                Swal.fire({
                    title: `🎲 Dadu ${finalDice} — Gagal!`,
                    html: `<div>Kamu butuh angka <b>6</b> untuk bebas.<br>Giliran dilewati.</div>`,
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
            }
        }
    }, 80);
}

function useJailCard(id) {
    if (!isActionPhase && id !== currentTurn) return;
    let p = players[id];
    if (!p.inJail) return Swal.fire('Tidak Bisa', 'Anda tidak di penjara, kartu tidak bisa digunakan sekarang.', 'warning');
    let cIdx = p.cards.findIndex(c => c.type === 'free_jail');
    if (cIdx > -1) {
        p.cards.splice(cIdx, 1);
        p.inJail = false;
        p.stars = 0;
        p.jailTurns = 0;
        playSfx(sfx.door);
        logGameEvent(`Player ${p.id + 1} menggunakan Kartu Bebas Penjara untuk keluar secara gratis.`, 'jail', p.id);
        updateUI();
        Swal.fire('Bebas!', 'Kartu digunakan. Silakan kocok dadu!', 'success').then(() => { if (!hasRolled) rollAndMove(p); });
    }
}
