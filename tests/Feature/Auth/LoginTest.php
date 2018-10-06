<?php

namespace Tests\Feature;

use App\Entity\User\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_opens_form(): void
    {
        $response = $this->get('/login');

        $response
            ->assertStatus(200)
            ->assertSee('Login');
    }

    /** @test */
    public function it_validates_wrong_input(): void
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => '',
        ]);

        $response
            ->assertStatus(302)
            ->assertSessionHasErrors(['email', 'password']);
    }

    /** @test */
    public function it_shows_message_about_waiting_for_verifing(): void
    {
        $user = factory(User::class)->create(['status' => User::STATUS_WAIT]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'secret',
        ]);

        $response
            ->assertStatus(302)
            ->assertRedirect('/')
            ->assertSessionHas('error', 'You need to confirm your account. Please check your email');
    }

    /** @test */
    public function it_logins_verified_user(): void
    {
        $user = factory(User::class)->create(['status' => User::STATUS_ACTIVE]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'secret',
        ]);

        $response
            ->assertStatus(302)
            ->assertRedirect('/cabinet');

        $this->assertAuthenticatedAs($user);
    }
}
