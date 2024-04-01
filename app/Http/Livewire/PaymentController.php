<?php
namespace App\Http\Livewire;

use Illuminate\Http\Request;
use Livewire\Component;

class PaymentController extends Component
{
public function handlePaymentNotification(Request $request)
{
// Traitez les données de notification de paiement ici
$requestData = $request->all();
dd($requestData);

// Logique de traitement des données de notification
// Mise à jour de l'état de la transaction, envoi de notifications, etc.

// Retournez une réponse appropriée à PayTabs
return response()->json(['status' => 'success']);
}
}
