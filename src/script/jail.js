/**
 * MONOPOLI - STATISTIKA GAME
 * Jail System
 */

function payJail(id) {
    let p = players[id];
    p.money -= 50;
    p.inJail = false;
    p.wrongAnswers = 0;
    playSfx(sfx.door);
    updateUI();
    if (p.money < 0) {
        hasRolled = true;
        updateUI();
        Swal.fire('Hutang!', 'Jual aset untuk membayar denda penjara.', 'warning');
    } else {
        Swal.fire('Bebas!', 'Denda dibayar.', 'success').then(() => rollAndMove(p));
    }
}

function skipJailTurn(id) {
    players[id].jailTurns++;
    if (players[id].jailTurns >= 2) {
        players[id].inJail = false;
        players[id].wrongAnswers = 0;
        playSfx(sfx.door);
    }
    hasRolled = true;
    updateUI();
    Swal.close();
}

function useJailCard(id) {
    if (!isActionPhase && id !== currentTurn) return;
    let p = players[id];
    if (!p.inJail) return Swal.fire('Tidak Bisa', 'Anda tidak di penjara, kartu tidak bisa digunakan sekarang.', 'warning');
    let cIdx = p.cards.findIndex(c => c.type === 'free_jail');
    if (cIdx > -1) {
        p.cards.splice(cIdx, 1);
        p.inJail = false;
        p.wrongAnswers = 0;
        playSfx(sfx.door);
        updateUI();
        Swal.fire('Bebas!', 'Kartu digunakan.', 'success').then(() => { if (!hasRolled) rollAndMove(p); });
    }
}
