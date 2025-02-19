<?php

namespace App\Http\Resources\Exam;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

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
            'exam_file' => url(Storage::url($this->exam_file)),
            'seminar_date' => $this->seminar_date,
            'submission_date' => $this->submission_date,
            'status' => $this->status,
            'score' => $this->score,
            'title' => [
                'id' => $this->title->id,
                'title' => $this->title->title,
            ],
            'supervisor' => [
                'id' => $this->supervisor->id,
                'name' => $this->supervisor->user->name,
                'nidn' => $this->supervisor->nidn,
            ],
            'examiner' => [
                'id' => $this->examiner->id,
                'name' => $this->examiner->user->name,
                'nidn' => $this->examiner->nidn,
            ],
            'student' => $this->whenLoaded('student', function () {
                return [
                    'id' => $this->student->id,
                    'nim' => $this->student->nim,
                ];
            }),
        ];
    }
}
