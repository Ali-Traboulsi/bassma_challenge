<?php

namespace App\Console\Commands;

use App\Mail\email;
use App\Models\Admin;
use App\Models\User;
use App\Notifications\NotifyWithUsersRegistered;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class EmailUsersRegistered extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:users-registered';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Email With Registered Users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $lastDay = Carbon::now()->subDay();
        $registeredUsers = User::where('created_at', '>=', $lastDay)->count();

        $admin = Admin::findOrFail(1);
//        dd($admin = Auth::guard('admins')->check());
        $admin->notify(new NotifyWithUsersRegistered($registeredUsers));
//        Mail::to($admin->email)->send(new NotifyWithUsersRegistered($registeredUsers));
//        Mail::to($admin->email)->
    }
}
