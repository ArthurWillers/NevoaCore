document.addEventListener('DOMContentLoaded', () => {
  const user_field = document.querySelector('input[name="username_register"]');
  const email_field = document.querySelector('input[name="email"]');
  const password_field = document.querySelector('input[name="password_register"]');
  const confirm_field = document.querySelector('input[name="confirm_password_register"]');
  const register_btn = document.querySelector('button[name="submit_register"]');
  const error_message = document.getElementById('password_error_message');

  function validate_form() {
    const user_filled = user_field.value.trim() !== '';
    const email_filled = email_field.value.trim() !== '';
    const pass_filled = password_field.value.trim() !== '';
    const confirm_filled = confirm_field.value.trim() !== '';
    const all_filled = user_filled && email_filled && pass_filled && confirm_filled;
    const passwords_match = password_field.value === confirm_field.value;
    
    error_message.textContent = passwords_match ? '' : 'As senhas não coincidem.';
    register_btn.disabled = !(all_filled && passwords_match);
  }

  [user_field, email_field, password_field, confirm_field].forEach(field => {
    field.addEventListener('input', validate_form);
  });
});