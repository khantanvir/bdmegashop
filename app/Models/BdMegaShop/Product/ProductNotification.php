<?php

namespace App\Models\BdMegaShop\Product;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\BdMegaShop\Category\Product;
use App\Models\BdMegaShop\Order\Order;
use Illuminate\Support\Facades\Auth;

class ProductNotification extends Model
{
    protected $table = 'product_notifications';

    protected $guarded = [];

    public function creator()
    {
        return $this->belongsTo(User::class, 'create_by', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    /**
     * Helper to log notifications easily from Product and Order controllers.
     */
    public static function addNotification($title, $description, $productId = 0, $orderId = 0, $userId = null, $slug = null)
    {
        try {
            $user = Auth::user();
            return self::create([
                'title'         => $title,
                'description'   => $description,
                'create_date'   => time(),
                'create_by'     => $user ? $user->id : null,
                'creator_name'  => $user ? ($user->name ?? 'Admin') : 'System',
                'creator_image' => $user ? ($user->photo ?? null) : null,
                'user_id'       => $userId ?? ($user ? $user->id : null),
                'product_id'    => $productId ?: 0,
                'order_id'      => $orderId ?: 0,
                'status'        => 1,
                'is_view'       => 1,
                'slug'          => $slug,
            ]);
        } catch (\Throwable $e) {
            // Fail silently or log so regular flow is never disrupted
            \Log::error('ProductNotification creation failed: ' . $e->getMessage());
            return null;
        }
    }
}
