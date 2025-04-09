document.addEventListener('DOMContentLoaded', function () {
  const toastElement = document.getElementById('toastMessage');
  if (toastElement) {
    const toast = new bootstrap.Toast(toastElement);
    toast.show();
  }
});