<?php

namespace App\Models;

use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Database\Concerns\MaintenanceMode;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    //use HasDateTimeFormatter, HasDatabase, HasDomains, MaintenanceMode;
    use HasDatabase, HasDomains;

    protected $table = 'tenants';

    protected $guarded = [];

    /**
     * 自定义列.
     *
     * @return string[]
     */
    /*public static function getCustomColumns(): array
    {
        return [
            'id',
            'name',
            'level',
            'expired_at'
        ];
    }*/
}
