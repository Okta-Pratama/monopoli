/**
 * MONOPOLI - STATISTIKA GAME
 * Property Management & Interaction
 */

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
        if (p.pos === tileIndex && p.money >= tile.harga) {
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
                Rumah: <b>${tile.houses < 5 ? (tile.houses || 0) : 0}</b> | Hotel: <b>${tile.houses === 5 ? 1 : 0}</b><br><br>
                <button class="btn btn-info fw-bold text-white" onclick="offerProperty(${buyerId}, ${sellerId}, ${tileIndex})"><i class="fa-solid fa-handshake"></i> Tawar Tanah</button>`;
    Swal.fire({ title: tile.nama, html: html, showCancelButton: true, cancelButtonText: 'Tutup', showConfirmButton: false });
}

// ========== ASSET MANAGEMENT ==========
function openPropertyMenu(ownerId, tileIndex) {
    let p = players[ownerId];
    let tile = BOARD[tileIndex];
    let htmlOpts = '';

    if (tile.mortgaged) {
        let tebusCost = (tile.harga / 2) * 1.1;
        htmlOpts += `<button class="btn btn-success m-2 fw-bold" onclick="unmortgage(${ownerId}, ${tileIndex}, ${tebusCost})"><i class="fa-solid fa-unlock"></i> Tebus Sertifikat (${formatRp(tebusCost)})</button>`;
    } else {
        if (checkMonopolyGroup(ownerId, tile.grup) && tile.tipe === 'properti' && (!tile.houses || tile.houses < 5)) {
            htmlOpts += `<button class="btn btn-primary m-2 fw-bold" onclick="buildHouse(${ownerId}, ${tileIndex})"><i class="fa-solid fa-hammer"></i> Bangun Properti (${formatRp(50)})</button>`;
        }
        
        if (tile.houses > 0) {
            if (tile.houses === 5) {
                htmlOpts += `<button class="btn btn-danger m-2 fw-bold" onclick="sellHotel(${ownerId}, ${tileIndex})"><i class="fa-solid fa-building"></i> Jual Hotel (+${formatRp(25)})</button>`;
            } else {
                htmlOpts += `<button class="btn btn-warning m-2 fw-bold" onclick="sellHouse(${ownerId}, ${tileIndex})"><i class="fa-solid fa-house"></i> Jual Rumah (+${formatRp(25)})</button>`;
            }
        } else {
            let gadaiGain = tile.harga / 2;
            htmlOpts += `<button class="btn btn-danger m-2 fw-bold" onclick="mortgage(${ownerId}, ${tileIndex}, ${gadaiGain})"><i class="fa-solid fa-lock"></i> Gadaikan (+${formatRp(gadaiGain)})</button>`;
        }
    }
    Swal.fire({ title: tile.nama, html: htmlOpts, showCancelButton: true, cancelButtonText: 'Tutup', showConfirmButton: false });
}

function checkMonopolyGroup(playerId, groupColor) {
    if (!groupColor) return false;
    return BOARD.filter(t => t.grup === groupColor).length === players[playerId].properties.filter(t => t.grup === groupColor).length;
}

function checkEvenBuildRule(tile) {
    let groupTiles = BOARD.filter(t => t.grup === tile.grup && t.tipe === 'properti');
    let minHouses = Math.min(...groupTiles.map(t => t.houses || 0));
    let currentHouses = tile.houses || 0;
    return currentHouses === minHouses;
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
        logGameEvent(`Player ${p.id + 1} membangun 1 Rumah di <b>${tile.nama}</b>.`, 'build', p.id);
        updateUI();
        Swal.fire('Sukses', '1 Rumah dibangun.', 'success');
    } else if (tile.houses === 4 && stockHotel > 0) {
        tile.houses = 5;
        stockRumah += 4;
        stockHotel--;
        p.money -= 50;
        playSfx(sfx.build);
        logGameEvent(`Player ${p.id + 1} mendirikan Hotel mewah di <b>${tile.nama}</b>!`, 'build', p.id);
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
    logGameEvent(`Player ${pId + 1} menggadaikan <b>${BOARD[tIdx].nama}</b> seharga ${formatRp(gain)}.`, 'buy', pId);
    updateUI();
    Swal.close();
}

function unmortgage(pId, tIdx, cost) {
    if (players[pId].money < cost) return Swal.fire('Gagal', 'Uang kurang', 'error');
    BOARD[tIdx].mortgaged = false;
    players[pId].money -= cost;
    playSfx(sfx.buy);
    logGameEvent(`Player ${pId + 1} menebus gadai <b>${BOARD[tIdx].nama}</b> seharga ${formatRp(cost)}.`, 'buy', pId);
    updateUI();
    Swal.close();
}

function sellHouse(pId, tIdx) {
    BOARD[tIdx].houses--;
    stockRumah++;
    players[pId].money += 25;
    playSfx(sfx.buy);
    logGameEvent(`Player ${pId + 1} menjual 1 Rumah di <b>${BOARD[tIdx].nama}</b> seharga ${formatRp(25)}.`, 'buy', pId);
    updateUI();
    Swal.close();
}

function sellHotel(pId, tIdx) {
    BOARD[tIdx].houses = 4;
    stockHotel++;
    stockRumah -= 4;
    players[pId].money += 25;
    playSfx(sfx.buy);
    logGameEvent(`Player ${pId + 1} menurunkan Hotel di <b>${BOARD[tIdx].nama}</b> menjadi 4 Rumah seharga ${formatRp(25)}.`, 'buy', pId);
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
        customClass: { popup: 'swal-card-stats' },
        showClass: { popup: 'animate__animated animate__fadeIn' }
    }).then((res) => {
        if (res.isConfirmed && res.value > 0) {
            let amount = parseInt(res.value);
            if (amount > buyer.money) return Swal.fire('Gagal', 'Uang tidak cukup', 'error');
            Swal.fire({
                title: `<span class="${pColors[sellerId]}">Tawaran (Player ${sellerId + 1})</span>`,
                html: `Player ${buyerId + 1} menawar <b>${tile.nama}</b> seharga <b>${formatRp(amount)}</b>. Terima?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Terima',
                cancelButtonText: 'Tolak',
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#ef4444'
            }).then((sellRes) => {
                if (sellRes.isConfirmed) {
                    buyer.money -= amount;
                    seller.money += amount;
                    tile.owner = buyerId;
                    seller.properties = seller.properties.filter(x => x.nama !== tile.nama);
                    buyer.properties.push(tile);
                    playSfx(sfx.buy);
                    logGameEvent(`Player ${buyerId + 1} membeli <b>${tile.nama}</b> dari Player ${sellerId + 1} seharga ${formatRp(amount)}.`, 'buy', buyerId);
                    updateUI();
                    Swal.fire('Deal!', 'Properti berpindah tangan.', 'success');
                } else {
                    logGameEvent(`Tawaran Player ${buyerId + 1} untuk membeli <b>${tile.nama}</b> ditolak oleh Player ${sellerId + 1}.`, 'system', sellerId);
                    Swal.fire('Ditolak', 'Tawaran ditolak.', 'error');
                }
            });
        }
    });
}
