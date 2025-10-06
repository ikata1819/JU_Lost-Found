document.addEventListener('DOMContentLoaded', () => {
    // Select all navigation links that point to local IDs
    const navLinks = document.querySelectorAll('nav a[href^="#"]');

    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // Prevent the default anchor jump behavior
            e.preventDefault();

            // Get the target element's ID from the href attribute
            const targetId = this.getAttribute('href');
            
            // Map the link href to the actual element ID containing the form/search
            let targetElement;
            if (targetId === '#report-lost') {
                // Find the first card/col-md-5 which contains the "Report Lost Item" form
                targetElement = document.querySelector('.row.g-4 > .col-md-5:nth-child(1)');
            } else if (targetId === '#report-found') {
                // Find the second card/col-md-5 which contains the "Report Found Item" form
                targetElement = document.querySelector('.row.g-4 > .col-md-5:nth-child(2)');
            } else if (targetId === '#search-items') {
                // Find the container for the "Search Items" form
                targetElement = document.querySelector('.row.justify-content-center .col-md-12');
            }

            if (targetElement) {
                // Scroll smoothly to the target element's position
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start' // Scroll to the top of the element
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

