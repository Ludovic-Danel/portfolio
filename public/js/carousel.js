let currentSlide = 0;
let slideInterval;

function showSlide(index) {
  const slides = document.querySelectorAll('.carousel-item');
  if (index >= slides.length) {
    currentSlide = 0;
  } else if (index < 0) {
    currentSlide = slides.length - 1;
  } else {
    currentSlide = index;
  }
  const newTransform = -currentSlide * 100;
  document.querySelector('.carousel-inner').style.transform = `translateX(${newTransform}%)`;
}

function nextSlide() {
  showSlide(currentSlide + 1);
}

function prevSlide() {
  showSlide(currentSlide - 1);
}

function startAutoSlide() {
  slideInterval = setInterval(nextSlide, 5000); // Change slide every 3 seconds
}

function stopAutoSlide() {
  clearInterval(slideInterval);
}

document.addEventListener('DOMContentLoaded', () => {
  showSlide(currentSlide);
  startAutoSlide();

  // Stop the auto sliding when user interacts with the controls
  document.querySelector('.carousel-control.prev').addEventListener('click', stopAutoSlide);
  document.querySelector('.carousel-control.next').addEventListener('click', stopAutoSlide);
  document.querySelector('.carousel-control.prev').addEventListener('click', prevSlide);
  document.querySelector('.carousel-control.next').addEventListener('click', nextSlide);
});
