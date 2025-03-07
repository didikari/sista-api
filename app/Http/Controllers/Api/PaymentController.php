<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\PaymentRequest;
use App\Http\Requests\Payment\UpdatePaymentRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\Payment\PaymentResource;
use App\Services\Payment\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    private $paymentService;
    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            $payments = $this->paymentService->getPayments($user, $request);
            $payments->setCollection(collect(PaymentResource::collection($payments->items())->toArray($request)));

            return ResponseHelper::success(
                new BaseCollection($payments),
                'Get data payments successfully',
                200
            );
        } catch (\Exception $e) {
            return ResponseHelper::exception($e);
        }
    }

    public function store(PaymentRequest $request)
    {
        try {
            $data = $request->validated();
            $studentId = Auth::user()->student->id;
            $payment = $this->paymentService->createPayment($data, $studentId);
            return ResponseHelper::success($payment, 'Create payment successfully', 201);
        } catch (\Exception $e) {
            return ResponseHelper::exception($e);
        }
    }

    public function show($id)
    {
        try {
            $payment = $this->paymentService->findById($id);
            $payment->load('verifiedBy');
            return ResponseHelper::success($payment, 'Get payment successfully', 200);
        } catch (\Exception $e) {
            return ResponseHelper::exception($e);
        }
    }

    public function update(UpdatePaymentRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $studentId = Auth::user()->student->id;
            $payment = $this->paymentService->updatePayment($studentId, $data, $id);
            return ResponseHelper::success($payment, 'Update payment successfully', 200);
        } catch (\Exception $e) {
            return ResponseHelper::exception($e);
        }
    }
}
