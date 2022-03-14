<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property-read string $name
 * @property-read string $ticker
 * @property-read int $accuracy
 */
class Currency extends Model
{
    use HasFactory;
}
