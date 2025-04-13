document.addEventListener('DOMContentLoaded', () => {
  const emailField = document.querySelector('input[name="email_recover_password"]');
  const loginBtn = document.querySelector('button[name="submit_recover_password"]');

  function toggleButton() {
    loginBtn.disabled = !emailField.value.trim();
  }

  emailField.addEventListener('input', toggleButton);
});