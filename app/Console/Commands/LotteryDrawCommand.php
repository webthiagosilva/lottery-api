<?php

namespace App\Console\Commands;

use App\Jobs\LotteryDrawJob;
use Illuminate\Console\Command;

class LotteryDrawCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lottery:draw';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the lottery draw';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        LotteryDrawJob::dispatch();

        return Command::SUCCESS;
    }
}
