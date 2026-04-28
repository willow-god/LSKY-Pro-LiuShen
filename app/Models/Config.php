<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property string $name
 * @property mixed $value
 */
class Config extends Model
{
    use HasFactory;

    protected $primaryKey = 'name';

    protected $fillable = ['name', 'value'];

    public $incrementing = false;

    protected $keyType = 'string';
}
