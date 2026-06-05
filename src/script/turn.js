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
        diceIcon.className = `fa-solid ${dCls[Math.floor(Math.random() * 6)]} cb-dice-icon text-secondary`;
        rollCount++;
        if (rollCount > 10) {
            clearInterval(rollInterval);
            let finalDice = Math.floor(Math.random() * 6) + 1;
            diceIcon.className = `fa-solid ${dCls[finalDice - 1]} cb-dice-icon ${pColors[p.id]} animate__animated animate__bounceIn`;
            logGameEvent(`${p.name} mengocok dadu dan mendapat angka <b>${finalDice}</b>.`, 'dice', p.id);

            let step = 0;
            let stepInterval = setInterval(() => {
                step++;
                p.pos = (p.pos + 1) % 40;
                playSfx(sfx.move);
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
    if (tileIndex === 0) {
        // START - deactivated
        canEndTurn = true;
        updateUI();
    } else if (tileIndex === 10) {
        // Taman Bunga card
        drawCard('kesempatan', p);
    } else if (tileIndex === 30) {
        // Bencana Alam card
        drawCard('dana_umum', p);
    } else if (tileIndex === 20) {
        // Kesempatan - choose and teleport
        if (typeof teleportAndSolveDialog === 'function') {
            teleportAndSolveDialog(p, false);
        } else {
            canEndTurn = true;
            updateUI();
        }
    } else {
        // Question tile
        triggerStatsQuestion(p, tileIndex, () => {
            endTurn();
        });
    }
}

function executeActualTileLogic(p, tile) {
    endTurn();
}

function declareBankrupt() {
    // Disabled in new rules
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
        logGameEvent(`${p.name} mendapatkan Bonus Jalan 2x!`, 'system', p.id);
        
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
            logGameEvent(`Giliran ${players[currentTurn].name} dilewati karena efek Kartu Bencana Alam!`, 'system', currentTurn);
            Swal.fire({
                toast: true,
                position: 'top-end',
                title: `🔇 ${players[currentTurn].name} Lewat Giliran!`,
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
    document.getElementById('turn-indicator').innerText = `GILIRAN ${players[currentTurn].name.toUpperCase()}`;
    document.getElementById('turn-indicator').className = `turn-indicator-text fw-bold mb-1 ${pColors[currentTurn]}`;

    hasRolled = false;
    canEndTurn = false;
    updateUI();

    const playerIcons = ['fa-chess-pawn', 'fa-chess-knight', 'fa-chess-rook', 'fa-chess-queen'];
    const playerColorNames = ['#ef4444', '#3b82f6', '#10b981', '#f59e0b'];

    Swal.fire({
        title: `<i class="fa-solid ${playerIcons[currentTurn]}" style="color:${playerColorNames[currentTurn]}; font-size:2rem;"></i><br><span style="color:${playerColorNames[currentTurn]}; font-size:1.3rem;">Giliran ${players[currentTurn].name}!</span>`,
        html: `<div style="padding:10px 0; color:#555;">Silakan lempar dadu untuk memulai giliran Anda.</div>`,
        background: pBgSoft[currentTurn],
        allowOutsideClick: false,
        showConfirmButton: true,
        confirmButtonText: 'Mulai Giliran'
    }).then(() => {
        startAutoRoll();
    });
}
