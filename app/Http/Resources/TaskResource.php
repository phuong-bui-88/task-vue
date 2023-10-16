<?php

namespace App\Http\Resources;

use App\Http\Constants\ConstantBase;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->whenNotNull($this->description),
            'created_at' => $this->whenNotNull($this->created_at),
            'start_date' => $this->whenNotNull($this->start_date),
            'documents' => $this->when($request->routeIs('tasks.show'), $this->documents),
            'is_favorite' => $this->isFavorite() ?? false,
        ];
    }
}
