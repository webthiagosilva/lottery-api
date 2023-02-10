<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
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
            'name' => $this->user_name,
            'your_numbers' => $this->selected_numbers,
            'machine_numbers' => $this->machine_numbers,
            'winner' => $this->is_winner,
            'message' => empty($this->is_winner)
                ? __('lottery.draw.not_drawn')
                : ($this->is_winner ? __('lottery.draw.won') : __('lottery.draw.lost')),
        ];
    }
}
