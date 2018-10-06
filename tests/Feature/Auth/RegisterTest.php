<?php

namespace Tests\Feature;

use App\Entity\User\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_opens_form(): void
    {
        $response = $this->get('/register');

        $response
            ->assertStatus(200)
            ->assertSee('Register');
    }

    /** @test */
    public function it_validates_wrong_input(): void
    {
        $response = $this->post('/register', [
            'name' => '',
            'email' => '',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response
            ->assertStatus(302)
            ->assertSessionHasErrors(['email', 'password']);
    }

    /** @test */
    public function it_registers_user(): void
    {
        $user = factory(User::class)->make();

        $response = $this->post('/register', [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ]);

        $response
            ->assertStatus(302)
            ->assertRedirect('/login')
            ->assertSessionHas('success', 'Check your email and click on the link to verify.');
    }

    /** @test */
    public function it_validates_wrong_verify_token(): void
    {
        $response = $this->get('/verify/' . Str::random());

        $response
            ->assertStatus(302)
            ->assertRedirect('/login')
            ->assertSessionHas('error', 'Sorry, your link cannot be identified.');
    }

    /** @test */
    public function it_verifies_user(): void
    {
        $user = factory(User::class)->create([
            'status' => User::STATUS_WAIT,
            'verify_token' => Str::random(),
        ]);

        $response = $this->get('/verify/' . $user->verify_token);

        $response
            ->assertStatus(302)
            ->assertRedirect('/login')
            ->assertSessionHas('success', 'Your email address is verified. You can now login.');
    }
}
