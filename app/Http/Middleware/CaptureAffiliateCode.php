<?php

namespace App\Http\Middleware;

use App\Models\AffiliateLink;
use Closure;
use Illuminate\Http\Request;

class CaptureAffiliateCode
{
    public function handle(Request $request, Closure $next)
    {
        $code = trim((string) $request->query('aff', ''));
        if ($code !== '') {
            $link = AffiliateLink::query()->where('code', $code)->where('is_active', true)->first();
            if ($link) {
                $request->session()->put('affiliate_code', $link->code);
            }
        }

        return $next($request);
    }
}
