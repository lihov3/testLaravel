<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use App\Models\Requests;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\AccessoryController;

class AccountController extends Controller
{
	private $user;

	public function __construct()
	{
		$this->user = JWTAuth::parseToken()->authenticate();
	}

    /*
    *   Show all the invitations to user;
    */
    public function showInvitation()
    {
        $invites = AccessoryController::getInvitations($this->user['id']);
        return response()->json(['error' => false, 'message' => $invites], 201);
    }

    /*
    *   Accept the invitation form user to requesting user ;
    */
    public function acceptInvitation(Request $req)
    {   
        $data = $req->all();
        $from = $data['request_user_id'];
        $to = $this->user['id'];
        if(!AccessoryController::isInvitationExist($from, $to)){
            return response()->json(['error' => true, 'message' => 'Invitation is not exist'], 404);
        }
        if(AccessoryController::isInvitationAccepted($from, $to)){
            return response()->json(['error' => true, 'message' => 'Invitation is accepted'], 400);
        }

        if(AccessoryController::createInvitation($to, $from, 'accept') && AccessoryController::updateInvitation($from, $to, 'accept'))
        {
            return response()->json(['error' => false, 'message' => 'Invitation Accepted'], 201);
        }
        
    }

    /*
    *   Cancel the invitation form user to requesting user ;
    */
    public function cancelInvitation(Request $req)
    {
        $data = $req->all();
        $from = $data['request_user_id'];
        $to = $this->user['id'];
        if(!$invite = AccessoryController::isInvitationExist($from, $to)) {
            return response()->json(['error' => true, 'message' => 'Invitation is to non-exist'], 409);
        }
        if(Requests::where('id', '=', $invite['id'])->delete())
        {
            return response()->json(['error' => false, 'message' => 'Invitation Deleted'], 201);
        }
        
    }

    /*
    *   Send the invitation form user to requesting user ;
    */
    public function sendInvitation(Request $req)
    {
    	$data = $req->all();

    	$validator = Validator::make($data, Requests::rules());
    	if($validator->fails()){
            return response()->json(['error' => true, 'message' => $validator->errors()->first()], 422);
        }
        
        if(AccessoryController::isInvitationExist($this->user['id'], $data['request_user_id'])) 
        {
            return response()->json(['error' => true, 'message' => 'Invitation is alredy exist'], 409);
        }
        if(!AccessoryController::isUserExist($data['request_user_id']))
        {
            return response()->json(['error' => true, 'message' => 'Invitation to non-existent user'], 404);
        }

        if(AccessoryController::createInvitation($this->user['id'], $data['request_user_id']))
        {
            return response()->json(['error' => false, 'message' => 'Invitation Created'], 201);
        }
    	
    }
}
