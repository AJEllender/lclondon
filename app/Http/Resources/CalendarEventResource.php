<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class CalendarEventResource extends JsonResource
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
            'id' => $this->getKey(),
            'start' => $this->start_at
                ? $this->start_at->format('Y-m-d H:i')
                : null,
            'end' => $this->end_at
                ? $this->end_at->format('Y-m-d H:i')
                : null,
            'title' => $this->getEventName(),
            'content' => '',
            'class' => $this->eventType
                ? 'vuecal__event-type--' . strtolower(Str::kebab($this->eventType->slug))
                : '',
            'background' => false,
            'split' => '1',
            'allDay' => false,
            'deletable' => false,
            'resizable' => false,
            'url' => $this->eventType
                ?route('events.show', [$this->eventType->slug, $this->uuid])
                : null,
        ];
    }
}
