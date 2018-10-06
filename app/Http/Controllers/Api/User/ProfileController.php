<?php

namespace App\Http\Controllers\Api\User;

 use App\Http\Controllers\Controller;
use App\Http\Requests\User\ProfileEditRequest;
use App\Http\Resources\ProfileResource;
use App\Services\User\ProfileService;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    private $service;

    public function __construct(ProfileService $service)
    {
        $this->service = $service;
    }

    /**
     * @SWG\Get(
     *     path="/user",
     *     tags={"Profile"},
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Schema(ref="#/definitions/Profile"),
     *     ),
     *     security={{"Bearer": {}, "OAuth2": {}}}
     * )
     */
    public function show(Request $request)
    {
        return new ProfileResource($request->user());
    }

    /**
     * @SWG\Put(
     *     path="/user",
     *     tags={"Profile"},
     *     @SWG\Parameter(name="body", in="body", required=true, @SWG\Schema(ref="#/definitions/ProfileEditRequest")),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *     ),
     *     security={{"Bearer": {}, "OAuth2": {}}}
     * )
     */
    public function update(ProfileEditRequest $request)
    {
        $this->service->edit($user = $request->user(), $request);

        return new ProfileResource($user);
    }
}
