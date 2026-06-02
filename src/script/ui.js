/**
 * MONOPOLI - STATISTIKA GAME
 * UI & Display Management
 */

// ========== GLOBAL GAME TIMER, SOUND & LOGS ==========
let gameStartTime = Date.now();
let gameTimerInterval;
let isMuted = false;
let unreadLogsCount = 0;

function initGameTimer() {
    gameStartTime = Date.now();
    clearInterval(gameTimerInterval);
    gameTimerInterval = setInterval(() => {
        const diff = Date.now() - gameStartTime;
        const hrs = Math.floor(diff / 3600000).toString().padStart(2, '0');
        const mins = Math.floor((diff % 3600000) / 60000).toString().padStart(2, '0');
        const secs = Math.floor((diff % 60000) / 1000).toString().padStart(2, '0');
        const timerEl = document.getElementById('game-duration');
        if (timerEl) {
            timerEl.textContent = `${hrs}:${mins}:${secs}`;
        }
    }, 1000);
}

// Sound Control Interceptor
const originalPlaySfx = playSfx;
playSfx = function(sound) {
    if (isMuted) return;
    originalPlaySfx(sound);
};

function toggleMute() {
    isMuted = !isMuted;
    const btn = document.getElementById('btn-sound-toggle');
    if (btn) {
        if (isMuted) {
            btn.innerHTML = `<i class="fa-solid fa-volume-xmark"></i>`;
            btn.title = "Unmute Suara";
            btn.style.color = '#ef4444';
        } else {
            btn.innerHTML = `<i class="fa-solid fa-volume-high"></i>`;
            btn.title = "Mute Suara";
            btn.style.color = '';
        }
    }
}

// Help Modal Controls
function showHelpModal() {
    const modal = document.getElementById('help-modal');
    if (modal) {
        modal.classList.remove('d-none');
        modal.classList.add('animate__animated', 'animate__fadeIn');
    }
}
function hideHelpModal() {
    const modal = document.getElementById('help-modal');
    if (modal) {
        modal.classList.add('d-none');
    }
}
function closeHelpModalOnOuterClick(e) {
    if (e.target.id === 'help-modal') {
        hideHelpModal();
    }
}

// Activity Log System (Disabled for performance optimization)
function logGameEvent(message, type = 'system', playerId = null) {}
function toggleLogPanel() {}
function clearGameLogs() {}

// Group names map helper
function getGroupNameByColor(color) {
    const colorsMap = {
        '#964b00': 'Kelompok Coklat',
        '#14b8a6': 'Kelompok Teal',
        '#d946ef': 'Kelompok Pink',
        '#f43f5e': 'Kelompok Rose',
        '#e11d48': 'Kelompok Merah',
        '#84cc16': 'Kelompok Lime',
        '#10b981': 'Kelompok Hijau',
        '#7c3aed': 'Kelompok Violet'
    };
    return colorsMap[color.toLowerCase()] || colorsMap[color] || 'Properti';
}

function hexToRgb(hex) {
    var shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
    hex = hex.replace(shorthandRegex, function(m, r, g, b) {
        return r + r + g + g + b + b;
    });
    var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result ? 
        parseInt(result[1], 16) + ',' + parseInt(result[2], 16) + ',' + parseInt(result[3], 16)
        : '255,255,255';
}

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
    
    // Live update for timer element inside SweetAlert
    const swalTimerEl = document.getElementById('swal-action-timer');
    if (swalTimerEl) {
        swalTimerEl.textContent = `⏱️ ${actionCountdown} detik`;
        if (actionCountdown <= 10) {
            swalTimerEl.style.color = '#ef4444';
            swalTimerEl.style.textShadow = '0 0 10px rgba(239, 68, 68, 0.6)';
        } else {
            swalTimerEl.style.color = '#fbbf24';
            swalTimerEl.style.textShadow = '0 0 8px rgba(251, 191, 36, 0.3)';
        }
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
    if (el) {
        const p = players[playerId];
        const stars = p.stars || 0;
        el.innerHTML = `
            <span class="red-stars-val" style="color: #ef4444; font-weight: bold; font-size: 0.9rem; filter: drop-shadow(0 0 3px rgba(239,68,68,0.6)); margin-right: 8px;">
                <i class="fa-solid fa-star"></i> ${stars}
            </span>
        `;
    }
    const blueEl = document.getElementById(`blue-stars-p${playerId}`);
    if (blueEl) {
        const p = players[playerId];
        const blueStars = p.blueStars || 0;
        blueEl.innerHTML = `
            <span class="blue-stars-val" style="color: #3b82f6; font-weight: bold; font-size: 0.9rem; filter: drop-shadow(0 0 3px rgba(59,130,246,0.6));">
                <i class="fa-solid fa-star"></i> ${blueStars}
            </span>
        `;
    }
}

function initGameTimer() {
    gameStartTime = Date.now();
    clearInterval(gameTimerInterval);
    const durationLimit = 15 * 60 * 1000; // 15 minutes in ms
    gameTimerInterval = setInterval(() => {
        const elapsed = Date.now() - gameStartTime;
        const remaining = Math.max(0, durationLimit - elapsed);
        const mins = Math.floor(remaining / 60000).toString().padStart(2, '0');
        const secs = Math.floor((remaining % 60000) / 1000).toString().padStart(2, '0');
        const timerEl = document.getElementById('game-duration');
        if (timerEl) {
            timerEl.textContent = `${mins}:${secs}`;
        }
        if (remaining <= 0) {
            clearInterval(gameTimerInterval);
            endGame();
        }
    }, 1000);
}

// ========== UI UPDATE ==========
function updateUI() {
    let p = players[currentTurn];

    players.forEach(player => {
        const sectionEl = document.getElementById(`ui-p${player.id}`);
        if (sectionEl) {
            sectionEl.classList.remove('bankrupt-ui');
            let overlay = sectionEl.querySelector('.bankrupt-overlay-banner');
            if (overlay) overlay.remove();
        }

        // Hide net worth badge if it exists
        let nwEl = document.getElementById(`networth-p${player.id}`);
        if (nwEl) nwEl.style.display = 'none';

        // Hide money element if it exists
        let mEl = document.getElementById(`money-p${player.id}`);
        if (mEl) mEl.style.display = 'none';

        // Update star indicator
        updateStarIndicator(player.id);

        // Active player highlight badge and section class
        const activeBadge = document.getElementById(`active-badge-${player.id}`);
        if (sectionEl) {
            sectionEl.classList.toggle('active-turn', player.id === currentTurn);
        }
        if (activeBadge) {
            activeBadge.classList.toggle('d-none', player.id !== currentTurn);
        }

        // ===== COMPACT PLAYER STATS PANEL =====
        const netEl = document.getElementById(`net-p${player.id}`);
        if (netEl) {
            let netScore = (player.blueStars || 0) - (player.stars || 0);
            netEl.textContent = (netScore > 0 ? '+' : '') + netScore;
            netEl.style.color = netScore > 0 ? '#34d399' : (netScore < 0 ? '#ef4444' : '#94a3b8');
        }
    });

    // Reset board highlights & houses
    BOARD.forEach((t, idx) => {
        let st = document.getElementById(`houses-${idx}`);
        let tileEl = document.getElementById(`tile-${idx}`);

        if (tileEl) tileEl.classList.remove('border-player-0', 'border-player-1', 'border-player-2', 'border-player-3');
        if (st) st.innerHTML = '';
    });

    // Action Buttons
    if (isActionPhase) {
        let btnRoll = document.getElementById('btn-roll');
        let btnEnd = document.getElementById('btn-end');
        let btnBank = document.getElementById('btn-bankrupt');

        if (btnBank) btnBank.classList.add('d-none');

        if (!hasRolled) {
            btnRoll.classList.remove('d-none');
            btnRoll.disabled = false;
            btnEnd.classList.add('d-none');
        } else {
            btnRoll.classList.add('d-none');
            if (canEndTurn) {
                btnEnd.disabled = false;
                btnEnd.innerText = '✅ AKHIRI GILIRAN';
                btnEnd.classList.remove('d-none');
            } else {
                btnEnd.disabled = true;
                btnEnd.innerText = '⏳ PROSES GILIRAN...';
                btnEnd.classList.remove('d-none');
            }
        }
    }
}

function endGame() {
    clearInterval(gameTimerInterval);
    if (typeof clearAutoRoll === 'function') clearAutoRoll();
    if (typeof clearActionTimer === 'function') clearActionTimer();

    playSfx(sfx.bell);

    let ranked = players.map(p => {
        let netScore = (p.blueStars || 0) - (p.stars || 0);
        return {
            id: p.id,
            name: `Player ${p.id + 1}`,
            blue: p.blueStars || 0,
            red: p.stars || 0,
            net: netScore
        };
    });

    ranked.sort((a, b) => {
        if (b.net !== a.net) return b.net - a.net;
        return b.blue - a.blue;
    });

    let maxNet = ranked[0].net;
    let winners = ranked.filter(r => r.net === maxNet);
    let winnerText = winners.length > 1
        ? `Pemenang Bersama: ${winners.map(w => w.name).join(' & ')} 🎉`
        : `Pemenang: ${winners[0].name} 🎉`;

    let tableHtml = `
        <div style="font-family:'Poppins',sans-serif; text-align:center;">
            <div style="font-size:1.6rem; color:#f59e0b; font-weight:800; margin-bottom:15px; text-shadow:0 0 10px rgba(245,158,11,0.3);">${winnerText}</div>
            <table class="table table-dark table-striped table-bordered" style="font-size:0.85rem; border-color:#334155; margin-bottom:10px; color:#f8fafc;">
                <thead>
                    <tr style="background-color:#1e293b; color:#94a3b8;">
                        <th>Peringkat</th>
                        <th>Player</th>
                        <th style="color:#3b82f6;"><i class="fa-solid fa-star"></i> Biru</th>
                        <th style="color:#ef4444;"><i class="fa-solid fa-star"></i> Merah</th>
                        <th style="color:#10b981;">Skor Akhir</th>
                    </tr>
                </thead>
                <tbody>
                    ${ranked.map((r, index) => {
                        let rankStr = ['🥇 1st', '🥈 2nd', '🥉 3rd', '4th'][index];
                        let isWinner = winners.some(w => w.id === r.id);
                        let highlightStyle = isWinner ? 'font-weight:800; color:#fbbf24; background-color:rgba(245,158,11,0.1);' : '';
                        return `
                            <tr style="${highlightStyle}">
                                <td>${rankStr}</td>
                                <td><b>${r.name}</b></td>
                                <td style="color:#60a5fa;">${r.blue}</td>
                                <td style="color:#f87171;">${r.red}</td>
                                <td style="color:#34d399; font-weight:bold;">${r.net}</td>
                            </tr>
                        `;
                    }).join('')}
                </tbody>
            </table>
            <div style="font-size:0.75rem; color:#94a3b8; margin-top:10px;">Skor Akhir = Bintang Biru - Bintang Merah</div>
        </div>
    `;

    Swal.fire({
        title: '⏱️ PERMAINAN SELESAI',
        html: tableHtml,
        background: '#0f172a',
        color: '#f8fafc',
        allowOutsideClick: false,
        confirmButtonText: '🔄 Main Lagi',
        confirmButtonColor: '#10b981',
        customClass: { popup: 'swal-card-stats' }
    }).then(() => {
        window.location.reload();
    });
}

function updatePawnPositions(isIntro = false) {
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
            const targetX = left + offsetX;
            const targetY = top + offsetY;

            if (isIntro) {
                // Posisi awal di atas langit-langit papan
                pawnEl.style.transform = `translate(${targetX}px, -150px) scale(2.5)`;
                pawnEl.style.opacity = '0';
                pawnEl.style.transition = 'none';

                // Memicu reflow
                pawnEl.offsetHeight;

                // Animasi drop secara staggered
                setTimeout(() => {
                    pawnEl.style.transition = 'transform 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275), opacity 0.8s ease';
                    pawnEl.style.transform = `translate(${targetX}px, ${targetY}px) scale(1)`;
                    pawnEl.style.opacity = '1';
                }, 200 + p.id * 150);
            } else {
                pawnEl.style.transition = 'all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1)';
                pawnEl.style.transform = `translate(${targetX}px, ${targetY}px)`;
                pawnEl.style.opacity = '1';
            }
        }
    });
}

function startGameWithIntro() {
    const mainCard = document.getElementById('intro-main-card');
    const rulesCard = document.getElementById('intro-rules-card');
    
    if (mainCard && rulesCard) {
        playSfx(sfx.card);
        mainCard.classList.add('animate__animated', 'animate__zoomOut');
        setTimeout(() => {
            mainCard.classList.add('d-none');
            rulesCard.classList.remove('d-none');
            rulesCard.classList.add('animate__animated', 'animate__zoomIn');
        }, 450);
    } else {
        confirmRulesAndStartCountdown();
    }
}

function switchRulesTab(tabIdx) {
    playSfx(sfx.move);
    const tabBtns = document.querySelectorAll('.rules-tab-btn');
    const tabContents = document.querySelectorAll('.rules-tab-content');
    
    tabBtns.forEach((btn, idx) => {
        if (idx === tabIdx) btn.classList.add('active');
        else btn.classList.remove('active');
    });
    
    tabContents.forEach((content, idx) => {
        if (idx === tabIdx) {
            content.classList.remove('d-none');
            content.classList.add('animate__animated', 'animate__fadeIn');
        } else {
            content.classList.add('d-none');
        }
    });
}

function confirmRulesAndStartCountdown() {
    playSfx(sfx.buy);
    
    const introScreen = document.getElementById('intro-screen');
    const countdownOverlay = document.getElementById('countdown-overlay');
    const countdownNum = document.getElementById('countdown-number');
    const countdownTxt = document.getElementById('countdown-text');
    
    if (introScreen) {
        introScreen.classList.add('animate__animated', 'animate__zoomOut');
        setTimeout(() => {
            introScreen.style.display = 'none';
        }, 500);
    }
    
    if (countdownOverlay) {
        countdownOverlay.classList.remove('d-none');
        
        let seconds = 3;
        const tickSfx = [sfx.card, sfx.card, sfx.card];
        const statusTexts = [
            "Mempersiapkan Dadu Keberuntungan...",
            "Mengacak Kartu Keberuntungan...",
            "Mempersiapkan Papan Statistika..."
        ];
        
        // Staggered pawn drop in the background during countdown to avoid heavy UI load
        setTimeout(() => {
            const appContainer = document.querySelector('.app-container');
            if (appContainer) appContainer.classList.add('game-started');
            updatePawnPositions(true);
        }, 800);
        
        let countdownInterval = setInterval(() => {
            seconds--;
            if (seconds > 0) {
                playSfx(tickSfx[seconds - 1]);
                if (countdownNum) {
                    countdownNum.textContent = seconds;
                    // Reset CSS animation to re-trigger bounce on number change
                    countdownNum.style.animation = 'none';
                    countdownNum.offsetHeight; /* trigger reflow */
                    countdownNum.style.animation = 'countdown-bounce 1s infinite ease-in-out';
                }
                if (countdownTxt) countdownTxt.textContent = statusTexts[seconds - 1];
            } else if (seconds === 0) {
                playSfx(sfx.door);
                if (countdownNum) {
                    countdownNum.textContent = "GO!";
                    countdownNum.style.color = "#10b981";
                    countdownNum.style.textShadow = "0 0 30px rgba(16,185,129,0.8), 0 0 60px rgba(16,185,129,0.4)";
                    countdownNum.style.animation = 'none';
                    countdownNum.offsetHeight;
                    countdownNum.style.animation = 'countdown-bounce 1s infinite ease-in-out';
                }
                if (countdownTxt) countdownTxt.textContent = "MEMULAI PERMAINAN!";
            } else {
                clearInterval(countdownInterval);
                countdownOverlay.style.opacity = '0';
                setTimeout(() => {
                    countdownOverlay.classList.add('d-none');
                    
                    // Re-calculate and lock pawn start positions once layout is 100% stable
                    updatePawnPositions(false);
                    
                    // Fire up game timer and auto-roll loops after dramatic loading finishes
                    initGameTimer();
                    if (typeof logGameEvent === 'function') {
                        logGameEvent("Permainan dimulai! Selamat datang di MONIKA (Monopoly Statistika).", "system");
                    }
                    if (typeof startAutoRoll === 'function') {
                        startAutoRoll();
                    }
                }, 500);
            }
        }, 1000);
    }
}
