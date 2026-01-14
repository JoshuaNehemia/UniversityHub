const savedTheme = localStorage.getItem('theme') || 'light';
document.documentElement.setAttribute('data-theme', savedTheme)

const API_ADDRESS = "http://localhost/universityhub/APP/index.php/";

function updateThemeIcon() {
    const btn = document.getElementById("themeToggle");
    if (!btn) return;

    const theme = document.documentElement.getAttribute("data-theme");
    btn.textContent = theme === "dark" ? "☀️" : "🌙";
}

function toggleTheme() {
    const currentTheme = document.documentElement.getAttribute('data-theme');
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

    document.documentElement.setAttribute('data-theme', newTheme);
    localStorage.setItem('theme', newTheme);

    updateThemeIcon();
}

document.addEventListener("DOMContentLoaded", updateThemeIcon);



