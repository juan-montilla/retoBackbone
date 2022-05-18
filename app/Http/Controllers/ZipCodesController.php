<?php

namespace App\Http\Controllers;

use App\Models\ZipCode;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class ZipCodesController extends Controller
{
    public function show($code): JsonResponse {
        $model = ZipCode::findByCode($code)->first();
        if (empty($model)) {
            abort(404);
        }
        return response()->json($model->formattedOutput());
    }
}
