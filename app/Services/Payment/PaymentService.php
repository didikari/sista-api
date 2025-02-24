<?php

namespace App\Services\Payment;

use Illuminate\Http\Request;
use LaravelEasyRepository\BaseService;

interface PaymentService extends BaseService
{
    public function createPayment(array $data, $studentId);
    public function updatePayment($studentId, array $data, $id);
    public function findById(string $id);
    public function getPayments($user, Request $request);
}
