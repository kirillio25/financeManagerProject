<?php

namespace App\Services\Cabinet;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProfileImportService
{
    public function import(UploadedFile $file, int $userId): bool
    {
        $lines = explode("\n", $file->get());
        DB::beginTransaction();

        try {
            foreach ($lines as $line) {
                $line = trim($line);
                if (!$line || !str_starts_with($line, 'INSERT INTO')) continue;

                $handled = false;

                if (str_starts_with($line, 'INSERT INTO categories_income')) {
                    if (!preg_match_all("/\('([^']+)', '([^']+)', '([^']+)'\)/", $line, $matches, PREG_SET_ORDER)) {
                        throw new \RuntimeException("Невалидный INSERT в categories_income: $line");
                    }
                    foreach ($matches as $m) {
                        DB::table('categories_income')->updateOrInsert([
                            'user_id' => $userId,
                            'name' => $m[1],
                        ], [
                            'created_at' => $m[2],
                            'updated_at' => $m[3],
                        ]);
                    }
                    $handled = true;
                }

                if (str_starts_with($line, 'INSERT INTO categories_expense')) {
                    if (!preg_match_all("/\('([^']+)', '([^']+)', '([^']+)'\)/", $line, $matches, PREG_SET_ORDER)) {
                        throw new \RuntimeException("Невалидный INSERT в categories_expense: $line");
                    }
                    foreach ($matches as $m) {
                        DB::table('categories_expense')->updateOrInsert([
                            'user_id' => $userId,
                            'name' => $m[1],
                        ], [
                            'created_at' => $m[2],
                            'updated_at' => $m[3],
                        ]);
                    }
                    $handled = true;
                }

                if (str_starts_with($line, 'INSERT INTO accounts')) {
                    if (!preg_match_all("/\('([^']+)', '([^']+)', '([^']+)', '([^']+)'\)/", $line, $matches, PREG_SET_ORDER)) {
                        throw new \RuntimeException("Невалидный INSERT в accounts: $line");
                    }
                    foreach ($matches as $m) {
                        DB::table('accounts')->updateOrInsert([
                            'user_id' => $userId,
                            'name' => $m[1],
                        ], [
                            'note' => $m[2],
                            'created_at' => $m[3],
                            'updated_at' => $m[4],
                        ]);
                    }
                    $handled = true;
                }

                if (str_starts_with($line, 'INSERT INTO debts')) {
                    if (!preg_match_all("/\((\d+), '([^']+)', ([\d.]+), '([^']*)', '([^']*)', (\d+), '([^']+)', '([^']+)'\)/", $line, $matches, PREG_SET_ORDER)) {
                        throw new \RuntimeException("Невалидный INSERT в debts: $line");
                    }
                    foreach ($matches as $m) {
                        DB::table('debts')->updateOrInsert([
                            'user_id' => $userId,
                            'debt_direction' => $m[1],
                            'name' => $m[2],
                        ], [
                            'amount' => $m[3],
                            'contact_method' => $m[4],
                            'description' => $m[5],
                            'status' => $m[6],
                            'created_at' => $m[7],
                            'updated_at' => $m[8],
                        ]);
                    }
                    $handled = true;
                }

                if (str_starts_with($line, 'INSERT INTO transactions')) {
                    if (!preg_match_all("/\(([\d.]+), (\d+), (\d+), (\d+), '([^']+)', '([^']+)'\)/", $line, $matches, PREG_SET_ORDER)) {
                        throw new \RuntimeException("Невалидный INSERT в transactions: $line");
                    }
                    foreach ($matches as $m) {
                        DB::table('transactions')->updateOrInsert([
                            'user_id' => $userId,
                            'amount' => $m[1],
                            'type_id' => $m[2],
                            'category_id' => $m[3],
                            'account_id' => $m[4],
                            'created_at' => $m[5],
                        ], [
                            'updated_at' => $m[6],
                        ]);
                    }
                    $handled = true;
                }

                if (!$handled) {
                    throw new \RuntimeException("Необработанная SQL-строка: $line");
                }
            }

            DB::commit();
            return true;
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Import failed: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return false;
        }
    }
}
