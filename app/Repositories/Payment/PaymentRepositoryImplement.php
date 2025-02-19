<?php

namespace App\Repositories\Payment;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Payment;

class PaymentRepositoryImplement extends Eloquent implements PaymentRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected Payment $model;

    public function __construct(Payment $model)
    {
        $this->model = $model;
    }

    public function createPayment(array $data)
    {
        return $this->model->create($data);
    }

    public function updatePayment(array $data, $id)
    {
        $payment = $this->model->findOrFail($id);
        $payment->update($data);
        return $payment->fresh();
    }

    public function findPayment($studentId, $type)
    {
        return $this->model->where(['student_id' => $studentId, 'payment_type' => $type])->first();
    }

    public function getByStudent(string $studentId)
    {
        return $this->model->where('student_id', $studentId)->get();
    }

    public function allPaymentType($studentId)
    {
        return $this->model->where('student_id', $studentId)
            ->whereIn('payment_type', ['stage_1', 'stage_2'])
            ->where('status', 'approved')
            ->pluck('payment_type')
            ->toArray();
    }
}
