<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PruneOldAvailability extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:prune-old-availability {--days=30 : Keep this many days of availability (inclusive)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete availability rows older than N days (optional cleanup)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = (int) $this->option('days');
        if ($days < 1) {
            $this->error('--days must be >= 1');
            return self::FAILURE;
        }

        $cutoff = now()->startOfDay()->subDays($days)->toDateString();

        $deleted = DB::table('availabilities')
            ->where('date', '<', $cutoff)
            ->delete();

        $this->info("Deleted {$deleted} availability rows older than {$cutoff}.");

        return self::SUCCESS;
    }
}
