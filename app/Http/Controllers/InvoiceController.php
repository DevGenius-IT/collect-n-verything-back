<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Stripe\StripeClient;
use Illuminate\Pagination\LengthAwarePaginator;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['error' => 'Utilisateur non authentifié.'], 401);
        }

        // Récupère toutes les factures
        $invoices = $user->invoices();

        // Paramètres de pagination
        $page = max((int) $request->query('page', 1), 1);
        $perPage = min((int) $request->query('limit', 20), 100);

        // Transformer la collection en paginator Laravel
        $invoicesPaginated = new LengthAwarePaginator(
            $invoices->forPage($page, $perPage), // données de la page
            $invoices->count(),                  // total
            $perPage,                            // nombre par page
            $page,                               // page actuelle
            ['path' => $request->url(), 'query' => $request->query()] // conserver les paramètres
        );

        return response()->json($invoicesPaginated);
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
