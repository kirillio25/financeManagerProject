<?php

namespace Tests\Unit\Cabinet;

use Tests\TestCase;
use App\Models\CategoriesExpense;
use App\Models\User;
use App\Services\Cabinet\CategoriesExpenseService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoriesExpenseServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_categories_expense_returns_only_user_categories()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        CategoriesExpense::factory()->create(['user_id' => $user->id, 'name' => 'Продукты']);
        CategoriesExpense::factory()->create(['user_id' => $user->id, 'name' => 'Транспорт']);
        CategoriesExpense::factory()->create(['user_id' => $otherUser->id, 'name' => 'Чужая категория']);

        $this->actingAs($user);

        $service = new CategoriesExpenseService();
        $categories = $service->getCategoriesExpense();

        $this->assertCount(2, $categories);
        $this->assertTrue($categories->pluck('name')->contains('Продукты'));
        $this->assertTrue($categories->pluck('name')->contains('Транспорт'));
        $this->assertFalse($categories->pluck('name')->contains('Чужая категория'));
    }
}
