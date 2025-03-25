// script.js
document.addEventListener('DOMContentLoaded', () => {
    const loadContent = async (page) => {
        try {
            const response = await fetch(`${page}.html`);
            const content = await response.text();
            document.getElementById('main-content').innerHTML = content;
        } catch (error) {
            console.error('Error loading content:', error);
        }
    };

    const handleNavigation = (event) => {
        event.preventDefault();
        const page = event.target.getAttribute('href').substring(1);
        window.location.hash = page;
        loadContent(page);
    };

    // Add event listeners to all navigation links
    document.querySelectorAll('nav a').forEach(link => {
        link.addEventListener('click', handleNavigation);
    });

    // Load initial content based on URL hash
    const initialPage = window.location.hash.substring(1) || 'home';
    loadContent(initialPage);

    // Handle browser back/forward navigation
    window.addEventListener('hashchange', () => {
        const page = window.location.hash.substring(1);
        loadContent(page);
    });
});

// Login functionality
function handleLogin(event) {
    event.preventDefault();
    // Add actual authentication logic here
    window.location.href = 'profile.html';
}