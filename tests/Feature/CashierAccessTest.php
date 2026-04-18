<?php

namespace Tests\Feature;

use App\Models\Level;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CashierAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_cashier_login(): void
    {
        $this->get('/')
            ->assertRedirect(route('cashier.login'));
    }

    public function test_cashier_can_login_and_access_pos(): void
    {
        $cashier = $this->createUserWithLevel('KSR', 'kasir1');

        $this->post('/login', [
            'username' => 'kasir1',
            'password' => 'password',
        ])->assertRedirect(route('cashier.index'));

        $this->assertAuthenticatedAs($cashier);

        $this->get('/')
            ->assertOk()
            ->assertSee('Cashier Session');
    }

    public function test_non_cashier_cannot_login_to_pos(): void
    {
        $this->createUserWithLevel('ADM', 'admin1');

        $this->from(route('cashier.login'))
            ->post('/login', [
                'username' => 'admin1',
                'password' => 'password',
            ])
            ->assertRedirect(route('cashier.login'))
            ->assertSessionHasErrors('username');

        $this->assertGuest();
    }

    protected function createUserWithLevel(string $levelCode, string $username): User
    {
        $level = Level::query()->create([
            'level_kode' => $levelCode,
            'level_nama' => $levelCode === 'KSR' ? 'Kasir' : 'Admin',
        ]);

        return User::query()->create([
            'level_id' => $level->getKey(),
            'username' => $username,
            'email' => "{$username}@example.com",
            'nama' => ucfirst($username),
            'password' => 'password',
        ]);
    }
}
