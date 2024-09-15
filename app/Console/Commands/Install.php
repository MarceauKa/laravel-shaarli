<?php

namespace App\Console\Commands;

use App\Models\Link;
use App\Models\User;
use Illuminate\Console\Command;

class Install extends Command
{
    protected $signature = 'shaark:install';
    protected $description = 'Shaark installer';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        if (false === $this->confirm('All existing data will be erased')) {
            $this->comment('Ok. Bye.');
            return;
        }

        $this->comment('Migrations');
        $this->callSilent('migrate:fresh');

        if ($this->confirm('Default data?')) {
            $this->callSilent('db:seed');
            exec('php8 artisan scout:import ' . Link::class);
        }

        $name = $this->ask('User name?', 'Admin');
        $email = $this->ask('User email?', 'shaark@example.com');
        $pass = $this->ask('User pass?', 'secret');

        $user = User::count() ? User::first() : new User();

        $user->fill([
            'name' => $name,
            'email' => $email,
            'password' => \Illuminate\Support\Facades\Hash::make($pass),
            'api_token' => $pass === 'secret' ? 'api-token-secret' : User::generateApiToken(),
        ]);

        $user->save();
        $this->comment('User udpated');

        $this->comment('Installation done');
    }
}
