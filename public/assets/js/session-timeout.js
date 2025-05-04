// Waktu timeout dalam milidetik (1 jam = 3600000 milidetik)
const TIMEOUT_DURATION = 3600000;
let timeoutId;

// Fungsi untuk mereset timer
function resetTimer() {
    clearTimeout(timeoutId);
    timeoutId = setTimeout(logout, TIMEOUT_DURATION);
}

// Fungsi untuk logout
function logout() {
    window.location.href = '/auth/logout';
}

// Event listeners untuk mendeteksi aktivitas user
const events = ['mousemove', 'mousedown', 'keypress', 'scroll', 'touchstart'];
events.forEach(event => {
    document.addEventListener(event, resetTimer);
});

// Mulai timer saat halaman dimuat
resetTimer(); 