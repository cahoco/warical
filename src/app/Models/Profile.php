<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    // 保存を許可するカラムを明示

    protected $fillable = [
        'a_name', 'a_birthday', 'a_disliked_foods',
        'b_name', 'b_birthday', 'b_disliked_foods',
        'anniversary',
    ];

}
