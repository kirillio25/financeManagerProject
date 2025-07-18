<?php


namespace Tests\Feature\Cabinet;

use App\Models\Account;
use App\Models\CategoriesIncome;
use App\Models\User;
use App\Services\Cabinet\Currency\CurrencyRateService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use function Tests\Feature\now;
use function Tests\Feature\number_format;
use function Tests\Feature\route;

class StoreTransactionTest extends TestCase
{
    use RefreshDatabase;

    public function it_stores_transaction_correctly()
    {
        $user = User::factory()->create();
        $category = CategoriesIncome::factory()->create(['user_id' => $user->id]);
        $account = Account::factory()->create(['user_id' => $user->id]);

        // Мокируем именно тот класс, который инъектируется в контроллер:
        $this->mock(CurrencyRateService::class, function ($mock) {
            $mock->shouldReceive('getUsdRate')->once()->andReturn(520.0);
        });

        $response = $this->actingAs($user)->post(route('monthlyStats.store'), [
            'amount'       => 1000,
            'type_id'      => 1,
            'category_id'  => $category->id,
            'account_id'   => $account->id,
            'date'         => now()->format('Y-m-d'),
        ]);

        $response->assertRedirect();

        $expectedAmount = number_format(1000 / 520, 2); // '1.92'

        $this->assertDatabaseHas('transactions', [
            'user_id'     => $user->id,
            'amount'      => $expectedAmount,
            'type_id'     => 1,
            'category_id' => $category->id,
            'account_id'  => $account->id,
        ]);
    }
}
