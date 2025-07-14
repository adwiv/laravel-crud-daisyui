/// crud-edit.js 2.0.0

/**
 * CRUD Form Protection Script
 * Monitors form changes and provides confirmation dialogs for unsaved changes
 * Requires: SweetAlert2
 */

(function () {
  'use strict';

  // Get the main form
  const form = document.querySelector('form#crud-edit');

  let isResetting = false;
  let isNavigatingAway = false;

  function isFormModified() {
    if (!form) return false;
    const inputs = form.querySelectorAll('input, textarea, select');
    return Array.from(inputs).some(input => {
      if (input.type === 'checkbox' || input.type === 'radio') {
        return input.checked !== input.defaultChecked;
      }
      if (input.tagName === 'SELECT') {
        const options = Array.from(input.options);
        return options.some(option => option.selected !== option.defaultSelected);
      }
      return input.value !== input.defaultValue;
    });
  }

  // Handle reset button click
  function handleFormReset(event) {
    if (isResetting) return;

    if (isFormModified()) {
      event.preventDefault();

      Swal.fire(confirmReset).then((result) => {
        if (result.isConfirmed) {
          isResetting = true;
          form.reset();
          isResetting = false;
        }
      });
    }
  }

  // Handle link clicks (navigating away)
  function handleLinkClick(event) {
    const link = event.target.closest('a');
    if (!link) return;

    // Skip if link opens in new window/tab
    if (link.target || link.getAttribute('rel') === 'noopener') {
      return;
    }

    // Skip if it's the back button or other specific links you want to ignore
    // You can customize this logic based on your needs
    const href = link.getAttribute('href');
    if (!href || href === '#' || href.startsWith('javascript:')) {
      return;
    }

    // Skip if form is not modified or if we're submitting
    if (!isFormModified() || isNavigatingAway) {
      return;
    }

    event.preventDefault();

    Swal.fire(confirmLeave).then((result) => {
      if (result.isConfirmed) {
        isNavigatingAway = true;
        window.location.href = href;
      }
    });
  }

  // Handle browser back/forward/close
  function handleBeforeUnload(event) {
    if (isFormModified() && !isNavigatingAway) {
      event.preventDefault();
      // Modern browsers ignore custom messages, but we still need to set returnValue
      event.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
      return event.returnValue;
    }
  }

  // Handle form submission
  function handleFormSubmit(event) {
    isNavigatingAway = true;
    setTimeout(() => {
      // If the form submission was prevented by another handler, reset the submitting flag
      if (event.defaultPrevented) {
        isNavigatingAway = false;
      }
    }, 0);
  }

  // Initialize the script
  function init() {
    if (!form) {
      return;
    }

    form.addEventListener('reset', handleFormReset);
    form.addEventListener('submit', handleFormSubmit);
    document.addEventListener('click', handleLinkClick);
    window.addEventListener('beforeunload', handleBeforeUnload);
  }

  // Initialize when DOM is ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

  // Cleanup function (optional - for SPA scenarios)
  window.crudFormProtectionCleanup = function () {
    form.removeEventListener('submit', handleFormSubmit);
    document.removeEventListener('click', handleLinkClick);
    window.removeEventListener('beforeunload', handleBeforeUnload);
    console.log('CRUD Form Protection cleaned up');
  };

  const confirmReset = {
    title: 'Reset Form?',
    text: 'Are you sure you want to reset the form? All unsaved changes will be lost.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Yes, reset it!',
    cancelButtonText: 'Cancel'
  };

  const confirmLeave = {
    title: 'Unsaved Changes',
    text: 'You have unsaved changes. Are you sure you want to leave this page?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Yes, leave page',
    cancelButtonText: 'Stay on page'
  };

})();