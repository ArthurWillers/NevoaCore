document.addEventListener('DOMContentLoaded', () => {
  const confirm_email_input = document.getElementById('delete_confirm_email');
  const delete_btn = document.getElementById('delete_account_btn');
  const email_feedback = document.getElementById('email_feedback');
  const user_email = "<?php echo $_SESSION['user_email'] ?? ''; ?>";

  confirm_email_input.addEventListener('input', () => {
    if (confirm_email_input.value === user_email) {
      delete_btn.disabled = false;
      email_feedback.classList.add('d-none');
    } else {
      delete_btn.disabled = true;
      if (confirm_email_input.value.trim() !== '') {
        email_feedback.classList.remove('d-none');
      } else {
        email_feedback.classList.add('d-none');
      }
    }
  });
});