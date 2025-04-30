<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use Carbon\Carbon;

class CompletePastAppointments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:complete-past-appointments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
    
        $updatedCount = Appointment::where('status', '!=', 'completed')
            ->where(function ($query) use ($now) {
                $query->whereDate('appointment_date', '<', $now->toDateString())
                    ->orWhere(function ($q) use ($now) {
                        $q->whereDate('appointment_date', $now->toDateString())
                          ->whereTime('appointment_time', '<=', $now->toTimeString());
                    });
            })
            ->update(['status' => 'completed']);
    
        $this->info("Updated $updatedCount appointments to completed.");
    }
    
}
