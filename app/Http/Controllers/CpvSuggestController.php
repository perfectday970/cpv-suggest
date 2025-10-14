<?php

namespace App\Http\Controllers;

use App\Http\Requests\CpvSuggestRequest;
use App\Services\CpvService;
use Illuminate\Http\JsonResponse;

class CpvSuggestController extends Controller
{
    /**
     * Handle CPV code suggestion request.
     */
    public function __invoke(CpvSuggestRequest $request, CpvService $service): JsonResponse
    {
        $data = $request->validated();

        $result = $service->suggest($data);

        return response()->json($result);
    }
}
