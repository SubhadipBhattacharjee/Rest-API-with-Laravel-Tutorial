<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseResouce extends JsonResource
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
            'user'=> $this->name,
            'course' => $this->title,
            'description' => $this->description,
            'fees' => $this->fees,
            'duration' => $this->duration,      
        ];

    }

    
}
