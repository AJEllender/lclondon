<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class LightBoxImageResource extends JsonResource
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
            'alt' => $this->alt_text,
            'src' => $this->getUrl(),
            'title' => $this->caption,
            'urls' => [
                'gallery_3' => $this->getResizeUrl('gallery_3', true),
                'gallery_4' => $this->getResizeUrl('gallery_4', true),
                'gallery_6' => $this->getResizeUrl('gallery_6', true),
            ],
        ];
    }
}
