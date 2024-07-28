document.addEventListener('DOMContentLoaded', function() {
    // Function to open the lightbox
    function openLightbox(event) {
        event.preventDefault();
        var lightboxId = this.getAttribute('href');
        document.querySelector(lightboxId).style.display = 'block';
    }

    // Function to close the lightbox
    function closeLightbox() {
        var lightboxes = document.querySelectorAll('.lightbox');
        lightboxes.forEach(function(lightbox) {
            lightbox.style.display = 'none';
        });
    }

    // Attach open lightbox event to links
    var lightboxLinks = document.querySelectorAll('a[data-toggle="lightbox"]');
    lightboxLinks.forEach(function(link) {
        link.addEventListener('click', openLightbox);
    });

    // Attach close lightbox event to close buttons
    var closeButtons = document.querySelectorAll('.lightbox-close');
    closeButtons.forEach(function(button) {
        button.addEventListener('click', closeLightbox);
    });
});