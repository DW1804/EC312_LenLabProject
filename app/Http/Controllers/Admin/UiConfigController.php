<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UiConfigController extends Controller
{
    /**
     * Display the UI configuration page
     */
    public function index()
    {
        $settings = Setting::getAll();
        
        return view('admin.ui_config', compact('settings'));
    }

    /**
     * Update UI configuration
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'site_name' => 'required|string|max:255',
            'primary_color' => ['required', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'favicon' => 'nullable|image|mimes:png,ico|max:512',
            'email_notifications' => 'boolean',
            'browser_notifications' => 'boolean',
        ], [
            'site_name.required' => 'Tên website là bắt buộc',
            'site_name.max' => 'Tên website không được quá 255 ký tự',
            'primary_color.required' => 'Màu sắc chủ đạo là bắt buộc',
            'primary_color.regex' => 'Màu sắc phải có định dạng hex hợp lệ (ví dụ: #D1A272)',
            'logo.image' => 'Logo phải là file hình ảnh',
            'logo.mimes' => 'Logo chỉ chấp nhận định dạng: jpeg, png, jpg, svg',
            'logo.max' => 'Logo không được vượt quá 2MB',
            'favicon.image' => 'Favicon phải là file hình ảnh',
            'favicon.mimes' => 'Favicon chỉ chấp nhận định dạng: png, ico',
            'favicon.max' => 'Favicon không được vượt quá 512KB',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Update basic settings
            Setting::set('site_name', $request->site_name);
            Setting::set('primary_color', $request->primary_color);
            Setting::set('email_notifications', $request->has('email_notifications') ? 1 : 0, 'boolean');
            Setting::set('browser_notifications', $request->has('browser_notifications') ? 1 : 0, 'boolean');

            $logoPath = null;
            $faviconPath = null;

            // Handle logo upload
            if ($request->hasFile('logo')) {
                $logoPath = $this->handleImageUpload($request->file('logo'), 'logo', 800, 400);
                Setting::set('logo_path', $logoPath, 'file');
            }

            // Handle favicon upload
            if ($request->hasFile('favicon')) {
                $faviconPath = $this->handleImageUpload($request->file('favicon'), 'favicon', 32, 32);
                Setting::set('favicon_path', $faviconPath, 'file');
            }

            // Clear settings cache
            Setting::clearCache();

            return response()->json([
                'success' => true,
                'message' => 'Cấu hình đã được lưu thành công!',
                'data' => [
                    'logo_url' => $logoPath ? Storage::url($logoPath) : null,
                    'favicon_url' => $faviconPath ? Storage::url($faviconPath) : null,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle image upload without resize (fallback when GD not available)
     */
    private function handleImageUpload($file, $type, $maxWidth, $maxHeight)
    {
        // Delete old file if exists
        $oldPath = Setting::get($type . '_path');
        if ($oldPath && Storage::exists($oldPath)) {
            Storage::delete($oldPath);
        }

        // Generate unique filename
        $extension = $file->getClientOriginalExtension();
        $filename = $type . '_' . time() . '.' . $extension;
        $path = "uploads/{$type}/" . $filename;

        // Save file directly without resize
        Storage::put($path, file_get_contents($file));
        
        return $path;
    }

    /**
     * Get current settings as JSON
     */
    public function getSettings()
    {
        $settings = Setting::getAll();
        
        // Add full URLs for files
        if (isset($settings['logo_path']) && $settings['logo_path']) {
            $settings['logo_url'] = Storage::url($settings['logo_path']);
        }
        
        if (isset($settings['favicon_path']) && $settings['favicon_path']) {
            $settings['favicon_url'] = Storage::url($settings['favicon_path']);
        }

        return response()->json([
            'success' => true,
            'data' => $settings
        ]);
    }

    /**
     * Delete uploaded file
     */
    public function deleteFile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:logo,favicon'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Loại file không hợp lệ'
            ], 422);
        }

        try {
            $type = $request->type;
            $path = Setting::get($type . '_path');

            if ($path && Storage::exists($path)) {
                Storage::delete($path);
                Setting::set($type . '_path', null, 'file');
                Setting::clearCache();

                return response()->json([
                    'success' => true,
                    'message' => ucfirst($type) . ' đã được xóa thành công'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'File không tồn tại'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
}