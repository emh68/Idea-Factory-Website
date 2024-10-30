let fullName = 'Eli Hansen';
let currentYear = 2024;
let profilePicture = '../../images/profilepicoriginal.webp';

/* Step 3 - Element Variables */
const nameElement = document.getElementById('name');
// const foodElement = document.getElementById('food');
let yearElement = document.querySelector('#year');
const imageElement = document.querySelector('main img');

/* Step 4 - Adding Content */
nameElement.innerHTML = `<strong>${fullName}</strong>`;
yearElement.textContent = currentYear;
imageElement.setAttribute('src', profilePicture);
imageElement.setAttribute('alt', `Profile image of ${fullName}`);