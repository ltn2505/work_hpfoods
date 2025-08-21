<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    /**
     * Hiển thị form quên mật khẩu
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Xử lý yêu cầu quên mật khẩu
     */
    public function sendResetLink(Request $request)
    {
        \Log::info('ForgotPassword: Request received', ['data' => $request->all()]);
        
        $validator = Validator::make($request->all(), [
            'account' => 'required|string',
        ]);

        if ($validator->fails()) {
            \Log::error('ForgotPassword: Validation failed', ['errors' => $validator->errors()]);
            return back()->withErrors($validator)->withInput();
        }

        // Kiểm tra tài khoản tồn tại
        $user = User::where('email', $request->account)
                    ->orWhere('phone', $request->account)
                    ->first();

        \Log::info('ForgotPassword: User search result', ['account' => $request->account, 'user_found' => $user ? $user->id : null]);

        if (!$user) {
            \Log::warning('ForgotPassword: User not found', ['account' => $request->account]);
            return back()->withErrors(['account' => 'Tài khoản không tồn tại trong hệ thống.'])->withInput();
        }

        // Lưu thông tin user vào session để hiển thị form reset password
        session(['reset_user_id' => $user->id]);
        
        \Log::info('ForgotPassword: Session set, redirecting', ['user_id' => $user->id]);

        return redirect()->route('password.reset')->with('success', 'Vui lòng nhập mật khẩu mới.');
    }

    /**
     * Hiển thị form reset password
     */
    public function showResetForm()
    {
        if (!session('reset_user_id')) {
            return redirect()->route('password.request')->withErrors(['error' => 'Phiên làm việc không hợp lệ.']);
        }

        return view('auth.reset-password');
    }

    /**
     * Xử lý reset password
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $userId = session('reset_user_id');

        if (!$userId) {
            return redirect()->route('password.request')->withErrors(['error' => 'Phiên làm việc không hợp lệ.']);
        }

        // Cập nhật mật khẩu mới
        $user = User::find($userId);
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Xóa session
        session()->forget(['reset_user_id']);

        return redirect()->route('login')->with('success', 'Mật khẩu đã được đặt lại thành công. Vui lòng đăng nhập.');
    }
}
