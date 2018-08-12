<?php

namespace Tests\Feature;

use App\Transaction;
use Tests\BrowserKitTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

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

    /** @test */
    public function user_can_see_search_transactions_by_date()
    {
        $transaction1 = factory(Transaction::class)->create(['created_at' => '2016-02-01']);
        $transaction2 = factory(Transaction::class)->create(['created_at' => '2016-02-02']);

        $this->loginAsUser();
        $this->visit(route('transactions.index', ['date' => '2016-02-01']));
        $this->see($transaction1->invoice_no);
        $this->dontSee($transaction2->invoice_no);
    }

    /** @test */
    public function user_can_see_search_transactions_by_invoice_no_and_date()
    {
        $transaction1 = factory(Transaction::class)->create([
            'invoice_no' => '123456',
            'created_at' => '2016-02-01',
        ]);
        $transaction2 = factory(Transaction::class)->create(['created_at' => '2016-02-01']);

        $this->loginAsUser();
        $this->visit(route('transactions.index', ['q' => '123', 'date' => '2016-02-01']));
        $this->see($transaction1->invoice_no);
        $this->dontSee($transaction2->invoice_no);
    }

    /** @test */
    public function user_can_see_search_transactions_by_customer_name_and_date()
    {
        $transaction1 = factory(Transaction::class)->create([
            'customer'   => ['name' => 'Nafies', 'phone' => '081234567890'],
            'created_at' => '2016-02-01',
        ]);
        $transaction2 = factory(Transaction::class)->create(['created_at' => '2016-02-01']);

        $this->loginAsUser();
        $this->visit(route('transactions.index', ['q' => 'Nafies', 'date' => '2016-02-01']));
        $this->see($transaction1->invoice_no);
        $this->dontSee($transaction2->invoice_no);
    }

    /** @test */
    public function user_can_see_search_transactions_by_customer_phone_and_date()
    {
        $transaction1 = factory(Transaction::class)->create([
            'customer'   => ['name' => 'Nafies', 'phone' => '081234567890'],
            'created_at' => '2016-02-01',
        ]);
        $transaction2 = factory(Transaction::class)->create([
            'customer'   => ['name' => 'Luthfi', 'phone' => '081234567891'],
            'created_at' => '2016-02-01',
        ]);

        $this->loginAsUser();
        $this->visit(route('transactions.index', ['q' => '7890', 'date' => '2016-02-01']));
        $this->see($transaction1->invoice_no);
        $this->dontSee($transaction2->invoice_no);
    }
}
