document.addEventListener('DOMContentLoaded', function () {
    const track = document.querySelector('.carousel-track');
    if (!track) return;

    const slides = Array.from(track.children);
    const totalSlides = slides.length;
    let currentIndex = 0;

    function showSlide(index) {
        const offset = -index * 100;
        track.style.transform = 'translateX(' + offset + '%)';
    }

    function nextSlide() {
        currentIndex = (currentIndex + 1) % totalSlides;
        showSlide(currentIndex);
    }

    showSlide(currentIndex);

    setInterval(nextSlide, 4000);
});