/**
 * UI Configuration JavaScript
 * Handles form submission, file uploads, color picker, and real-time preview
 */

class UIConfig {
    constructor() {
        this.form = document.getElementById('ui-config-form');
        this.submitBtn = document.getElementById('submit-btn');
        this.cancelBtn = document.getElementById('cancel-btn');
        this.colorInput = document.getElementById('primary-color');
        this.colorPreview = document.getElementById('color-preview');
        this.logoUpload = document.getElementById('logo-upload');
        this.faviconUpload = document.getElementById('favicon-upload');
        this.logoPreview = document.getElementById('logo-preview');
        this.faviconPreview = document.getElementById('favicon-preview');
        
        this.init();
    }

    init() {
        this.bindEvents();
        this.loadCurrentSettings();
        this.initColorPicker();
    }

    bindEvents() {
        // Form submission
        if (this.form) {
            this.form.addEventListener('submit', (e) => this.handleSubmit(e));
        }

        // Cancel button
        if (this.cancelBtn) {
            this.cancelBtn.addEventListener('click', () => this.handleCancel());
        }

        // Color picker
        if (this.colorInput) {
            this.colorInput.addEventListener('input', (e) => this.handleColorChange(e));
            this.colorInput.addEventListener('change', (e) => this.handleColorChange(e));
        }

        // File uploads
        if (this.logoUpload) {
            this.logoUpload.addEventListener('change', (e) => this.handleFileUpload(e, 'logo'));
        }

        if (this.faviconUpload) {
            this.faviconUpload.addEventListener('change', (e) => this.handleFileUpload(e, 'favicon'));
        }

        // Toggle switches
        document.querySelectorAll('.toggle-checkbox').forEach(toggle => {
            toggle.addEventListener('change', (e) => this.handleToggleChange(e));
        });

        // Color change button
        const colorChangeBtn = document.getElementById('color-change-btn');
        if (colorChangeBtn) {
            colorChangeBtn.addEventListener('click', () => this.openColorPicker());
        }
    }

    async loadCurrentSettings() {
        try {
            const response = await fetch('/admin/ui-configuration/settings', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const data = await response.json();

            if (data.success) {
                this.populateForm(data.data);
            }
        } catch (error) {
            console.error('Error loading settings:', error);
        }
    }

    populateForm(settings) {
        // Site name
        const siteNameInput = document.getElementById('site-name');
        if (siteNameInput && settings.site_name) {
            siteNameInput.value = settings.site_name;
        }

        // Primary color
        if (this.colorInput && settings.primary_color) {
            this.colorInput.value = settings.primary_color;
            this.updateColorPreview(settings.primary_color);
        }

        // Toggles
        const emailToggle = document.getElementById('toggle-email');
        if (emailToggle) {
            emailToggle.checked = settings.email_notifications;
        }

        const browserToggle = document.getElementById('toggle-browser');
        if (browserToggle) {
            browserToggle.checked = settings.browser_notifications;
        }

        // File previews
        if (settings.logo_url) {
            this.showImagePreview('logo', settings.logo_url);
        }

        if (settings.favicon_url) {
            this.showImagePreview('favicon', settings.favicon_url);
        }
    }

    initColorPicker() {
        // Create color picker input if not exists
        if (!document.getElementById('color-picker-input')) {
            const colorPicker = document.createElement('input');
            colorPicker.type = 'color';
            colorPicker.id = 'color-picker-input';
            colorPicker.style.display = 'none';
            document.body.appendChild(colorPicker);

            colorPicker.addEventListener('change', (e) => {
                this.colorInput.value = e.target.value;
                this.handleColorChange(e);
            });
        }
    }

    openColorPicker() {
        const colorPicker = document.getElementById('color-picker-input');
        if (colorPicker) {
            colorPicker.value = this.colorInput.value;
            colorPicker.click();
        }
    }

    handleColorChange(e) {
        const color = e.target.value;
        this.updateColorPreview(color);
        this.applyColorPreview(color);
    }

    updateColorPreview(color) {
        if (this.colorPreview) {
            this.colorPreview.style.backgroundColor = color;
        }

        // Update color display text
        const colorText = document.getElementById('color-text');
        if (colorText) {
            colorText.textContent = color.toUpperCase();
        }
    }

    applyColorPreview(color) {
        // Apply color to primary elements for real-time preview
        document.documentElement.style.setProperty('--color-primary', color);
        
        // Update specific elements
        document.querySelectorAll('.bg-primary, .text-primary, .border-primary').forEach(el => {
            if (el.classList.contains('bg-primary')) {
                el.style.backgroundColor = color;
            }
            if (el.classList.contains('text-primary')) {
                el.style.color = color;
            }
            if (el.classList.contains('border-primary')) {
                el.style.borderColor = color;
            }
        });
    }

    handleFileUpload(e, type) {
        const file = e.target.files[0];
        if (!file) return;

        // Validate file
        const validation = this.validateFile(file, type);
        if (!validation.valid) {
            this.showNotification(validation.message, 'error');
            e.target.value = '';
            return;
        }

        // Show preview
        const reader = new FileReader();
        reader.onload = (e) => {
            this.showImagePreview(type, e.target.result);
        };
        reader.readAsDataURL(file);
    }

    validateFile(file, type) {
        const maxSizes = {
            logo: 2 * 1024 * 1024, // 2MB
            favicon: 512 * 1024     // 512KB
        };

        const allowedTypes = {
            logo: ['image/jpeg', 'image/png', 'image/jpg', 'image/svg+xml'],
            favicon: ['image/png', 'image/x-icon', 'image/vnd.microsoft.icon']
        };

        if (file.size > maxSizes[type]) {
            return {
                valid: false,
                message: `${type === 'logo' ? 'Logo' : 'Favicon'} không được vượt quá ${type === 'logo' ? '2MB' : '512KB'}`
            };
        }

        if (!allowedTypes[type].includes(file.type)) {
            return {
                valid: false,
                message: `${type === 'logo' ? 'Logo' : 'Favicon'} phải có định dạng hợp lệ`
            };
        }

        return { valid: true };
    }

    showImagePreview(type, src) {
        const container = document.getElementById(`${type}-preview-container`);
        if (!container) return;

        container.innerHTML = `
            <div class="relative group">
                <img src="${src}" alt="${type}" class="w-full h-full object-contain rounded-lg">
                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center">
                    <button type="button" onclick="uiConfig.removeFile('${type}')" class="text-white hover:text-red-300 transition-colors">
                        <span class="material-icons-round">delete</span>
                    </button>
                </div>
            </div>
        `;
    }

    async removeFile(type) {
        if (!confirm(`Bạn có chắc muốn xóa ${type === 'logo' ? 'logo' : 'favicon'}?`)) {
            return;
        }

        try {
            // Get CSRF token from meta tag
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            
            const response = await fetch('/admin/ui-configuration/file', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ type })
            });

            const data = await response.json();

            if (data.success) {
                // Clear preview
                const container = document.getElementById(`${type}-preview-container`);
                if (container) {
                    container.innerHTML = '';
                }

                // Clear input
                const input = document.getElementById(`${type}-upload`);
                if (input) {
                    input.value = '';
                }

                this.showNotification(data.message, 'success');
            } else {
                this.showNotification(data.message, 'error');
            }
        } catch (error) {
            console.error('Delete error:', error);
            this.showNotification('Có lỗi xảy ra khi xóa file', 'error');
        }
    }

    handleToggleChange(e) {
        // Add visual feedback for toggle changes
        const toggle = e.target;
        toggle.parentElement.classList.add('scale-95');
        setTimeout(() => {
            toggle.parentElement.classList.remove('scale-95');
        }, 150);
    }

    async handleSubmit(e) {
        e.preventDefault();

        if (this.submitBtn) {
            this.submitBtn.disabled = true;
            this.submitBtn.innerHTML = `
                <span class="material-icons-round animate-spin mr-2">refresh</span>
                Đang lưu...
            `;
        }

        try {
            const formData = new FormData(this.form);
            
            // Get CSRF token from meta tag
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            
            const response = await fetch('/admin/ui-configuration/update', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();

            if (data.success) {
                this.showNotification(data.message, 'success');
                
                // Update previews if new files uploaded
                if (data.data.logo_url) {
                    this.showImagePreview('logo', data.data.logo_url);
                }
                if (data.data.favicon_url) {
                    this.showImagePreview('favicon', data.data.favicon_url);
                }
            } else {
                this.showNotification(data.message, 'error');
                
                // Show validation errors
                if (data.errors) {
                    Object.keys(data.errors).forEach(field => {
                        this.showFieldError(field, data.errors[field][0]);
                    });
                }
            }
        } catch (error) {
            console.error('Submit error:', error);
            this.showNotification('Có lỗi xảy ra khi lưu cấu hình', 'error');
        } finally {
            if (this.submitBtn) {
                this.submitBtn.disabled = false;
                this.submitBtn.innerHTML = 'Lưu thay đổi';
            }
        }
    }

    handleCancel() {
        if (confirm('Bạn có chắc muốn hủy bỏ các thay đổi?')) {
            this.loadCurrentSettings();
            this.showNotification('Đã hủy bỏ các thay đổi', 'info');
        }
    }

    showFieldError(field, message) {
        const input = document.getElementById(field.replace('_', '-'));
        if (input) {
            // Remove existing error
            const existingError = input.parentElement.querySelector('.field-error');
            if (existingError) {
                existingError.remove();
            }

            // Add error message
            const errorDiv = document.createElement('div');
            errorDiv.className = 'field-error text-red-500 text-xs mt-1';
            errorDiv.textContent = message;
            input.parentElement.appendChild(errorDiv);

            // Add error styling
            input.classList.add('border-red-500');

            // Remove error after 5 seconds
            setTimeout(() => {
                errorDiv.remove();
                input.classList.remove('border-red-500');
            }, 5000);
        }
    }

    showNotification(message, type = 'info') {
        // Remove existing notifications
        document.querySelectorAll('.notification').forEach(n => n.remove());

        const notification = document.createElement('div');
        notification.className = `notification fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm transform transition-all duration-300 translate-x-full`;
        
        const colors = {
            success: 'bg-green-500 text-white',
            error: 'bg-red-500 text-white',
            info: 'bg-blue-500 text-white',
            warning: 'bg-yellow-500 text-black'
        };

        const icons = {
            success: 'check_circle',
            error: 'error',
            info: 'info',
            warning: 'warning'
        };

        notification.className += ` ${colors[type]}`;
        notification.innerHTML = `
            <div class="flex items-center gap-3">
                <span class="material-icons-round">${icons[type]}</span>
                <span class="flex-1">${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-2 hover:opacity-70">
                    <span class="material-icons-round text-sm">close</span>
                </button>
            </div>
        `;

        document.body.appendChild(notification);

        // Animate in
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);

        // Auto remove after 5 seconds
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => notification.remove(), 300);
        }, 5000);
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.uiConfig = new UIConfig();
});