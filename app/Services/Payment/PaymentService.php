<?php

namespace App\Services\Payment;

use LaravelEasyRepository\BaseService;

interface PaymentService extends BaseService
{
    public function createPayment(array $data, $studentId);
    public function updatePayment($studentId, array $data, $id);
    public function getPaymentRole(string $roles, object $user);
    public function findById(string $id);
}
