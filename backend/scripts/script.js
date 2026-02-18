const element = document.querySelector('.fade-in');
element.addEventListener('animationend', () => {
    element.classList.remove('fade-in');
});