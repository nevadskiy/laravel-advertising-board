<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Controllers\Controller;
use App\Services\Auth\RegisterService;
use Illuminate\Http\Response;

class RegisterController extends Controller
{
    protected $registerService;

    public function __construct(RegisterService $registerService)
    {
        $this->registerService = $registerService;
    }

    /**
     * @SWG\Post(
     *     path="/register",
     *     tags={"Profile"},
     *     @SWG\Parameter(name="body", in="body", required=true, @SWG\Schema(ref="#/definitions/RegisterRequest")),
     *     @SWG\Response(
     *         response=201,
     *         description="Success response",
     *     )
     * )
     */
    public function register(RegisterRequest $request)
    {
        $this->registerService->register($request);

        return response()->json([
            'success' => 'Check your email and click on the link to verify.'
        ], Response::HTTP_CREATED);
    }
}
