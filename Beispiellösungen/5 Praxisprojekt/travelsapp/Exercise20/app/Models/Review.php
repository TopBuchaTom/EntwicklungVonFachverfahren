<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Mapping\ReviewsTable_V1 as Table;

class Review extends Model
{
    use HasFactory;

    const ID = Table::COLUMN_ID;
    const TRAVEL_ID = Table::COLUMN_TRAVEL_ID;
    const USER_ID = Table::COLUMN_USER_ID;
    const TYPE = Table::COLUMN_TYPE;
    const STATE = Table::COLUMN_STATE;
    const CHANGED = Table::COLUMN_CHANGED;
    const COMMENT = Table::COLUMN_COMMENT;

    const USER = "user";

    protected $table = Table::TABLE_NAME;

    protected $fillable = [self::TRAVEL_ID, self::USER_ID, self::TYPE, self::STATE, self::CHANGED, self::COMMENT];

    public function user() {
        return $this->hasOne(User::class, User::ID, Review::USER_ID);
    }
}
