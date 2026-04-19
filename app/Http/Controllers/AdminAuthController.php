<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Support\PortfolioDataStore;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function __construct(
        private readonly PortfolioDataStore $portfolioDataStore,
    ) {
    }

    public function bootstrap(): JsonResponse
    {
        $admin = $this->adminUser();

        return response()->json([
            'authenticated' => $admin !== null,
            'user' => $admin ? [
                'id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
            ] : null,
            'data' => $admin ? $this->portfolioDataStore->full() : [],
        ]);
    }

    public function login(Request $request): JsonResponse
    {
        $payload = $request->isJson() ? $request->json()->all() : $request->all();
        $password = (string) ($payload['password'] ?? '');

        if ($password === '') {
            return response()->json(['error' => 'Password is required.'], 422);
        }

        $admin = User::query()
            ->where('is_admin', true)
            ->orderBy('id')
            ->first();

        if (! $admin || ! Hash::check($password, $admin->password)) {
            return response()->json(['error' => 'Incorrect password. Try again.'], 422);
        }

        Auth::login($admin);
        $request->session()->regenerate();

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
            ],
            'data' => $this->portfolioDataStore->full(),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['success' => true]);
    }

    public function updatePassword(Request $request): JsonResponse
    {
        $admin = $this->adminUser();
        if (! $admin) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        $payload = $request->isJson() ? $request->json()->all() : $request->all();
        $current = (string) ($payload['current_password'] ?? '');
        $new = (string) ($payload['new_password'] ?? '');
        $confirm = (string) ($payload['new_password_confirmation'] ?? '');

        if (! Hash::check($current, $admin->password)) {
            return response()->json(['error' => 'Current password is incorrect.'], 422);
        }

        if ($new === '' || strlen($new) < 6) {
            return response()->json(['error' => 'New password must be at least 6 characters.'], 422);
        }

        if ($new !== $confirm) {
            return response()->json(['error' => 'Passwords do not match.'], 422);
        }

        $admin->password = $new;
        $admin->save();

        return response()->json(['success' => true]);
    }

    private function adminUser(): ?User
    {
        $user = Auth::user();

        if (! $user instanceof User || ! $user->is_admin) {
            return null;
        }

        return $user;
    }
}
