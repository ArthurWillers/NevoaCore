document.addEventListener('DOMContentLoaded', () => {
  const userField = document.querySelector('input[name="user_name_register"]');
  const emailField = document.querySelector('input[name="email"]');
  const passwordField = document.querySelector('input[name="password_register"]');
  const confirmField = document.querySelector('input[name="confirm_password_register"]');
  const registerBtn = document.querySelector('button[name="submit_register"]');
  const errorMessage = document.getElementById('password_error_message');

  function validateForm() {
    const userFilled = userField.value.trim() !== '';
    const emailFilled = emailField.value.trim() !== '';
    const passFilled = passwordField.value.trim() !== '';
    const confirmFilled = confirmField.value.trim() !== '';
    const allFilled = userFilled && emailFilled && passFilled && confirmFilled;
    const passwordsMatch = passwordField.value === confirmField.value;
    
    errorMessage.textContent = passwordsMatch ? '' : 'As senhas não coincidem.';
    registerBtn.disabled = !(allFilled && passwordsMatch);
  }

  [userField, emailField, passwordField, confirmField].forEach(field => {
    field.addEventListener('input', validateForm);
  });
});