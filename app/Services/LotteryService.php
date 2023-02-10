<?php

namespace App\Services;

use App\Models\Ticket;
use Illuminate\Support\Facades\DB;

class LotteryService
{
    /**
     * Create a new ticket
     *
     * @param array<string, mixed> $data
     * @return Ticket
     */
    public function storeTicket(array $data): Ticket
    {
        return DB::transaction(function () use ($data) {
            $ticket = new Ticket();
            $ticket->user_name = $data['name'];
            $ticket->selected_numbers = $data['numbers'];

            $ticket->save();

            return $ticket;
        });
    }

    /**
     * Show a ticket
     *
     * @param string $uuid
     * @return Ticket
     */
    public function showTicket(string $uuid): Ticket
    {
        return Ticket::query()
            ->where('uuid', $uuid)
            ->where('is_active', true)
            ->first();
    }
}
