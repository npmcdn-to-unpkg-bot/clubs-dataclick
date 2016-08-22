<?php

namespace App\Http\Controllers;

use App\Club;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ClubController extends Controller
{
    public function index()
    {
        $clubs = Club::all();

        if (count($clubs) == 0) {
            return new Response(null, 204);
        }

        return $clubs;
    }

    public function show(Request $request, Club $club)
    {
        return Club::with('members')->where('id', $club->id)->get();
    }

    public function store(Request $request)
    {
        $this->validate($request, ['name' => 'required|unique:clubs']);

        $club = new Club();
        $club->name = $request->name;
        $club->save();

        return new JsonResponse($club, 201);
    }

    public function destroy(Request $request, Club $club)
    {
        $club->delete();

        return new Response(null, 204);
    }

}