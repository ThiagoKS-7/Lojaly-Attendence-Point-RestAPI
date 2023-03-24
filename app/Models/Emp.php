<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emp extends Model
{
    use HasFactory;


        /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'employee as emp';

    protected $fillable = [
        'name',
        'signed_in',
        'age',
        'office',
        'user_id',
        'resp_adm_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

}
