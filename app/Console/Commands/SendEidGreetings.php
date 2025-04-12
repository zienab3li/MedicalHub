<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Mail\EidGreetingMail;
use Illuminate\Support\Facades\Mail;

class SendEidGreetings extends Command
{
    protected $signature = 'email:eid-greetings';
    protected $description = 'إرسال بريد تهنئة بعيد الفطر لجميع المستخدمين';

    public function handle()
    {
        $users = User::all();
        foreach ($users as $user) {
            Mail::to($user->email)->send(new EidGreetingMail($user));
        }

        $this->info('Emails has been sent 🎉');
    }
}
