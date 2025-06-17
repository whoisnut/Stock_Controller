@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-lg-5 col-md-7">
            <!-- Login Card -->
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-transparent border-0 text-center py-4">
                    <div class="mb-3">
                        <i class="bi bi-box-arrow-in-right text-primary" style="font-size: 3rem;"></i>
                    </div>
                    <h3 class="mb-0 text-gray-800">ចូលប្រព័ន្ធ</h3>
                    <p class="text-muted mb-0">ចូលទៅកាន់ប្រព័ន្ធគ្រប់គ្រងស្តុកទំនិញ</p>
                </div>
                
                <div class="card-body px-4 pb-4">
                    <!-- Welcome Message for Returning User -->
                    @if(session('welcome_back'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-person-check me-2"></i>
                            <strong>សូមស្វាគមន៍មកវិញ!</strong> {{ session('welcome_back') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Demo User Info -->
                    <div class="alert alert-info border-0 mb-4">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-info-circle me-2"></i>
                            <div>
                                <strong>សម្រាប់ការសាកល្បង:</strong>
                                <br><small>អ៊ីម៉ែល: demo@stockcontroller.com</small>
                                <br><small>ពាក្យសម្ងាត់: password123</small>
                            </div>
                        </div>
                    </div>
                    
                    <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate>
                        @csrf
                        
                        <!-- Email Field -->
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold">
                                អ៊ីម៉ែល <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-envelope text-muted"></i>
                                </span>
                                <input type="email" 
                                       class="form-control border-start-0 @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', 'demo@stockcontroller.com') }}" 
                                       required
                                       placeholder="បញ្ចូលអ៊ីម៉ែលរបស់អ្នក"
                                       autocomplete="email"
                                       autofocus>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Password Field -->
                        <div class="mb-4">
                            <label for="password" class="form-label fw-semibold">
                                ពាក្យសម្ងាត់ <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-lock text-muted"></i>
                                </span>
                                <input type="password" 
                                       class="form-control border-start-0 @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       required
                                       placeholder="បញ្ចូលពាក្យសម្ងាត់"
                                       autocomplete="current-password">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="bi bi-eye" id="toggleIcon"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Remember Me & Forgot Password -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember" name="remember" 
                                       {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label small" for="remember">
                                    ចងចាំខ្ញុំ
                                </label>
                            </div>
                            <a href="#" class="text-decoration-none small" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal">
                                ភ្លេចពាក្យសម្ងាត់?
                            </a>
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg" id="loginBtn">
                                <i class="bi bi-box-arrow-in-right me-2"></i>ចូលប្រព័ន្ធ
                            </button>
                        </div>
                    </form>
                    
                    <!-- Quick Login Options -->
                    <div class="mb-4">
                        <div class="text-center mb-3">
                            <small class="text-muted">ការចូលរហ័ស</small>
                        </div>
                        <div class="row g-2">
                            <div class="col-6">
                                <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="quickLogin('admin')">
                                    <i class="bi bi-person-gear me-1"></i>Admin
                                </button>
                            </div>
                            <div class="col-6">
                                <button type="button" class="btn btn-outline-success btn-sm w-100" onclick="quickLogin('demo')">
                                    <i class="bi bi-person me-1"></i>Demo
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Divider -->
                    <div class="text-center my-4">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 border-top"></div>
                            <span class="px-3 text-muted small">ឬ</span>
                            <div class="flex-grow-1 border-top"></div>
                        </div>
                    </div>
                    
                    <!-- Register Link -->
                    <div class="text-center">
                        <p class="text-muted mb-0">
                            មិនទាន់មានគណនី? 
                            <a href="{{ route('register') }}" class="text-decoration-none fw-semibold">
                                ចុះឈ្មោះនៅទីនេះ
                            </a>
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- System Info -->
            <div class="text-center mt-4">
                <div class="card border-0 bg-light">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-center align-items-center gap-4 text-muted small">
                            <div>
                                <i class="bi bi-shield-check me-1"></i>
                                ការពារដោយសុវត្ថិភាព
                            </div>
                            <div>
                                <i class="bi bi-clock me-1"></i>
                                17/06/2025 10:43:39
                            </div>
                            <div>
                                <i class="bi bi-server me-1"></i>
                                ស្ថានភាព: <span class="text-success">សកម្ម</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Last Activity Info -->
            <div class="text-center mt-3">
                <div class="alert alert-light border small">
                    <i class="bi bi-info-circle text-muted me-2"></i>
                    <span class="text-muted">
                        អ្នកប្រើចុងក្រោយ: <strong>whoisnut</strong> • 
                        ការចូលចុងក្រោយ: <strong>16/06/2025 15:22:14</strong>
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Side Information Panel (Hidden on mobile) -->
        <div class="col-lg-4 d-none d-lg-block">
            <div class="ps-5">
                <div class="mb-5">
                    <h2 class="text-primary mb-3">
                        <i class="bi bi-box-seam me-2"></i>
                        Stock Controller System
                    </h2>
                    <p class="text-muted lead">
                        ប្រព័ន្ធគ្រប់គ្រងស្តុកទំនិញទំនើបដោយ CHHANNUT
                    </p>
                </div>
                
                <!-- Live Statistics -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-transparent border-0">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-graph-up text-success me-2"></i>
                            ស្ថិតិបច្ចុប្បន្ន
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3 text-center">
                            <div class="col-6">
                                <div class="border rounded p-3">
                                    <h4 class="text-primary mb-1">247</h4>
                                    <small class="text-muted">ទំនិញសរុប</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="border rounded p-3">
                                    <h4 class="text-warning mb-1">23</h4>
                                    <small class="text-muted">ស្តុកតិច</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-3 pt-3 border-top text-center">
                            <div class="text-muted small">
                                <i class="bi bi-arrow-clockwise me-1"></i>
                                ធ្វើបច្ចុប្បន្នភាពចុងក្រោយ: 10:43:39
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Activity -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-0">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-clock-history text-info me-2"></i>
                            សកម្មភាពថ្មីៗ
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle me-3">
                                    <i class="bi bi-plus text-primary small"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="small fw-semibold">បានបន្ថែមទំនិញថ្មី</div>
                                    <div class="text-muted" style="font-size: 0.75rem;">២ នាទីមុន</div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar-sm bg-success bg-opacity-10 rounded-circle me-3">
                                    <i class="bi bi-arrow-up text-success small"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="small fw-semibold">បានធ្វើបច្ចុប្បន្នភាពស្តុក</div>
                                    <div class="text-muted" style="font-size: 0.75rem;">៥ នាទីមុន</div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-warning bg-opacity-10 rounded-circle me-3">
                                    <i class="bi bi-exclamation-triangle text-warning small"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="small fw-semibold">ការជូនដំណឹងស្តុកតិច</div>
                                    <div class="text-muted" style="font-size: 0.75rem;">១០ នាទីមុន</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Forgot Password Modal -->
<div class="modal fade" id="forgotPasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-key me-2"></i>ស្ដារពាក្យសម្ងាត់
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-3">
                    បញ្ចូលអ៊ីម៉ែលរបស់អ្នក ហើយយើងនឹងផ្ញើតំណភ្ជាប់សម្រាប់ស្ដារពាក្យសម្ងាត់។
                </p>
                <form>
                    <div class="mb-3">
                        <label for="resetEmail" class="form-label">អ៊ីម៉ែល</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="bi bi-envelope text-muted"></i>
                            </span>
                            <input type="email" class="form-control" id="resetEmail" placeholder="បញ្ចូលអ៊ីម៉ែលរបស់អ្នក">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    បោះបង់
                </button>
                <button type="button" class="btn btn-primary" onclick="sendResetLink()">
                    <i class="bi bi-send me-1"></i>ផ្ញើតំណ
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Enhanced Form Styling */
.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
}

.input-group-text {
    background-color: #f8f9fa !important;
    border-color: #e9ecef;
}

.form-control.border-start-0:focus {
    border-left: 1px solid #0d6efd;
}

.input-group:focus-within .input-group-text {
    border-color: #0d6efd;
    background-color: rgba(13, 110, 253, 0.05) !important;
}

.input-group:focus-within .input-group-text i {
    color: #0d6efd !important;
}

/* Card Enhancements */
.card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.15) !important;
}

/* Avatar styling */
.avatar-sm {
    width: 2rem;
    height: 2rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Background Opacity */
.bg-opacity-10 {
    --bs-bg-opacity: 0.1;
}

/* Button Enhancements */
.btn {
    transition: all 0.2s ease-in-out;
}

.btn:hover {
    transform: translateY(-1px);
}

/* Khmer Font Support */
body {
    font-family: 'Noto Sans Khmer', 'Khmer OS', sans-serif;
}

.form-label {
    font-weight: 500;
    margin-bottom: 0.5rem;
}

/* Custom spacing for better Khmer text rendering */
.small {
    line-height: 1.6;
}

/* Text colors */
.text-gray-800 {
    color: #374151 !important;
}

/* Validation styling */
.was-validated .form-control:valid,
.form-control.is-valid {
    border-color: #198754;
}

.was-validated .form-control:invalid,
.form-control.is-invalid {
    border-color: #dc3545;
}

/* Minimum height for full screen */
.min-vh-100 {
    min-height: 100vh;
}

/* Loading state */
.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Timeline styling */
.timeline {
    position: relative;
}

/* Modal enhancements */
.modal-content {
    border: none;
    box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
}
</style>

<!-- Enhanced JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const form = document.querySelector('.needs-validation');
    form.addEventListener('submit', function(e) {
        if (!form.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        form.classList.add('was-validated');
        
        // Add loading state
        const submitBtn = document.getElementById('loginBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>កំពុងចូលប្រព័ន្ធ...';
    }, false);
    
    // Password visibility toggle
    const togglePassword = document.getElementById('togglePassword');
    const passwordField = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');
    
    togglePassword.addEventListener('click', function() {
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);
        
        if (type === 'password') {
            toggleIcon.className = 'bi bi-eye';
        } else {
            toggleIcon.className = 'bi bi-eye-slash';
        }
    });
    
    // Quick login function
    window.quickLogin = function(type) {
        const emailField = document.getElementById('email');
        const passwordField = document.getElementById('password');
        
        if (type === 'admin') {
            emailField.value = 'admin@stockcontroller.com';
            passwordField.value = 'admin123';
        } else if (type === 'demo') {
            emailField.value = 'demo@stockcontroller.com';
            passwordField.value = 'password123';
        }
        
        // Focus on login button
        document.getElementById('loginBtn').focus();
    };
    
    // Send reset link function
    window.sendResetLink = function() {
        const email = document.getElementById('resetEmail').value;
        
        if (!email) {
            alert('សូមបញ្ចូលអ៊ីម៉ែលរបស់អ្នក');
            return;
        }
        
        // Simulate sending reset link
        alert('តំណស្ដារពាក្យសម្ងាត់បានផ្ញើទៅ ' + email);
        
        // Close modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('forgotPasswordModal'));
        modal.hide();
    };
    
    // Auto-focus email field
    document.getElementById('email').focus();
    
    // Remember me persistence
    const rememberCheckbox = document.getElementById('remember');
    const emailField = document.getElementById('email');
    
    // Load remembered email
    const rememberedEmail = localStorage.getItem('rememberedEmail');
    if (rememberedEmail && rememberCheckbox.checked) {
        emailField.value = rememberedEmail;
    }
    
    // Save email when remember me is checked
    rememberCheckbox.addEventListener('change', function() {
        if (this.checked) {
            localStorage.setItem('rememberedEmail', emailField.value);
        } else {
            localStorage.removeItem('rememberedEmail');
        }
    });
    
    // Update current time every second
    function updateTime() {
        const timeElements = document.querySelectorAll('.current-time');
        const now = new Date();
        const timeString = now.toLocaleString('en-GB', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false
        }).replace(/(\d{2})\/(\d{2})\/(\d{4}), /, '$3-$2-$1 ');
        
        timeElements.forEach(element => {
            element.textContent = timeString;
        });
    }
    
    // Update time immediately and then every second
    updateTime();
    setInterval(updateTime, 1000);
});
</script>

@endsection