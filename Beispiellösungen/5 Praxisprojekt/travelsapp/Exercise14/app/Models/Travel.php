<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Mapping\TravelsTable_V1 as Table;

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

    public $timestamps = false;

    protected $table = Table::TABLE_NAME;

    protected $fillable = [self::TITLE, self::LOCATION, self::START, self::END, self::AMOUNT, self::DETAILS];

    protected $casts = [
        self::START => 'datetime',
        self::END => 'datetime'
    ];
}
