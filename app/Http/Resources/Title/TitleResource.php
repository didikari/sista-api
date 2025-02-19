<?php

namespace App\Http\Resources\Title;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class TitleResource extends JsonResource
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
            'title' => $this->title,
            'abstract' => $this->abstract,
            'proposal_file' => url(Storage::url($this->proposal_file)),
            'supervisor' => [
                'id' => $this->supervisor->id,
                'name' => $this->supervisor->user->name,
                'nidn' => $this->supervisor->nidn,
            ],
            'status' => $this->status,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}
