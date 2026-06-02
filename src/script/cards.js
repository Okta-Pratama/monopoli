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
                <div style="font-size: 0.9rem; color: #555;">(Reward: ${formatRp(q.poin)})</div>
            </div>
        `,
        input: 'text',
        customClass: { popup: 'swal-card-stats' },
        showClass: { popup: 'animate__animated animate__flipInY' },
        showCancelButton: false,
        allowOutsideClick: false,
        confirmButtonText: 'Kunci Jawaban'
    }).then((res) => {
        if (res.value && res.value.toLowerCase().trim() == q.jawaban_kunci.toLowerCase().trim()) {
            // Jawaban benar — reset stars
            p.stars = 0;
            p.money += q.poin;
            updateStarIndicator(p.id);
            logGameEvent(`Player ${p.id + 1} menjawab kuis Statistika dengan <b>BENAR</b>! (Reward: ${formatRp(q.poin)})`, 'statistika', p.id);
            
            Swal.fire({
                title: 'Tepat!',
                text: `Jawaban benar! Reward ${formatRp(q.poin)} ditambahkan ke saldo Anda.`,
                icon: 'success',
                confirmButtonText: 'Lanjutkan'
            }).then(() => {
                updateUI();
                if (typeof onComplete === 'function') onComplete();
            });
        } else {
            // Jawaban salah — tambah bintang
            p.stars++;
            updateStarIndicator(p.id);
            logGameEvent(`Player ${p.id + 1} menjawab kuis Statistika dengan <b>SALAH</b>! (Jawaban Anda: "${res.value || ''}", Kunci: "${q.jawaban_kunci}", Peringatan: ${p.stars}/3)`, 'statistika', p.id);

            if (p.stars >= 3) {
                p.stars = 0;
                p.money -= 200; // denda Rp 200.000
                updateStarIndicator(p.id);
                updateUI();
                playSfx(sfx.bell);
                
                Swal.fire({
                    title: '⭐⭐⭐ Tiga Bintang!',
                    html: `<div style="font-size:1.1rem;">Jawaban salah sudah <b>3x</b>!<br>Uang Anda dikurangi <b>Rp 200.000</b> secara otomatis! 💸</div>`,
                    icon: 'error',
                    confirmButtonText: 'Lanjutkan'
                }).then(() => {
                    if (p.money < 0) {
                        hasRolled = true;
                        updateUI();
                        Swal.fire('Hutang!', 'Uangmu minus, segera jual aset untuk membayar hutang!', 'warning').then(() => {
                            if (typeof onComplete === 'function') onComplete();
                        });
                    } else {
                        if (typeof onComplete === 'function') onComplete();
                    }
                });
            } else {
                const starDisplay = '⭐'.repeat(p.stars) + '☆'.repeat(3 - p.stars);
                Swal.fire({
                    title: 'Salah!',
                    html: `<div>Jawaban benar: <b>${q.jawaban_kunci}</b></div>
                           <div style="font-size:1.5rem; margin: 10px 0;">${starDisplay}</div>
                           <div style="font-size:0.85rem; color:#e74c3c;">Peringatan: ${p.stars}/3 — Di bintang ke-3 kamu kena denda Rp 200.000!</div>`,
                    icon: 'warning',
                    confirmButtonText: 'Lanjutkan'
                }).then(() => {
                    updateUI();
                    if (typeof onComplete === 'function') onComplete();
                });
            }
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
                    <div style="font-size: 0.9rem; color: #34d399; font-weight: 700; font-family: 'Poppins', sans-serif;">(Reward Kuis: +${formatRp(q.poin)})</div>
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
            if (res.value && res.value.toLowerCase().trim() == q.jawaban_kunci.toLowerCase().trim()) {
                // Jawaban benar — KARTU BAGUS!
                p.stars = 0;
                let bonusReward = 150; // Bonus Rp 150.000
                p.money += q.poin + bonusReward;
                updateStarIndicator(p.id);
                logGameEvent(`Player ${p.id + 1} menjawab kuis spesial dengan BENAR! Mendapatkan KARTU BAGUS! (Reward: ${formatRp(q.poin + bonusReward)})`, 'statistika', p.id);
                
                Swal.fire({
                    title: '🎉 KARTU BAGUS! (BENAR)',
                    html: `<div style="font-family: 'Poppins', sans-serif;">Jawaban Anda sangat tepat!</div>
                           <div style="font-size: 1.12rem; margin-top: 12px; color:#34d399; font-weight: 800; font-family: 'Poppins', sans-serif; line-height: 1.5;">Reward Kuis: +${formatRp(q.poin)}<br>Bonus Kartu Bagus: +${formatRp(bonusReward)}</div>`,
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
                p.stars++;
                let penaltyCost = 100; // Denda Rp 100.000
                p.money -= penaltyCost;
                updateStarIndicator(p.id);
                logGameEvent(`Player ${p.id + 1} menjawab kuis spesial dengan SALAH! Mendapatkan KARTU JELEK! (Denda: ${formatRp(penaltyCost)})`, 'statistika', p.id);
                
                if (p.stars >= 3) {
                    p.stars = 0;
                    p.money -= 200; // Denda tambahan 3x salah
                    updateStarIndicator(p.id);
                    updateUI();
                    playSfx(sfx.bell);
                    
                    Swal.fire({
                        title: '☠️ KARTU JELEK! (SALAH)',
                        html: `<div style="font-family: 'Poppins', sans-serif; margin-bottom: 8px;">Jawaban benar: <b>${q.jawaban_kunci}</b></div>
                               <div style="font-size: 1.12rem; margin-top: 12px; color:#f87171; font-weight: 800; font-family: 'Poppins', sans-serif; line-height: 1.5;">Hukuman Kartu Jelek: -${formatRp(penaltyCost)}<br>Denda 3 Bintang Salah: -${formatRp(200)}</div>`,
                        icon: 'error',
                        background: '#450a0a',
                        color: '#f8fafc',
                        customClass: { popup: 'swal-card-outcome-bad' },
                        confirmButtonText: 'Lanjutkan'
                    }).then(() => {
                        if (p.money < 0) {
                            hasRolled = true;
                            updateUI();
                            Swal.fire({
                                title: 'Hutang!',
                                text: 'Uangmu minus, segera jual aset untuk membayar hutang!',
                                icon: 'warning',
                                confirmButtonText: 'Mengerti'
                            }).then(() => {
                                if (typeof onComplete === 'function') onComplete();
                            });
                        } else {
                            if (typeof onComplete === 'function') onComplete();
                        }
                    });
                } else {
                    const starDisplay = '⭐'.repeat(p.stars) + '☆'.repeat(3 - p.stars);
                    Swal.fire({
                        title: '👿 KARTU JELEK! (SALAH)',
                        html: `<div style="font-family: 'Poppins', sans-serif; margin-bottom: 8px;">Jawaban benar: <b>${q.jawaban_kunci}</b></div>
                               <div style="font-size: 1.12rem; margin-top: 12px; color:#f87171; font-weight: 800; font-family: 'Poppins', sans-serif; line-height: 1.5;">Hukuman Kartu Jelek: -${formatRp(penaltyCost)}</div>
                               <div style="font-size:1.4rem; margin: 12px 0; letter-spacing: 2px;">${starDisplay}</div>`,
                        icon: 'error',
                        background: '#450a0a',
                        color: '#f8fafc',
                        customClass: { popup: 'swal-card-outcome-bad' },
                        confirmButtonText: 'Lanjutkan'
                    }).then(() => {
                        updateUI();
                        if (typeof onComplete === 'function') onComplete();
                    });
                }
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

function addWarningStars(p, count) {
    p.stars += count;
    updateStarIndicator(p.id);
    updateUI();
    if (p.stars >= 3) {
        p.stars = 0;
        p.money -= 200;
        updateStarIndicator(p.id);
        updateUI();
        playSfx(sfx.bell);
        
        Swal.fire({
            title: '⭐⭐⭐ Tiga Bintang Peringatan!',
            html: `<div style="font-size:1.1rem; font-family:'Poppins',sans-serif;">Bintang peringatan kamu mencapai <b>3</b>!<br>Uang kamu dikurangi <b>Rp 200.000</b> secara otomatis! 💸</div>`,
            icon: 'error',
            confirmButtonText: 'Lanjutkan'
        }).then(() => {
            if (p.money < 0) {
                hasRolled = true;
                updateUI();
                Swal.fire('Hutang!', 'Uangmu minus, segera jual aset untuk membayar hutang!', 'warning');
            }
        });
    } else {
        const starDisplay = '⭐'.repeat(p.stars) + '☆'.repeat(3 - p.stars);
        Swal.fire({
            title: 'Bintang Merah Bertambah!',
            html: `<div style="font-size:1.1rem; font-family:'Poppins',sans-serif;">Kamu mendapatkan bintang peringatan merah!<br><div style="font-size:1.6rem; margin:10px 0;">${starDisplay}</div>Peringatan: ${p.stars}/3</div>`,
            icon: 'warning',
            confirmButtonText: 'Lanjutkan'
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
            ${opponents.map(x => `<option value="${x.id}">Player ${x.id + 1}</option>`).join('')}
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
            logGameEvent(`Player ${p.id + 1} memberikan 2 bintang merah ke Player ${targetId + 1}`, 'system', p.id);
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
            ${opponents.map(x => `<option value="${x.id}">Player ${x.id + 1}</option>`).join('')}
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
            logGameEvent(`Player ${p.id + 1} memindahkan Player ${targetPlayer.id + 1} ke petak ${tileIdx} (${BOARD[tileIdx].nama})`, 'system', p.id);
            
            teleportPlayer(targetPlayer, tileIdx, () => {
                Swal.fire('Berhasil!', `Player ${targetPlayer.id + 1} telah dipindahkan ke ${BOARD[tileIdx].nama}.`, 'success').then(() => {
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
        if (i !== 20) {
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
        title: isDoubleReward ? 'Pilih Petak (Poin 2x)' : 'Pilih Petak & Jawab Soal',
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
            logGameEvent(`Player ${p.id + 1} memilih untuk teleportasi ke petak ${tileIdx} (${BOARD[tileIdx].nama})`, 'system', p.id);
            
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
                                <div style="font-size: 0.9rem; color: #10b981; font-weight: bold;">(Reward Ganda: +${formatRp(q.poin * 2)})</div>
                            </div>
                        `,
                        input: 'text',
                        customClass: { popup: 'swal-card-stats' },
                        showClass: { popup: 'animate__animated animate__flipInY' },
                        showCancelButton: false,
                        allowOutsideClick: false,
                        confirmButtonText: 'Kunci Jawaban'
                    }).then((answerRes) => {
                        if (answerRes.value && answerRes.value.toLowerCase().trim() == q.jawaban_kunci.toLowerCase().trim()) {
                            p.stars = 0;
                            p.money += q.poin * 2;
                            updateStarIndicator(p.id);
                            logGameEvent(`Player ${p.id + 1} menjawab kuis bonus dengan BENAR! (Reward Ganda: +${formatRp(q.poin * 2)})`, 'statistika', p.id);
                            
                            Swal.fire({
                                title: 'Luar Biasa!',
                                text: `Jawaban benar! Reward ganda +${formatRp(q.poin * 2)} ditambahkan ke saldo Anda.`,
                                icon: 'success',
                                confirmButtonText: 'Lanjutkan'
                            }).then(() => {
                                executeActualTileLogic(p, BOARD[tileIdx]);
                            });
                        } else {
                            addWarningStars(p, 1);
                            setTimeout(() => {
                                executeActualTileLogic(p, BOARD[tileIdx]);
                            }, 1500);
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
            { id: 1, title: 'TAMAN BUNGA', icon: 'fa-star', color: '#3b82f6', image: 'keberuntungan1.png', textCode: 'teks-keberuntungan1', action: () => { p.blueStars += 2; updateStarIndicator(p.id); }, text: 'Bonus 2 bintang biru!' },
            { id: 2, title: 'TAMAN BUNGA', icon: 'fa-star', color: '#3b82f6', image: 'keberuntungan2.png', textCode: 'teks-keberuntungan2', action: () => { p.blueStars += 3; updateStarIndicator(p.id); }, text: 'Bonus 3 bintang biru!' },
            { id: 3, title: 'TAMAN BUNGA', icon: 'fa-star', color: '#3b82f6', image: 'keberuntungan3.png', textCode: 'teks-keberuntungan3', action: () => { p.blueStars += 1; updateStarIndicator(p.id); }, text: 'Bonus 1 bintang biru!' },
            { id: 4, title: 'TAMAN BUNGA', icon: 'fa-leaf', color: '#10b981', image: 'keberuntungan4.png', textCode: 'teks-keberuntungan4', action: () => { p.doubleTamanBunga = true; }, text: 'Bonus mengambil 2x Kartu Taman Bunga pada putaran selanjutnya.' },
            { id: 5, title: 'TAMAN BUNGA', icon: 'fa-shield', color: '#10b981', image: 'keberuntungan5.png', textCode: 'teks-keberuntungan5', action: () => { p.skipPeristiwaAlam = true; }, text: 'Bonus melewati peristiwa alam.' },
            { id: 6, title: 'TAMAN BUNGA', icon: 'fa-arrow-right-to-bracket', color: '#10b981', image: 'keberuntungan6.png', textCode: 'teks-keberuntungan6', action: () => { teleportOpponentDialog(p, true); }, text: 'Tempatkan teman di depanmu ke petak mana pun.' },
            { id: 7, title: 'TAMAN BUNGA', icon: 'fa-arrow-left-long', color: '#10b981', image: 'keberuntungan7.png', textCode: 'teks-keberuntungan7', action: () => { teleportOpponentDialog(p, false); }, text: 'Tempatkan teman di belakangmu ke petak mana pun.' },
            { id: 8, title: 'TAMAN BUNGA', icon: 'fa-map-location-dot', color: '#10b981', image: 'keberuntungan8.png', textCode: 'teks-keberuntungan8', action: () => { teleportAndSolveDialog(p, false); }, text: 'Pilih petak mana pun dan selesaikan soal.' },
            { id: 9, title: 'TAMAN BUNGA', icon: 'fa-forward-fast', color: '#10b981', image: 'keberuntungan9.png', textCode: 'teks-keberuntungan9', action: () => { p.extraTurn = true; }, text: 'Bonus jalan 2x.' },
            { id: 10, title: 'TAMAN BUNGA', icon: 'fa-circle-xmark', color: '#10b981', image: 'keberuntungan10.png', textCode: 'teks-keberuntungan10', action: () => { teleportAndSolveDialog(p, true); }, text: 'Pilih petak mana pun, jika benar menjawab soal maka poin dikali 2.' }
        ];
        cardDetails = chances[Math.floor(Math.random() * chances.length)];
    } else {
        const chests = [
            { id: 1, title: 'PERISTIWA ALAM', icon: 'fa-backward', color: '#ef4444', image: 'peristiwa1.png', textCode: 'teks-peristiwa1', action: () => { movePlayerAnimate(p, -3, () => { triggerStatsQuestion(p, p.pos, () => { executeActualTileLogic(p, BOARD[p.pos]); }); }); }, text: 'Mundur 3 langkah dan selesaikan soalnya.' },
            { id: 2, title: 'PERISTIWA ALAM', icon: 'fa-circle-exclamation', color: '#ef4444', image: 'peristiwa2.png', textCode: 'teks-peristiwa2', action: () => { addWarningStars(p, 2); }, text: 'Mendapatkan 2 bintang merah.' },
            { id: 3, title: 'PERISTIWA ALAM', icon: 'fa-hourglass-half', color: '#ef4444', image: 'peristiwa3.png', textCode: 'teks-peristiwa3', action: () => { p.skipNextTurn = true; }, text: 'Kehilangan giliran 1x.' },
            { id: 4, title: 'PERISTIWA ALAM', icon: 'fa-forward', color: '#ef4444', image: 'peristiwa4.png', textCode: 'teks-peristiwa4', action: () => { movePlayerAnimate(p, 5, () => { triggerStatsQuestion(p, p.pos, () => { executeActualTileLogic(p, BOARD[p.pos]); }); }); }, text: 'Maju 5 langkah dan selesaikan soal.' },
            { id: 5, title: 'PERISTIWA ALAM', icon: 'fa-triangle-exclamation', color: '#ef4444', image: 'peristiwa5.png', textCode: 'teks-peristiwa5', action: () => { addWarningStars(p, 3); }, text: 'Mendapatkan 3 bintang merah.' },
            { id: 6, title: 'PERISTIWA ALAM', icon: 'fa-circle-notch', color: '#ef4444', image: 'peristiwa6.png', textCode: 'teks-peristiwa6', action: () => { addWarningStars(p, 1); }, text: 'Mendapatkan 1 bintang merah.' },
            { id: 7, title: 'PERISTIWA ALAM', icon: 'fa-backward-fast', color: '#ef4444', image: 'peristiwa7.png', textCode: 'teks-peristiwa7', action: () => { movePlayerAnimate(p, -4, () => { triggerStatsQuestion(p, p.pos, () => { executeActualTileLogic(p, BOARD[p.pos]); }); }); }, text: 'Mundur 4 langkah dan selesaikan soal.' },
            { id: 8, title: 'PERISTIWA ALAM', icon: 'fa-ban', color: '#ef4444', image: 'peristiwa8.png', textCode: 'teks-peristiwa8', action: () => { p.blockTamanBunga = true; }, text: 'Kehilangan kesempatan mengambil Kartu Taman Bunga.' },
            { id: 9, title: 'PERISTIWA ALAM', icon: 'fa-forward-step', color: '#ef4444', image: 'peristiwa9.png', textCode: 'teks-peristiwa9', action: () => { movePlayerAnimate(p, 2, () => { triggerStatsQuestion(p, p.pos, () => { executeActualTileLogic(p, BOARD[p.pos]); }); }); }, text: 'Maju 2 langkah dan selesaikan soal.' },
            { id: 10, title: 'PERISTIWA ALAM', icon: 'fa-people-arrows', color: '#ef4444', image: 'peristiwa10.png', textCode: 'teks-peristiwa10', action: () => { giveStarsToOpponent(p, 2); }, text: 'Memberikan 2 bintang ke teman (pemain lain).' }
        ];
        cardDetails = chests[Math.floor(Math.random() * chests.length)];
    }

    let baseFrontImg = isTaman 
        ? '../public/images/card/keberuntungan/keberuntungan-depan.png' 
        : '../public/images/card/peristiwa/peristiwa-depan.png';
        
    let imgSrc = `../public/images/cards/${cardDetails.image}`;

    Swal.fire({
        html: `
            <div class="swal-drawn-card-container" style="
                perspective: 1000px;
                width: 100%;
                display: flex;
                justify-content: center;
                margin: 15px 0 5px 0;
            ">
                <div class="swal-drawn-card-inner" style="
                    width: 280px;
                    min-height: 380px;
                    border-radius: 16px;
                    background-image: url('${baseFrontImg}');
                    background-size: cover;
                    background-position: center;
                    border: 2px solid ${cardDetails.color}50;
                    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.5), 0 0 20px ${cardDetails.color}20;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: space-between;
                    padding: 24px;
                    position: relative;
                    overflow: hidden;
                    transition: all 0.3s ease;
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

                    <img class="card-custom-img" src="${imgSrc}" onerror="this.style.display='none'; document.getElementById('card-overlay-content-${cardDetails.id}').style.display='flex';" onload="document.getElementById('card-overlay-content-${cardDetails.id}').style.display='none';" style="
                        position: absolute;
                        top: 0; left: 0;
                        width: 100%; height: 100%;
                        object-fit: cover;
                        border-radius: 14px;
                        z-index: 2;
                    ">

                    <div id="card-overlay-content-${cardDetails.id}" style="
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        justify-content: space-between;
                        width: 100%;
                        height: 100%;
                        z-index: 1;
                        flex-grow: 1;
                    ">
                        <div style="
                            width: 90px;
                            height: 90px;
                            border-radius: 50%;
                            background: rgba(15, 23, 42, 0.4);
                            border: 2px solid ${cardDetails.color}70;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            margin-top: 20px;
                            margin-bottom: 20px;
                            box-shadow: 0 0 15px ${cardDetails.color}30;
                            backdrop-filter: blur(4px);
                        ">
                            <i class="fa-solid ${cardDetails.icon}" style="
                                font-size: 3rem;
                                color: ${cardDetails.color};
                                filter: drop-shadow(0 0 10px ${cardDetails.color}aa);
                            "></i>
                        </div>

                        <div style="
                            width: 100%;
                            text-align: center;
                            background: rgba(15, 23, 42, 0.72);
                            backdrop-filter: blur(6px);
                            border-radius: 12px;
                            border: 1px solid rgba(255, 255, 255, 0.08);
                            padding: 16px 12px;
                            margin-bottom: 5px;
                            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
                        ">
                            <div style="
                                font-size: 0.75rem;
                                font-weight: 800;
                                letter-spacing: 2px;
                                text-transform: uppercase;
                                color: ${cardDetails.color};
                                margin-bottom: 6px;
                            ">${cardDetails.title}</div>
                            
                            <hr style="
                                width: 30%;
                                border-top: 2px solid ${cardDetails.color}80;
                                margin: 6px auto 12px auto;
                            ">
                            
                            <p style="
                                font-size: 0.95rem;
                                font-weight: 600;
                                line-height: 1.5;
                                color: #f1f5f9;
                                margin: 0;
                                font-family: 'Poppins', sans-serif;
                            ">${cardDetails.text}</p>
                        </div>
                    </div>
                </div>
            </div>
        `,
        customClass: { popup: isTaman ? 'swal-card-chance' : 'swal-card-chest' },
        showClass: { popup: 'animate__animated animate__zoomIn' },
        background: 'transparent',
        allowOutsideClick: false,
        confirmButtonText: 'Ambil'
    }).then(() => {
        logGameEvent(`Player ${p.id + 1} menarik Kartu <b>${cardDetails.title}</b>: "${cardDetails.text}"`, 'card', p.id);
        
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
        if (p.money < 0) {
            hasRolled = true;
            Swal.fire('Hutang!', 'Uangmu minus, segera jual aset untuk membayar hutang!', 'warning');
            updateUI();
        }
        
        if (!isAsyncAction) {
            canEndTurn = true;
            updateUI();
            if (typeof onComplete === 'function') onComplete();
        }
    });
}
