<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $invoices = $request->user()->invoices();

        return response()->json($invoices);
    }

    public function details(Request $request, $id)
    {
        $invoice = $request->user()->findInvoice($id);

        return response()->json($invoice);
    }

    public function download(Request $request, $id)
    {
        return $request->user()->downloadInvoice($id, [
            'vendor'  => config('app.name'),
            'product' => 'Abonnement',
        ]);
    }
}
