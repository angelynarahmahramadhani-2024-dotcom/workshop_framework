/**
 * ==================== JAVASCRIPT GLOBAL ====================
 * Script yang berlaku untuk semua halaman
 * Berdasarkan template Purple Admin dari Bootstrap Dash
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all components
    initSidebar();
    initDropdown();
    initAlerts();
    initFormValidation();
    initTooltips();
});

/**
 * ==================== SIDEBAR TOGGLE ====================
 * Mengatur toggle sidebar untuk responsive
 */
function initSidebar() {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const contentWrapper = document.querySelector('.content-wrapper');
    const navbar = document.querySelector('.navbar');
    const footer = document.querySelector('.footer');
    
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            sidebar.classList.toggle('show');
            
            if (contentWrapper) contentWrapper.classList.toggle('expanded');
            if (navbar) navbar.classList.toggle('expanded');
            if (footer) footer.classList.toggle('expanded');
        });
    }
    
    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 992) {
            if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                sidebar.classList.remove('show');
            }
        }
    });
}

/**
 * ==================== DROPDOWN ====================
 * Mengatur dropdown menu di header
 */
function initDropdown() {
    const userBtn = document.querySelector('.user-btn');
    const dropdownMenu = document.querySelector('.dropdown-menu');
    
    if (userBtn && dropdownMenu) {
        userBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdownMenu.classList.toggle('show');
        });
        
        document.addEventListener('click', function() {
            dropdownMenu.classList.remove('show');
        });
    }
}

/**
 * ==================== ALERT AUTO DISMISS ====================
 * Otomatis menutup alert setelah beberapa detik
 */
function initAlerts() {
    const alerts = document.querySelectorAll('.alert[data-auto-dismiss]');
    
    alerts.forEach(function(alert) {
        const timeout = parseInt(alert.dataset.autoDismiss) || 5000;
        
        setTimeout(function() {
            alert.style.animation = 'slideOut 0.3s ease';
            setTimeout(function() {
                alert.remove();
            }, 300);
        }, timeout);
    });
}

/**
 * ==================== FORM VALIDATION ====================
 * Validasi form sebelum submit
 */
function initFormValidation() {
    const forms = document.querySelectorAll('form[data-validate]');
    
    forms.forEach(function(form) {
        form.addEventListener('submit', function(e) {
            if (!validateForm(form)) {
                e.preventDefault();
            }
        });
    });
}

/**
 * Validasi form
 * @param {HTMLFormElement} form 
 * @returns {boolean}
 */
function validateForm(form) {
    let isValid = true;
    const inputs = form.querySelectorAll('input[required], textarea[required], select[required]');
    
    // Clear previous errors
    form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
    
    inputs.forEach(function(input) {
        if (!input.value.trim()) {
            isValid = false;
            showFieldError(input, 'Field ini wajib diisi');
        } else if (input.type === 'email' && !isValidEmail(input.value)) {
            isValid = false;
            showFieldError(input, 'Format email tidak valid');
        } else if (input.minLength && input.value.length < input.minLength) {
            isValid = false;
            showFieldError(input, `Minimal ${input.minLength} karakter`);
        }
    });
    
    if (!isValid) {
        showMessage('Mohon lengkapi semua field yang wajib diisi!', 'error');
    }
    
    return isValid;
}

/**
 * Menampilkan error pada field
 * @param {HTMLElement} input 
 * @param {string} message 
 */
function showFieldError(input, message) {
    input.classList.add('is-invalid');
    
    const feedback = document.createElement('div');
    feedback.className = 'invalid-feedback';
    feedback.textContent = message;
    
    input.parentNode.appendChild(feedback);
}

/**
 * Validasi email
 * @param {string} email 
 * @returns {boolean}
 */
function isValidEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

/**
 * ==================== TOOLTIPS ====================
 * Inisialisasi tooltip
 */
function initTooltips() {
    const tooltips = document.querySelectorAll('[data-tooltip]');
    
    tooltips.forEach(function(element) {
        element.addEventListener('mouseenter', function() {
            const tooltip = document.createElement('div');
            tooltip.className = 'tooltip';
            tooltip.textContent = element.dataset.tooltip;
            tooltip.style.cssText = `
                position: absolute;
                background: #343a40;
                color: white;
                padding: 5px 10px;
                border-radius: 5px;
                font-size: 12px;
                z-index: 9999;
            `;
            document.body.appendChild(tooltip);
            
            const rect = element.getBoundingClientRect();
            tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
            tooltip.style.top = rect.top - tooltip.offsetHeight - 10 + 'px';
            
            element._tooltip = tooltip;
        });
        
        element.addEventListener('mouseleave', function() {
            if (element._tooltip) {
                element._tooltip.remove();
                delete element._tooltip;
            }
        });
    });
}

/**
 * ==================== TOAST MESSAGE ====================
 * Menampilkan pesan notifikasi
 * @param {string} message 
 * @param {string} type - 'success', 'error', 'warning', 'info'
 */
function showMessage(message, type = 'success') {
    const colors = {
        success: '#28a745',
        error: '#dc3545',
        warning: '#ffc107',
        info: '#17a2b8'
    };
    
    const icons = {
        success: '✓',
        error: '✕',
        warning: '⚠',
        info: 'ℹ'
    };
    
    const toast = document.createElement('div');
    toast.className = 'toast-message';
    toast.innerHTML = `
        <span class="toast-icon">${icons[type]}</span>
        <span class="toast-text">${message}</span>
    `;
    toast.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        padding: 15px 25px;
        background: ${colors[type]};
        color: white;
        border-radius: 10px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        z-index: 9999;
        display: flex;
        align-items: center;
        gap: 10px;
        animation: slideIn 0.3s ease;
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(function() {
        toast.style.animation = 'slideOut 0.3s ease';
        setTimeout(function() {
            toast.remove();
        }, 300);
    }, 3000);
}

/**
 * ==================== CONFIRM DELETE ====================
 * Menampilkan konfirmasi sebelum hapus
 * @param {string} message 
 * @returns {boolean}
 */
function confirmDelete(message = 'Apakah Anda yakin ingin menghapus data ini?') {
    return confirm(message);
}

/**
 * ==================== LOADING STATE ====================
 * Menampilkan loading pada button
 * @param {HTMLButtonElement} button 
 * @param {boolean} loading 
 */
function setLoading(button, loading = true) {
    if (loading) {
        button.disabled = true;
        button.dataset.originalText = button.innerHTML;
        button.innerHTML = '<span class="spinner"></span> Loading...';
    } else {
        button.disabled = false;
        button.innerHTML = button.dataset.originalText;
    }
}

/**
 * ==================== FORMAT CURRENCY ====================
 * Format angka ke format mata uang Indonesia
 * @param {number} amount 
 * @returns {string}
 */
function formatCurrency(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(amount);
}

/**
 * ==================== FORMAT DATE ====================
 * Format tanggal ke format Indonesia
 * @param {string|Date} date 
 * @returns {string}
 */
function formatDate(date) {
    return new Intl.DateTimeFormat('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    }).format(new Date(date));
}

/**
 * ==================== DEBOUNCE ====================
 * Membatasi eksekusi function
 * @param {Function} func 
 * @param {number} wait 
 * @returns {Function}
 */
function debounce(func, wait = 300) {
    let timeout;
    return function executedFunction(...args) {
        const later = function() {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

/**
 * ==================== AJAX HELPER ====================
 * Helper untuk request AJAX
 * @param {string} url 
 * @param {object} options 
 * @returns {Promise}
 */
async function ajaxRequest(url, options = {}) {
    const defaults = {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
        }
    };
    
    const config = { ...defaults, ...options };
    
    try {
        const response = await fetch(url, config);
        const data = await response.json();
        
        if (!response.ok) {
            throw new Error(data.message || 'Terjadi kesalahan');
        }
        
        return data;
    } catch (error) {
        showMessage(error.message, 'error');
        throw error;
    }
}

// Export functions for use in other scripts
window.showMessage = showMessage;
window.confirmDelete = confirmDelete;
window.setLoading = setLoading;
window.validateForm = validateForm;
window.formatCurrency = formatCurrency;
window.formatDate = formatDate;
window.debounce = debounce;
window.ajaxRequest = ajaxRequest;
