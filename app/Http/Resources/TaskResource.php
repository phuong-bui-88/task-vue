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
        try {
            return [
                'id' => $this->id,
                'title' => $this->title,
                'description' => $this->when($this->description, $this->description),
                'created_at' => $this->created_at->toDateString(),
                'documents' => $this->when($request->routeIs('tasks.show'), $this->documents)
            ];
        }
        catch(\Exception $e) {
            info(print_r($this));
        }
    }
}
