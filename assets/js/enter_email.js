document.addEventListener('DOMContentLoaded', () => {
  const email_field = document.querySelector('input[name="email_recover_password"]');
  const login_btn = document.querySelector('button[name="submit_recover_password"]');

  function toggle_button() {
    login_btn.disabled = !email_field.value.trim();
  }

  email_field.addEventListener('input', toggle_button);
});