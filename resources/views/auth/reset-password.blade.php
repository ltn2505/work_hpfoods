@extends('layouts.guest')

@section('title', 'Đặt lại mật khẩu')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6 col-xl-5">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-success text-white text-center py-4">
                    <h4 class="mb-0">
                        <i class="fas fa-lock me-2"></i>
                        Đặt lại mật khẩu
                    </h4>
                </div>
                
                <div class="card-body p-4">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="text-center mb-4">
                        <p class="text-muted">
                            Nhập mật khẩu mới cho tài khoản của bạn
                        </p>
                    </div>

                    <form method="POST" action="{{ route('password.reset.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock me-2"></i>
                                Mật khẩu mới <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input 
                                    type="password" 
                                    class="form-control @error('password') is-invalid @enderror" 
                                    id="password" 
                                    name="password" 
                                    placeholder="Nhập mật khẩu mới"
                                    required 
                                    autofocus
                                >
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fas fa-eye" id="togglePasswordIcon"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Mật khẩu phải có ít nhất 8 ký tự
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">
                                <i class="fas fa-lock me-2"></i>
                                Xác nhận mật khẩu <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input 
                                    type="password" 
                                    class="form-control @error('password_confirmation') is-invalid @enderror" 
                                    id="password_confirmation" 
                                    name="password_confirmation" 
                                    placeholder="Nhập lại mật khẩu mới"
                                    required
                                >
                                <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm">
                                    <i class="fas fa-eye" id="togglePasswordConfirmIcon"></i>
                                </button>
                            </div>
                            @error('password_confirmation')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-save me-2"></i>
                                Đặt lại mật khẩu
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-4">
                        <a href="{{ route('password.request') }}" class="text-decoration-none">
                            <i class="fas fa-arrow-left me-1"></i>
                            Quay lại quên mật khẩu
                        </a>
                    </div>
                </div>
            </div>

            <div class="text-center mt-3">
                <small class="text-muted">
                    Đã nhớ mật khẩu? 
                    <a href="{{ route('login') }}" class="text-decoration-none">Đăng nhập ngay</a>
                </small>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 15px;
}

.card-header {
    border-radius: 15px 15px 0 0 !important;
}

.form-control {
    border-radius: 10px;
    padding: 12px 16px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #198754;
    box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25);
}

.btn-success {
    border-radius: 10px;
    padding: 12px 24px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(25, 135, 84, 0.3);
}

.alert {
    border-radius: 10px;
    border: none;
}

.password-strength {
    margin-top: 8px;
    font-size: 0.875rem;
}

.strength-weak { color: #dc3545; }
.strength-medium { color: #ffc107; }
.strength-strong { color: #198754; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirmation');
    
    // Kiểm tra mật khẩu trùng khớp
    function checkPasswordMatch() {
        if (passwordInput.value !== confirmInput.value) {
            confirmInput.setCustomValidity('Mật khẩu không trùng khớp');
        } else {
            confirmInput.setCustomValidity('');
        }
    }
    
    passwordInput.addEventListener('input', checkPasswordMatch);
    confirmInput.addEventListener('input', checkPasswordMatch);
    
    // Toggle password visibility
    function togglePasswordVisibility(inputId, buttonId, iconId) {
        const input = document.getElementById(inputId);
        const button = document.getElementById(buttonId);
        const icon = document.getElementById(iconId);
        
        button.addEventListener('click', function() {
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
                button.classList.remove('btn-outline-secondary');
                button.classList.add('btn-secondary');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
                button.classList.remove('btn-secondary');
                button.classList.add('btn-outline-secondary');
            }
        });
    }
    
    // Initialize toggle for both password fields
    togglePasswordVisibility('password', 'togglePassword', 'togglePasswordIcon');
    togglePasswordVisibility('password_confirmation', 'togglePasswordConfirm', 'togglePasswordConfirmIcon');
});
</script>
@endsection
