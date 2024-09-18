<?php

namespace App\Http\Controllers;

use App\Models\Paiement;
use App\Models\PaiementLiquide;
use App\Models\PaiementMoyenDeTransfert;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PaiementController extends Controller
{
    public function index()
    {
        $paiements = Paiement::with(['paiementLiquide', 'paiementMoyenDeTransfert'])->get();
        return response()->json(['paiements' => $paiements], Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $request->validate([
            'reservation_id' => 'required|exists:reservations,id',
            'montant' => 'required|numeric',
            'date_paiement' => 'required|date',
            'type' => 'required|string|in:liquide,moyen_de_transfert',
           
        ]);

        $paiement = Paiement::create($request->only(['reservation_id', 'montant', 'date_paiement', 'type']));

        if ($request->type === 'liquide') {
            PaiementLiquide::create([
                'paiement_id' => $paiement->id,
              
            ]);
        } else if ($request->type === 'moyen_de_transfert') {
            PaiementMoyenDeTransfert::create([
                'paiement_id' => $paiement->id,
               
            ]);
        }

        return response()->json(['paiement' => $paiement], Response::HTTP_CREATED);
    }

    public function show(Paiement $paiement)
    {
        $paiement->load(['paiementLiquide', 'paiementMoyenDeTransfert']);
        return response()->json(['paiement' => $paiement], Response::HTTP_OK);
    }

    public function update(Request $request, Paiement $paiement)
    {
        $request->validate([
            'reservation_id' => 'required|exists:reservations,id',
            'montant' => 'required|numeric',
            'date_paiement' => 'required|date',
            'type' => 'required|string|in:liquide,moyen_de_transfert',
            
        ]);

        $paiement->update($request->only(['reservation_id', 'montant', 'date_paiement', 'type']));

        if ($request->type === 'liquide') {
            $paiement->paiementLiquide()->updateOrCreate(
                ['paiement_id' => $paiement->id],
              
            );
        } else if ($request->type === 'moyen_de_transfert') {
            $paiement->paiementMoyenDeTransfert()->updateOrCreate(
                ['paiement_id' => $paiement->id],
              
            );
        }

        return response()->json(['paiement' => $paiement], Response::HTTP_OK);
    }

    public function destroy(Paiement $paiement)
    {
        $paiement->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
