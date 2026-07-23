<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EpreuveLevel extends Model
{
    protected $table = 'epreuve_levels';

    protected $fillable = [
        'group',
        'slug',
        'name',
        'order',
    ];
}
