
// Dynamically populate the current year
document.getElementById('currentYear').textContent = new Date().getFullYear();

// // Hamburger navigation button
// // Store the selected elements that we are going to use. 
// const mainnav = document.querySelector('.navigation')
// const hambutton = document.querySelector('#menu');

// // Add a click event listender to the hamburger button and use a callback function that toggles the list element's list of classes.
// hambutton.addEventListener('click', () => {
//     mainnav.classList.toggle('show');
//     hambutton.classList.toggle('show');
// });

document.addEventListener('DOMContentLoaded', function () {

    // Hamburger navigation button and nav element
    const hambutton = document.querySelector('#menu');
    const nav = document.querySelector('nav');

    // Add a click event listener to the hamburger button
    hambutton.addEventListener('click', function (e) {
        e.preventDefault(); // Prevent the default anchor click behavior
        nav.classList.toggle('show'); // Toggle the 'show' class on the nav
        console.log(nav.classList.contains('show')); // Log to check if nav is shown
    });
});

