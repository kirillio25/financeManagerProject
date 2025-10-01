<?php

namespace Tests\Feature\Cabinet;

use App\Models\Debt;
use App\Models\User;
use App\Services\Cabinet\Currency\CurrencyRateService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
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

        // Мокаем курс доллара
        $mock = \Mockery::mock(CurrencyRateService::class);
        $mock->shouldReceive('getUsdRate')->andReturn(500.0); // курс для расчета

        App::instance(CurrencyRateService::class, $mock);
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

        $this->assertDatabaseHas('debts', [
            'name' => 'Тест',
            'user_id' => $this->user->id,
            'amount' => 2.00, // 1000 / 500
        ]);
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

        $this->assertDatabaseHas('debts', [
            'id' => $debt->id,
            'name' => 'Изменено',
            'amount' => 4.00, // 2000 / 500
        ]);
    }

    public function test_destroy_deletes_debt()
    {
        $debt = Debt::factory()->create(['user_id' => $this->user->id]);

        $response = $this->delete("/debts/{$debt->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('debts', ['id' => $debt->id]);
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
