document.addEventListener('DOMContentLoaded', () => {
  const code_field = document.querySelector('input[name="verification_code"]');
  const new_password_field = document.getElementById('new_password');
  const confirm_password_field = document.getElementById('confirm_new_password');
  const reset_btn = document.querySelector('button[name="submit_recover_password"]');
  const error_message = document.getElementById('password_error_message');

  function validate_form() {
    const code_filled = code_field.value.trim().length === 8;
    const new_pass_filled = new_password_field.value.trim() !== '';
    const confirm_pass_filled = confirm_password_field.value.trim() !== '';
    const all_filled = code_filled && new_pass_filled && confirm_pass_filled;
    const passwords_match = new_password_field.value === confirm_password_field.value;
    
    error_message.textContent = passwords_match ? '' : 'As senhas não coincidem.';
    reset_btn.disabled = !(all_filled && passwords_match);
  }

  code_field.addEventListener('input', validate_form);
  new_password_field.addEventListener('input', validate_form);
  confirm_password_field.addEventListener('input', validate_form);
});