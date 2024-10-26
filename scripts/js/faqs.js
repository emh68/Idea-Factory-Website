document.querySelectorAll('.question').forEach(question => {
    question.addEventListener('click', () => {
        // Toggle the answer visibility
        const answer = question.nextElementSibling; // Get the associated answer element

        if (answer.style.display === "block") {
            answer.style.display = "none"; // Hide the answer
            question.classList.remove('active'); // Remove active class
        } else {
            answer.style.display = "block"; // Show the answer
            question.classList.add('active'); // Add active class
        }
    });
});
