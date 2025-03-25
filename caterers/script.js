document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    // Simple validation
    if (username === "user" && password === "password") {
        // Save username to localStorage
        localStorage.setItem('username', username);
        // Redirect to profile page
        window.location.href = 'profile.html';
    } else {
        alert('Invalid username or password');
    }
});

// On profile page load
if (window.location.pathname.endsWith('profile.html')) {
    const username = localStorage.getItem('username');
    if (username) {
        document.getElementById('welcomeMessage').textContent = `Hello, ${username}!`;
    } else {
        // Redirect back to login if no user is logged in
        window.location.href = 'login.html';
    }
}

function logout() {
    localStorage.removeItem('username');
    window.location.href = 'login.html';
}// Function to change the background color of the hero section
document.getElementById('colorChangeBtn').addEventListener('click', function() {
    const colors = ['#28a745', '#007bff', '#dc3545', '#ffc107', '#17a2b8'];
    const randomColor = colors[Math.floor(Math.random() * colors.length)];
    document.querySelector('.hero').style.backgroundColor = randomColor;
});