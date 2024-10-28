document.querySelectorAll('.toggle-btn').forEach(button => {
    button.addEventListener('click', function () {
        // Find the closest parent `.class-description` and then toggle the associated `.course-info`
        const courseInfo = this.nextElementSibling; // `.course-info` is the next sibling of the button
        if (courseInfo.style.display === 'none') {
            courseInfo.style.display = 'block';
            this.textContent = '-'; // Change button text to '-' when expanded
        } else {
            courseInfo.style.display = 'none';
            this.textContent = '+'; // Change button text to '+' when collapsed
        }
    });
});
