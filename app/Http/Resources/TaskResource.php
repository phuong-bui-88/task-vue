<?php

namespace App\Http\Resources;

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
            'description' => $this->when($this->description, $this->description),
            'created_at' => $this->created_at->toDateString(),
            'start_date' => $this->when($request->routeIs('tasks.show'), $this->start_date),
            'documents' => $this->when($request->routeIs('tasks.show'), $this->documents)
        ];
    }
}
