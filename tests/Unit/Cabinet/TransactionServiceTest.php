<?php 

namespace Tests\Unit\Cabinet;

use Tests\TestCase;
use App\Models\Transaction;
use App\Services\Cabinet\Transaction\TransactionService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransactionServiceTest extends TestCase
{
    use RefreshDatabase;

    public function it_creates_transaction()
    {
        $service = new TransactionService();

        $service->store([
            'user_id' => 1,
            'amount' => 10.55,
            'type_id' => 1,
            'category_id' => 2,
            'account_id' => 3,
            'date' => '2024-07-04',
        ]);
        //проверяем что записи появились
        $this->assertDatabaseHas('transactions', [
            'user_id' => 1,
            'amount' => 10.55,
            'type_id' => 1,
            'category_id' => 2,
            'account_id' => 3,
            'created_at' => '2024-07-04 00:00:00',
        ]);
    }
}
