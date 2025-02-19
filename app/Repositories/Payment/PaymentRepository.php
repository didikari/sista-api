<?php

namespace App\Repositories\Payment;

use LaravelEasyRepository\Repository;

interface PaymentRepository extends Repository
{
    public function createPayment(array $data);
    public function updatePayment(array $data, $id);
    public function findPayment($studentId, $type);
    public function allPaymentType($studentId);
    public function getByStudent(string $studentId);
}
