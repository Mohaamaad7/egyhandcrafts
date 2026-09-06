<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of system administrators.
     */
    public function index(Request $request): View
    {
        $query = User::query();

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new administrator.
     */
    public function create(): View
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created administrator in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'alpha_dash', 'max:50', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'in:admin,super_admin'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'username.unique' => 'اسم المستخدم هذا مستخدم بالفعل.',
            'email.unique' => 'البريد الإلكتروني هذا مستخدم بالفعل.',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق.',
            'password.min' => 'يجب ألا تقل كلمة المرور عن 8 أحرف.',
        ]);

        User::create([
            'name' => $validated['name'],
            'username' => strtolower($validated['username']),
            'email' => strtolower($validated['email']),
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'تمت إضافة المسؤول الجديد بنجاح.');
    }

    /**
     * Show the form for editing the specified administrator.
     */
    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified administrator in storage.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'alpha_dash', 'max:50', Rule::unique('users', 'username')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', 'in:admin,super_admin'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ], [
            'username.unique' => 'اسم المستخدم هذا مستخدم بالفعل.',
            'email.unique' => 'البريد الإلكتروني هذا مستخدم بالفعل.',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق.',
            'password.min' => 'يجب ألا تقل كلمة المرور عن 8 أحرف.',
        ]);

        // Prevent current super admin from demoting themselves if they are the only super admin
        if ($user->id === auth()->id() && $user->role === 'super_admin' && $validated['role'] !== 'super_admin') {
            if (User::where('role', 'super_admin')->count() <= 1) {
                return back()->with('error', 'لا يمكنك إلغاء صلاحية مدير النظام عن نفسك لأنك المشرف الوحيد.');
            }
        }

        $user->name = $validated['name'];
        $user->username = strtolower($validated['username']);
        $user->email = strtolower($validated['email']);
        $user->role = $validated['role'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', 'تم تحديث بيانات المسؤول بنجاح.');
    }

    /**
     * Remove the specified administrator from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        // Safeguard 1: Admin cannot delete their own account
        if ($user->id === auth()->id()) {
            return back()->with('error', 'لا يمكنك حذف حسابك الشخصي النشط.');
        }

        // Safeguard 2: Cannot delete the last remaining super_admin
        if ($user->role === 'super_admin' && User::where('role', 'super_admin')->count() <= 1) {
            return back()->with('error', 'لا يمكن حذف مدير النظام الوحيد المتبقي.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'تم حذف حساب المسؤول بنجاح.');
    }
}
