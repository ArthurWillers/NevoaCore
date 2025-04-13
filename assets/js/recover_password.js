document.addEventListener('DOMContentLoaded', () => {
  const codeField = document.querySelector('input[name="verification_code"]');
  const newPasswordField = document.getElementById('new_password');
  const confirmPasswordField = document.getElementById('confirm_new_password');
  const resetBtn = document.querySelector('button[name="submit_reset_password"]');
  const errorMessage = document.getElementById('password_error_message');

  function validateForm() {
    const codeFilled = codeField.value.trim().length === 8;
    const newPassFilled = newPasswordField.value.trim() !== '';
    const confirmPassFilled = confirmPasswordField.value.trim() !== '';
    const allFilled = codeFilled && newPassFilled && confirmPassFilled;
    const passwordsMatch = newPasswordField.value === confirmPasswordField.value;
    
    errorMessage.textContent = passwordsMatch ? '' : 'As senhas não coincidem.';
    resetBtn.disabled = !(allFilled && passwordsMatch);
  }

  codeField.addEventListener('input', validateForm);
  newPasswordField.addEventListener('input', validateForm);
  confirmPasswordField.addEventListener('input', validateForm);
});