<?php

namespace App\Libraries;

use Illuminate\Support\Facades\DB;

class LogicControllers{

	static public function checkEmail($email) {
		$exist_email = DB::table('users')->where('email', '=', strtolower($email))->first();
		if($exist_email !== NULL) return 422;
		return 200;
	}
}