<?php

namespace App\DTOs;

abstract class AbstractPdfDTO
{
    public string $title;
    public string $student;
    public string $examiner;
    public string $supervisor;
    public string $seminar_date;
    public string $nidn_supervisor;
    public string $nidn_examiner;
    public array $supervisor_grades;
    public array $examiner_grades;
    public float $examiner_percentage;
    public float $supervisor_percentage;

    public function __construct(array $data)
    {
        $this->title = $data['title'] ?? 'Laporan';
        $this->student = $data['student'] ?? 'Unknown';
        $this->examiner = $data['examiner'] ?? 'Unknown';
        $this->supervisor = $data['supervisor'] ?? 'Unknown';
        $this->nidn_supervisor = $data['nidn_supervisor'] ?? 'Unknown';
        $this->nidn_examiner = $data['nidn_examiner'] ?? 'Unknown';
        $this->seminar_date = $data['seminar_date'] ?? now()->toDateString();
        $this->examiner_percentage = 0.5;
        $this->supervisor_percentage = 0.5;

        $this->supervisor_grades = $data['supervisor_grades'] ?? $this->defaultGrades();
        $this->examiner_grades = $data['examiner_grades'] ?? $this->defaultGrades();
    }

    protected function defaultGrades(): array
    {
        return [
            'a1' => null,
            'a2' => null,
            'a3' => null,
            'a4' => null,
            'a5' => null,
            'a6' => null,
        ];
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'student' => $this->student,
            'supervisor_percentage' => $this->supervisor_percentage,
            'examiner_percentage' => $this->examiner_percentage,
            'examiner' => $this->examiner,
            'nidn_supervisor' => $this->nidn_supervisor,
            'nidn_examiner' => $this->nidn_examiner,
            'supervisor' => $this->supervisor,
            'seminar_date' => $this->seminar_date,
            'supervisor_grades' => $this->supervisor_grades,
            'examiner_grades' => $this->examiner_grades,
        ];
    }
}
