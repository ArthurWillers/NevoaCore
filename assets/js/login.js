document.addEventListener('DOMContentLoaded', () => {
  const emailField = document.querySelector('input[name="email_login"]');
  const passwordField = document.querySelector('input[name="password_login"]');
  const loginBtn = document.querySelector('button[name="submit_login"]');

  function toggleButton() {
    loginBtn.disabled = !emailField.value.trim() || !passwordField.value.trim();
  }

  emailField.addEventListener('input', toggleButton);
  passwordField.addEventListener('input', toggleButton);
});