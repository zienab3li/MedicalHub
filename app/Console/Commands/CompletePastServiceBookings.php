<?php

namespace App\Console\Commands;

use App\Models\ServiceBooking;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CompletePastServiceBookings extends Command
{
    protected $signature = 'app:complete-past-service-bookings';
    protected $description = 'Mark past service bookings as completed';

    public function handle()
    {
        ServiceBooking::where('status', 'pending')
            ->where(function ($query) {
                $now = Carbon::now();
                $query->where('appointment_date', '<', $now->toDateString())
                    ->orWhere(function ($q) use ($now) {
                        $q->where('appointment_date', $now->toDateString())
                            ->where('appointment_time', '<=', $now->toTimeString());
                    });
            })
            ->update(['status' => 'completed']);

        $this->info('Past service bookings have been marked as completed.');
    }
} 