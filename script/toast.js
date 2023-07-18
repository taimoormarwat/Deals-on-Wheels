function showToast(message, position = 'top-right', duration = 3000, style = 'default') {
    // Create a unique ID for the toast element
    const toastId = `toast-${Date.now()}`;
  
    // Map the position to the corresponding CSS classes
    const positionClasses = {
      'top-right': 'toast-top-right',
      'top-left': 'toast-top-left',
      'bottom-right': 'toast-bottom-right',
      'bottom-left': 'toast-bottom-left',
    };
  
    // Map the style to the corresponding CSS classes
    const styleClasses = {
      default: '',
      success: 'bg-success',
      error: 'bg-danger',
      info: 'bg-info',
    };
  
    // Create the toast element
    const toast = `
      <div id="${toastId}" class="toast ${positionClasses[position]} ${styleClasses[style]}" role="alert" aria-live="assertive" aria-atomic="true" data-autohide="true" data-delay="${duration}">
        <div class="toast-body">
          ${message}
        </div>
      </div>
    `;
  
    // Append the toast to the toast container
    $('#toastContainer').append(toast);
  
    // Show the toast
    $(`#${toastId}`).toast('show');
  
    // Remove the toast after it is hidden
    $(`#${toastId}`).on('hidden.bs.toast', function () {
      $(this).remove();
    });
  }
  