<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionWebTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test a user can update their own transaction.
     */
    public function test_user_can_update_own_transaction(): void
    {
        $user = User::factory()->create();
        
        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'Makanan',
            'type' => 'expense',
        ]);

        $newCategory = Category::create([
            'user_id' => $user->id,
            'name' => 'Transportasi',
            'type' => 'expense',
        ]);

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'type' => 'expense',
            'amount' => 50000.00,
            'description' => 'Makan Siang',
            'transaction_date' => '2026-06-08',
        ]);

        $response = $this->actingAs($user)->put("/transactions/{$transaction->id}", [
            'category_id' => $newCategory->id,
            'type' => 'expense',
            'amount' => 75000.00,
            'description' => 'Beli bensin',
            'transaction_date' => '2026-06-09',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Transaksi berhasil diperbarui!');

        $transaction->refresh();
        $this->assertEquals($newCategory->id, $transaction->category_id);
        $this->assertEquals(75000.00, $transaction->amount);
        $this->assertEquals('Beli bensin', $transaction->description);
        $this->assertEquals('2026-06-09', $transaction->transaction_date->format('Y-m-d'));
    }

    /**
     * Test a user cannot update other user's transaction.
     */
    public function test_user_cannot_update_other_user_transaction(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $category = Category::create([
            'user_id' => $user1->id,
            'name' => 'Makanan',
            'type' => 'expense',
        ]);

        $transaction = Transaction::create([
            'user_id' => $user1->id,
            'category_id' => $category->id,
            'type' => 'expense',
            'amount' => 50000.00,
            'description' => 'Makan Siang',
            'transaction_date' => '2026-06-08',
        ]);

        $response = $this->actingAs($user2)->put("/transactions/{$transaction->id}", [
            'category_id' => $category->id,
            'type' => 'expense',
            'amount' => 75000.00,
            'description' => 'Hacked',
            'transaction_date' => '2026-06-08',
        ]);

        $response->assertStatus(403);
        
        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'description' => 'Makan Siang', // unchanged
            'amount' => 50000.00,
        ]);
    }
}
