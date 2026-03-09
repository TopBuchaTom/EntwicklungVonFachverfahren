<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Mapping\TravelsTable_V2 as Table;

class Travel extends Model
{
    use HasFactory;

    const ID = Table::COLUMN_ID;
    const TITLE = Table::COLUMN_TITLE;
    const LOCATION = Table::COLUMN_LOCATION;
    const START = Table::COLUMN_START;
    const END = Table::COLUMN_END;
    const AMOUNT = Table::COLUMN_AMOUNT;
    const DETAILS = Table::COLUMN_DETAILS;
    const APPLICANT_ID = Table::COLUMN_APPLICANT_ID;

    const APPLICANT = "applicant";
    const PARTICIPANTS = "participants";
    const REVIEWS = "reviews";

    public $timestamps = false;

    protected $table = Table::TABLE_NAME;

    protected $fillable = [self::TITLE, self::LOCATION, self::START, self::END, self::AMOUNT, self::DETAILS, self::APPLICANT_ID];

    protected $casts = [
        self::START => 'datetime',
        self::END => 'datetime'
    ];

    public function applicant() {
        return $this->hasOne(User::class, User::ID, self::APPLICANT_ID);
    }

    public function participants() {
        return $this->belongsToMany(User::class, 'participants');
    }

    public function reviews() {
        return $this->hasMany(Review::class);
    }
}
