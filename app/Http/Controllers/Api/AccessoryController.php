<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Requests;


use JWTAuth;

class AccessoryController extends Controller
{

    /*
    *   Return response with auth token;
    */
    static function respondWithToken($token, $message = "", $code = 200)
    {
        return response()->json([
            'error' => false,
            'message' => $message,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ], $code);
    }

    /*
    *   Check is the user exist;
    */
    static function isUserExist($id) 
    {
        return User::where('id', '=', $id)->first();
    }

    /*
    *   Check is the invitation exist;
    */
    static function isInvitationExist($from, $to)
    {
        return Requests::where('user_id', '=', $from)->where('request_user_id', '=', $to)->first();
    }

    /*
    *   Check is the invitation accepted;
    */
    static function isInvitationAccepted($from, $to)
    {
        $invite = Requests::select('status')->where('user_id', '=', $from)->where('request_user_id', '=', $to)->first();
        if($invite['status'] == 'accept')
        {
            return true;
        }
        return false;
    }

    /*
    *   Create the invitation by id user who send invitations and user id for whom the invitation;
    */
    static function createInvitation($from, $to, $status = 'unmarked')
    {
        return Requests::create([
                    "user_id" => $from,
                    "request_user_id" => $to,
                    "status" => $status,
                ]);
    }

    /*
    *   Update the invitation by id user who send invitations and user id for whom the invitation;
    */
    static function updateInvitation($from, $to, $status)
    {
        return Requests::where('user_id', '=', $from)->where('request_user_id', '=', $to)->update(['status' => $status]);
    }
    /*
    *   Get all the invitations to user;
    */
    static function getInvitations($user_id)
    {
        return Requests::select('requests.user_id AS from_user_id', 'users.name AS from_user_name', 'requests.status')->join('users', 'users.id', '=', 'requests.user_id')->where('request_user_id', '=', $user_id)->get();
    }
}
