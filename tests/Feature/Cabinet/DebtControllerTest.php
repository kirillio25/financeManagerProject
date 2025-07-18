<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Debt;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DebtControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function test_index_returns_ok()
    {
        $response = $this->get('/debts');
        $response->assertStatus(200);
    }

    public function test_store_creates_debt()
    {
        $response = $this->post('/debts', [
            'name' => 'Тест',
            'debt_direction' => 0,
            'amount' => 1000,
            'contact_method' => 'телефон',
            'description' => 'описание',
        ]);

        $response->assertRedirect();

        $this->assertTrue(
            Debt::where('name', 'Тест')
                ->where('user_id', $this->user->id)
                ->where('amount', 1000)
                ->exists()
        );
    }

    public function test_update_changes_debt()
    {
        $debt = Debt::factory()->create(['user_id' => $this->user->id]);

        $response = $this->put("/debts/{$debt->id}", [
            'name' => 'Изменено',
            'debt_direction' => 1,
            'amount' => 2000,
            'contact_method' => 'почта',
            'description' => 'обновлено',
        ]);

        $response->assertRedirect();

        $this->assertTrue(
            Debt::where('id', $debt->id)
                ->where('name', 'Изменено')
                ->where('amount', 2000)
                ->exists()
        );
    }

    public function test_destroy_deletes_debt()
    {
        $debt = Debt::factory()->create(['user_id' => $this->user->id]);

        $response = $this->delete("/debts/{$debt->id}");

        $response->assertRedirect();
        $this->assertFalse(Debt::where('id', $debt->id)->exists());
    }

    public function test_toggle_status_changes_status()
    {
        $debt = Debt::factory()->create([
            'user_id' => $this->user->id,
            'status' => 1,
        ]);

        $response = $this->patch("/debts/{$debt->id}/toggle-status");
        $response->assertRedirect();

        $debt->refresh();
        $this->assertEquals(2, $debt->status);
    }
}
