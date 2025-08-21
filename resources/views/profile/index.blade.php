@extends('layouts.master')

@section('title', 'Hồ sơ cá nhân')

@section('content')
<style>
.card {
    border-radius: 15px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border: none;
}

.card-header {
    background: linear-gradient(135deg, #558EC1 0%, #5DA444 100%);
    color: white;
    border-radius: 15px 15px 0 0 !important;
    border: none;
    padding: 1.5rem;
}

.card-header h5 {
    margin: 0;
    font-weight: 600;
}

.section-header {
    background: linear-gradient(135deg, #5DA444 0%, #558EC1 100%);
    color: white;
    padding: 1rem 1.5rem;
    border-radius: 10px;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.section-header i {
    font-size: 1.2rem;
}

.form-control {
    border-radius: 8px;
    border: 2px solid #e9ecef;
    padding: 12px 16px;
    transition: all 0.3s ease;
}

.input-group .form-control {
    border-radius: 8px 0 0 8px;
    border-right: none;
}

.form-control:focus {
    border-color: #558EC1;
    box-shadow: 0 0 0 0.2rem rgba(85, 142, 193, 0.25);
}

.btn-primary {
    background: linear-gradient(135deg, #558EC1 0%, #5DA444 100%);
    border: none;
    border-radius: 8px;
    padding: 12px 24px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(85, 142, 193, 0.3);
}

.btn-success {
    background: linear-gradient(135deg, #5DA444 0%, #558EC1 100%);
    border: none;
    border-radius: 8px;
    padding: 12px 24px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(93, 164, 68, 0.3);
}

.alert {
    border-radius: 10px;
    border: none;
}

.input-group .btn {
    border-radius: 0 8px 8px 0;
    border-left: none;
    padding: 12px 16px;
    min-width: 50px;
    border: 2px solid #e9ecef;
    background-color: #f8f9fa;
    color: #6c757d;
    display: flex !important;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    z-index: 10;
}

.input-group .btn:hover {
    background-color: #6c757d;
    border-color: #6c757d;
    color: white;
}

.input-group .btn:focus {
    box-shadow: 0 0 0 0.2rem rgba(85, 142, 193, 0.25);
}

.input-group .btn i {
    font-size: 16px;
}

.input-group {
    position: relative;
    display: flex !important;
    flex-wrap: nowrap;
    align-items: stretch;
    width: 100%;
}

.input-group > .form-control {
    position: relative;
    flex: 1 1 auto;
    width: 1%;
    min-width: 0;
}

.input-group > .btn {
    position: relative;
    z-index: 2;
}

.input-group .form-control:focus {
    border-color: #558EC1;
    border-right: none;
    box-shadow: 0 0 0 0.2rem rgba(85, 142, 193, 0.25);
}

.input-group .btn.btn-secondary {
    background-color: #558EC1;
    border-color: #558EC1;
    color: white;
}

@media (max-width: 768px) {
    .row {
        margin: 0;
    }
    
    .col-md-6 {
        padding: 0 0 1rem 0;
    }
}
</style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-user me-2"></i>Hồ sơ cá nhân</h5>
                </div>
                <div class="card-body p-4">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Có lỗi xảy ra. Vui lòng kiểm tra lại thông tin.
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="row">
                        <!-- Thông tin cá nhân -->
                        <div class="col-md-6">
                            <div class="section-header">
                                <i class="fas fa-user"></i>
                                <span>Thông tin cá nhân</span>
                            </div>
                            
                            <p class="text-muted mb-4">
                                <i class="fas fa-info-circle me-1"></i>
                                Cập nhật thông tin cá nhân và địa chỉ email
                            </p>

                            <form method="POST" action="{{ route('profile.update') }}">
                                @csrf
                                
                                <div class="mb-3">
                                    <label for="name" class="form-label fw-semibold">
                                        <i class="fas fa-user me-1"></i>Họ tên
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', $user->name) }}" 
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label fw-semibold">
                                        <i class="fas fa-envelope me-1"></i>Email
                                    </label>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email', $user->email) }}" 
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="phone" class="form-label fw-semibold">
                                        <i class="fas fa-phone me-1"></i>Số điện thoại
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" 
                                           name="phone" 
                                           value="{{ old('phone', $user->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-check me-2"></i>Lưu thay đổi
                                </button>
                            </form>
                        </div>

                        <!-- Đổi mật khẩu -->
                        <div class="col-md-6">
                            <div class="section-header">
                                <i class="fas fa-lock"></i>
                                <span>Đổi mật khẩu</span>
                            </div>
                            
                            <p class="text-muted mb-4">
                                <i class="fas fa-shield-alt me-1"></i>
                                Đảm bảo tài khoản của bạn sử dụng mật khẩu dài và ngẫu nhiên để bảo mật
                            </p>

                            <form method="POST" action="{{ route('profile.password') }}">
                                @csrf
                                
                                <div class="mb-3">
                                    <label for="password" class="form-label fw-semibold">
                                        <i class="fas fa-lock me-1"></i>Mật khẩu mới
                                    </label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control @error('password') is-invalid @enderror" 
                                               id="password" 
                                               name="password" 
                                               placeholder="Nhập mật khẩu mới"
                                               required>
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword" onclick="togglePasswordVisibility('password', 'togglePassword', 'togglePasswordIcon')">
                                            <i class="fas fa-eye" id="togglePasswordIcon"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Mật khẩu phải có ít nhất 8 ký tự
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="password_confirmation" class="form-label fw-semibold">
                                        <i class="fas fa-lock me-1"></i>Xác nhận mật khẩu mới
                                    </label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control @error('password_confirmation') is-invalid @enderror" 
                                               id="password_confirmation" 
                                               name="password_confirmation" 
                                               placeholder="Nhập lại mật khẩu mới"
                                               required>
                                        <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm" onclick="togglePasswordVisibility('password_confirmation', 'togglePasswordConfirm', 'togglePasswordConfirmIcon')">
                                            <i class="fas fa-eye" id="togglePasswordConfirmIcon"></i>
                                        </button>
                                    </div>
                                    @error('password_confirmation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check me-2"></i>Lưu mật khẩu
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePasswordVisibility(inputId, buttonId, iconId) {
    console.log('Toggle called for:', inputId, buttonId, iconId);
    
    const input = document.getElementById(inputId);
    const button = document.getElementById(buttonId);
    const icon = document.getElementById(iconId);
    
    console.log('Elements found:', {input, button, icon});
    
    if (!input || !button || !icon) {
        console.error('Missing elements');
        return;
    }
    
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
}

// Kiểm tra mật khẩu trùng khớp
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirmation');
    
    function checkPasswordMatch() {
        if (passwordInput.value !== confirmInput.value) {
            confirmInput.setCustomValidity('Mật khẩu không trùng khớp');
        } else {
            confirmInput.setCustomValidity('');
        }
    }
    
    passwordInput.addEventListener('input', checkPasswordMatch);
    confirmInput.addEventListener('input', checkPasswordMatch);
});
</script>
@endsection
