<?php

namespace App\Console\Commands\User;

use App\Entity\User\User;
use Illuminate\Console\Command;

class RoleCommand extends Command
{
    protected $signature = 'user:role {email} {role}';

    protected $description = 'Set role for user';

    public function handle()
    {
        $email = $this->argument('email');
        $role = $this->argument('role');

        if (!$user = User::where('email', $email)->first()) {
            $this->error('User not found with given email');
            return;
        }

        try {
            $user->changeRole($role);
        } catch (\DomainException | \InvalidArgumentException $e) {
            $this->error($e->getMessage());
            return;
        }

        $this->info('Role has been successfully changed.');
    }
}
