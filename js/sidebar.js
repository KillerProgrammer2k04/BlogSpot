let dropdowns = document.querySelectorAll('.dropdown');


dropdowns.forEach( dropdown =>{

    let arrow = dropdown.querySelector('.arrow');
    let megamenu = dropdown.querySelector('.megamenu');

    arrow.addEventListener('click', function()
    {
        arrow.classList.add('arrowrotate');
        megamenu.classList.add('openclose');
    });

    dropdown.addEventListener('mouseleave', function()
    {
        arrow.classList.remove('arrowrotate');
        megamenu.classList.remove('openclose');
    });
});


let hamburger = document.querySelector('.hamburger');
let nav = document.querySelector('.nav');
let closesidebar = document.querySelector('#closesidebar');
let mobilemenu = document.querySelector('.mobilemenu');

hamburger.addEventListener('click', function()
{
    nav.classList.add('openclosenav');
    mobilemenu.classList.add('displaynone');
});


closesidebar.addEventListener('click', function()
{
    nav.classList.remove('openclosenav');
    mobilemenu.classList.remove('displaynone');
});
