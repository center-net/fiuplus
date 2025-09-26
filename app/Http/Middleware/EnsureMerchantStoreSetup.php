<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureMerchantStoreSetup
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (!$user) {
            return $next($request);
        }

        // Must be merchant and have inactive store
        $user->loadMissing(['roles', 'store']);
        $isMerchant = $user->roles->contains('key', 'merchant');
        if ($isMerchant) {
            $store = $user->store;
            // If merchant has no store yet OR store is inactive, force setup
            $needsSetup = !$store || ($store && !$store->is_active);
            if ($needsSetup && !$request->routeIs('merchant.store.setup')) {
                return redirect()->route('merchant.store.setup');
            }
        }

        return $next($request);
    }
}