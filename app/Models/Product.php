<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'product_id';

    /**
     * Boot function from Laravel.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if ($model->isDirty('product_name')) {
                $product_id = self::convertIdByProductName($model->product_name);
                $model->product_id = $product_id;
            }
        });
    }

    /**
     * Get the value indicating whether the IDs are incrementing.
     *
     * @return bool
     */
    public function getIncrementing()
    {
        return false;
    }

    /**
     * Get the auto-incrementing key type.
     *
     * @return string
     */
    public function getKeyType()
    {
        return 'string';
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'product_name',
        'product_image',
        'product_price',
        'is_sales',
        'description',
        'created_at',
        'updated_at'
    ];

    public function getProductImageAttribute($value)
    {
        return $value ? Storage::disk('tmp')->url($value) : null;
    }

    public function getStatusAttribute()
    {
        if ($this->is_active === 0) {
            return 'Tạm khóa';
        }

        return 'Đang hoạt động';
    }

    public static function convertIdByProductName($product_name)
    {
        $firstCharactor = strtoupper(substr(Str::slug($product_name), 0, 1));
        $latestProduct = self::latest()->where('product_id', 'regexp', $firstCharactor.'[0-9]+')->first();

        $count = 1;
        if ($latestProduct) {
            $count = intval(substr($latestProduct->product_id, 1)) + 1;
        }

        return $firstCharactor . sprintf("%09d", $count);
    }
}
