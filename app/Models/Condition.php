<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Condition extends Model
{
    use HasFactory;

    public static $simpleOperands = ['=', '!=', '<', '>', '<=', '>='];
    public static $complexOperands = ['END WITH', 'START WITH', 'CONTAIN'];

    public static $operators = [
        ['name' => '[user] id', 'value' => 'u.id'],
        ['name' => '[user] name', 'value' => 'u.name'],
        ['name' => '[user] email', 'value' => 'u.email'],
        ['name' => '[user] idUpline', 'value' => 'u.idUpline'],
        ['name' => '[user] idUser', 'value' => 'u.idUser'],
        ['name' => '[user] mobile', 'value' => 'u.mobile'],
        ['name' => '[user] fullphone_number', 'value' => 'u.fullphone_number'],
        ['name' => '[user] status', 'value' => 'u.status'],
        ['name' => '[metta user] id', 'value' => 'metta.id'],
        ['name' => '[metta user] idUser', 'value' => 'metta.idUser'],
        ['name' => '[metta user] arFirstName', 'value' => 'metta.arFirstName'],
        ['name' => '[metta user] arLastName', 'value' => 'metta.arLastName'],
        ['name' => '[metta user] enFirstName', 'value' => 'metta.enFirstName'],
        ['name' => '[metta user] enLastName', 'value' => 'metta.enLastName'],
        ['name' => '[metta user] childrenCount', 'value' => 'metta.childrenCount'],
        ['name' => '[metta user] email', 'value' => 'metta.email'],
        ['name' => '[metta user] secondEmail', 'value' => 'metta.secondEmail'],
        ['name' => '[metta user] nationalID', 'value' => 'metta.nationalID'],
        ['name' => '[metta user] adresse', 'value' => 'metta.adresse'],
        ['name' => '[metta user] note', 'value' => 'metta.note'],
        ['name' => '[country] id', 'value' => 'country.id'],
        ['name' => '[country] name', 'value' => 'country.name'],
        ['name' => '[country] continant', 'value' => 'country.continant'],
        ['name' => '[country] apha2', 'value' => 'country.apha2'],
        ['name' => '[country] ExchangeRate', 'value' => 'country.ExchangeRate'],
        ['name' => '[country] lang', 'value' => 'country.lang'],
        ['name' => '[state] id', 'value' => 'state.id'],
        ['name' => '[state] name', 'value' => 'state.name'],
    ];

    protected $fillable = [
        'operand',
        'operator',
        'value',
        'group_id',
        'target_id',
    ];

    public function target()
    {
        return $this->belongsTo(Target::class, 'target_id', 'id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }

    public static function operands()
    {
        return array_merge(self::$simpleOperands, self::$complexOperands);
    }


}
