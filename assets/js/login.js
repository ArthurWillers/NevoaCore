document.addEventListener('DOMContentLoaded', () => {
  const email_field = document.querySelector('input[name="email_login"]');
  const password_field = document.querySelector('input[name="password_login"]');
  const login_btn = document.querySelector('button[name="submit_login"]');

  function toggle_button() {
    login_btn.disabled = !email_field.value.trim() || !password_field.value.trim();
  }

  email_field.addEventListener('input', toggle_button);
  password_field.addEventListener('input', toggle_button);
});