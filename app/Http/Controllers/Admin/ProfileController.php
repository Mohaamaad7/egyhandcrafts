<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Show the profile, credential, and system path management page.
     */
    public function edit(): View
    {
        $user = auth()->user();
        $currentAdminPath = admin_path();

        return view('admin.profile.edit', compact('user', 'currentAdminPath'));
    }

    /**
     * Update the active administrator's profile information and job title.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'job_title' => ['nullable', 'string', 'max:255'],
            'username' => ['required', 'string', 'alpha_dash', 'max:50', Rule::unique('users', 'username')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
        ], [
            'username.unique' => 'اسم المستخدم هذا مستخدم بالفعل من قبل حساب آخر.',
            'email.unique' => 'البريد الإلكتروني هذا مستخدم بالفعل من قبل حساب آخر.',
        ]);

        $user->update([
            'name' => $validated['name'],
            'job_title' => !empty($validated['job_title']) ? trim($validated['job_title']) : null,
            'username' => strtolower($validated['username']),
            'email' => strtolower($validated['email']),
        ]);

        return back()->with('success', 'تم تحديث البيانات الشخصية والمسمى الوظيفي بنجاح.');
    }

    /**
     * Update the active administrator's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.current_password' => 'كلمة المرور الحالية غير صحيحة.',
            'password.min' => 'يجب ألا تقل كلمة المرور الجديدة عن 8 أحرف.',
            'password.confirmed' => 'تأكيد كلمة المرور الجديدة غير متطابق.',
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'تم تحديث كلمة المرور بنجاح.');
    }

    /**
     * Safely update the administrative route prefix.
     */
    public function updateAdminPath(Request $request): RedirectResponse
    {
        if (! auth()->user()?->isSuperAdmin()) {
            abort(403, 'تعديل مسار لوحة التحكم مقتصر على مدير النظام فقط.');
        }

        $validated = $request->validate([
            'admin_path' => [
                'required',
                'string',
                'min:2',
                'max:50',
                'regex:/^[a-zA-Z0-9_\-]+$/',
                'not_in:login,logout,register,forgot-password,reset-password,api,sanctum,up,storage,assets,crafts,workshops,stories,map,home',
            ],
        ], [
            'admin_path.required' => 'يرجى إدخال مسار لوحة التحكم الجديد.',
            'admin_path.min' => 'يجب ألا يقل المسار عن حرفين.',
            'admin_path.max' => 'يجب ألا يزيد المسار عن 50 حرفاً.',
            'admin_path.regex' => 'يجب أن يحتوي المسار على أحرف إنجليزية وأرقام وشرطات فقط بدون مسافات أو رموز خاصة.',
            'admin_path.not_in' => 'هذا المسار محجوز لمسارات البوابة العامة ولا يمكن استخدامه كمسار للإدارة.',
        ]);

        $newPath = strtolower(trim($validated['admin_path'], '/'));

        Setting::set('admin_path', $newPath);

        return redirect('/' . $newPath . '/profile')
            ->with('success', "تم تحديث مسار لوحة التحكم بنجاح إلى: (/{$newPath}). تم تفعيل المسار فوراً.");
    }
}
