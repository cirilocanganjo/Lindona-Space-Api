<?php

namespace App\Services;

use Illuminate\Http\Request;

class PaginationService
{
    public function perPage(Request $request): int
    {
        $request->validate([
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        return max(1, min((int) $request->input('per_page', 5), 100));
    }
}