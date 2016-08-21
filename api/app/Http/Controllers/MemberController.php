<?php

namespace App\Http\Controllers;

use App\Club;
use App\Member;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use Illuminate\Http\Response;
use Validator;

class MemberController extends Controller
{
    public function index()
    {
        $clubs = Member::all();

        if (count($clubs) == 0) {
            return new Response(null, 204);
        }

        return $clubs;
    }

    public function show(Request $request, Member $member)
    {

        return Member::with('clubs')->where('id', $member->id)->get();
    }

    public function store(Request $request)
    {
        try {
            $this->validateFormForStore($request);

            $member = $this->save($request);

            return new JsonResponse($member, 201);
        } catch (\Exception $e) {
            return $this->makeErrorResponse('Member story update', $e->getMessage());
        }
    }

    public function update(Request $request, Member $member)
    {
        try {
            $this->validateFormForUpdate($request);

            $this->doUpdate($request, $member);

            return new JsonResponse(Member::with('clubs')->where('id', $member->id)->get(), 200);
        } catch (\Exception $e) {
            return $this->makeErrorResponse('Member update error', $e->getMessage());
        }
    }

    public function destroy(Request $request, Member $member)
    {
        $member->delete();

        return new JsonResponse(null, 204);
    }

    /**
     * @param String $shortDescription
     * @param String|null $longDescription
     * @return JsonResponse
     */
    public function makeErrorResponse(String $shortDescription, String $longDescription = null)
    {
        $error = [
            'error' => $shortDescription,
            'error_description' => $longDescription,
        ];

        return new JsonResponse($error, 400);
    }

    /**
     * @param array $clubsID
     * @return \App\Club[]
     * @throws \Exception
     * @internal param Request $request
     */
    private function validateAndGetClub(Array $clubsID)
    {
        $clubs = [];

        foreach ($clubsID as $clubID) {
            if ($club = Club::find($clubID['id'])) {
                array_push($clubs, $club);
            } else {
                throw new \Exception('Invalid club');
            }
        }

        return $clubs;
    }

    /**
     * @param Request $request
     * @throws \Exception
     */
    private function validateFormForStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:members',
            'clubs' => 'required'
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors());
        }
    }

    private function validateFormForUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'op' => 'required',
            'path' => 'required',
            'value' => 'required',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors());
        }
    }

    /**
     * @param Request $request
     * @return Member
     */
    private function save(Request $request)
    {
        $clubs = $this->validateAndGetClub($request->clubs);

        $member = new Member();
        $member->name = $request->name;
        $member->save();

        foreach ($clubs as $club) {
            $member->clubs()->attach($club->id);
        }

        return $member;
    }

    /**
     * @param Request $request
     * @param Member $member
     */
    private function doUpdate(Request $request, Member $member)
    {
        switch ($request->op) {
            case 'add':
                if ($request->path == '/clubs') {
                    $clubs = $this->validateAndGetClub($request->value);

                    foreach ($clubs as $club) {
                        if (!$member->clubs()->find($club->id)) {
                            $member->clubs()->attach($club->id);
                        }
                    }
                }
                break;
            case 'remove':
                if ($request->path == '/clubs') {
                    $clubs = $this->validateAndGetClub($request->value);
                    foreach ($clubs as $club) {
                        $member->clubs()->detach($club->id);
                    }
                }
                break;
        }
    }

}
