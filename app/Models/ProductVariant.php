<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'variant',
        'sku',
        'price',
        'stock',
        'theme_id',
        'store_id'
    ];

    public function getOriginalPriceAttribute()
    {
        return SetNumber(!empty($this->variation_price)?$this->variation_price: $this->price);
    }

    public function getDiscountPriceAttribute()
    {
        $price =  $this->price;
        $Product = Product::find($this->product_id);
        $discount_type =  $Product->discount_type;
        $discount_amount =  $Product->discount_amount;
        if($discount_type == 'percentage') {
            $discount_amount =  $price * $discount_amount / 100;
        }
        return SetNumber($discount_amount);
    }

    public function getFinalPriceAttribute()
    {
        $price =  !empty($this->price)? $this->price : $this->variation_price;
        $Product = Product::find($this->product_id);

        date_default_timezone_set('Asia/Kolkata');
        $currentDateTime = \Carbon\Carbon::now()->toDateTimeString();
        $sale_product = \App\Models\FlashSale::where('theme_id', APP_THEME())
            ->where('store_id', getCurrentStore())
            ->get();
        $latestSales = [];
        foreach ($sale_product as $flashsale) {
            $saleEnableArray = json_decode($flashsale->sale_product, true);
            $startDate = \Carbon\Carbon::parse($flashsale['start_date'] . ' ' . $flashsale['start_time']);
            $endDate = \Carbon\Carbon::parse($flashsale['end_date'] . ' ' . $flashsale['end_time']);

            if ($endDate < $startDate) {
                $endDate->addDay();
            }

            if ($currentDateTime >= $startDate && $currentDateTime <= $endDate) {
                if (is_array($saleEnableArray) && in_array($this->product_id, $saleEnableArray)) {
                    $latestSales[$this->product_id] = [
                        'discount_type' => $flashsale->discount_type,
                        'discount_amount' => $flashsale->discount_amount,
                    ];
                }
            }
        }
        if ($latestSales == null) {
            if ($Product->variant_product == 0) {
                $latestSales[$this->product_id] = [
                    'discount_type' => $Product->discount_type,
                    'discount_amount' => $Product->discount_amount,
                ];
            }
        }
        foreach ($latestSales as $productId => $saleData) {
            if ($Product->variant_product == 0) {
                if ($saleData['discount_type'] == 'flat') {
                    $price = $this->price - $saleData['discount_amount'];
                }
                if ($saleData['discount_type'] == 'percentage') {
                    $discount_price =  $this->price * $saleData['discount_amount'] / 100;
                    $price = $this->price - $discount_price;
                }
            } else {
                $product_variant_data = ProductVariant::where('product_id', $this->product_id)->where('id',$this->id)->first();
                if ($product_variant_data) {
                    if ($saleData['discount_type'] == 'flat') {
                        $price = $product_variant_data->price - $saleData['discount_amount'];
                    } elseif ($saleData['discount_type'] == 'percentage') {
                        $discount_price = $product_variant_data->price * $saleData['discount_amount'] / 100;
                        $price = $product_variant_data->price - $discount_price;
                    }else{
                        $price = $product_variant_data->price;
                    }
                }
            }
        }
        return SetNumber($price);
    }

    public static function variantlist($product_variant_id = 0)
    {
        $return = '';
        $varian_name = '';
        $ProductStock = ProductVariant::find($product_variant_id);
        if(!empty($ProductStock)) {
            $product = Product::find($ProductStock->product_id);
            $variant_attribute = json_decode($product->product_attribute);
            foreach ($variant_attribute as $key => $value) {
                $variant_data = ProductAttribute::find($value->attribute_id);
                if(!empty($variant_data)) {
                    $varian_name = $variant_data->name;
                }

                $ProductStock_variant = explode('-',$ProductStock->variant);
                $varian_value = !empty($ProductStock_variant[$key]) ? $ProductStock_variant[$key] : '';
                $return .= '<p><strong>'.$varian_name.':</strong> '.$varian_value.'</p>';
            }
        }
        return '<div class="cart-variable">'.$return.'</div>';
    }
}

