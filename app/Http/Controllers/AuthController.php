<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;
use Illuminate\Http\JsonResponse;

class AuthController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(RegisterRequest $request): JsonResponse
    {

        $input = $request->validated();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['id'] =  $user->id;
        $success['name'] =  $user->name;

        return $this->sendResponse($success, 'User register successfully.');
    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request): JsonResponse
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $expiration = config('sanctum.expiration');

            $user = Auth::user();
            $success['token'] =  $user->createToken('onflymode')->plainTextToken;
            $success['name'] =  $user->name;
            $success['expires_at'] =  Carbon::now()->addMinute($expiration)->toDateTimeString();

            return $this->sendResponse($success, 'User login successfully.');
        }
        else{
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        }
    }
}
