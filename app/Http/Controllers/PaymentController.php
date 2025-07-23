<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        return response()->json($request->user()->paymentMethods());
    }

    public function update(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string',
        ]);

        $user = $request->user();
        $user->updateDefaultPaymentMethod($request->payment_method);

        return response()->json(['message' => 'Méthode de paiement mise à jour.']);
    }
    
    public function success(Request $request)
    {
        return response()->json(['message' => 'Paiement réussi. Le user a été associé à son abonnement.']);
    }
    
    public function cancel()
    {

        return response()->json(['message' => 'Paiement échoué.']);
    }
}
