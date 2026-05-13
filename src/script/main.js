/**
 * MONOPOLI - STATISTIKA GAME
 * Main Game Logic
 */

// ========== FORMATTING & UTILITY ==========
function formatRp(val) {
    return 'Rp ' + (val * 1000).toLocaleString('id-ID');
}

// ========== SOUND EFFECTS ==========
const sfx = {
    move: new Audio('src/sfx/place.mp3'),
    door: new Audio('src/sfx/door-opening.mp3'),
    jail: new Audio('src/sfx/jail.mp3'),
    build: new Audio('src/sfx/building.mp3'),
    bell: new Audio('src/sfx/bell.mp3'),
    dice: new Audio('src/sfx/roll-dice.mp3'),
    card: new Audio('src/sfx/taking-card.mp3'),
    buy: new Audio('src/sfx/purchase.mp3')
};

function playSfx(sound) {
    if(sound) {
        sound.currentTime = 0;
        sound.play().catch(e => console.log('SFX Blocked:', e));
    }
}

// ========== PLAYER & GAME STATE ==========
const pColors = ['text-player-0', 'text-player-1', 'text-player-2', 'text-player-3'];
const pBgSoft = ['#fef2f2', '#eff6ff', '#ecfdf5', '#fffbeb'];

let players = [
    { id: 0, pos: 0, money: 1500, properties: [], cards: [], wrongAnswers: 0, inJail: false, jailTurns: 0, isBankrupt: false },
    { id: 1, pos: 0, money: 1500, properties: [], cards: [], wrongAnswers: 0, inJail: false, jailTurns: 0, isBankrupt: false },
    { id: 2, pos: 0, money: 1500, properties: [], cards: [], wrongAnswers: 0, inJail: false, jailTurns: 0, isBankrupt: false },
    { id: 3, pos: 0, money: 1500, properties: [], cards: [], wrongAnswers: 0, inJail: false, jailTurns: 0, isBankrupt: false }
];

let currentTurn = 0;
let stockRumah = 32;
let stockHotel = 11;
let isActionPhase = true;
let hasRolled = false;
let autoRollInterval;
let rollCountdown = 3;

window.onload = () => {
    updatePawnPositions();
    startAutoRoll();
};

// ========== AUTO ROLL TIMER ==========
function startAutoRoll() {
    rollCountdown = 3;
    document.getElementById('btn-roll').innerText = `KOCOK DADU (${rollCountdown})`;
    autoRollInterval = setInterval(() => {
        rollCountdown--;
        if(rollCountdown > 0) {
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
        if(player.isBankrupt) return;
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
        if(!st) return;
        if(t.owner === undefined) { st.innerHTML = ''; return; }
        if(t.mortgaged) st.innerHTML = '<i class="fa-solid fa-lock text-secondary"></i>';
        else {
            st.innerHTML = '';
            if(t.houses > 0 && t.houses < 5) {
                for(let i=0; i<t.houses; i++) st.innerHTML += `<i class="fa-solid fa-house ${pColors[t.owner]}"></i>`;
            } else if(t.houses === 5) {
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

// ========== TURN PROCESSING ==========
function processTurn() {
    clearAutoRoll();
    let p = players[currentTurn];
    document.getElementById('btn-roll').disabled = true;

    if (p.inJail) {
        let hasJailCard = p.cards.find(c => c.type === 'free_jail');
        let jailOpts = `<button class="btn btn-warning m-1 fw-bold" onclick="payJail(${p.id})">Bayar ${formatRp(50)}</button>`;
        if(hasJailCard) jailOpts += `<button class="btn btn-info m-1 fw-bold" onclick="useJailCard(${p.id})">Pakai Kartu</button>`;
        jailOpts += `<br><button class="btn btn-danger m-1 mt-2 fw-bold" onclick="skipJailTurn(${p.id})">Lewati Giliran</button>`;
        Swal.fire({ title: 'Terkurung di Penjara!', html: jailOpts, showConfirmButton: false, allowOutsideClick: false });
    } else {
        rollAndMove(p);
    }
}

function payJail(id) {
    let p = players[id];
    p.money -= 50;
    p.inJail = false;
    p.wrongAnswers = 0;
    playSfx(sfx.door);
    updateUI();
    if(p.money < 0) {
        hasRolled = true;
        updateUI();
        Swal.fire('Hutang!', 'Jual aset untuk membayar denda penjara.', 'warning');
    } else {
        Swal.fire('Bebas!', 'Denda dibayar.', 'success').then(() => rollAndMove(p));
    }
}

function skipJailTurn(id) {
    players[id].jailTurns++;
    if(players[id].jailTurns >= 2) {
        players[id].inJail = false;
        players[id].wrongAnswers = 0;
        playSfx(sfx.door);
    }
    hasRolled = true;
    updateUI();
    Swal.close();
}

function useJailCard(id) {
    if(!isActionPhase && id !== currentTurn) return;
    let p = players[id];
    if(!p.inJail) return Swal.fire('Tidak Bisa', 'Anda tidak di penjara, kartu tidak bisa digunakan sekarang.', 'warning');
    let cIdx = p.cards.findIndex(c => c.type === 'free_jail');
    if(cIdx > -1) {
        p.cards.splice(cIdx, 1);
        p.inJail = false;
        p.wrongAnswers = 0;
        playSfx(sfx.door);
        updateUI();
        Swal.fire('Bebas!', 'Kartu digunakan.', 'success').then(() => { if(!hasRolled) rollAndMove(p); });
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
                if(step === finalDice) {
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
            if(tile.mortgaged) {
                Swal.fire('Bebas Sewa', 'Properti sedang digadaikan.', 'info');
            } else {
                let rent = tile.sewa || 25;
                if(tile.houses) rent += (tile.houses * 20);
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

// ========== TILE INTERACTION ==========
function handleTileClick(tileIndex) {
    if (!isActionPhase) return;
    let p = players[currentTurn];
    let tile = BOARD[tileIndex];

    if (tile.owner === p.id) {
        openPropertyMenu(p.id, tileIndex);
    } else if (tile.owner !== undefined && tile.owner !== p.id && !tile.mortgaged) {
        viewOtherProperty(tileIndex, p.id);
    } else if (tile.owner === undefined && tile.harga > 0) {
        if(p.pos === tileIndex && p.money >= tile.harga) {
            triggerStatsQuestion(p, tileIndex);
        } else {
            Swal.fire({
                title: tile.nama,
                html: `Harga: <b>${formatRp(tile.harga)}</b><br>Sewa: <b>${formatRp(tile.sewa)}</b><br><br><i>Tanah ini belum dimiliki siapa pun.</i>`,
                icon: 'info'
            });
        }
    }
}

function viewOtherProperty(tileIndex, buyerId) {
    let tile = BOARD[tileIndex];
    let sellerId = tile.owner;
    let html = `Milik: <b>Player ${sellerId + 1}</b><br>Sewa Dasar: <b>${formatRp(tile.sewa)}</b><br>
                Rumah: <b>${tile.houses < 5 ? (tile.houses||0) : 0}</b> | Hotel: <b>${tile.houses === 5 ? 1 : 0}</b><br><br>
                <button class="btn btn-info w-100 fw-bold text-white" onclick="offerProperty(${buyerId}, ${sellerId}, ${tileIndex})"><i class="fa-solid fa-handshake"></i> Tawar Tanah</button>`;
    Swal.fire({ title: tile.nama, html: html, showCancelButton: true, cancelButtonText: 'Tutup', showConfirmButton: false });
}

// ========== ASSET MANAGEMENT ==========
function openPropertyMenu(ownerId, tileIndex) {
    let p = players[ownerId];
    let tile = BOARD[tileIndex];
    let htmlOpts = '';

    if (tile.mortgaged) {
        let tebusCost = (tile.harga / 2) * 1.1;
        htmlOpts += `<button class="btn btn-success m-2 w-100 fw-bold" onclick="unmortgage(${ownerId}, ${tileIndex}, ${tebusCost})"><i class="fa-solid fa-unlock"></i> Tebus Sertifikat (${formatRp(tebusCost)})</button>`;
    } else {
        if (tile.houses > 0) {
            if(tile.houses === 5) {
                htmlOpts += `<button class="btn btn-danger m-2 w-100 fw-bold" onclick="sellHotel(${ownerId}, ${tileIndex})"><i class="fa-solid fa-building"></i> Jual Hotel (+${formatRp(25)})</button>`;
            } else {
                htmlOpts += `<button class="btn btn-warning m-2 w-100 fw-bold" onclick="sellHouse(${ownerId}, ${tileIndex})"><i class="fa-solid fa-house"></i> Jual Rumah (+${formatRp(25)})</button>`;
            }
        } else {
            if (checkMonopolyGroup(ownerId, tile.grup) && tile.tipe === 'properti') {
                htmlOpts += `<button class="btn btn-primary m-2 w-100 fw-bold" onclick="buildHouse(${ownerId}, ${tileIndex})"><i class="fa-solid fa-hammer"></i> Bangun Properti (${formatRp(50)})</button>`;
            }
            let gadaiGain = tile.harga / 2;
            htmlOpts += `<button class="btn btn-danger m-2 w-100 fw-bold" onclick="mortgage(${ownerId}, ${tileIndex}, ${gadaiGain})"><i class="fa-solid fa-lock"></i> Gadaikan (+${formatRp(gadaiGain)})</button>`;
        }
    }
    Swal.fire({ title: tile.nama, html: htmlOpts, showCancelButton: true, cancelButtonText: 'Tutup', showConfirmButton: false });
}

function checkMonopolyGroup(playerId, groupColor) {
    if(!groupColor) return false;
    return BOARD.filter(t => t.grup === groupColor).length === players[playerId].properties.filter(t => t.grup === groupColor).length;
}

function checkEvenBuildRule(tile) {
    let groupTiles = BOARD.filter(t => t.grup === tile.grup && t.tipe === 'properti');
    let minHouses = Math.min(...groupTiles.map(t => t.houses || 0));
    let maxHouses = Math.max(...groupTiles.map(t => t.houses || 0));
    let currentHouses = tile.houses || 0;
    return currentHouses === minHouses || (currentHouses > minHouses && currentHouses <= maxHouses);
}

function buildHouse(playerId, tileIndex) {
    let p = players[playerId];
    let tile = BOARD[tileIndex];
    if (p.money < 50) return Swal.fire('Gagal', 'Uang tidak cukup.', 'error');
    if (!checkEvenBuildRule(tile)) return Swal.fire('Gagal', 'Pembangunan di grup ini harus merata!', 'error');

    if (!tile.houses) tile.houses = 0;
    if (tile.houses < 4 && stockRumah > 0) {
        tile.houses++;
        stockRumah--;
        p.money -= 50;
        playSfx(sfx.build);
        updateUI();
        Swal.fire('Sukses', '1 Rumah dibangun.', 'success');
    } else if (tile.houses === 4 && stockHotel > 0) {
        tile.houses = 5;
        stockRumah += 4;
        stockHotel--;
        p.money -= 50;
        playSfx(sfx.build);
        updateUI();
        Swal.fire('Megah!', 'Hotel dibangun.', 'success');
    } else {
        Swal.fire('Gagal', 'Stok habis / Level Maksimal.', 'error');
    }
}

function mortgage(pId, tIdx, gain) {
    BOARD[tIdx].mortgaged = true;
    players[pId].money += gain;
    playSfx(sfx.buy);
    updateUI();
    Swal.close();
}

function unmortgage(pId, tIdx, cost) {
    if(players[pId].money < cost) return Swal.fire('Gagal','Uang kurang','error');
    BOARD[tIdx].mortgaged = false;
    players[pId].money -= cost;
    playSfx(sfx.buy);
    updateUI();
    Swal.close();
}

function sellHouse(pId, tIdx) {
    BOARD[tIdx].houses--;
    stockRumah++;
    players[pId].money += 25;
    playSfx(sfx.buy);
    updateUI();
    Swal.close();
}

function sellHotel(pId, tIdx) {
    BOARD[tIdx].houses = 4;
    stockHotel++;
    stockRumah -= 4;
    players[pId].money += 25;
    playSfx(sfx.buy);
    updateUI();
    Swal.close();
}

// ========== TRADING ==========
function offerProperty(buyerId, sellerId, tileIndex) {
    let buyer = players[buyerId];
    let seller = players[sellerId];
    let tile = BOARD[tileIndex];
    Swal.fire({
        title: `Tawar ${tile.nama}`,
        text: `Maksimal: ${formatRp(buyer.money)}`,
        input: 'number',
        inputAttributes: { min: 1, max: buyer.money },
        showCancelButton: true,
        confirmButtonText: 'Tawar',
        customClass: { popup: 'swal-card-stats animate__animated animate__fadeIn' }
    }).then((res) => {
        if(res.isConfirmed && res.value > 0) {
            let amount = parseInt(res.value);
            if(amount > buyer.money) return Swal.fire('Gagal', 'Uang tidak cukup', 'error');
            Swal.fire({
                title: `<span class="${pColors[sellerId]}">Tawaran (Player ${sellerId+1})</span>`,
                html: `Player ${buyerId+1} menawar <b>${tile.nama}</b> seharga <b>${formatRp(amount)}</b>. Terima?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Terima',
                cancelButtonText: 'Tolak',
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#ef4444'
            }).then((sellRes) => {
                if(sellRes.isConfirmed) {
                    buyer.money -= amount;
                    seller.money += amount;
                    tile.owner = buyerId;
                    seller.properties = seller.properties.filter(x => x.nama !== tile.nama);
                    buyer.properties.push(tile);
                    playSfx(sfx.buy);
                    updateUI();
                    Swal.fire('Deal!', 'Properti berpindah tangan.', 'success');
                } else Swal.fire('Ditolak', 'Tawaran ditolak.', 'error');
            });
        }
    });
}

// ========== QUESTIONS & CARDS ==========
function triggerStatsQuestion(p, tileIndex) {
    playSfx(sfx.card);
    let tile = BOARD[tileIndex];
    const qTypes = ['K', 'M', 'D', 'J', 'R'];
    const q = QUESTIONS[qTypes[Math.floor(Math.random() * qTypes.length)]][Math.floor(Math.random() * QUESTIONS[qTypes[0]].length)];

    Swal.fire({
        title: '<i class="fa-solid fa-brain" style="font-size:3rem; color:#e74c3c;"></i><br><span style="font-size:1.2rem; letter-spacing:2px; font-weight:800; color:#c0392b;">STATISTIKA</span>',
        text: q.soal,
        footer: `Reward: ${formatRp(q.poin)}`,
        input: 'text',
        customClass: { popup: 'swal-card-stats animate__animated animate__flipInY' },
        showCancelButton: false,
        allowOutsideClick: false,
        confirmButtonText: 'Kunci Jawaban'
    }).then((res) => {
        if (res.value.toLowerCase() == q.jawaban_kunci.toLowerCase()) {
            p.wrongAnswers = 0;
            Swal.fire({
                title:'Tepat!',
                text:`Reward ${formatRp(q.poin)} akan masuk saldo. Beli ${tile.nama} seharga ${formatRp(tile.harga)}?`,
                showCancelButton: true,
                confirmButtonText: `Beli`,
                cancelButtonText: 'Lewati'
            }).then((b) => {
                if(b.isConfirmed) {
                    p.money += (q.poin * 1000);
                    if(p.money >= tile.harga) {
                        p.money -= tile.harga;
                        tile.owner = p.id;
                        p.properties.push(tile);
                        playSfx(sfx.buy);
                        Swal.fire('Sukses!', `${tile.nama} berhasil dibeli!`, 'success').then(() => updateUI());
                    } else {
                        Swal.fire('Uang Kurang', `Reward diterima tapi uang kurang untuk beli tanah. Sekarang punya ${formatRp(p.money)}.`, 'info').then(() => updateUI());
                    }
                } else {
                    p.money += (q.poin * 1000);
                    Swal.fire('OK', `Reward ${formatRp(q.poin)} masuk saldo.`, 'info').then(() => updateUI());
                }
            });
        } else {
            p.wrongAnswers++;
            if (p.wrongAnswers >= 3) {
                Swal.fire('Salah 3x!', 'Masuk Penjara!', 'error').then(() => {
                    p.pos = 10;
                    p.inJail = true;
                    p.jailTurns = 0;
                    playSfx(sfx.jail);
                    updatePawnPositions();
                    updateUI();
                });
            } else {
                Swal.fire('Salah!', `Jawaban: ${q.jawaban_kunci}. Peringatan: ${p.wrongAnswers}/3.`, 'warning').then(()=>updateUI());
            }
        }
    });
}

function drawCard(type, p) {
    playSfx(sfx.card);
    let isChance = (type === 'chance');
    let isGetOutJail = Math.random() > 0.8;

    Swal.fire({
        title: `<i class="fa-solid ${isChance?'fa-question':'fa-treasure-chest'}" style="font-size:3rem; color:${isChance?'#f39c12':'#3498db'};"></i><br><span style="font-size:1.2rem; letter-spacing:2px; font-weight:800; color:${isChance?'#d35400':'#2980b9'};">${isChance?'KESEMPATAN':'DANA UMUM'}</span>`,
        text: isGetOutJail ? "Dapat Kartu Bebas Penjara." : "Bonus Rp 150.000.",
        customClass: { popup: `${isChance?'swal-card-chance':'swal-card-chest'} animate__animated animate__zoomIn` },
        confirmButtonText: 'Ambil'
    }).then(() => {
        if (isGetOutJail) p.cards.push({ nama: 'Bebas Penjara', type: 'free_jail' }); else p.money += 150;
        updateUI();
    });
}

// ========== BANKRUPTCY & END TURN ==========
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
    document.getElementById('pawn-'+p.id).classList.add('bankrupt-pawn');
    document.getElementById('ui-p'+p.id).classList.add('bankrupt-ui');
    Swal.fire('Bangkrut!', `Player ${p.id+1} telah bangkrut dan keluar dari permainan.`, 'error').then(() => endTurn());
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
    startAutoRoll();

    Swal.fire({
        title: `<span class="${pColors[currentTurn]}">Giliran Player ${currentTurn + 1}</span>`,
        background: pBgSoft[currentTurn],
        allowOutsideClick: false,
        confirmButtonText: 'OK',
        didOpen: () => { Swal.disableButtons(); setTimeout(() => { Swal.enableButtons(); }, 2000); }
    });
}

function updatePawnPositions() {
    players.forEach(p => {
        if(p.isBankrupt) return;
        const tileEl = document.getElementById(`tile-${p.pos}`);
        const pawnEl = document.getElementById(`pawn-${p.id}`);
        if (tileEl && pawnEl) {
            const rect = tileEl.getBoundingClientRect();
            const boardRect = document.getElementById('board').getBoundingClientRect();
            const top = rect.top - boardRect.top + (rect.height/2) - 15;
            const left = rect.left - boardRect.left + (rect.width/2) - 15;
            const offsetX = p.id % 2 === 0 ? -12 : 12;
            const offsetY = p.id < 2 ? -12 : 12;
            pawnEl.style.transform = `translate(${left + offsetX}px, ${top + offsetY}px)`;
        }
    });
}
