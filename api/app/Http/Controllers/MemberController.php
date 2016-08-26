<?php

namespace App\Http\Controllers;

use App\Club;
use App\Exceptions\ApiException;
use App\Member;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use Illuminate\Http\Response;
use Illuminate\Support\MessageBag;
use Validator;

class MemberController extends Controller
{
    public function index()
    {
        $clubs = $this->getAllMembers();

        if (count($clubs) == 0) {
            return new Response(null, 204);
        } else {
            return $clubs;
        }
    }

    public function show(Request $request, Member $member)
    {
        return $this->getMemberById($member->id);
    }

    public function store(Request $request)
    {
        try {
            $this->validateMemberForStore($request);

            $member = $this->saveMember($request);

            return new JsonResponse([$member], 200);
        } catch (ApiException $e) {
            return $this->makeErrorResponse('Error storing member', $e->errors);
        }
    }

    public function update(Request $request, Member $member)
    {
        try {
            $this->validateMemberForUpdate($request);

            $this->updateMember($request, $member);

            return new JsonResponse(Member::with('clubs')->where('id', $member->id)->get(), 200);
        } catch (ApiException $e) {
            return $this->makeErrorResponse('Error updating member', $e->errors);
        }
    }

    public function destroy(Request $request, Member $member)
    {
        $member->delete();

        return new JsonResponse(null, 204);
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

    private function validateMemberForStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:members',
            'clubs' => 'required',
            'clubs.*.id' => 'exists:clubs'
        ]);

        if ($validator->fails()) {
            throw new ApiException($validator->errors());
        }
    }

    private function validateMemberForUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'op' => 'required',
            'path' => 'required',
            'value' => 'required',
            'value.*.id' => 'exists:clubs'
        ]);

        if ($validator->fails()) {
            throw new ApiException($validator->errors());
        }
    }

    /**
     * @param Request $request
     * @return Member
     */
    private function saveMember(Request $request)
    {
        $member = new Member();
        $member->name = $request->name;
        $member->save();

        foreach ($request->clubs as $club) {
            $member->clubs()->attach($club['id']);
        }

        return $member;
    }

    /**
     * @param Request $request
     * @param Member $member
     */
    private function updateMember(Request $request, Member $member)
    {
        switch ($request->op) {
            case 'add':
                if ($request->path == '/clubs') {
                    foreach ($request->value[0] as $clubId) {
                        if (!$member->clubs()->find($clubId)) {
                            $member->clubs()->attach(Club::find($clubId));
                        }
                    }
                }
                break;
            case 'delete':
                if ($request->path == '/clubs') {
                    foreach ($request->value[0] as $clubId) {
                        $member->clubs()->detach(Club::find($clubId));
                    }
                }
                break;
        }
    }

    /**
     * @param bool $withClubs
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getAllMembers($withClubs = false)
    {
        if ($withClubs) {
            $clubs = Member::with('clubs')->get();
        } else {
            $clubs = Member::all();
        }

        return $clubs;
    }

    /**
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getMemberById($id)
    {
        return Member::with('clubs')->where('id', $id)->get();
    }
}
