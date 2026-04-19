<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Support\PortfolioDataStore;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminPortfolioController extends Controller
{
    public function __construct(
        private readonly PortfolioDataStore $portfolioDataStore,
    ) {
    }

    public function store(Request $request): JsonResponse
    {
        if (! $this->adminUser()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        $payload = $request->isJson() ? $request->json()->all() : $request->all();
        $data = $payload['data'] ?? null;

        if (! is_array($data)) {
            return response()->json(['error' => 'Invalid portfolio payload.'], 422);
        }

        return response()->json([
            'success' => true,
            'data' => $this->portfolioDataStore->save($data),
        ]);
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
