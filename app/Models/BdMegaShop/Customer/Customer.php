<?php

namespace App\Models\BdMegaShop\Customer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\BdMegaShop\Order\Order;

class Customer extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'customers';

    protected $fillable = [
        'first_name',
        'last_name',
        'name',
        'email',
        'password',
        'phone',
        'photo',
        'country',
        'state',
        'city',
        'zip_code',
        'address',
        'role',
        'slug',
        'create_by',
        'active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id', 'id');
    }
}
