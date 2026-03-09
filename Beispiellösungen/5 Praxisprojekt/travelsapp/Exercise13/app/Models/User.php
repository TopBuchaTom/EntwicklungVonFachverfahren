<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Mapping\UsersTable_V2 as Table;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    const ID = Table::COLUMN_ID;
    const NAME = Table::COLUMN_NAME;
    const EMAIL = Table::COLUMN_EMAIL;
    const PASSWORD = Table::COLUMN_PASSWORD;
    const EMAIL_VERIFIED_AT = Table::COLUMN_EMAIL_VERIFIED_AT;
    const REMEMBER_TOKEN = Table::COLUMN_REMEMBER_TOKEN;
    const IS_ADMIN = Table::COLUMN_IS_ADMIN;
    const IS_VERIFIER = Table::COLUMN_IS_VERIFIER;
    const IS_APPROVER = Table::COLUMN_IS_APPROVER;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        self::NAME,
        self::EMAIL,
        self::PASSWORD,
        self::IS_ADMIN,
        self::IS_VERIFIER,
        self::IS_APPROVER
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
