<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Domain as BaseDomain;

class Domain extends BaseDomain
{
    //use HasDateTimeFormatter;

    protected $table = 'domains';

    protected $guarded = [];
}
