@extends('layouts.guest')

@section('title', 'Quên mật khẩu')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6 col-xl-5">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center py-4">
                    <h4 class="mb-0">
                        <i class="fas fa-key me-2"></i>
                        Quên mật khẩu
                    </h4>
                </div>
                
                <div class="card-body p-4">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="text-center mb-4">
                        <p class="text-muted">
                            Nhập email hoặc số điện thoại của bạn để đặt lại mật khẩu
                        </p>
                    </div>

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="account" class="form-label">
                                <i class="fas fa-user me-2"></i>
                                Tài khoản <span class="text-danger">*</span>
                            </label>
                            <input 
                                type="text" 
                                class="form-control @error('account') is-invalid @enderror" 
                                id="account" 
                                name="account" 
                                value="{{ old('account') }}"
                                placeholder="Nhập email hoặc số điện thoại"
                                required 
                                autofocus
                            >
                            @error('account')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Bạn có thể nhập email hoặc số điện thoại đã đăng ký
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-paper-plane me-2"></i>
                                Gửi yêu cầu đặt lại mật khẩu
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-4">
                        <a href="{{ route('login') }}" class="text-decoration-none">
                            <i class="fas fa-arrow-left me-1"></i>
                            Quay lại đăng nhập
                        </a>
                    </div>
                </div>
            </div>

            <div class="text-center mt-3">
                <small class="text-muted">
                    Chưa có tài khoản? 
                    <a href="{{ route('register') }}" class="text-decoration-none">Đăng ký ngay</a>
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
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.btn-primary {
    border-radius: 10px;
    padding: 12px 24px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
}

.alert {
    border-radius: 10px;
    border: none;
}
</style>
@endsection
