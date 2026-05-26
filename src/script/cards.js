/**
 * MONOPOLI - STATISTIKA GAME
 * Cards & Questions Logic
 */

// ========== QUESTIONS & CARDS ==========
function triggerStatsQuestion(p, tileIndex) {
    playSfx(sfx.card);
    let tile = BOARD[tileIndex];
    const qTypes = ['K', 'M', 'D', 'J', 'R'];
    const q = QUESTIONS[qTypes[Math.floor(Math.random() * qTypes.length)]][Math.floor(Math.random() * QUESTIONS[qTypes[0]].length)];

    Swal.fire({
        html: `
            <div class="stat-inner-card">
                <i class="fa-solid fa-brain" style="font-size:3rem; color:#e74c3c;"></i><br>
                <span style="font-size:1.2rem; letter-spacing:2px; font-weight:800; color:#c0392b; display:inline-block; margin-top:10px;">STATISTIKA</span>
                <hr style="margin-top:15px; margin-bottom:15px; border-top: 2px solid #e74c3c; width: 60%; margin-left: auto; margin-right: auto;">
                <div style="font-size: 1.1rem; font-weight: bold; margin-bottom: 10px;">${q.soal}</div>
                <div style="font-size: 0.9rem; color: #777;">(Reward: ${formatRp(q.poin)})</div>
            </div>
        `,
        input: 'text',
        customClass: { popup: 'swal-card-stats' },
        showClass: { popup: 'animate__animated animate__flipInY' },
        showCancelButton: false,
        allowOutsideClick: false,
        confirmButtonText: 'Kunci Jawaban'
    }).then((res) => {
        if (res.value && res.value.toLowerCase() == q.jawaban_kunci.toLowerCase()) {
            // Jawaban benar — reset stars
            p.stars = 0;
            updateStarIndicator(p.id);
            logGameEvent(`Player ${p.id + 1} menjawab kuis Statistika dengan <b>BENAR</b>! (Reward: ${formatRp(q.poin)})`, 'statistika', p.id);
            Swal.fire({
                title: 'Tepat!',
                text: `Reward ${formatRp(q.poin)} akan masuk saldo. Beli ${tile.nama} seharga ${formatRp(tile.harga)}?`,
                showCancelButton: true,
                confirmButtonText: `Beli`,
                cancelButtonText: 'Lewati'
            }).then((b) => {
                if (b.isConfirmed) {
                    p.money += q.poin;
                    if (p.money >= tile.harga) {
                        p.money -= tile.harga;
                        tile.owner = p.id;
                        p.properties.push(tile);
                        playSfx(sfx.buy);
                        logGameEvent(`Player ${p.id + 1} membeli <b>${tile.nama}</b> seharga ${formatRp(tile.harga)}.`, 'buy', p.id);
                        Swal.fire('Sukses!', `${tile.nama} berhasil dibeli!`, 'success').then(() => updateUI());
                    } else {
                        Swal.fire('Uang Kurang', `Reward diterima tapi uang kurang untuk beli tanah. Sekarang punya ${formatRp(p.money)}.`, 'info').then(() => updateUI());
                    }
                } else {
                    p.money += q.poin;
                    Swal.fire('OK', `Reward ${formatRp(q.poin)} masuk saldo.`, 'info').then(() => updateUI());
                }
            });
        } else {
            // Jawaban salah — tambah bintang
            p.stars++;
            updateStarIndicator(p.id);
            logGameEvent(`Player ${p.id + 1} menjawab kuis Statistika dengan <b>SALAH</b>! (Jawaban Anda: "${res.value || ''}", Kunci: "${q.jawaban_kunci}", Peringatan: ${p.stars}/3)`, 'statistika', p.id);

            if (p.stars >= 3) {
                Swal.fire({
                    title: '⭐⭐⭐ Tiga Bintang!',
                    html: `<div style="font-size:1.1rem;">Jawaban salah sudah <b>3x</b>!<br>Kamu <b>masuk penjara</b>! 🚔</div>`,
                    icon: 'error',
                    confirmButtonText: 'Masuk Penjara!'
                }).then(() => {
                    p.stars = 0;
                    updateStarIndicator(p.id);
                    p.pos = 10;
                    p.inJail = true;
                    p.jailTurns = 0;
                    playSfx(sfx.jail);
                    updatePawnPositions();
                    updateUI();
                    logGameEvent(`Player ${p.id + 1} dijebloskan ke Penjara karena 3x salah menjawab soal!`, 'jail', p.id);
                });
            } else {
                const starDisplay = '⭐'.repeat(p.stars) + '☆'.repeat(3 - p.stars);
                Swal.fire({
                    title: 'Salah!',
                    html: `<div>Jawaban benar: <b>${q.jawaban_kunci}</b></div>
                           <div style="font-size:1.5rem; margin: 10px 0;">${starDisplay}</div>
                           <div style="font-size:0.85rem; color:#e74c3c;">Peringatan: ${p.stars}/3 — Di bintang ke-3 kamu masuk penjara!</div>
                           <br><div>Tetap ingin membeli <b>${tile.nama}</b> seharga <b>${formatRp(tile.harga)}</b>?</div>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Beli',
                    cancelButtonText: 'Lewati'
                }).then((b) => {
                    if (b.isConfirmed) {
                        if (p.money >= tile.harga) {
                            p.money -= tile.harga;
                            tile.owner = p.id;
                            p.properties.push(tile);
                            playSfx(sfx.buy);
                            logGameEvent(`Player ${p.id + 1} membeli <b>${tile.nama}</b> seharga ${formatRp(tile.harga)}.`, 'buy', p.id);
                            Swal.fire('Sukses!', `${tile.nama} berhasil dibeli!`, 'success').then(() => updateUI());
                        } else {
                            Swal.fire('Uang Kurang', `Uang tidak cukup untuk beli tanah. Sekarang punya ${formatRp(p.money)}.`, 'error').then(() => updateUI());
                        }
                    } else {
                        updateUI();
                    }
                });
            }
        }
    });
}

function drawCard(type, p) {
    playSfx(sfx.card);
    let isChance = (type === 'chance' || type === 'kesempatan');

    let cardDetails;
    if (isChance) {
        const chances = [
            { title: 'KESEMPATAN', icon: 'fa-question', color: '#f39c12', bgColor: '#d35400', reward: { type: 'jail_card' }, text: 'Dapat Kartu Bebas Penjara!' },
            { title: 'KESEMPATAN', icon: 'fa-question', color: '#f39c12', bgColor: '#d35400', reward: { type: 'money', amount: 200 }, text: 'Menang turnamen Statistika! Bonus Rp 200.000.' },
            { title: 'KESEMPATAN', icon: 'fa-question', color: '#f39c12', bgColor: '#d35400', reward: { type: 'penalty', amount: -50 }, text: 'Ngebut di jalan tol! Denda Rp 50.000.' },
            { title: 'KESEMPATAN', icon: 'fa-question', color: '#f39c12', bgColor: '#d35400', reward: { type: 'money', amount: 20 }, text: 'Menemukan uang di jalan! Dapat Rp 20.000.' },
            { title: 'KESEMPATAN', icon: 'fa-question', color: '#f39c12', bgColor: '#d35400', reward: { type: 'penalty', amount: -100 }, text: 'HP rusak! Bayar biaya servis Rp 100.000.' },
            { title: 'KESEMPATAN', icon: 'fa-question', color: '#f39c12', bgColor: '#d35400', reward: { type: 'money', amount: 150 }, text: 'Kerja lembur bagai kuda! Gaji tambahan Rp 150.000.' },
            { title: 'KESEMPATAN', icon: 'fa-question', color: '#f39c12', bgColor: '#d35400', reward: { type: 'penalty', amount: -150 }, text: 'Kena scam online! Rugi Rp 150.000.' },
            { title: 'KESEMPATAN', icon: 'fa-question', color: '#f39c12', bgColor: '#d35400', reward: { type: 'money', amount: 300 }, text: 'Investasi Saham Menguntungkan! Untung Rp 300.000.' }
        ];
        cardDetails = chances[Math.floor(Math.random() * chances.length)];
    } else {
        const chests = [
            { title: 'DANA UMUM', icon: 'fa-gift', color: '#3498db', bgColor: '#2980b9', reward: { type: 'money', amount: 150 }, text: 'Menang Undian! Bonus Rp 150.000.' },
            { title: 'DANA UMUM', icon: 'fa-gift', color: '#3498db', bgColor: '#2980b9', reward: { type: 'money', amount: 50 }, text: 'Ulang Tahun! Dapat kado hadiah Rp 50.000.' },
            { title: 'DANA UMUM', icon: 'fa-gift', color: '#3498db', bgColor: '#2980b9', reward: { type: 'penalty', amount: -100 }, text: 'Bayar Pajak Jalan! Denda Rp 100.000.' },
            { title: 'DANA UMUM', icon: 'fa-gift', color: '#3498db', bgColor: '#2980b9', reward: { type: 'penalty', amount: -200 }, text: 'Biaya rumah sakit! Bayar tagihan Rp 200.000.' },
            { title: 'DANA UMUM', icon: 'fa-gift', color: '#3498db', bgColor: '#2980b9', reward: { type: 'money', amount: 250 }, text: 'Cairan asuransi kesehatan! Dapat Rp 250.000.' },
            { title: 'DANA UMUM', icon: 'fa-gift', color: '#3498db', bgColor: '#2980b9', reward: { type: 'money', amount: 100 }, text: 'Uang sekolah cair! Dapat Rp 100.000.' },
            { title: 'DANA UMUM', icon: 'fa-gift', color: '#3498db', bgColor: '#2980b9', reward: { type: 'penalty', amount: -150 }, text: 'Pergi liburan boros! Bayar tagihan Rp 150.000.' },
            { title: 'DANA UMUM', icon: 'fa-gift', color: '#3498db', bgColor: '#2980b9', reward: { type: 'money', amount: 300 }, text: 'Hadiah lomba cerdas cermat! Dapat Rp 300.000.' }
        ];
        cardDetails = chests[Math.floor(Math.random() * chests.length)];
    }

    Swal.fire({
        title: `<i class="fa-solid ${cardDetails.icon}" style="font-size:3rem; color:${cardDetails.color};"></i><br><span style="font-size:1.2rem; letter-spacing:2px; font-weight:800; color:${cardDetails.bgColor};">${cardDetails.title}</span><hr style="margin-top:15px; margin-bottom:15px; border-top: 2px solid ${cardDetails.color}; width: 60%; margin-left: auto; margin-right: auto;">`,
        text: cardDetails.text,
        customClass: { popup: isChance ? 'swal-card-chance' : 'swal-card-chest' },
        showClass: { popup: 'animate__animated animate__zoomIn' },
        confirmButtonText: 'Ambil'
    }).then(() => {
        if (cardDetails.reward.type === 'jail_card') {
            p.cards.push({ nama: 'Bebas Penjara', type: 'free_jail' });
        } else if (cardDetails.reward.type === 'money' || cardDetails.reward.type === 'penalty') {
            p.money += cardDetails.reward.amount;
        }
        logGameEvent(`Player ${p.id + 1} menarik Kartu <b>${cardDetails.title}</b>: "${cardDetails.text}"`, 'card', p.id);
        updateUI();
        if (p.money < 0) {
            hasRolled = true;
            Swal.fire('Hutang!', 'Uangmu minus, segera jual aset untuk membayar hutang!', 'warning');
            updateUI();
        }
    });
}
