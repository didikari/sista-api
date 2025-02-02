<?php

namespace App\Http\Resources\Exam;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'student' => [
                'id' => $this->student_id,
                'name' => $this->student->user->name,
            ],
            'title' => [
                'id' => $this->title_id,
                'name' => $this->title->title,
            ],
            'supervisor' => [
                'id' => $this->supervisor_id,
                'name' => $this->supervisor->name ?? $this->supervisor->user->name,
            ],
            'examiner' => [
                'id' => $this->examiner_id,
                'name' => $this->examiner->name ?? $this->examiner->user->name,
            ],
            'exam_file' => $this->exam_file,
            'score' => $this->score,
            'submission_date' => $this->submission_date,
            'seminar_date' => $this->seminar_date,
            'status' => $this->status,
        ];
    }
}
