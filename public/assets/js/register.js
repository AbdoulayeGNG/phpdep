document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    });

    // Validation du mot de passe en temps réel
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('password_confirm');
    
    function validatePassword() {
        if (password.value !== confirmPassword.value) {
            confirmPassword.setCustomValidity('Les mots de passe ne correspondent pas');
        } else {
            confirmPassword.setCustomValidity('');
        }
    }

    password.addEventListener('change', validatePassword);
    confirmPassword.addEventListener('keyup', validatePassword);
});