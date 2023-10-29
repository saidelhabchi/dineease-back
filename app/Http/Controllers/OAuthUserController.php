<?php

namespace App\Http\Controllers;

use App\Models\OAuthUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OAuthUserController extends Controller
{
    public function connectCheckAction(Request $request): JsonResponse
    {
        $data = $request->all();

        /**
         * @var OAuthUser $potentialUser
         */
        $potentialUser = OAuthUser::where('email', $data['user']['email'])
            ->where('profile_image', $data['user']['image'])
            ->first();

        if($potentialUser != null) {
            return response()->json([
                "feedback" => "User has been logged in successfully.",
                "user" => $potentialUser,
                "token" => $potentialUser->createToken("API token for " . $potentialUser->getAttribute('name'))->plainTextToken
            ], 200);
        }else {

            /**
             * @var OAuthUser $user
             */
            $user = new OAuthUser();

            $user->setAttribute('name', $data['user']['name']);
            $user->setAttribute('email', $data['user']['email']);
            $user->setAttribute('profile_image', $data['user']['image']);
            $user->setAttribute('role', $data['role']);
            $user->setCreatedAt(new \DateTimeImmutable());
            $user->setUpdatedAt(new \DateTimeImmutable());

            $user->save();

            return response()->json([
                'feedback' => "New User has been registered to the app",
                "user" => $user,
                "token" => $potentialUser->createToken("API token for " . $user->getAttribute('name'))->plainTextToken
            ], 201);
        }
    }
}
