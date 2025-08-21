<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Đây là khu vực bảo mật của ứng dụng. Vui lòng xác nhận mật khẩu để tiếp tục.') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Mật khẩu')" />

            <div class="input-group">
                <x-text-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />
                <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('password', 'togglePassword', 'togglePasswordIcon')">
                    <i class="fas fa-eye" id="togglePasswordIcon"></i>
                </button>
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex justify-end mt-4">
            <x-primary-button>
                {{ __('Xác nhận') }}
            </x-primary-button>
        </div>
    </form>

    <script>
    function togglePasswordVisibility(inputId, buttonId, iconId) {
        const input = document.getElementById(inputId);
        const button = document.getElementById(buttonId);
        const icon = document.getElementById(iconId);
        
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
    </script>
</x-guest-layout>
