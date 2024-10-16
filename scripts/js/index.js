
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

    const menuButton = document.getElementById('menu');
    const nav = document.querySelector('nav');

    menuButton.addEventListener('click', () => {
        nav.classList.toggle('show'); // Show/hide the navigation
        menuButton.classList.toggle('show'); // Toggle the menu button's show class
    });

});

document.querySelectorAll('#registrationForm input').forEach(input => {
    input.addEventListener('input', function () {
        if (this.validity.valid) {
            // Clear error message or apply success styles
        } else {
            // Display error message or apply error styles
        }
    });
});
