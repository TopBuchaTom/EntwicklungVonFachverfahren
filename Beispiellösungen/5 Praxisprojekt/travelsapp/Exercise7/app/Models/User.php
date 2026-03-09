<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Mapping\UsersTable_V1 as Table;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    const ID = Table::COLUMN_ID;
    const NAME = Table::COLUMN_NAME;
    const EMAIL = Table::COLUMN_EMAIL;
    const PASSWORD = Table::COLUMN_PASSWORD;
    const EMAIL_VERIFIED_AT = Table::COLUMN_EMAIL_VERIFIED_AT;
    const REMEMBER_TOKEN = Table::COLUMN_REMEMBER_TOKEN;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        self::NAME,
        self::EMAIL,
        self::PASSWORD,
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        self::PASSWORD,
        self::REMEMBER_TOKEN,
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        self::EMAIL_VERIFIED_AT => 'datetime',
        self::PASSWORD => 'hashed',
    ];
}
