<?php

namespace Tests\Unit\Cabinet;

use Tests\TestCase;
use App\Models\CategoriesIncome;
use App\Models\User;
use App\Services\Cabinet\CategoriesIncomeService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoriesIncomeServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_categories_income_returns_only_user_categories()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        CategoriesIncome::factory()->create(['user_id' => $user->id, 'name' => 'Фриланс 2.0']);
        CategoriesIncome::factory()->create(['user_id' => $user->id, 'name' => 'Нашел']);
        CategoriesIncome::factory()->create(['user_id' => $otherUser->id, 'name' => 'Прочее']);

        $this->actingAs($user);

        $service = new CategoriesIncomeService();
        $categories = $service->getCategoriesIncome();

        $this->assertCount(2, $categories);
        $this->assertTrue($categories->pluck('name')->contains('Фриланс 2.0'));
        $this->assertTrue($categories->pluck('name')->contains('Нашел'));
        $this->assertFalse($categories->pluck('name')->contains('Прочее'));
    }
}
