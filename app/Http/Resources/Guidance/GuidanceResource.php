<?php

namespace App\Http\Resources\Guidance;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class GuidanceResource extends JsonResource
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
            'student_id' => $this->student_id,
            'title' => [
                'id' => $this->student->title->id,
                'title' => $this->student->title->title,
                'abstract' => $this->student->title->abstract
            ],
            'proposal_file' => url(Storage::url($this->proposal_file)),
            'supervisor' => [
                'id' => $this->supervisor->id,
                'name' => $this->supervisor->user->name,
                'nidn' => $this->supervisor->nidn,
            ],
            'status' => $this->status,
            'guidance_date' => $this->guidance_date,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}
