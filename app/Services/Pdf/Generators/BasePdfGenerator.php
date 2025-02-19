<?php

namespace App\Services\Pdf\Generators;

use App\Services\Pdf\PdfGeneratorInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

abstract class BasePdfGenerator implements PdfGeneratorInterface
{
    protected $repository;

    public function __construct($repository)
    {
        $this->repository = $repository;
    }

    abstract public function getView(): string;

    abstract protected function getActivityType(): string;

    public function getData(string $id): array
    {
        $activity = $this->repository->findOrFail($id);
        if (!$activity) {
            throw new ModelNotFoundException("Data {$this->getActivityType()} dengan ID {$id} tidak ditemukan.");
        }

        $activity->load(['student', 'supervisor', 'examiner', 'title', 'grades']);

        $supervisorGrades = $activity->grades
            ->where('role_in_activity', 'supervisor')
            ->first();

        $examinerGrades = $activity->grades
            ->where('role_in_activity', 'examiner')
            ->first();

        $activityType = str_replace('-', '', ucwords($this->getActivityType(), '-'));
        $dtoClass = "App\\DTOs\\" . $activityType . "PdfDTO";

        $dto = new $dtoClass([
            'title' => $activity->title->title ?? 'Unknown',
            'student' => $activity->student->user->name ?? 'Unknown',
            'nidn_supervisor' => $activity->supervisor->nidn ?? 'Unknown',
            'nidn_examiner' => $activity->examiner->nidn ?? 'Unknown',
            'supervisor' => $activity->supervisor->user->name ?? 'Unknown',
            'examiner' => $activity->examiner->user->name ?? 'Unknown',
            'activity_date' => $activity->seminar_date ?? 'Unknown',
            'supervisor_grades' => [
                'a1' => $supervisorGrades->a1 ?? null,
                'a2' => $supervisorGrades->a2 ?? null,
                'a3' => $supervisorGrades->a3 ?? null,
                'a4' => $supervisorGrades->a4 ?? null,
                'a5' => $supervisorGrades->a5 ?? null,
                'a6' => $supervisorGrades->a6 ?? null,
            ],
            'examiner_grades' => [
                'a1' => $examinerGrades->a1 ?? null,
                'a2' => $examinerGrades->a2 ?? null,
                'a3' => $examinerGrades->a3 ?? null,
                'a4' => $examinerGrades->a4 ?? null,
                'a5' => $examinerGrades->a5 ?? null,
                'a6' => $examinerGrades->a6 ?? null,
            ]
        ]);

        return $dto->toArray();
    }

    public function getFilename(string $id): string
    {
        return "rekap-laporan-{$this->getActivityType()}-{$id}.pdf";
    }
}
