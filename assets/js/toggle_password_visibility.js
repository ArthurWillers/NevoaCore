function toggle_password_visibility(inputID, button) {
  const input = document.getElementById(inputID);
  if (input.type === "password") {
      input.type = "text";
      button.innerHTML = '<i class="bi bi-eye"></i>';
  } else {
      input.type = "password";
      button.innerHTML = '<i class="bi bi-eye-slash"></i>';
  }
}