<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Update user profile
     */
    public function update(Request $request)
    {
        try {
            $user = Auth::user();
            
            // Validation rules
            $rules = [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'phone' => 'nullable|string|max:20',
                'gender' => 'nullable|in:male,female,other',
                'birth_date' => 'nullable|date|before:today',
            ];
            
            // Add password validation only if provided
            if ($request->filled('current_password') || $request->filled('password')) {
                $rules['current_password'] = 'required|string';
                $rules['password'] = ['required', 'string', 'confirmed', Password::min(8)];
            }
            
            $validated = $request->validate($rules);
            
            // Check current password if changing password
            if ($request->filled('current_password')) {
                if (!Hash::check($request->current_password, $user->password)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Mật khẩu hiện tại không đúng'
                    ], 422);
                }
            }
            
            // Hash new password if provided
            if ($request->filled('password')) {
                $validated['password'] = Hash::make($request->password);
            }
            
            // Remove password confirmation and current password from update data
            unset($validated['password_confirmation'], $validated['current_password']);
            
            // Update user
            $user->update($validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật hồ sơ thành công',
                'user' => $user->fresh()
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            \Log::error('Profile update error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật hồ sơ'
            ], 500);
        }
    }
}