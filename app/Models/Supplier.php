<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'document_number',
        'company_name',
        'email',
        'phone',
        'zipcode',
        'address',
        'address_number',
        'address_complement',
        'neighborhood',
        'city',
        'state',
    ];
}
