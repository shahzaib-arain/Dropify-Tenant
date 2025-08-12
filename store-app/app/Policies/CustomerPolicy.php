<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Customer;

class CustomerPolicy
{
    public function view(User $user, Customer $customer)
    {
        return $user->tenant_id === $customer->tenant_id;
    }

    public function update(User $user, Customer $customer)
    {
        return $user->tenant_id === $customer->tenant_id;
    }

    public function delete(User $user, Customer $customer)
    {
        return $user->tenant_id === $customer->tenant_id;
    }
}
