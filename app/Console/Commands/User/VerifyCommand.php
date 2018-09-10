<?php

namespace App\Console\Commands\User;

use App\Entity\User;
use App\Services\Auth\RegisterService;
use Illuminate\Console\Command;

class VerifyCommand extends Command
{
    protected $signature = 'user:verify {email}';

    protected $description = 'Verify user by his email';

    private $registerService;

    public function __construct(RegisterService $registerService)
    {
        parent::__construct();

        $this->registerService = $registerService;
    }

    public function handle()
    {
        $email = $this->argument('email');

        if (!$user = User::where('email', $email)->first()) {
            $this->error('User not found with given email');
            return;
        }

        try {
            $this->registerService->verify($user->id);
        } catch (\DomainException $e) {
            $this->error($e->getMessage());
            return;
        }

        $this->info('Verified ' . $this->argument('email'));
    }
}
