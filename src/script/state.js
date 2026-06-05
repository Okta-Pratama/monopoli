/**
 * MONOPOLI - STATISTIKA GAME
 * State & Utilities
 */

// ========== FORMATTING & UTILITY ==========
function formatRp(val) {
    return 'Rp ' + (val * 1000).toLocaleString('id-ID');
}

// ========== SOUND EFFECTS ==========
const sfx = {
    move: new Audio('../public/sfx/place.mp3'),
    door: new Audio('../public/sfx/door-opening.mp3'),
    build: new Audio('../public/sfx/building.mp3'),
    bell: new Audio('../public/sfx/bell.mp3'),
    dice: new Audio('../public/sfx/roll-dice.mp3'),
    card: new Audio('../public/sfx/taking-card.mp3'),
    buy: new Audio('../public/sfx/purchase.mp3')
};

function playSfx(sound) {
    if (sound) {
        sound.currentTime = 0;
        sound.play().catch(e => console.log('SFX Blocked:', e));
    }
}

// ========== PLAYER & GAME STATE ==========
const pColors = ['text-player-0', 'text-player-1', 'text-player-2', 'text-player-3'];
const pBgSoft = ['#fef2f2', '#eff6ff', '#ecfdf5', '#fffbeb'];

let players = [
    { id: 0, name: 'Player 1', pos: 0, cards: [], stars: 0, blueStars: 0, isBankrupt: false },
    { id: 1, name: 'Player 2', pos: 0, cards: [], stars: 0, blueStars: 0, isBankrupt: false },
    { id: 2, name: 'Player 3', pos: 0, cards: [], stars: 0, blueStars: 0, isBankrupt: false },
    { id: 3, name: 'Player 4', pos: 0, cards: [], stars: 0, blueStars: 0, isBankrupt: false }
];

let currentTurn = 0;
let stockRumah = 32;
let stockHotel = 12;
let isActionPhase = true;
let hasRolled = false;
let canEndTurn = false;
let autoRollInterval;
let rollCountdown = 3;
let actionTimerInterval;
let actionCountdown = ACTION_COUNTDOWN;
let isActionTimeout = false;
let isGamePaused = false;
let gameTimeRemaining = 15 * 60; // 15 minutes in seconds


