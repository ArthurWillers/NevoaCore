<?php
  if (isset($_SESSION['message'])) {
    $toast_class = isset($_SESSION['message_type']) && $_SESSION['message_type'] == 'success' ? 'text-bg-success' : 'text-bg-danger';
    echo '<div class="toast-container top-0 start-50 translate-middle-x mt-2">
              <div id="toastMessage" class="toast align-items-center ' . $toast_class . ' border-0 w-auto" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                  <div class="toast-body">' . $_SESSION['message'] . '</div>
                  <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
              </div>
            </div>';
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
  }
?>