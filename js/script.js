// Basic form validation
function validateForm() {
    const email = document.querySelector('input[type="email"]');
    const password = document.querySelector('input[type="password"]');
    if (!email || !password) return true;

    if (!email.value.includes('@')) {
        alert('Please enter a valid email');
        return false;
    }
    if (password.value.length < 6) {
        alert('Password must be at least 6 characters');
        return false;
    }
    return true;
}

// Attach to forms
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!validateForm()) {
                e.preventDefault();
            }
        });
    });

    // Optional JS session check for home.php (for dynamic name; server-side already protects access)
    if (window.location.pathname.includes('home.')) {
        fetch('php/check_session.php')
            .then(response => response.json())
            .then(data => {
                if (!data.logged_in) {
                    window.location.href = 'login.html';
                } else {
                    document.querySelector('#welcome').textContent = `Welcome, ${data.name}!`;
                }
            })
            .catch(() => {
                window.location.href = 'login.html';
            });
    }
});



document.addEventListener('DOMContentLoaded', function() {
    const footer = document.querySelector('footer');
    const body = document.body;
    const html = document.documentElement;

    function adjustFooter() {
        const bodyHeight = Math.max(body.scrollHeight, body.offsetHeight,
                                    html.clientHeight, html.scrollHeight, html.offsetHeight);
        const windowHeight = window.innerHeight;

        if (bodyHeight < windowHeight) {
            footer.style.position = 'absolute';
            footer.style.bottom = '0';
            footer.style.width = '100%';
        } else {
            footer.style.position = 'static';
        }
    }

    adjustFooter();
    window.addEventListener('resize', adjustFooter);
});
