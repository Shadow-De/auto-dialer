document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    const usernameInput = document.querySelector('input[name="username"]');
    const passwordInput = document.querySelector('input[name="password"]');

    form.addEventListener('submit', function (event) {
        if (usernameInput.value === '' || passwordInput.value === '') {
            event.preventDefault(); // Prevent form submission
            alert('Please fill in both username and password.');
        }
    });
});
