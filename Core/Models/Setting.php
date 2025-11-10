<?php

namespace Core\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditing;

class Setting extends Model
{
    use HasAuditing;

    public $primaryKey = 'idSETTINGS';
    protected $table = 'settings';
    public $timestamps = true;

    protected $fillable = [
        'ParameterName',
        'IntegerValue',
        'StringValue',
        'DecimalValue',
        'Unit',
        'Automatically_calculated',
        'Description',
        'created_by',
        'updated_by',
    ];
}
