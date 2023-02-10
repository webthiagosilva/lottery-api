<?php

namespace App\Jobs;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LotteryDrawJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var array<int, int>
     */
    private array $machineNumbers;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->machineNumbers = array_rand(range(1, 60), 6);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('LotteryDrawJob started');

        $now = now();

        $lotteryTickets = Ticket::query()
            ->from('tickets as t')
            ->select([
                't.id',
                't.selected_numbers',
            ])
            ->where('t.is_active', true)
            ->where('t.machine_draw_at', null)
            ->where('t.created_at', '>=', $now->subSeconds(30))
            ->get();

        DB::transaction(function () use ($lotteryTickets, $now) {
            foreach ($lotteryTickets->chunk(100) as $chunk) {
                foreach ($chunk as $ticket) {
                    Ticket::query()
                        ->where('id', $ticket->id)
                        ->update([
                            'machine_numbers' => json_encode($this->machineNumbers),
                            'machine_draw_at' => $now,
                            'is_winner' => count(array_intersect($ticket->selected_numbers, $this->machineNumbers)) == 6,
                        ]);
                }
            }
        });

        Log::info('LotteryDrawJob finished');
    }
}
