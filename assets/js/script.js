// File: assets/js/script.js (PERBAIKAN LENGKAP)

// HealingKuy!.id - Custom JavaScript dengan fungsi real
class HealingKuyApp {
    constructor() {
        this.init();
    }

    init() {
        this.initializeComponents();
        this.setupEventListeners();
        this.setupInterceptors();
        this.checkUserStatus();
    }

    // Check user login status
    checkUserStatus() {
        const body = document.body;
        if (body.classList.contains('logged-in')) {
            this.setupUserSpecificFeatures();
        }
    }

    // Initialize all components
    initializeComponents() {
        this.setupSmoothScroll();
        this.setupFormValidations();
        this.setupToastNotifications();
        this.setupLoadingStates();
        this.setupAutoLogout();
        this.setupPasswordToggle();
        this.setupImageLazyLoading();
        this.setupBackToTop();
        this.setupServiceSelection();
        this.setupDateRestrictions();
    }

    // Setup event listeners
    setupEventListeners() {
        // Global click handler for dynamic elements
        document.addEventListener('click', this.handleGlobalClick.bind(this));
        
        // Form submission handler
        document.addEventListener('submit', this.handleFormSubmit.bind(this));
        
        // Window resize handler
        window.addEventListener('resize', this.debounce(this.handleResize.bind(this), 250));
        
        // Scroll handler
        window.addEventListener('scroll', this.throttle(this.handleScroll.bind(this), 100));
        
        // Handle service selection
        document.addEventListener('DOMContentLoaded', () => {
            this.initializeServiceSelection();
        });
    }

    // Setup user-specific features
    setupUserSpecificFeatures() {
        this.setupReservationManagement();
        this.setupProfileUpdates();
    }

    // Setup service selection for reservation
    setupServiceSelection() {
        // This will be initialized after DOM load
    }

    initializeServiceSelection() {
        const serviceCards = document.querySelectorAll('.service-card');
        if (serviceCards.length > 0) {
            serviceCards.forEach(card => {
                card.addEventListener('click', () => {
                    // Remove selected class from all cards
                    serviceCards.forEach(c => c.classList.remove('selected'));
                    // Add selected class to clicked card
                    card.classList.add('selected');
                    
                    // Update hidden input
                    const serviceId = card.dataset.serviceId;
                    const serviceIdInput = document.getElementById('service_id');
                    if (serviceIdInput) {
                        serviceIdInput.value = serviceId;
                    }
                    
                    // Update summary
                    this.updateReservationSummary();
                });
            });
        }
    }

    // Update reservation summary
    updateReservationSummary() {
        const selectedCard = document.querySelector('.service-card.selected');
        const guestsInput = document.getElementById('guests');
        
        if (selectedCard && guestsInput) {
            const price = parseFloat(selectedCard.dataset.price) || 0;
            const guests = parseInt(guestsInput.value) || 0;
            const total = price * guests;
            
            // Update summary elements
            const serviceSummary = document.getElementById('service-summary');
            const pricePerPerson = document.getElementById('price-per-person');
            const guestCount = document.getElementById('guest-count');
            const totalCost = document.getElementById('total-cost');
            
            if (serviceSummary) {
                serviceSummary.textContent = selectedCard.querySelector('.card-title').textContent;
            }
            if (pricePerPerson) {
                pricePerPerson.textContent = this.formatCurrency(price);
            }
            if (guestCount) {
                guestCount.textContent = guests;
            }
            if (totalCost) {
                totalCost.textContent = this.formatCurrency(total);
            }
        }
    }

    // Setup date restrictions
    setupDateRestrictions() {
        const dateInputs = document.querySelectorAll('input[type="date"]');
        dateInputs.forEach(input => {
            // Set min date to today
            const today = new Date().toISOString().split('T')[0];
            if (!input.min) {
                input.min = today;
            }
        });
    }

    // Setup reservation management
    setupReservationManagement() {
        // Handle reservation status updates
        const statusButtons = document.querySelectorAll('.btn-status-update');
        statusButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const reservationId = button.dataset.reservationId;
                const newStatus = button.dataset.status;
                this.updateReservationStatus(reservationId, newStatus);
            });
        });
    }

    // Update reservation status
    async updateReservationStatus(reservationId, status) {
        try {
            this.showLoading();
            const response = await fetch(`api/update_reservation.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    reservation_id: reservationId,
                    status: status
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.showToast('Status reservasi berhasil diperbarui', 'success');
                // Reload page after 2 seconds
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } else {
                this.showToast('Gagal memperbarui status: ' + result.message, 'error');
            }
        } catch (error) {
            this.showToast('Terjadi kesalahan jaringan', 'error');
            console.error('Error:', error);
        } finally {
            this.hideLoading();
        }
    }

    // Setup profile updates
    setupProfileUpdates() {
        const profileForm = document.getElementById('profileForm');
        if (profileForm) {
            profileForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                await this.updateProfile(profileForm);
            });
        }
    }

    // Update user profile
    async updateProfile(form) {
        try {
            this.showLoading();
            const formData = new FormData(form);
            
            const response = await fetch('api/update_profile.php', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.showToast('Profil berhasil diperbarui', 'success');
            } else {
                this.showToast('Gagal memperbarui profil: ' + result.message, 'error');
            }
        } catch (error) {
            this.showToast('Terjadi kesalahan jaringan', 'error');
            console.error('Error:', error);
        } finally {
            this.hideLoading();
        }
    }

    // ... (rest of the existing functions remain the same with improvements)

    // Enhanced form validation
    validateField(field) {
        const value = field.value.trim();
        const constraints = field.dataset.constraints;
        
        if (!constraints) return true;

        let isValid = true;
        let errorMessage = '';

        // Required validation
        if (constraints.includes('required') && !value) {
            isValid = false;
            errorMessage = field.dataset.requiredMessage || 'Field ini wajib diisi';
        }

        // Email validation
        if (constraints.includes('email') && value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                isValid = false;
                errorMessage = field.dataset.emailMessage || 'Format email tidak valid';
            }
        }

        // Phone validation
        if (constraints.includes('phone') && value) {
            const phoneRegex = /^[0-9+\-\s()]{10,}$/;
            if (!phoneRegex.test(value.replace(/\s/g, ''))) {
                isValid = false;
                errorMessage = field.dataset.phoneMessage || 'Format telepon tidak valid';
            }
        }

        // Date validation (must be future date)
        if (constraints.includes('future_date') && value) {
            const selectedDate = new Date(value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            if (selectedDate < today) {
                isValid = false;
                errorMessage = field.dataset.futureDateMessage || 'Tanggal harus di masa depan';
            }
        }

        // Number validation
        if (constraints.includes('number') && value) {
            if (isNaN(value) || value <= 0) {
                isValid = false;
                errorMessage = field.dataset.numberMessage || 'Harus berupa angka positif';
            }
        }

        if (!isValid) {
            this.showFieldError(field, errorMessage);
        } else {
            this.clearFieldError(field);
            this.showFieldSuccess(field);
        }

        return isValid;
    }

    // Enhanced form submission handler
    handleFormSubmit(e) {
        const form = e.target;
        
        // Skip validation for forms that don't require it
        if (form.classList.contains('no-validate')) {
            return;
        }

        // Add loading state to submit button
        const submitButton = form.querySelector('button[type="submit"]');
        if (submitButton && !form.classList.contains('no-loading')) {
            const originalText = submitButton.innerHTML;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
            submitButton.disabled = true;

            // Restore button state after form submission
            setTimeout(() => {
                if (submitButton) {
                    submitButton.innerHTML = originalText;
                    submitButton.disabled = false;
                }
            }, 5000);
        }

        // Special handling for specific forms
        if (form.id === 'contactForm') {
            this.handleContactForm(form, e);
        }
    }

    // Handle contact form specifically
    handleContactForm(form, e) {
        // Additional validation for contact form
        const email = form.querySelector('#contact_email');
        if (email && !this.validateField(email)) {
            e.preventDefault();
            this.showToast('Harap perbaiki error pada form', 'warning');
        }
    }

    // Enhanced auto-logout with warnings
    setupAutoLogout() {
        let timeout;
        let warningTimeout;
        const logoutTime = 25 * 60 * 1000; // 25 minutes
        const warningTime = 5 * 60 * 1000; // 5 minutes before logout
        
        const resetTimer = () => {
            clearTimeout(timeout);
            clearTimeout(warningTimeout);
            
            // Set warning timeout
            warningTimeout = setTimeout(() => {
                this.showToast('Session akan berakhir dalam 5 menit. Silakan simpan pekerjaan Anda.', 'warning', 10000);
            }, logoutTime - warningTime);
            
            // Set logout timeout
            timeout = setTimeout(() => {
                if (this.isUserLoggedIn()) {
                    this.showToast('Session berakhir karena tidak ada aktivitas', 'warning');
                    setTimeout(() => {
                        window.location.href = 'logout.php';
                    }, 3000);
                }
            }, logoutTime);
        };

        // Reset timer on user activity
        ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart', 'click'].forEach(event => {
            document.addEventListener(event, resetTimer, false);
        });

        resetTimer();
    }

    // Utility: Format currency with improved formatting
    formatCurrency(amount) {
        if (isNaN(amount)) return 'Rp 0';
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(amount);
    }

    // Utility: Format date with Indonesian locale
    formatDate(date, format = 'id-ID') {
        const options = {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            weekday: 'long'
        };
        return new Intl.DateTimeFormat(format, options).format(new Date(date));
    }

    // Utility: Generate booking code
    generateBookingCode() {
        const timestamp = Date.now().toString(36).toUpperCase();
        const random = Math.random().toString(36).substr(2, 5).toUpperCase();
        return `HK${timestamp}${random}`;
    }

    // Utility: Calculate refund amount based on cancellation policy
    calculateRefund(totalAmount, daysBefore) {
        if (daysBefore > 7) {
            return totalAmount * 0.9; // 90% refund minus 10% admin fee
        } else if (daysBefore >= 3) {
            return totalAmount * 0.5; // 50% refund
        } else {
            return 0; // No refund
        }
    }
}

// Initialize the application when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.healingKuyApp = new HealingKuyApp();
    
    // Add loaded class to body for CSS animations
    document.body.classList.add('loaded');
    
    // Initialize Bootstrap components
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });

    // Initialize any third-party plugins if needed
    if (typeof Chart !== 'undefined') {
        // Initialize charts if Chart.js is available
        window.healingKuyApp.initializeCharts();
    }
});

// Make app globally available
if (typeof window !== 'undefined') {
    window.HealingKuyApp = HealingKuyApp;
}