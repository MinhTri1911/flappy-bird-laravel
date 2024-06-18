<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ExportService;
use App\Utils\Response;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function __construct(private readonly ExportService $service)
    {

    }

    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $perPage = $request->get('per_page', 15);
        $data = $this->service->exportData($perPage);

        return response()->json(Response::success(data: $data));
    }
}
