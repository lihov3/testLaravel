<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Requests extends Model
{
	protected $table = "requests";

    protected $fillable = [
        'user_id', 'request_user_id', 'status',
    ];

    public static function rules() {
        return [
            'request_user_id' => 'required|string',
        ];
    }
}
