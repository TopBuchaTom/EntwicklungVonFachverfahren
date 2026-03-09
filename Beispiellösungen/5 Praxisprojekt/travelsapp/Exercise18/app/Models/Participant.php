<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Mapping\ParticipantsTable_V1 as Table;

class Participant extends Model
{
    use HasFactory;

    const ID = Table::COLUMN_ID;
    const USER_ID = Table::COLUMN_USER_ID;
    const TRAVEL_ID = Table::COLUMN_TRAVEL_ID;

    public $timestamps = false;

    protected $table = Table::TABLE_NAME;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        self::USER_ID,
        self::TRAVEL_ID,
    ];
}
