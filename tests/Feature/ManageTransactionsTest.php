<?php

namespace Tests\Feature;

use App\Transaction;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\BrowserKitTestCase;

class ManageTransactionsTest extends BrowserKitTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_can_see_transactions_in_transactions_index_page()
    {
        $transaction1 = factory(Transaction::class)->create();
        $transaction2 = factory(Transaction::class)->create();

        $this->loginAsUser();
        $this->visit(route('transactions.index'));
        $this->see($transaction1->invoice_no);
        $this->see($transaction2->invoice_no);
    }

    /** @test */
    public function user_can_see_search_transactions_by_invoice_number()
    {
        $transaction1 = factory(Transaction::class)->create();
        $transaction2 = factory(Transaction::class)->create();

        $this->loginAsUser();
        $this->visit(route('transactions.index', ['q' => $transaction2->invoice_no]));
        $this->dontSee($transaction1->invoice_no);
        $this->see($transaction2->invoice_no);
    }

    /** @test */
    public function user_can_see_search_transactions_by_customer_name()
    {
        $transaction1 = factory(Transaction::class)->create(['customer' => ['name' => 'Nafies', 'phone' => '081234567890']]);
        $transaction2 = factory(Transaction::class)->create();

        $this->loginAsUser();
        $this->visit(route('transactions.index', ['q' => 'nafies']));
        $this->see($transaction1->invoice_no);
        $this->dontSee($transaction2->invoice_no);
    }

    /** @test */
    public function user_can_see_search_transactions_by_customer_phone()
    {
        $transaction1 = factory(Transaction::class)->create(['customer' => ['name' => 'Nafies', 'phone' => '081234567890']]);
        $transaction2 = factory(Transaction::class)->create();

        $this->loginAsUser();
        $this->visit(route('transactions.index', ['q' => '7890']));
        $this->see($transaction1->invoice_no);
        $this->dontSee($transaction2->invoice_no);
    }
}
