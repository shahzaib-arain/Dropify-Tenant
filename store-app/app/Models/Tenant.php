<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
/**
 * App\Models\Tenant
 *
 * @property User|null $user
 */
class Tenant extends Model
{
    protected $table = 'tenants';
    use HasFactory;

    protected $fillable = ['name', 'subdomain', 'user_id']; // Add user_id here
    
    // For the admin user (one-to-one)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // For all users belonging to this tenant (one-to-many)
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Your other relationships...
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function shippingMethods()
    {
        return $this->hasMany(ShippingMethod::class);
    }
}