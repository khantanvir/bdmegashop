<?php

namespace App\Models\BdMegaShop\Order;

use Illuminate\Database\Eloquent\Model;
use App\Models\BdMegaShop\Customer\Customer;

class Order extends Model
{
    protected $table = 'orders';

    protected $guarded = [];

    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class, 'order_id', 'id');
    }

    public function orderAddress()
    {
        return $this->hasOne(OrderAddress::class, 'order_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function processor()
    {
        return $this->belongsTo(\App\Models\User::class, 'order_processing_by', 'id');
    }

    /**
     * Calculate order processing benefit and percentage commission.
     *
     * Rule:
     * - Item Benefit = (Selling Price - Purchase Price) * Quantity
     * - If order_processing_by == product_owner: No percentage calculated (0 BDT commission)
     * - If other people processing: Commission = Eligible Benefit * (processing_percentage / 100)
     */
    public function calculateProcessingData($overrideProcessingBy = null, $overridePercentage = null)
    {
        if (!$this->relationLoaded('orderProducts.product.owner')) {
            $this->load(['orderProducts.product.owner']);
        }

        $processingBy = $overrideProcessingBy !== null 
            ? ($overrideProcessingBy ? (int)$overrideProcessingBy : null)
            : ($this->order_processing_by ? (int)$this->order_processing_by : null);

        $percentage = $overridePercentage !== null 
            ? floatval($overridePercentage) 
            : floatval(($this->processing_percentage !== null && $this->processing_percentage !== '') ? $this->processing_percentage : 25);

        $totalOrderBenefit = 0;
        $totalEligibleBenefit = 0;
        $itemsBreakdown = [];
        $ownerIds = [];

        foreach ($this->orderProducts as $item) {
            $product = $item->product;
            $unitPrice = floatval($item->unit_price ?? ($product->selling_price ?? $product->price ?? 0));
            $purchasePrice = floatval($product->purchase_price ?? 0);
            $qty = max(1, intval($item->quantity ?? 1));

            $unitBenefit = max(0, $unitPrice - $purchasePrice);
            $itemBenefit = $unitBenefit * $qty;
            $totalOrderBenefit += $itemBenefit;

            $productOwnerId = $product && $product->product_owner ? (int)$product->product_owner : null;
            if ($productOwnerId) {
                $ownerIds[] = $productOwnerId;
            }

            // If processor is the product owner, no percentage commission
            $isOwnerProcessing = ($processingBy && $productOwnerId && $processingBy === $productOwnerId);
            $eligibleBenefit = $isOwnerProcessing ? 0 : $itemBenefit;
            $totalEligibleBenefit += $eligibleBenefit;

            $img = !empty($item->product_image)
                ? (str_starts_with($item->product_image, 'http') ? $item->product_image : asset($item->product_image))
                : (!empty($product->image) ? asset($product->image) : asset('bdmegashop/dist/images/product/05.jpg'));

            $itemsBreakdown[] = [
                'order_product_id'   => $item->id,
                'product_id'         => $item->product_id,
                'product_name'       => $item->product_name ?: ($product->title ?? 'Product #' . $item->product_id),
                'product_image'      => $img,
                'product_owner_id'   => $productOwnerId,
                'product_owner_name' => ($product && $product->owner) ? $product->owner->name : ($productOwnerId ? 'Owner #' . $productOwnerId : 'Unassigned'),
                'selling_price'      => $unitPrice,
                'purchase_price'     => $purchasePrice,
                'unit_benefit'       => $unitBenefit,
                'quantity'           => $qty,
                'item_benefit'       => $itemBenefit,
                'is_owner_processing'=> $isOwnerProcessing,
                'eligible_benefit'   => $eligibleBenefit,
            ];
        }

        $ownerIds = array_values(array_unique($ownerIds));
        $processingAmount = round(($totalEligibleBenefit * $percentage) / 100, 2);

        return [
            'order_id'                  => $this->id,
            'order_number'              => $this->order_number ?? (string)$this->id,
            'order_processing_by'       => $processingBy,
            'processing_percentage'     => $percentage,
            'processing_amount'         => $processingAmount,
            'is_paid_processing_amount' => $this->is_paid_processing_amount ?: 'No',
            'total_order_benefit'       => round($totalOrderBenefit, 2),
            'total_eligible_benefit'    => round($totalEligibleBenefit, 2),
            'items'                     => $itemsBreakdown,
            'product_owner_ids'         => $ownerIds,
        ];
    }
}
