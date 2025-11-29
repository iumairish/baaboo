/**
 * Support Ticket System - Main JavaScript
 * 
 * This file contains all custom JavaScript functionality
 * for the support ticket system.
 */

// Immediately Invoked Function Expression to avoid global scope pollution
(function() {
    'use strict';

    /**
     * Initialize application when DOM is ready
     */
    document.addEventListener('DOMContentLoaded', function() {
        initializeAlerts();
        initializeFormValidation();
        initializeTooltips();
        initializeConfirmDialogs();
    });

    /**
     * Auto-dismiss alerts after 5 seconds
     */
    function initializeAlerts() {
        const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
        
        alerts.forEach(function(alert) {
            setTimeout(function() {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000); // 5 seconds
        });
    }

    /**
     * Real-time form validation
     */
    function initializeFormValidation() {
        const forms = document.querySelectorAll('.needs-validation');
        
        Array.from(forms).forEach(function(form) {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                
                form.classList.add('was-validated');
            }, false);
        });

        // Email validation
        const emailInputs = document.querySelectorAll('input[type="email"]');
        emailInputs.forEach(function(input) {
            input.addEventListener('blur', function() {
                validateEmail(input);
            });
        });

        // Description character counter
        const descriptionField = document.getElementById('description');
        if (descriptionField) {
            const minLength = 10;
            const maxLength = 5000;
            
            // Create counter element
            const counter = document.createElement('small');
            counter.className = 'form-text text-muted';
            descriptionField.parentNode.appendChild(counter);
            
            descriptionField.addEventListener('input', function() {
                const length = this.value.length;
                counter.textContent = `${length} / ${maxLength} characters`;
                
                if (length < minLength) {
                    counter.classList.add('text-danger');
                    counter.classList.remove('text-success');
                } else {
                    counter.classList.add('text-success');
                    counter.classList.remove('text-danger');
                }
            });
            
            // Trigger on page load
            descriptionField.dispatchEvent(new Event('input'));
        }
    }

    /**
     * Validate email format
     */
    function validateEmail(input) {
        const email = input.value;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (email && !emailRegex.test(email)) {
            input.classList.add('is-invalid');
            
            // Add error message if it doesn't exist
            if (!input.nextElementSibling || !input.nextElementSibling.classList.contains('invalid-feedback')) {
                const feedback = document.createElement('div');
                feedback.className = 'invalid-feedback';
                feedback.textContent = 'Please enter a valid email address.';
                input.parentNode.appendChild(feedback);
            }
        } else {
            input.classList.remove('is-invalid');
        }
    }

    /**
     * Initialize Bootstrap tooltips
     */
    function initializeTooltips() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }

    /**
     * Confirmation dialogs for destructive actions
     */
    function initializeConfirmDialogs() {
        const confirmButtons = document.querySelectorAll('[data-confirm]');
        
        confirmButtons.forEach(function(button) {
            button.addEventListener('click', function(event) {
                const message = this.getAttribute('data-confirm');
                
                if (!confirm(message)) {
                    event.preventDefault();
                    event.stopPropagation();
                    return false;
                }
            });
        });
    }

    /**
     * Copy to clipboard functionality
     */
    window.copyToClipboard = function(text) {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(text).then(function() {
                showToast('Copied to clipboard!', 'success');
            }).catch(function(err) {
                console.error('Failed to copy:', err);
                showToast('Failed to copy to clipboard', 'error');
            });
        } else {
            // Fallback for older browsers
            const textarea = document.createElement('textarea');
            textarea.value = text;
            textarea.style.position = 'fixed';
            textarea.style.opacity = '0';
            document.body.appendChild(textarea);
            textarea.select();
            
            try {
                document.execCommand('copy');
                showToast('Copied to clipboard!', 'success');
            } catch (err) {
                console.error('Failed to copy:', err);
                showToast('Failed to copy to clipboard', 'error');
            }
            
            document.body.removeChild(textarea);
        }
    };

    /**
     * Show toast notification
     */
    function showToast(message, type = 'info') {
        // Create toast container if it doesn't exist
        let toastContainer = document.querySelector('.toast-container');
        
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
            document.body.appendChild(toastContainer);
        }

        // Create toast element
        const toastEl = document.createElement('div');
        toastEl.className = `toast align-items-center text-white bg-${type} border-0`;
        toastEl.setAttribute('role', 'alert');
        toastEl.setAttribute('aria-live', 'assertive');
        toastEl.setAttribute('aria-atomic', 'true');
        
        toastEl.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        
        toastContainer.appendChild(toastEl);
        
        const toast = new bootstrap.Toast(toastEl, {
            autohide: true,
            delay: 3000
        });
        
        toast.show();
        
        // Remove toast element after it's hidden
        toastEl.addEventListener('hidden.bs.toast', function() {
            toastEl.remove();
        });
    }

    /**
     * Print ticket functionality
     */
    window.printTicket = function() {
        window.print();
    };

    /**
     * Back to top button
     */
    function initializeBackToTop() {
        // Create back to top button
        const backToTopButton = document.createElement('button');
        backToTopButton.innerHTML = '<i class="fas fa-arrow-up"></i>';
        backToTopButton.className = 'btn btn-primary btn-back-to-top';
        backToTopButton.setAttribute('aria-label', 'Back to top');
        backToTopButton.style.cssText = `
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: none;
            z-index: 1000;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            padding: 0;
        `;
        
        document.body.appendChild(backToTopButton);
        
        // Show/hide button based on scroll position
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTopButton.style.display = 'block';
            } else {
                backToTopButton.style.display = 'none';
            }
        });
        
        // Scroll to top on click
        backToTopButton.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    // Initialize back to top button
    initializeBackToTop();

    /**
     * Export functions to global scope if needed
     */
    window.TicketSystem = {
        showToast: showToast,
        copyToClipboard: window.copyToClipboard,
        printTicket: window.printTicket
    };

})();