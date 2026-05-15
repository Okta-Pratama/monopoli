/**
 * MONOPOLI - STATISTIKA GAME
 * UI & Display Management
 */

// ========== AUTO ROLL TIMER ==========
function startAutoRoll() {
    rollCountdown = 3;
    document.getElementById('btn-roll').innerText = `KOCOK DADU (${rollCountdown})`;
    autoRollInterval = setInterval(() => {
        rollCountdown--;
        if (rollCountdown > 0) {
            document.getElementById('btn-roll').innerText = `KOCOK DADU (${rollCountdown})`;
        } else {
            clearInterval(autoRollInterval);
            processTurn();
        }
    }, 1000);
}

function clearAutoRoll() {
    clearInterval(autoRollInterval);
    document.getElementById('btn-roll').innerText = `KOCOK DADU`;
}

// ========== UI UPDATE ==========
function updateUI() {
    document.getElementById('stock-rumah').innerText = stockRumah;
    document.getElementById('stock-hotel').innerText = stockHotel;

    let p = players[currentTurn];

    players.forEach(player => {
        if (player.isBankrupt) return;
        let mEl = document.getElementById(`money-p${player.id}`);
        mEl.innerText = formatRp(player.money);
        mEl.className = player.money < 0 ? 'player-money text-danger' : 'player-money text-success';

        document.getElementById(`jail-badge-${player.id}`).classList.toggle('d-none', !player.inJail);
        const invDiv = document.getElementById(`inv-p${player.id}`);
        invDiv.innerHTML = '';

        player.properties.forEach((prop) => {
            let tileIndex = BOARD.findIndex(t => t.nama === prop.nama);
            let tile = BOARD[tileIndex];
            let status = '0';
            if (tile.mortgaged) status = '🔒';
            else if (tile.houses > 0) status = tile.houses;

            invDiv.innerHTML += `
                <div class="inv-item" style="border-left-color: ${prop.grup}">
                    <div class="inv-top">${prop.nama}</div>
                    <div style="display: flex; gap: 5px; align-items: center;">
                        <div class="badge-status">${status}</div>
                        <button class="btn btn-sm btn-info" style="padding: 2px 8px; font-size: 0.6rem;" onclick="handleTileClick(${tileIndex})"><i class="fa-solid fa-eye"></i></button>
                    </div>
                </div>`;
        });

        player.cards.forEach((c) => {
            invDiv.innerHTML += `<div class="inv-item" style="border-left-color: #f39c12"><div class="inv-top">${c.nama}</div></div>`;
        });
    });

    // Board Indicators
    BOARD.forEach((t, idx) => {
        let st = document.getElementById(`houses-${idx}`);
        let tileEl = document.getElementById(`tile-${idx}`);
        
        if (tileEl) tileEl.classList.remove('border-player-0', 'border-player-1', 'border-player-2', 'border-player-3');

        if (!st) return;
        if (t.owner === undefined) { st.innerHTML = ''; return; }
        
        if (tileEl) tileEl.classList.add(`border-player-${t.owner}`);

        if (t.mortgaged) st.innerHTML = '<i class="fa-solid fa-lock text-secondary"></i>';
        else {
            st.innerHTML = '';
            if (t.houses > 0 && t.houses < 5) {
                for (let i = 0; i < t.houses; i++) st.innerHTML += `<i class="fa-solid fa-house ${pColors[t.owner]}"></i>`;
            } else if (t.houses === 5) {
                st.innerHTML = `<i class="fa-solid fa-building ${pColors[t.owner]} fs-6"></i>`;
            }
        }
    });

    // Action Buttons
    if (isActionPhase && !p.isBankrupt) {
        let btnRoll = document.getElementById('btn-roll');
        let btnEnd = document.getElementById('btn-end');
        let btnBank = document.getElementById('btn-bankrupt');

        if (!hasRolled) {
            btnRoll.classList.remove('d-none');
            btnRoll.disabled = false;
            btnEnd.classList.add('d-none');
            btnBank.classList.add('d-none');
        } else {
            btnRoll.classList.add('d-none');
            if (p.money < 0) {
                let totalAssets = calculateAssets(p.id);
                if (p.money + totalAssets < 0) {
                    btnEnd.classList.add('d-none');
                    btnBank.classList.remove('d-none');
                } else {
                    btnEnd.disabled = true;
                    btnEnd.innerText = 'LUNASI HUTANG!';
                    btnEnd.classList.remove('d-none');
                    btnBank.classList.add('d-none');
                }
            } else {
                btnEnd.disabled = false;
                btnEnd.innerText = 'AKHIRI GILIRAN';
                btnEnd.classList.remove('d-none');
                btnBank.classList.add('d-none');
            }
        }
    }
}

function calculateAssets(id) {
    let val = 0;
    players[id].properties.forEach(prop => {
        let t = BOARD.find(x => x.nama === prop.nama);
        if (!t.mortgaged) val += t.harga / 2;
        if (t.houses > 0 && t.houses < 5) val += t.houses * 25;
        if (t.houses === 5) val += 125;
    });
    return val;
}

function updatePawnPositions() {
    players.forEach(p => {
        if (p.isBankrupt) return;
        const tileEl = document.getElementById(`tile-${p.pos}`);
        const pawnEl = document.getElementById(`pawn-${p.id}`);
        if (tileEl && pawnEl) {
            const rect = tileEl.getBoundingClientRect();
            const boardRect = document.getElementById('board').getBoundingClientRect();
            const top = rect.top - boardRect.top + (rect.height / 2) - 15;
            const left = rect.left - boardRect.left + (rect.width / 2) - 15;
            const offsetX = p.id % 2 === 0 ? -12 : 12;
            const offsetY = p.id < 2 ? -12 : 12;
            pawnEl.style.transform = `translate(${left + offsetX}px, ${top + offsetY}px)`;
        }
    });
}
