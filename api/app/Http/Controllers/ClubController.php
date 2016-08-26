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
    public function index(Request $request)
    {
        $clubs = $this->getAllClubs();

        if (count($clubs) == 0) {
            return new Response(null, 204);
        } else {
            return new JsonResponse($this->makeIndexResponse($request, $clubs));
        }
    }

    public function show(Request $request, Club $club)
    {
        return $this->getClubById($club->id);
    }

    public function store(Request $request)
    {
        try {
            $this->validateClub($request);

            $club = $this->saveClub($request);

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
    private function validateClub(Request $request)
    {
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

    /**
     * @param Request $request
     * @param $clubs
     * @return mixed
     */
    private function makeIndexResponse(Request $request, $clubs)
    {
        if ($fields = $request->fields) {
            $fields = explode(',', $fields);
            array_push($fields, 'id');

            return $clubs->makeHidden(['name', 'members'])->makeVisible($fields);
        } else {
            return $clubs;
        }
    }

    /**
     * @param bool $withMembers
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getAllClubs($withMembers = true)
    {
        if ($withMembers) {
            $clubs = Club::with('members')->get();
        } else {
            $clubs = Club::all();
        }

        return $clubs;
    }

    /**
     * @param string $id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getClubById($id)
    {
        return Club::with('members')->where('id', '=', $id)->get();
    }

    /**
     * @param Request $request
     * @return Club
     */
    private function saveClub(Request $request)
    {
        $club = new Club();
        $club->name = $request->name;
        $club->save();

        return $club;
    }
}
