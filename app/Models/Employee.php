<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;


        /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'employee';

    protected $fillable = [
        'name',
        'signed_in',
        'age',
        'role',
        'office',
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
