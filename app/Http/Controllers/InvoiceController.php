<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Stripe\StripeClient;

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
       $stripe = new StripeClient(config('services.stripe.secret'));

        try {
            $invoice = $stripe->invoices->retrieve($id, []);

            // Vérifie que la facture appartient bien au customer de l'utilisateur
            if ($invoice->customer !== $request->user()->stripe_id) {
                return response()->json(['error' => 'Cette facture ne vous appartient pas.'], 403);
            }

            // Récupère l’URL du PDF de la facture
            $pdfUrl = $invoice->invoice_pdf;

            return redirect($pdfUrl);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Impossible de récupérer la facture',
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
