var longText = {
    init: function () {
        // Mendapatkan semua elemen dengan class 'long-text'
        var longTextElements = document.querySelectorAll('.long-text');
        // Menambahkan event listener ke setiap elemen
        longTextElements.forEach(function (element) {
            if (!element.classList.contains('long-text-initialized')) {
                element.classList.add('long-text-initialized');
                element.addEventListener('click', function () {
                    this.classList.toggle('long-text-collapsed');
                });
            }
        });
    }
}

var copyText = {
    init: function () {
        // Mendapatkan semua elemen dengan class 'copy'
        var copyElements = document.querySelectorAll('.copy');
        // Menambahkan event listener ke setiap elemen
        copyElements.forEach(function (element) {
            if (!element.classList.contains('copy-initialized')) {
                element.classList.add('copy-initialized');

                // Create the copy icon
                var copyIcon = document.createElement('i');
                copyIcon.classList.add('copy-icon');
                copyIcon.classList.add('bi');
                copyIcon.classList.add('bi-copy');

                // Add the copy icon to the div
                element.appendChild(copyIcon);

                // Add event listener to copy text on click
                copyIcon.addEventListener('click', function (e) {
                    e.stopPropagation(); // Prevents event bubbling to parent div
                    var textToCopy = element.textContent.replace(copyIcon.textContent, '').trim();

                    // Try using the Clipboard API first
                    if (navigator.clipboard && navigator.clipboard.writeText) {
                        navigator.clipboard.writeText(textToCopy).then(() => {
                            copyText.showToast('Text copied to clipboard!', element);
                        }).catch(err => {
                            console.error('Failed to copy text: ', err);
                            copyText.showToast('Failed to copy text.', element);
                        });
                    } else {
                        // Fallback to using a hidden textarea element
                        var textArea = document.createElement('textarea');
                        textArea.value = textToCopy;
                        document.body.appendChild(textArea);
                        textArea.select();
                        try {
                            document.execCommand('copy');
                            copyText.showToast('Text copied to clipboard!', element);
                        } catch (err) {
                            console.error('Failed to copy text: ', err);
                            copyText.showToast('Failed to copy text.', element);
                        }
                        document.body.removeChild(textArea);
                    }
                });
            }
        });
    },

    showToast: function (message, element) {
        // Create toast container if it doesn't exist
        var toastContainer = document.getElementById('toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'toast-container';
            document.body.appendChild(toastContainer);
        }

        // Calculate position of the element
        var rect = element.getBoundingClientRect();
        var top = rect.bottom + window.scrollY;
        var left = rect.left + window.scrollX;

        // Create toast element
        var toastElement = document.createElement('div');
        toastElement.classList.add('toast', 'align-items-center', 'w-auto', 'justify-content-center', 'bg-light-primary', 'border-0', 'position-absolute');
        toastElement.setAttribute('role', 'alert');
        toastElement.setAttribute('aria-live', 'assertive');
        toastElement.setAttribute('aria-atomic', 'true');
        toastElement.style.top = `${top}px`;
        toastElement.style.left = `${left}px`;
        toastElement.innerHTML = `
            <div class="d-flex">
                <div class="toast-body text-primary px-2 py-1 text-center">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        `;

        toastContainer.appendChild(toastElement);

        // Initialize and show the toast
        var toast = new bootstrap.Toast(toastElement, {
            delay: 500,
            autohide: true
        });
        toast.show();

        // Remove the toast after it hides
        toastElement.addEventListener('hidden.bs.toast', function () {
            toastElement.remove();
        });
    }
}

longText.init()
copyText.init()
