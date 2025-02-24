<?php

namespace App\Services\Payment;

use App\Exceptions\ConflictException;
use LaravelEasyRepository\ServiceApi;
use App\Repositories\Payment\PaymentRepository;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PaymentServiceImplement extends ServiceApi implements PaymentService
{

    /**
     * set title message api for CRUD
     * @param string $title
     */
    protected string $title = "";
    /**
     * uncomment this to override the default message
     * protected string $create_message = "";
     * protected string $update_message = "";
     * protected string $delete_message = "";
     */

    /**
     * don't change $this->mainRepository variable name
     * because used in extends service class
     */
    protected PaymentRepository $mainRepository;

    public function __construct(PaymentRepository $mainRepository)
    {
        $this->mainRepository = $mainRepository;
    }

    public function createPayment(array $data, $studentId)
    {
        try {
            $this->checkPaymentType($studentId, $data['payment_type']);
            $data['student_id'] = $studentId;
            $data['payment_file'] = $this->storeFile($data['payment_file']);
            return $this->mainRepository->createPayment($data);
        } catch (ConflictException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new Exception('Store Failed : ' . $e->getMessage());
        }
    }

    public function getPaymentRole(string $roles, object $user, Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $page = $request->get('page', 1);
        $searchTerm = $request->get('search', '');
        try {
            return match ($roles) {
                'admin', 'staff' => $this->mainRepository->getAll(),
                'mahasiswa' => $this->mainRepository->getByStudent($user->student->id),
                default => null,
            };
        } catch (\Exception $e) {
            throw new Exception('Get payment failed', $e->getMessage());
        }
    }


    public function getPayments($user, Request $request)
    {
        $role = $user->getRoleNames()->first();
        $query = $this->mainRepository->getPaymentRole($role, $user);

        $query = $this->mainRepository->searchPayments($query, $request->get('search', ''));

        $relations = $role === 'mahasiswa' ? ['verifiedBy'] : ['student', 'verifiedBy'];

        return $this->mainRepository->getPaginatedPayments(
            $query,
            $relations,
            (int) $request->get('perPage', 10),
            (int) $request->get('page', 1)
        );
    }

    public function updatePayment($studentId, array $data, $id)
    {
        try {
            $payment = $this->mainRepository->findOrFail($id);
            if ($payment->student_id !== $studentId) {
                throw new AuthorizationException('You are not authorized to update this payment.');
            }

            if (isset($data['payment_file'])) {
                $this->deleteOldFile($payment->payment_file);
                $data['status'] = "pending";
                $data['payment_file'] = $this->storeFile($data['payment_file']);
            }

            return $this->mainRepository->updatePayment($data, $id);
        } catch (\Exception $e) {
            throw new Exception('Update payment failed : ', $e->getMessage());
        }
    }


    public function findById(string $id)
    {
        try {
            return $this->mainRepository->findOrFail($id);
        } catch (\Exception $e) {
            throw new Exception('Get payment failed : ', $e->getMessage());
        }
    }

    private function deleteOldFile($filePath)
    {
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }
    }

    private function storeFile($paymentFile)
    {
        if ($paymentFile) {
            return $paymentFile->store('uploads/payments', 'public');
        }
        return null;
    }

    private function checkPaymentType($studentId, $type)
    {
        $payment = $this->mainRepository->findPayment($studentId, $type);
        if ($payment !== null) {
            throw new ConflictException('A payment type record already exists.');
        }
    }
}
