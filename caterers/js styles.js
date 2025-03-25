// Example JavaScript for form submission
document.getElementById('contactForm').addEventListener('submit', function(event) {
    event.preventDefault();
    alert('Thank you for contacting us! We will get back to you soon.');
});// script.js

// Function to handle form submission
document.addEventListener('DOMContentLoaded', function () {
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function (event) {
            event.preventDefault(); // Prevent the form from submitting

            // Get form values
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const message = document.getElementById('message').value.trim();

            // Simple validation
            if (name === '' || email === '' || message === '') {
                alert('Please fill out all fields.');
                return;
            }

            // Simulate form submission (replace with actual backend logic)
            alert(`Thank you, ${name}! Your message has been sent. We will get back to you soon.`);

            // Clear the form
            contactForm.reset();
        });
    }

    // Add hover effects to gallery items (if the gallery exists)
    const galleryItems = document.querySelectorAll('.gallery-item');
    galleryItems.forEach(item => {
        item.addEventListener('mouseenter', function () {
            item.querySelector('.overlay').style.opacity = '1';
        });

        item.addEventListener('mouseleave', function () {
            item.querySelector('.overlay').style.opacity = '0';
        });
    });

    // Add smooth scrolling for anchor links (if any)
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });

    // Dynamic year in the footer
    const footer = document.querySelector('footer p');
    if (footer) {
        const year = new Date().getFullYear();
        footer.innerHTML = `&copy; ${year} Catering Delight. All rights reserved.`;
    }
});