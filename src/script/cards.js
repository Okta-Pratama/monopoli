/**
 * MONOPOLI - STATISTIKA GAME
 * Cards & Questions Logic
 */

// ========== TILE QUIZ CONFIGURATION ==========
const TILE_QUIZ_CONFIG = {
    1: { category: 'R', level: 1 },
    2: { category: 'M', level: 1 },
    3: { category: 'J', level: 2 },
    4: { category: 'D', level: 2 },
    5: { category: 'R', level: 1 },
    6: { category: 'K', level: 2 },
    7: { category: 'J', level: 1 },
    8: { category: 'D', level: 2 },
    9: { category: 'M', level: 1 },
    // 10: Special Selection
    11: { category: 'R', level: 2 },
    12: { category: 'K', level: 3 },
    13: { category: 'M', level: 2 },
    14: { category: 'D', level: 4 },
    15: { category: 'J', level: 3 },
    16: { category: 'R', level: 2 },
    17: { category: 'M', level: 4 },
    18: { category: 'D', level: 3 },
    19: { category: 'K', level: 2 },
    // 20: No kuis (Bebas Parkir)
    21: { category: 'M', level: 3 },
    22: { category: 'J', level: 2 },
    23: { category: 'K', level: 1 },
    24: { category: 'D', level: 2 },
    25: { category: 'M', level: 2 },
    26: { category: 'J', level: 2 },
    27: { category: 'R', level: 2 },
    28: { category: 'J', level: 3 },
    29: { category: 'K', level: 2 },
    // 30: Special Selection
    31: { category: 'M', level: 2 },
    32: { category: 'D', level: 3 },
    33: { category: 'R', level: 3 },
    34: { category: 'K', level: 4 },
    35: { category: 'M', level: 3 },
    36: { category: 'R', level: 4 },
    37: { category: 'D', level: 1 },
    38: { category: 'J', level: 4 },
    39: { category: 'K', level: 1 }
};

function getCategoryDisplayName(cat) {
    const map = {
        'K': 'Kuartil',
        'M': 'Median',
        'D': 'Modus',
        'J': 'Jangkauan',
        'R': 'Rata-rata'
    };
    return map[cat] || 'Statistika';
}

function getQuestionForTile(tileIndex) {
    const conf = TILE_QUIZ_CONFIG[tileIndex];
    if (!conf) return null;

    const cat = conf.category;
    const level = conf.level;

    // Filter questions of this category and level
    let pool = QUESTIONS[cat].filter(q => q.level === level);
    if (pool.length === 0) {
        pool = QUESTIONS[cat]; // fallback
    }
    
    // Pick a random question from matching pool
    return pool[Math.floor(Math.random() * pool.length)];
}

function triggerStatsQuestion(p, tileIndex, onComplete) {
    if (tileIndex === 10 || tileIndex === 30) {
        triggerSpecialCardQuiz(p, tileIndex, onComplete);
        return;
    }

    playSfx(sfx.card);
    let q = getQuestionForTile(tileIndex);
    if (!q) {
        if (typeof onComplete === 'function') onComplete();
        return;
    }

    let conf = TILE_QUIZ_CONFIG[tileIndex];
    let levelStr = '⭐'.repeat(conf.level);

    Swal.fire({
        html: `
            <div class="stat-inner-card">
                <i class="fa-solid fa-brain" style="font-size:3rem; color:#e74c3c;"></i><br>
                <span style="font-size:1.2rem; letter-spacing:2px; font-weight:800; color:#c0392b; display:inline-block; margin-top:10px;">STATISTIKA</span>
                <hr style="margin-top:15px; margin-bottom:15px; border-top: 2px solid #e74c3c; width: 60%; margin-left: auto; margin-right: auto;">
                <div style="font-size: 0.8rem; color: #777; margin-bottom: 8px;">Materi: ${getCategoryDisplayName(conf.category)} (Tingkat: ${levelStr})</div>
                <div style="font-size: 1.1rem; font-weight: bold; margin-bottom: 10px;">${q.soal}</div>
                <div style="font-size: 0.9rem; color: #3b82f6; font-weight: bold;">(Reward: +${conf.level} Bintang Biru)</div>
                <div id="swal-action-timer" style="font-size: 1.15rem; font-weight: 900; color: #fbbf24; margin-top: 15px; font-family:'Poppins',sans-serif; text-shadow: 0 0 8px rgba(251,191,36,0.3);">⏱️ 60 detik</div>
            </div>
        `,
        input: 'text',
        customClass: { popup: 'swal-card-stats' },
        showClass: { popup: 'animate__animated animate__flipInY' },
        showCancelButton: false,
        allowOutsideClick: false,
        confirmButtonText: 'Kunci Jawaban'
    }).then((res) => {
        if (isActionTimeout) return;
        if (res.value && res.value.toLowerCase().trim() == q.jawaban_kunci.toLowerCase().trim()) {
            // Jawaban benar — tambah bintang biru sesuai tingkat kesulitan
            p.blueStars = (p.blueStars || 0) + conf.level;
            updateStarIndicator(p.id);
            logGameEvent(`${p.name} menjawab kuis Statistika dengan <b>BENAR</b>! (Total Bintang Biru: ${p.blueStars})`, 'statistika', p.id);
            
            Swal.fire({
                title: 'Tepat!',
                html: `<div>Jawaban benar! Anda mendapatkan ${conf.level} Bintang Biru.</div>
                       <div style="font-size: 1.2rem; margin: 10px 0; color: #3b82f6;"><i class="fa-solid fa-star"></i> Bintang Biru Bertambah!</div>`,
                icon: 'success',
                confirmButtonText: 'Lanjutkan'
            }).then(() => {
                updateUI();
                if (typeof onComplete === 'function') onComplete();
            });
        } else {
            // Jawaban salah — tambah bintang merah & cek denda lewat addWarningStars secara silent
            logGameEvent(`${p.name} menjawab kuis Statistika dengan <b>SALAH</b>! (Jawaban Anda: "${res.value || ''}", Kunci: "${q.jawaban_kunci}")`, 'statistika', p.id);

            let countdown = 3;
            let countdownInterval;
            Swal.fire({
                title: 'Salah!',
                html: `<div>Jawaban benar: <b>${q.jawaban_kunci}</b></div>
                       <div style="font-size: 1.2rem; margin: 10px 0; color: #ef4444;"><i class="fa-solid fa-star"></i> Bintang Merah Bertambah!</div>
                       <div style="margin-top: 10px; font-size: 0.9rem; color: #555;">Lanjut otomatis dalam <b id="swal-countdown">${countdown}</b> detik...</div>`,
                icon: 'warning',
                confirmButtonText: 'Lanjutkan',
                timer: 3000,
                timerProgressBar: true,
                didOpen: () => {
                    const countdownEl = document.getElementById('swal-countdown');
                    countdownInterval = setInterval(() => {
                        countdown--;
                        if (countdownEl) countdownEl.textContent = countdown;
                        if (countdown <= 0) clearInterval(countdownInterval);
                    }, 1000);
                },
                willClose: () => {
                    clearInterval(countdownInterval);
                }
            }).then(() => {
                addWarningStars(p, 1, true); // Silent because we already showed the warning!
                updateUI();
                if (typeof onComplete === 'function') onComplete();
            });
        }
    });
}

function getCategoryCardStyle(cat) {
    const map = {
        'K': { icon: 'fa-percent', color: '#10b981', bg: 'linear-gradient(135deg, #064e3b, #022c22)', border: '#10b981' },
        'M': { icon: 'fa-chart-simple', color: '#f59e0b', bg: 'linear-gradient(135deg, #78350f, #451a03)', border: '#f59e0b' },
        'D': { icon: 'fa-calculator', color: '#ef4444', bg: 'linear-gradient(135deg, #7f1d1d, #450a0a)', border: '#ef4444' },
        'J': { icon: 'fa-chart-pie', color: '#3b82f6', bg: 'linear-gradient(135deg, #1e3a8a, #172554)', border: '#3b82f6' },
        'R': { icon: 'fa-chart-line', color: '#ec4899', bg: 'linear-gradient(135deg, #831843, #500724)', border: '#ec4899' }
    };
    return map[cat] || { icon: 'fa-brain', color: '#fbbf24', bg: 'linear-gradient(135deg, #1e293b, #0f172a)', border: '#3b82f6' };
}

function triggerSpecialCardQuiz(p, tileIndex, onComplete) {
    playSfx(sfx.card);
    const categories = ['K', 'M', 'D', 'J', 'R'];
    
    // Pick 3 random distinct categories
    let shuffled = categories.sort(() => 0.5 - Math.random());
    let chosenCats = shuffled.slice(0, 3);
    
    let html = `
        <div style="font-size: 1.15rem; font-weight: bold; margin-bottom: 10px; color: #f8fafc; text-shadow: 0 2px 4px rgba(0,0,0,0.5); font-family: 'Poppins', sans-serif;">🃏 PILIH KARTU KEBERUNTUNGAN</div>
        <p style="font-size: 0.8rem; color: #cbd5e1; margin-top: 0; margin-bottom: 18px; line-height: 1.4; font-family: 'Poppins', sans-serif;">Kategori materi terlihat di bagian belakang kartu, namun tingkat kesulitan (Level 1-4) dan efek kartu bagus/jelek masih tersembunyi!</p>
        <div class="special-deck-container" style="display:flex; justify-content:center; gap:16px; margin-top:10px; margin-bottom: 10px;">
            ${chosenCats.map((cat) => {
                const style = getCategoryCardStyle(cat);
                return `
                <div class="special-card-item" onclick="handleSpecialCardSelection('${cat}')" style="
                    width: 108px; height: 155px; border-radius: 12px;
                    background: ${style.bg};
                    border: 2px solid ${style.border};
                    color: white;
                    display: flex; flex-direction: column; align-items: center; justify-content: center;
                    cursor: pointer; transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
                    box-shadow: 0 8px 16px rgba(0,0,0,0.4);
                    padding: 10px; text-align: center;
                    position: relative;
                    overflow: hidden;
                " onmouseover="this.style.transform='translateY(-8px) scale(1.05)'; this.style.boxShadow='0 12px 24px ${style.color}40'; this.style.borderColor='#ffffff';" onmouseout="this.style.transform='translateY(0) scale(1)'; this.style.boxShadow='0 8px 16px rgba(0,0,0,0.4)'; this.style.borderColor='${style.border}';">
                    <div style="position: absolute; top: -20px; right: -20px; width: 60px; height: 60px; border-radius: 50%; background: rgba(255,255,255,0.03); pointer-events: none;"></div>
                    <i class="fa-solid ${style.icon}" style="font-size: 2.2rem; color: ${style.color}; margin-bottom: 12px; filter: drop-shadow(0 0 4px ${style.color}aa);"></i>
                    <div style="font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; color: #f8fafc; line-height:1.2; font-family: 'Poppins', sans-serif;">${getCategoryDisplayName(cat)}</div>
                    <div style="font-size: 0.55rem; color: #94a3b8; margin-top: 8px; border-top: 1px solid rgba(255,255,255,0.15); width:100%; padding-top:6px; font-weight: 600;">LEVEL: ???</div>
                </div>
                `;
            }).join('')}
        </div>
    `;
    
    // Register selection callback on window for Swal dynamic execution
    window.handleSpecialCardSelection = (selectedCat) => {
        Swal.close();
        
        // Generate a random difficulty level (1 to 4)
        let randomLevel = Math.floor(Math.random() * 4) + 1;
        let levelStr = '⭐'.repeat(randomLevel);
        
        // Filter question
        let pool = QUESTIONS[selectedCat].filter(q => q.level === randomLevel);
        if (pool.length === 0) pool = QUESTIONS[selectedCat];
        let q = pool[Math.floor(Math.random() * pool.length)];
        
        // Trigger question SweetAlert
        Swal.fire({
            html: `
                <div class="stat-inner-card" style="text-align: center;">
                    <i class="fa-solid fa-star" style="font-size:3rem; color:#fbbf24; filter: drop-shadow(0 0 5px rgba(245,158,11,0.8));"></i><br>
                    <span style="font-size:1.2rem; letter-spacing:2px; font-weight:800; color:#fbbf24; display:inline-block; margin-top:10px; font-family: 'Poppins', sans-serif;">KARTU SPESIAL</span>
                    <hr style="margin-top:15px; margin-bottom:15px; border-top: 2px solid #fbbf24; width: 60%; margin-left: auto; margin-right: auto;">
                    <div style="font-size: 0.8rem; color: #cbd5e1; text-transform: uppercase; margin-bottom: 8px; font-family: 'Poppins', sans-serif; font-weight: 500;">Materi: ${getCategoryDisplayName(selectedCat)} (Tingkat: ${levelStr})</div>
                    <div style="font-size: 1.1rem; font-weight: bold; margin-bottom: 15px; color: #f8fafc; line-height: 1.5; font-family: 'Poppins', sans-serif;">${q.soal}</div>
                    <div style="font-size: 0.9rem; color: #34d399; font-weight: 700; font-family: 'Poppins', sans-serif;">(Reward Kuis: +2 Bintang Biru)</div>
                    <div id="swal-action-timer" style="font-size: 1.15rem; font-weight: 900; color: #fbbf24; margin-top: 15px; font-family:'Poppins',sans-serif; text-shadow: 0 0 8px rgba(251,191,36,0.3);">⏱️ 60 detik</div>
                </div>
            `,
            input: 'text',
            background: '#0f172a',
            color: '#f8fafc',
            customClass: { popup: 'swal-card-special-quiz' },
            showClass: { popup: 'animate__animated animate__flipInY' },
            showCancelButton: false,
            allowOutsideClick: false,
            confirmButtonText: 'Kunci Jawaban'
        }).then((res) => {
            if (isActionTimeout) return;
            if (res.value && res.value.toLowerCase().trim() == q.jawaban_kunci.toLowerCase().trim()) {
                // Jawaban benar — KARTU BAGUS!
                p.blueStars = (p.blueStars || 0) + 2;
                updateStarIndicator(p.id);
                logGameEvent(`${p.name} menjawab kuis spesial dengan BENAR! Mendapatkan KARTU BAGUS! (Total Bintang Biru: ${p.blueStars})`, 'statistika', p.id);
                
                Swal.fire({
                    title: '🎉 KARTU BAGUS! (BENAR)',
                    html: `<div style="font-family: 'Poppins', sans-serif;">Jawaban Anda sangat tepat!</div>
                           <div style="font-size: 1.12rem; margin-top: 12px; color:#34d399; font-weight: 800; font-family: 'Poppins', sans-serif; line-height: 1.5;">Bonus Kartu Bagus: +2 Bintang Biru</div>
                           <div style="font-size:1.2rem; margin-top:12px; color:#34d399;"><i class="fa-solid fa-star"></i> Bintang Biru Bertambah!</div>`,
                    icon: 'success',
                    background: '#064e3b',
                    color: '#f8fafc',
                    customClass: { popup: 'swal-card-outcome-good' },
                    confirmButtonText: 'Lanjutkan'
                }).then(() => {
                    updateUI();
                    if (typeof onComplete === 'function') onComplete();
                });
            } else {
                // Jawaban salah — KARTU JELEK!
                logGameEvent(`${p.name} menjawab kuis spesial dengan SALAH! Mendapatkan KARTU JELEK!`, 'statistika', p.id);
                
                let countdown = 3;
                let countdownInterval;
                Swal.fire({
                    title: '👿 KARTU JELEK! (SALAH)',
                    html: `<div style="font-family: 'Poppins', sans-serif; margin-bottom: 8px;">Jawaban benar: <b>${q.jawaban_kunci}</b></div>
                           <div style="font-size: 1.2rem; margin-top: 12px; color:#f87171;"><i class="fa-solid fa-star"></i> Bintang Merah Bertambah!</div>
                           <div style="margin-top: 15px; font-size: 0.9rem; color: #94a3b8;">Lanjut otomatis dalam <b id="swal-countdown">${countdown}</b> detik...</div>`,
                    icon: 'error',
                    background: '#450a0a',
                    color: '#f8fafc',
                    customClass: { popup: 'swal-card-outcome-bad' },
                    confirmButtonText: 'Lanjutkan',
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: () => {
                        const countdownEl = document.getElementById('swal-countdown');
                        countdownInterval = setInterval(() => {
                            countdown--;
                            if (countdownEl) countdownEl.textContent = countdown;
                            if (countdown <= 0) clearInterval(countdownInterval);
                        }, 1000);
                    },
                    willClose: () => {
                        clearInterval(countdownInterval);
                    }
                }).then(() => {
                    addWarningStars(p, 1, true); // Silent check since we showed the star warning in this card popup
                    updateUI();
                    if (typeof onComplete === 'function') onComplete();
                });
            }
        });
    };
    
    Swal.fire({
        title: '<i class="fa-solid fa-clover" style="font-size: 2.5rem; color: #10b981; filter: drop-shadow(0 0 6px rgba(16,185,129,0.5));"></i>',
        html: html,
        width: '460px',
        background: '#0f172a',
        showConfirmButton: false,
        allowOutsideClick: false,
        customClass: { popup: 'swal-card-special-selection' }
    });
}

function addWarningStars(p, count, silent = false) {
    p.stars = (p.stars || 0) + count;
    updateStarIndicator(p.id);
    updateUI();
    
    if (!silent) {
        let countdown = 3;
        let countdownInterval;
        Swal.fire({
            title: 'Bintang Merah Bertambah!',
            html: `<div style="font-size:1.1rem; font-family:'Poppins',sans-serif; text-align: center; color: #f1f5f9;">
                       Kamu mendapatkan bintang merah!<br>
                       <div style="font-size:1.8rem; margin:15px 0; color:#ef4444; font-weight:bold;"><i class="fa-solid fa-star"></i> +${count} Bintang Merah</div>
                       Total Bintang Merah: ${p.stars}
                       <div style="margin-top: 15px; font-size: 0.9rem; color: #94a3b8;">Lanjut otomatis dalam <b id="swal-countdown">${countdown}</b> detik...</div>
                   </div>`,
            icon: 'warning',
            confirmButtonText: 'Lanjutkan',
            customClass: { popup: 'swal-card-stats' },
            timer: 3000,
            timerProgressBar: true,
            didOpen: () => {
                const countdownEl = document.getElementById('swal-countdown');
                countdownInterval = setInterval(() => {
                    countdown--;
                    if (countdownEl) countdownEl.textContent = countdown;
                    if (countdown <= 0) clearInterval(countdownInterval);
                }, 1000);
            },
            willClose: () => {
                clearInterval(countdownInterval);
            }
        });
    }
}

function giveStarsToOpponent(p, count) {
    let opponents = players.filter(x => x.id !== p.id && !x.isBankrupt);
    if (opponents.length === 0) {
        Swal.fire('Info', 'Tidak ada pemain lain yang aktif.', 'info');
        return;
    }
    
    let html = `
        <div style="font-family:'Poppins',sans-serif; text-align:left; font-size:0.95rem; margin-bottom:12px;">Pilih pemain lain untuk diberikan 2 Bintang Peringatan:</div>
        <select id="stars-target-player" class="form-select" style="font-family:'Poppins',sans-serif;">
            ${opponents.map(x => `<option value="${x.id}">${x.name}</option>`).join('')}
        </select>
    `;
    
    Swal.fire({
        title: 'Berikan Bintang Merah',
        html: html,
        icon: 'question',
        showCancelButton: false,
        confirmButtonText: 'Kirim Bintang',
        preConfirm: () => {
            return document.getElementById('stars-target-player').value;
        }
    }).then((res) => {
        if (res.isConfirmed) {
            let targetId = parseInt(res.value);
            let targetPlayer = players[targetId];
            logGameEvent(`${p.name} memberikan 2 bintang merah ke ${targetPlayer.name}`, 'system', p.id);
            addWarningStars(targetPlayer, count);
        }
    });
}

function teleportOpponentDialog(p, isFront) {
    let opponents = players.filter(x => x.id !== p.id && !x.isBankrupt);
    if (opponents.length === 0) {
        Swal.fire('Info', 'Tidak ada pemain lain yang aktif.', 'info');
        return;
    }
    
    let targetTiles = [];
    for (let i = 0; i < 40; i++) {
        let tile = BOARD[i];
        let relativePos = (i - p.pos + 40) % 40;
        let isMatch = isFront ? (relativePos > 0 && relativePos <= 20) : (relativePos > 20 && relativePos < 40);
        if (isMatch || relativePos === 0) {
            targetTiles.push({ index: i, name: `[Petak ${i}] ${tile.nama}` });
        }
    }
    
    let html = `
        <div style="font-family:'Poppins',sans-serif; text-align:left; font-size:0.95rem; margin-bottom:8px;">Pilih pemain:</div>
        <select id="teleport-target-player" class="form-select mb-3" style="font-family:'Poppins',sans-serif;">
            ${opponents.map(x => `<option value="${x.id}">${x.name}</option>`).join('')}
        </select>
        <div style="font-family:'Poppins',sans-serif; text-align:left; font-size:0.95rem; margin-bottom:8px;">Pilih petak tujuan (${isFront ? 'di depanmu' : 'di belakangmu'}):</div>
        <select id="teleport-target-tile" class="form-select" style="font-family:'Poppins',sans-serif;">
            ${targetTiles.map(x => `<option value="${x.index}">${x.name}</option>`).join('')}
        </select>
    `;
    
    Swal.fire({
        title: isFront ? 'Pindahkan Teman ke Depan' : 'Pindahkan Teman ke Belakang',
        html: html,
        icon: 'question',
        showCancelButton: false,
        confirmButtonText: 'Pindahkan',
        preConfirm: () => {
            return {
                targetPlayerId: parseInt(document.getElementById('teleport-target-player').value),
                targetTileIdx: parseInt(document.getElementById('teleport-target-tile').value)
            };
        }
    }).then((res) => {
        if (res.isConfirmed) {
            let targetPlayer = players[res.value.targetPlayerId];
            let tileIdx = res.value.targetTileIdx;
            logGameEvent(`${p.name} memindahkan ${targetPlayer.name} ke petak ${tileIdx} (${BOARD[tileIdx].nama})`, 'system', p.id);
            
            teleportPlayer(targetPlayer, tileIdx, () => {
                Swal.fire('Berhasil!', `${targetPlayer.name} telah dipindahkan ke ${BOARD[tileIdx].nama}.`, 'success').then(() => {
                    canEndTurn = true;
                    updateUI();
                });
            });
        }
    });
}

function teleportAndSolveDialog(p, isDoubleReward = false) {
    let availableTiles = [];
    for (let i = 1; i < 40; i++) {
        if (i !== 10 && i !== 20 && i !== 30) {
            availableTiles.push({ index: i, name: `[Petak ${i}] ${BOARD[i].nama}` });
        }
    }
    
    let html = `
        <div style="font-family:'Poppins',sans-serif; text-align:left; font-size:0.95rem; margin-bottom:8px;">Pilih petak tujuan untuk diteleportasikan dan menjawab soal:</div>
        <select id="solve-target-tile" class="form-select" style="font-family:'Poppins',sans-serif;">
            ${availableTiles.map(x => `<option value="${x.index}">${x.name}</option>`).join('')}
        </select>
    `;
    
    Swal.fire({
        title: isDoubleReward ? 'Pilih Petak (Bintang 2x)' : 'Pilih Petak & Jawab Soal',
        html: html,
        icon: 'question',
        showCancelButton: false,
        confirmButtonText: 'Teleportasi',
        preConfirm: () => {
            return parseInt(document.getElementById('solve-target-tile').value);
        }
    }).then((res) => {
        if (res.isConfirmed) {
            let tileIdx = res.value;
            logGameEvent(`${p.name} memilih untuk teleportasi ke petak ${tileIdx} (${BOARD[tileIdx].nama})`, 'system', p.id);
            
            teleportPlayer(p, tileIdx, () => {
                if (isDoubleReward) {
                    playSfx(sfx.card);
                    let q = getQuestionForTile(tileIdx);
                    if (!q) {
                        executeActualTileLogic(p, BOARD[tileIdx]);
                        return;
                    }
                    let conf = TILE_QUIZ_CONFIG[tileIdx];
                    let levelStr = '⭐'.repeat(conf.level);
                    
                    Swal.fire({
                        html: `
                            <div class="stat-inner-card">
                                <i class="fa-solid fa-gift" style="font-size:3rem; color:#10b981; filter: drop-shadow(0 0 5px rgba(16,185,129,0.8));"></i><br>
                                <span style="font-size:1.2rem; letter-spacing:2px; font-weight:800; color:#10b981; display:inline-block; margin-top:10px; font-family:'Poppins',sans-serif;">KUIS BONUS (POIN 2X)</span>
                                <hr style="margin-top:15px; margin-bottom:15px; border-top: 2px solid #10b981; width: 60%; margin-left: auto; margin-right: auto;">
                                <div style="font-size: 0.8rem; color: #777; margin-bottom: 8px;">Materi: ${getCategoryDisplayName(conf.category)} (Tingkat: ${levelStr})</div>
                                <div style="font-size: 1.1rem; font-weight: bold; margin-bottom: 10px;">${q.soal}</div>
                                <div style="font-size: 0.9rem; color: #10b981; font-weight: bold;">(Reward Ganda: +${conf.level * 2} Bintang Biru)</div>
                                <div id="swal-action-timer" style="font-size: 1.15rem; font-weight: 900; color: #fbbf24; margin-top: 15px; font-family:'Poppins',sans-serif; text-shadow: 0 0 8px rgba(251,191,36,0.3);">⏱️ 60 detik</div>
                            </div>
                        `,
                        input: 'text',
                        customClass: { popup: 'swal-card-stats' },
                        showClass: { popup: 'animate__animated animate__flipInY' },
                        showCancelButton: false,
                        allowOutsideClick: false,
                        confirmButtonText: 'Kunci Jawaban'
                    }).then((answerRes) => {
                        if (isActionTimeout) return;
                        if (answerRes.value && answerRes.value.toLowerCase().trim() == q.jawaban_kunci.toLowerCase().trim()) {
                            p.blueStars = (p.blueStars || 0) + (conf.level * 2);
                            updateStarIndicator(p.id);
                            logGameEvent(`${p.name} menjawab kuis bonus dengan BENAR! (Reward Ganda: +${conf.level * 2} Bintang Biru, Total Bintang Biru: ${p.blueStars})`, 'statistika', p.id);
                            
                            Swal.fire({
                                title: 'Luar Biasa!',
                                text: `Jawaban benar! Anda mendapatkan +${conf.level * 2} Bintang Biru.`,
                                icon: 'success',
                                confirmButtonText: 'Lanjutkan'
                            }).then(() => {
                                executeActualTileLogic(p, BOARD[tileIdx]);
                            });
                        } else {
                            logGameEvent(`${p.name} menjawab kuis bonus dengan SALAH! (Jawaban Anda: "${answerRes.value || ''}", Kunci: "${q.jawaban_kunci}")`, 'statistika', p.id);

                            let countdown = 3;
                            let countdownInterval;
                            Swal.fire({
                                title: 'Salah!',
                                html: `<div>Jawaban benar: <b>${q.jawaban_kunci}</b></div>
                                       <div style="font-size: 1.2rem; margin: 10px 0; color: #ef4444;"><i class="fa-solid fa-star"></i> Bintang Merah Bertambah!</div>
                                       <div style="margin-top: 10px; font-size: 0.9rem; color: #555;">Lanjut otomatis dalam <b id="swal-countdown">${countdown}</b> detik...</div>`,
                                icon: 'warning',
                                confirmButtonText: 'Lanjutkan',
                                timer: 3000,
                                timerProgressBar: true,
                                didOpen: () => {
                                    const countdownEl = document.getElementById('swal-countdown');
                                    countdownInterval = setInterval(() => {
                                        countdown--;
                                        if (countdownEl) countdownEl.textContent = countdown;
                                        if (countdown <= 0) clearInterval(countdownInterval);
                                    }, 1000);
                                },
                                willClose: () => {
                                    clearInterval(countdownInterval);
                                }
                            }).then(() => {
                                addWarningStars(p, 1, true); // Silent
                                executeActualTileLogic(p, BOARD[tileIdx]);
                            });
                        }
                    });
                } else {
                    triggerStatsQuestion(p, tileIdx, () => {
                        executeActualTileLogic(p, BOARD[tileIdx]);
                    });
                }
            });
        }
    });
}

function drawCard(type, p, onComplete) {
    playSfx(sfx.card);
    let isTaman = (type === 'chance' || type === 'kesempatan');

    let cardDetails;
    if (isTaman) {
        const chances = [
            { id: 1, name: 'Bunga Matahari', title: 'TAMAN BUNGA', icon: 'fa-star', color: '#3b82f6', image: 'keberuntungan1.png', textCode: 'teks-keberuntungan1', action: () => { p.blueStars += 2; updateStarIndicator(p.id); }, text: 'Bonus 2 bintang biru!', customIcon: 'bluestar2.png' },
            { id: 2, name: 'Bunga Mawar', title: 'TAMAN BUNGA', icon: 'fa-star', color: '#3b82f6', image: 'keberuntungan2.png', textCode: 'teks-keberuntungan2', action: () => { p.blueStars += 3; updateStarIndicator(p.id); }, text: 'Bonus 3 bintang biru!', customIcon: 'bluestar3.png' },
            { id: 3, name: 'Bunga Anggrek', title: 'TAMAN BUNGA', icon: 'fa-star', color: '#3b82f6', image: 'keberuntungan3.png', textCode: 'teks-keberuntungan3', action: () => { p.blueStars += 1; updateStarIndicator(p.id); }, text: 'Bonus 1 bintang biru!', customIcon: 'bluestar1.png' },
            { id: 4, name: 'Bunga Tulip', title: 'TAMAN BUNGA', icon: 'fa-leaf', color: '#10b981', image: 'keberuntungan4.png', textCode: 'teks-keberuntungan4', action: () => { p.doubleTamanBunga = true; }, text: 'Bonus mengambil 2x Kartu Taman Bunga pada putaran selanjutnya.', customIcon: 'save.png' },
            { id: 5, name: 'Bunga Sakura', title: 'TAMAN BUNGA', icon: 'fa-shield', color: '#10b981', image: 'keberuntungan5.png', textCode: 'teks-keberuntungan5', action: () => { p.skipPeristiwaAlam = true; }, text: 'Bonus melewati peristiwa alam.', customIcon: 'run.png' },
            { id: 6, name: 'Bunga Sepatu', title: 'TAMAN BUNGA', icon: 'fa-arrow-right-to-bracket', color: '#10b981', image: 'keberuntungan6.png', textCode: 'teks-keberuntungan6', action: () => { teleportOpponentDialog(p, true); }, text: 'Tempatkan teman di depanmu ke petak mana pun.', customIcon: 'pinobjective.png' },
            { id: 7, name: 'Bunga Sakura', title: 'TAMAN BUNGA', icon: 'fa-arrow-left-long', color: '#10b981', image: 'keberuntungan7.png', textCode: 'teks-keberuntungan7', action: () => { teleportOpponentDialog(p, false); }, text: 'Tempatkan teman di belakangmu ke petak mana pun.', customIcon: 'pinobjective.png' },
            { id: 8, name: 'Bunga Teratai', title: 'TAMAN BUNGA', icon: 'fa-map-location-dot', color: '#10b981', image: 'keberuntungan8.png', textCode: 'teks-keberuntungan8', action: () => { teleportAndSolveDialog(p, false); }, text: 'Pilih petak mana pun dan selesaikan soal.', customIcon: 'lamp.png' },
            { id: 9, name: 'Bunga Lavender', title: 'TAMAN BUNGA', icon: 'fa-forward-fast', color: '#10b981', image: 'keberuntungan9.png', textCode: 'teks-keberuntungan9', action: () => { p.extraTurn = true; }, text: 'Bonus jalan 2x.', customIcon: 'maps.png' },
            { id: 10, name: 'Bunga Bougenville', title: 'TAMAN BUNGA', icon: 'fa-circle-xmark', color: '#10b981', image: 'keberuntungan10.png', textCode: 'teks-keberuntungan10', action: () => { teleportAndSolveDialog(p, true); }, text: 'Pilih petak mana pun, jika benar menjawab soal maka poin dikali 2.', customIcon: '2x.png' }
        ];
        cardDetails = chances[Math.floor(Math.random() * chances.length)];
    } else {
        const chests = [
            { id: 1, name: 'Angin Topan', title: 'PERISTIWA ALAM', icon: 'fa-backward', color: '#ef4444', image: 'peristiwa1.png', textCode: 'teks-peristiwa1', action: () => { movePlayerAnimate(p, -3, () => { triggerStatsQuestion(p, p.pos, () => { executeActualTileLogic(p, BOARD[p.pos]); }); }); }, text: 'Mundur 3 langkah dan selesaikan soalnya.', customIcon: 'pinobjective.png' },
            { id: 2, name: 'Hujan badai', title: 'PERISTIWA ALAM', icon: 'fa-circle-exclamation', color: '#ef4444', image: 'peristiwa2.png', textCode: 'teks-peristiwa2', action: () => { addWarningStars(p, 2); }, text: 'Mendapatkan 2 bintang merah.', customIcon: 'redstar2' },
            { id: 3, name: 'Letusan Gunung', title: 'PERISTIWA ALAM', icon: 'fa-hourglass-half', color: '#ef4444', image: 'peristiwa3.png', textCode: 'teks-peristiwa3', action: () => { p.skipNextTurn = true; }, text: 'Kehilangan giliran 1x.', customIcon: 'ban1x.png' },
            { id: 4, name: 'Pelangi', title: 'PERISTIWA ALAM', icon: 'fa-forward', color: '#ef4444', image: 'peristiwa4.png', textCode: 'teks-peristiwa4', action: () => { movePlayerAnimate(p, 5, () => { triggerStatsQuestion(p, p.pos, () => { executeActualTileLogic(p, BOARD[p.pos]); }); }); }, text: 'Maju 5 langkah dan selesaikan soal.', customIcon: 'pinobjective.png' },
            { id: 5, name: 'Kebakaran', title: 'PERISTIWA ALAM', icon: 'fa-triangle-exclamation', color: '#ef4444', image: 'peristiwa5.png', textCode: 'teks-peristiwa5', action: () => { addWarningStars(p, 3); }, text: 'Mendapatkan 3 bintang merah.', customIcon: 'redstar3.png' },
            { id: 6, name: 'Kekeringan', title: 'PERISTIWA ALAM', icon: 'fa-circle-notch', color: '#ef4444', image: 'peristiwa6.png', textCode: 'teks-peristiwa6', action: () => { addWarningStars(p, 1); }, text: 'Mendapatkan 1 bintang merah.', customIcon: 'redstar1.png' },
            { id: 7, name: 'Pohon Berbuah', title: 'PERISTIWA ALAM', icon: 'fa-backward-fast', color: '#ef4444', image: 'peristiwa7.png', textCode: 'teks-peristiwa7', action: () => { movePlayerAnimate(p, -4, () => { triggerStatsQuestion(p, p.pos, () => { executeActualTileLogic(p, BOARD[p.pos]); }); }); }, text: 'Mundur 4 langkah dan selesaikan soal.', customIcon: 'pinobjective.png' },
            { id: 8, name: 'Bom Meledak', title: 'PERISTIWA ALAM', icon: 'fa-ban', color: '#ef4444', image: 'peristiwa8.png', textCode: 'teks-peristiwa8', action: () => { p.blockTamanBunga = true; }, text: 'Kehilangan kesempatan mengambil Kartu Taman Bunga.', customIcon: 'skipcard.png' },
            { id: 9, name: 'Hujan Koin', title: 'PERISTIWA ALAM', icon: 'fa-forward-step', color: '#ef4444', image: 'peristiwa9.png', textCode: 'teks-peristiwa9', action: () => { movePlayerAnimate(p, 2, () => { triggerStatsQuestion(p, p.pos, () => { executeActualTileLogic(p, BOARD[p.pos]); }); }); }, text: 'Maju 2 langkah dan selesaikan soal.', customIcon: 'pinobjective.png' },
            { id: 10, name: 'Hujan Petir', title: 'PERISTIWA ALAM', icon: 'fa-people-arrows', color: '#ef4444', image: 'peristiwa10.png', textCode: 'teks-peristiwa10', action: () => { giveStarsToOpponent(p, 2); }, text: 'Memberikan 2 bintang ke teman (pemain lain).', customIcon: 'give2star.png' }
        ];
        cardDetails = chests[Math.floor(Math.random() * chests.length)];
    }

    let baseFrontImg = isTaman 
        ? '../public/images/card/keberuntungan/peristiwa-depan.png' 
        : '../public/images/card/peristiwa/keberuntungan-depan.png';
        
    Swal.fire({
        html: `
            <div class="swal-drawn-card-container" style="
                perspective: 1000px;
                width: 100%;
                display: flex;
                justify-content: center;
                margin: 5px 0;
            ">
                <div class="swal-drawn-card-inner" style="
                    width: 597px;
                    height: 397px;
                    border-radius: 18px;
                    background-image: url('${baseFrontImg}');
                    background-size: 100% 100%;
                    background-repeat: no-repeat;
                    background-position: center;
                    border: 2px solid ${cardDetails.color}60;
                    box-shadow: 0 16px 40px rgba(0, 0, 0, 0.6), 0 0 30px ${cardDetails.color}30;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: space-between;
                    padding: 30px 24px;
                    position: relative;
                    overflow: hidden;
                    box-sizing: border-box;
                ">
                    <div style="
                        position: absolute;
                        top: -50px; left: -50px;
                        width: 150px; height: 150px;
                        background: ${cardDetails.color}15;
                        filter: blur(40px);
                        border-radius: 50%;
                        pointer-events: none;
                    "></div>

                    <!-- Judul Spesifik Kartu -->
                    <div style="
                        position: relative;
                        z-index: 2;
                        font-size: 1.9rem;
                        font-weight: 900;
                        color: #ffffff;
                        text-shadow: 0 2px 5px rgba(0,0,0,0.95), 0 0 15px rgba(0,0,0,0.7);
                        font-family: 'Outfit', 'Poppins', sans-serif;
                        letter-spacing: 1px;
                        text-align: center;
                        margin-top: 5px;
                    ">${cardDetails.name}</div>
 
                    <!-- Ikon Kustom Absolute (90% width, centered vertically & horizontally) -->
                    <div style="
                        position: absolute;
                        top: 50%;
                        left: 50%;
                        transform: translate(-50%, -50%);
                        width: 90%;
                        height: 260px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        z-index: 1;
                        pointer-events: none;
                    ">
                        ${cardDetails.customIcon === 'redstar2' ? `
                            <div style="display: flex; gap: 20px; justify-content: center; align-items: center;">
                                <img src="../public/images/card/icon-card/redstar1.png" style="height: 200px !important; width: auto !important; object-fit: contain !important;">
                                <img src="../public/images/card/icon-card/redstar1.png" style="height: 200px !important; width: auto !important; object-fit: contain !important;">
                            </div>
                        ` : `
                            <img src="../public/images/card/icon-card/${cardDetails.customIcon}" style="
                                height: 240px !important;
                                width: auto !important;
                                max-width: 90% !important;
                                object-fit: contain !important;
                            ">
                        `}
                    </div>
 
                    <!-- Teks Deskripsi Kartu di Bagian Bawah -->
                    <div style="
                        position: relative;
                        z-index: 2;
                        font-size: 1.35rem;
                        font-weight: 800;
                        color: #ffffff;
                        text-shadow: 0 2px 5px rgba(0,0,0,0.95), 0 0 15px rgba(0,0,0,0.7);
                        font-family: 'Outfit', 'Poppins', sans-serif;
                        text-align: center;
                        max-width: 90%;
                        line-height: 1.4;
                        margin-bottom: 5px;
                    ">${cardDetails.text}</div>
                </div>
            </div>
        `,
        width: '630px',
        customClass: { popup: isTaman ? 'swal-card-chance-horizontal' : 'swal-card-chest-horizontal' },
        showClass: { popup: 'animate__animated animate__zoomIn' },
        background: 'transparent',
        allowOutsideClick: false,
        confirmButtonText: 'Ambil'
    }).then(() => {
        if (isActionTimeout) return;
        logGameEvent(`${p.name} menarik Kartu <b>${cardDetails.title}</b>: "${cardDetails.text}"`, 'card', p.id);
        
        let isAsyncAction = false;
        if (isTaman) {
            if ([6, 7, 8, 10].includes(cardDetails.id)) isAsyncAction = true;
        } else {
            if ([1, 4, 7, 9].includes(cardDetails.id)) isAsyncAction = true;
        }

        if (typeof cardDetails.action === 'function') {
            cardDetails.action();
        }
        updateUI();
        
        if (!isAsyncAction) {
            canEndTurn = true;
            updateUI();
            if (typeof onComplete === 'function') onComplete();
        }
    });
}
