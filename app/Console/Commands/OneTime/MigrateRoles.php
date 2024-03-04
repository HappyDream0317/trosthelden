<?php

namespace App\Console\Commands\OneTime;

use Illuminate\Console\Command;
use App\User;
use Illuminate\Support\Str;

class MigrateRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'one-time:migrate-roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate the old role structure to new schema';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        User::all()->collect()->each(function (User $user) {
            $origin = $user->b2bUser()->exists() ? $user->b2bUser->type : $user->role;
            $role = Str::lower(Str::replace('_', '-', $origin));
            $user->assignRole($role);
        });
        return 0;
    }
}