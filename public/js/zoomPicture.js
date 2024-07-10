
    document.addEventListener('DOMContentLoaded', (event) => {
    const clickableImage = document.getElementById('clickableImage');
    const fullscreenOverlay = document.getElementById('fullscreenOverlay');
    const fullscreenImage = document.getElementById('fullscreenImage');
    const closeBtn = document.getElementById('closeBtn');

    clickableImage.addEventListener('click', () => {
        fullscreenImage.src = clickableImage.src;
        fullscreenOverlay.classList.add('show');
        setTimeout(() => {
            fullscreenImage.classList.add('zoomed');
        }, 10); 
    });

    closeBtn.addEventListener('click', () => {
        fullscreenImage.classList.remove('zoomed');
        setTimeout(() => {
            fullscreenOverlay.classList.remove('show');
        }, 400); 

    });

    fullscreenOverlay.addEventListener('click', (event) => {
        if (event.target == fullscreenOverlay) {
            fullscreenImage.classList.remove('zoomed');
            setTimeout(() => {
                fullscreenOverlay.classList.remove('show');
            }, 400); 
        }
    });
});


