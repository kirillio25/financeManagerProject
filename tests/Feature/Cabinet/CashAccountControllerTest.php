<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Account;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CashAccountControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_accounts_page()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/accounts');
        $response->assertOk();
    }

    public function test_user_can_create_account()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/accounts', [
            'name' => 'Тестовый счёт',
            'note' => 'Примечание',
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('accounts', ['name' => 'Тестовый счёт']);
    }

    public function test_user_can_update_account()
    {
        $user = User::factory()->create();
        $account = Account::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->put("/accounts/{$account->id}", [
            'name' => 'Обновлённый счёт',
            'note' => 'Новое примечание',
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('accounts', ['id' => $account->id, 'name' => 'Обновлённый счёт']);
    }

    public function test_user_can_delete_account()
    {
        $user = User::factory()->create();
        $account = Account::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->delete("/accounts/{$account->id}");
        $response->assertRedirect();
        $this->assertDatabaseMissing('accounts', ['id' => $account->id]);
    }
}
