<?php

namespace App\Http\Controllers;

use App\Club;
use App\Exceptions\ApiException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\MessageBag;
use Validator;

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
        try {
            $this->validateForm($request);

            $club = new Club();
            $club->name = $request->name;
            $club->save();

            return new JsonResponse([$club], 200);
        } catch (ApiException $e) {
            return $this->makeErrorResponse('Error storing club', $e->errors);
        }
    }

    public function destroy(Request $request, Club $club)
    {
        $club->delete();

        return new Response(null, 204);
    }

    /**
     * @param Request $request
     * @throws ApiException
     */
    private function validateForm(Request $request) {
        $validator = Validator::make($request->all(), ['name' => 'required|unique:clubs']);

        if ($validator->fails()) {
            throw new ApiException($validator->errors());
        }
    }

    /**
     * @param String $error
     * @param MessageBag $errors
     * @return JsonResponse
     */
    private function makeErrorResponse(String $error, MessageBag $errors)
    {
        $errors = [
            'error' => $error,
            'error_description' => $errors
        ];

        return new JsonResponse($errors, 400);
    }




}