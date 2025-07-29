<?php 
namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TenantScope implements Scope
{
    public function __construct(protected $tenantId) {}

    public function apply(Builder $builder, Model $model)
    {
        $builder->where('tenant_id', $this->tenantId);
    }
}