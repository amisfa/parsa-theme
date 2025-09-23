var toggleOpen = document.getElementById('toggleOpen');
var toggleClose = document.getElementById('toggleClose');
var collapseMenu = document.getElementById('collapseMenu');

function handleClick() {
    if (collapseMenu.style.display === 'block') {
        collapseMenu.style.display = 'none';
    } else {
        collapseMenu.style.display = 'block';
    }
}

toggleOpen.addEventListener('click', handleClick);
toggleClose.addEventListener('click', handleClick);

const setViewportVars = () => {
    const vh = window.innerHeight * 0.01;
    document.documentElement.style.setProperty('--vh', `${vh}px`);
    const header = document.querySelector('.site-header');
    const wpHeader = document.querySelector('.nojq')?.getBoundingClientRect().height || 0
    const headerHeight = (header ? header.getBoundingClientRect().height : 0) + wpHeader;
    document.documentElement.style.setProperty('--header-height', `${headerHeight}px`);
    const slider = document.getElementById('movie-slider');
    const wrapper = document.getElementById('swiper-wrapper');
    if (slider) slider.style.height = `calc(var(--vh) * 100 - ${headerHeight}px)`;
    if (wrapper) wrapper.style.height = `calc(var(--vh) * 100 - ${headerHeight}px)!important`;
};
setViewportVars();
window.addEventListener('resize', setViewportVars);
window.addEventListener('orientationchange', setViewportVars);
window.addEventListener('load', setViewportVars);
setTimeout(setViewportVars, 500);
document.addEventListener('DOMContentLoaded', function () {
    if (typeof Swiper !== 'undefined') {
        var swiper = new Swiper(".progress-slide-carousel", {
            loop: true,
            fraction: true,
            autoplay: {
                delay: 1800,
                disableOnInteraction: false,
            },
            pagination: {
                el: ".progress-slide-carousel .swiper-pagination",
                type: "progressbar",
            },
        });
    }
});