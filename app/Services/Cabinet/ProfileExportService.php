<?php
namespace App\Services\Cabinet;

use App\Models\User;
use App\Models\CategoriesIncome;
use App\Models\CategoriesExpense;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProfileExportService
{
    public function exportSql(User $user): StreamedResponse
    {
        $filename = 'user_export_' . $user->id . '.sql';

        return response()->streamDownload(function () use ($user) {
            echo "-- SQL export for user ID {$user->id}\n\n";

            // Categories: income
            foreach (CategoriesIncome::where('user_id', $user->id)->get() as $item) {
                printf(
                    "INSERT INTO categories_income (name, created_at, updated_at) VALUES ('%s', '%s', '%s');\n",
                    addslashes($item->name),
                    $item->created_at,
                    $item->updated_at
                );
            }

            echo "\n";

            // Categories: expense
            foreach (CategoriesExpense::where('user_id', $user->id)->get() as $item) {
                printf(
                    "INSERT INTO categories_expense (name, created_at, updated_at) VALUES ('%s', '%s', '%s');\n",
                    addslashes($item->name),
                    $item->created_at,
                    $item->updated_at
                );
            }

            echo "\n";

            // Accounts
            foreach ($user->accounts()->get() as $item) {
                printf(
                    "INSERT INTO accounts (name, note, created_at, updated_at) VALUES ('%s', %s, '%s', '%s');\n",
                    addslashes($item->name),
                    $item->note ? "'" . addslashes($item->note) . "'" : 'NULL',
                    $item->created_at,
                    $item->updated_at
                );
            }

            echo "\n";

            // Debts
            foreach ($user->debts()->get() as $item) {
                printf(
                    "INSERT INTO debts (debt_direction, name, amount, contact_method, description, status, created_at, updated_at) VALUES (%d, '%s', %.2f, %s, %s, %d, '%s', '%s');\n",
                    $item->debt_direction,
                    addslashes($item->name),
                    $item->amount,
                    $item->contact_method ? "'" . addslashes($item->contact_method) . "'" : 'NULL',
                    $item->description ? "'" . addslashes($item->description) . "'" : 'NULL',
                    $item->status,
                    $item->created_at,
                    $item->updated_at
                );
            }

            echo "\n";

            // Transactions — последними
            foreach ($user->transactions()->get() as $item) {
                printf(
                    "INSERT INTO transactions (amount, type_id, category_id, account_id, created_at, updated_at) VALUES (%.2f, %d, %d, %d, '%s', '%s');\n",
                    $item->amount,
                    $item->type_id,
                    $item->category_id,
                    $item->account_id,
                    $item->created_at,
                    $item->updated_at
                );
            }

        }, $filename);
    }
}

