/**
 * MONOPOLI - STATISTIKA GAME
 * UI & Display Management
 */

// ========== AUTO ROLL TIMER — detik ==========
function startAutoRoll() {
    rollCountdown = 3;
    updateRollButton();
    autoRollInterval = setInterval(() => {
        rollCountdown--;
        if (rollCountdown > 0) {
            updateRollButton();
        } else {
            clearInterval(autoRollInterval);
            processTurn();
        }
    }, 1000);
}

function updateRollButton() {
    const btn = document.getElementById('btn-roll');
    if (!btn) return;
    const isUrgent = rollCountdown <= 10;
    btn.innerText = `🎲 KOCOK DADU (${rollCountdown}s)`;
    btn.style.background = isUrgent
        ? `linear-gradient(135deg, #dc2626, #991b1b)`
        : `linear-gradient(135deg, #2c3e50, #1a252f)`;
    if (isUrgent) {
        btn.classList.add('btn-roll-urgent');
    } else {
        btn.classList.remove('btn-roll-urgent');
    }
}

function clearAutoRoll() {
    clearInterval(autoRollInterval);
    const btn = document.getElementById('btn-roll');
    if (btn) {
        btn.innerText = `🎲 KOCOK DADU`;
        btn.style.background = '';
        btn.classList.remove('btn-roll-urgent');
    }
}

// ========== ACTION PHASE TIMER — 60 detik setelah kocok dadu ==========
function startActionTimer() {
    clearActionTimer();
    actionCountdown = 60;
    updateActionTimerDisplay();
    showActionTimerBar();

    actionTimerInterval = setInterval(() => {
        actionCountdown--;
        updateActionTimerDisplay();
        if (actionCountdown <= 0) {
            clearActionTimer();
            // Force close any open SweetAlert dialog
            Swal.close();
            Swal.fire({
                toast: true,
                position: 'top-end',
                title: '⏰ Waktu habis! Giliran otomatis berakhir.',
                icon: 'warning',
                showConfirmButton: false,
                timer: 2000
            }).then(() => {
                endTurn();
            });
        }
    }, 1000);
}

function clearActionTimer() {
    clearInterval(actionTimerInterval);
    hideActionTimerBar();
}

function updateActionTimerDisplay() {
    const timerEl = document.getElementById('action-timer-text');
    const barEl = document.getElementById('action-timer-progress');
    if (timerEl) {
        const mins = Math.floor(actionCountdown / 60);
        const secs = actionCountdown % 60;
        timerEl.textContent = `⏱️ ${mins}:${secs.toString().padStart(2, '0')}`;
        timerEl.className = actionCountdown <= 10 ? 'action-timer-text action-timer-urgent' : 'action-timer-text';
    }
    if (barEl) {
        const pct = (actionCountdown / 60) * 100;
        barEl.style.width = `${pct}%`;
        barEl.className = actionCountdown <= 10 ? 'action-timer-progress action-timer-bar-urgent' : 'action-timer-progress';
    }
}

function showActionTimerBar() {
    const container = document.getElementById('action-timer-container');
    if (container) container.classList.remove('d-none');
}

function hideActionTimerBar() {
    const container = document.getElementById('action-timer-container');
    if (container) container.classList.add('d-none');
}

// ========== STAR INDICATOR ==========
function updateStarIndicator(playerId) {
    const el = document.getElementById(`stars-p${playerId}`);
    if (!el) return;
    const p = players[playerId];
    const stars = p.stars || 0;
    el.innerHTML = '';
    for (let i = 0; i < 3; i++) {
        const star = document.createElement('span');
        star.className = i < stars ? 'star-filled' : 'star-empty';
        star.textContent = i < stars ? '⭐' : '☆';
        el.appendChild(star);
    }
}

// ========== UI UPDATE ==========
function updateUI() {
    document.getElementById('stock-rumah').innerText = stockRumah;
    document.getElementById('stock-hotel').innerText = stockHotel;

    let p = players[currentTurn];

    players.forEach(player => {
        if (player.isBankrupt) return;

        // Update money
        let mEl = document.getElementById(`money-p${player.id}`);
        mEl.innerText = formatRp(player.money);
        mEl.className = player.money < 0 ? 'player-money text-danger' : 'player-money text-success';

        // Update jail badge
        document.getElementById(`jail-badge-${player.id}`).classList.toggle('d-none', !player.inJail);

        // Update star indicator
        updateStarIndicator(player.id);

        // Active player highlight badge
        const activeBadge = document.getElementById(`active-badge-${player.id}`);
        if (activeBadge) {
            activeBadge.classList.toggle('d-none', player.id !== currentTurn);
        }

        // ===== REDESIGNED INVENTORY / CERTIFICATE =====
        const invDiv = document.getElementById(`inv-p${player.id}`);
        invDiv.innerHTML = '';

        player.properties.forEach((prop) => {
            let tileIndex = BOARD.findIndex(t => t.nama === prop.nama);
            let tile = BOARD[tileIndex];

            // Determine status description
            let statusText = '';
            let statusIcon = '';
            let statusClass = 'cert-status-empty';

            if (tile.mortgaged) {
                statusText = 'Digadaikan';
                statusIcon = '🔒';
                statusClass = 'cert-status-mortgaged';
            } else if (!tile.houses || tile.houses === 0) {
                statusText = 'Tanah Kosong';
                statusIcon = '🌿';
                statusClass = 'cert-status-empty';
            } else if (tile.houses < 5) {
                statusText = `${tile.houses} Rumah`;
                statusIcon = '🏠'.repeat(tile.houses);
                statusClass = 'cert-status-house';
            } else {
                statusText = 'Hotel';
                statusIcon = '🏨';
                statusClass = 'cert-status-hotel';
            }

            invDiv.innerHTML += `
                <div class="cert-card" style="border-left-color: ${prop.grup || '#aaa'}">
                    <div class="cert-top-row">
                        <div class="cert-name" style="color: ${prop.grup || '#333'}">${prop.nama}</div>
                        <button class="cert-inspect-btn" onclick="handleTileClick(${tileIndex})" title="Inspeksi Properti">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                    <div class="cert-status ${statusClass}">
                        <span class="cert-status-icon">${statusIcon}</span>
                        <span class="cert-status-text">${statusText}</span>
                    </div>
                </div>`;
        });

        player.cards.forEach((c) => {
            invDiv.innerHTML += `
                <div class="cert-card cert-card-special">
                    <div class="cert-top-row">
                        <div class="cert-name" style="color: #f39c12;">🎫 ${c.nama}</div>
                    </div>
                    <div class="cert-status cert-status-card">
                        <span class="cert-status-text">Kartu Khusus</span>
                    </div>
                </div>`;
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
                    btnEnd.innerText = '⚠️ LUNASI HUTANG!';
                    btnEnd.classList.remove('d-none');
                    btnBank.classList.add('d-none');
                }
            } else {
                btnEnd.disabled = false;
                btnEnd.innerText = '✅ AKHIRI GILIRAN';
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
