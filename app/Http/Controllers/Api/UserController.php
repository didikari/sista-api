<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    public function index()
    {
        try {
            $users = $this->userService->getDosen();
            return ResponseHelper::success($users, 'Get data dosen successfully', 200);
        } catch (\Exception $e) {
            return ResponseHelper::exception($e);
        }
    }

    public function store() {}
}
