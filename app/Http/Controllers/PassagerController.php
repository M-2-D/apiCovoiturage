<?php

namespace App\Http\Controllers;

use App\Models\Passager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PassagerController extends Controller
{
    public function index()
    {
        $passagers = Passager::with('user', 'reservations')->get();
        return response()->json($passagers, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $passager = Passager::create($request->all());

        return response()->json($passager, 201);
    }

    public function show(Passager $passager)
    {
        $passager->load('user', 'reservations');
        return response()->json($passager, 200);
    }

    public function update(Request $request, Passager $passager)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'sometimes|required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $passager->update($request->all());

        return response()->json($passager, 200);
    }

    public function destroy(Passager $passager)
    {
        $passager->delete();
        return response()->json(null, 204);
    }
}
