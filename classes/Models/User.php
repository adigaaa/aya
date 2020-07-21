<?php


namespace App\Models;


use App\Enums\Tables;

class User extends BaseModel
{
    protected $table = Tables::USERS;
    protected $columns = [
        'id',
        'username',
        'email',
        'password'
    ];
}
