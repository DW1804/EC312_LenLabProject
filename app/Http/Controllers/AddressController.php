<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    // Lấy danh sách địa chỉ của user
    public function index()
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Chưa đăng nhập'
                ], 401);
            }

            // Try to get addresses without relationships first
            $addresses = Auth::user()->addresses()->get();

            // If we have addresses, try to load relationships
            if ($addresses->count() > 0) {
                try {
                    $addresses = Auth::user()
                        ->addresses()
                        ->with(['province', 'ward'])
                        ->orderBy('is_default', 'desc')
                        ->orderBy('created_at', 'desc')
                        ->get();
                } catch (\Exception $relationError) {
                    \Log::warning('Could not load address relationships:', ['error' => $relationError->getMessage()]);
                    // Fallback to addresses without relationships
                    $addresses = Auth::user()
                        ->addresses()
                        ->orderBy('is_default', 'desc')
                        ->orderBy('created_at', 'desc')
                        ->get();
                }
            }

            return response()->json([
                'success' => true,
                'addresses' => $addresses
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading addresses:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => true,
                'addresses' => [],
                'message' => 'Chưa có địa chỉ đã lưu'
            ]);
        }
    }

    // Tạo địa chỉ mới
    public function store(Request $request)
    {
        try {
            // Validate without is_default first
            $validated = $request->validate([
                'full_name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'province_id' => 'required|exists:provinces,id',
                'ward_id' => 'required|exists:wards,id',
                'specific_address' => 'required|string|max:500'
            ]);

            // Handle is_default separately
            $isDefaultInput = $request->input('is_default');
            $validated['is_default'] = in_array($isDefaultInput, [true, 'true', 1, '1'], true);
            $validated['user_id'] = Auth::id();

            // Nếu đặt làm mặc định → reset các địa chỉ khác
            if ($validated['is_default']) {
                Auth::user()->addresses()->update(['is_default' => false]);
            }

            $address = Address::create($validated);

            return response()->json([
                'success' => true,
                'address' => $address->load(['province', 'ward']),
                'message' => 'Đã thêm địa chỉ mới'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error creating address:', ['error' => $e->getMessage(), 'request' => $request->all()]);
            return response()->json([
                'success' => false,
                'message' => 'Không thể thêm địa chỉ: ' . $e->getMessage()
            ], 500);
        }
    }

    // Cập nhật địa chỉ
    public function update(Request $request, $id)
    {
        try {
            $address = Auth::user()->addresses()->findOrFail($id);

            // Validate without is_default first
            $validated = $request->validate([
                'full_name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'province_id' => 'required|exists:provinces,id',
                'ward_id' => 'required|exists:wards,id',
                'specific_address' => 'required|string|max:500'
            ]);

            // Handle is_default separately
            $isDefaultInput = $request->input('is_default');
            $validated['is_default'] = in_array($isDefaultInput, [true, 'true', 1, '1'], true);

            if ($validated['is_default']) {
                Auth::user()
                    ->addresses()
                    ->where('id', '!=', $id)
                    ->update(['is_default' => false]);
            }

            $address->update($validated);

            return response()->json([
                'success' => true,
                'address' => $address->load(['province', 'ward']),
                'message' => 'Đã cập nhật địa chỉ'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error updating address:', ['error' => $e->getMessage(), 'request' => $request->all()]);
            return response()->json([
                'success' => false,
                'message' => 'Không thể cập nhật địa chỉ: ' . $e->getMessage()
            ], 500);
        }
    }

    // Xóa địa chỉ
    public function destroy($id)
    {
        try {
            $address = Auth::user()->addresses()->findOrFail($id);
            $address->delete();

            return response()->json([
                'success' => true,
                'message' => 'Đã xóa địa chỉ'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể xóa địa chỉ: ' . $e->getMessage()
            ], 500);
        }
    }

    // Đặt địa chỉ mặc định
    public function setDefault($id)
    {
        try {
            Auth::user()->addresses()->update(['is_default' => false]);

            $address = Auth::user()->addresses()->findOrFail($id);
            $address->update(['is_default' => true]);

            return response()->json([
                'success' => true,
                'message' => 'Đã đặt làm địa chỉ mặc định'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể đặt địa chỉ mặc định: ' . $e->getMessage()
            ], 500);
        }
    }
}
