<?php

namespace App\Http\Controllers;

use App\Transaction;
use Illuminate\Http\Request;
use PDF;

class TransactionsController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q');
        $transactions = Transaction::orderBy('invoice_no', 'desc')
            ->where(function ($query) use ($q) {
                if ($q) {
                    $query->where('invoice_no', 'like', '%'.$q.'%');
                    $query->orWhere('customer', 'like', '%'.$q.'%');
                }
            })->paginate(25);

        return view('transactions.index', compact('transactions'));
    }

    public function show(Transaction $transaction)
    {
        return view('transactions.show', compact('transaction'));
    }

    public function pdf(Transaction $transaction)
    {
        // return view('transactions.pdf', compact('transaction'));
        $pdf = PDF::loadView('transactions.pdf', compact('transaction'));

        return $pdf->stream($transaction->invoice_no.'.faktur.pdf');
    }
}
