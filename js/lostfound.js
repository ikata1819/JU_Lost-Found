document.addEventListener('DOMContentLoaded', () => {
    const navLinks = document.querySelectorAll('nav a[href^="#"]');
    const header = document.querySelector('header'); // Get sticky header

    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');

            let targetElement;
            if (targetId === '#report-lost') {
                targetElement = document.querySelector('.row.g-4 > .col-md-5:nth-child(1)');
            } else if (targetId === '#report-found') {
                targetElement = document.querySelector('.row.g-4 > .col-md-5:nth-child(2)');
            } else if (targetId === '#search-items') {
                targetElement = document.querySelector('.row.justify-content-center .col-md-12');
            }

            if (targetElement) {
                // Calculate the element's top offset minus header height
                const headerHeight = header.offsetHeight;
                const elementTop = targetElement.getBoundingClientRect().top + window.scrollY;
                const scrollToPosition = elementTop - headerHeight -10; // 10px padding

                window.scrollTo({
                    top: scrollToPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
});


document.addEventListener('DOMContentLoaded', () => {
    const popup = document.getElementById('popup-msg');
    if (popup) {
        popup.style.display = 'block';
        setTimeout(() => {
            popup.style.transition = 'opacity 0.5s ease';
            popup.style.opacity = '0';
            setTimeout(() => popup.remove(), 500);
        }, 3000); // disappears after 3s
    }
});
