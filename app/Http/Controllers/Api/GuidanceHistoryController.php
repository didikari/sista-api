<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\GuidanceHistory;
use App\Services\GuidanceHistory\GuidanceHistoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuidanceHistoryController extends Controller
{
    private $guidanceHistoryService;
    public function __construct(GuidanceHistoryService $guidanceHistoryService)
    {
        $this->guidanceHistoryService = $guidanceHistoryService;
    }

    public function index()
    {
        try {
            $user = Auth::user();
            $role = $user->getRoleNames()->first();
            $guidanceHistory = $this->guidanceHistoryService->getByRole($role, $user);
            return ResponseHelper::success($guidanceHistory, 'Get data guidance history successfully', 200);
        } catch (\Exception $e) {
            return ResponseHelper::exception($e);
        }
    }
}
