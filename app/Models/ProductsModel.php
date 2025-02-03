<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductsModel extends Model
{
    use HasFactory;

    protected $table ='products';
    protected $primaryKey = 'product_id';
    protected $fillable = [
        'product_name',
        'product_stock',
        'product_price',
        'product_category_id'
    ];

    public static function getProducts(){
        $products = self::all();

        return $products;
    }

    public static function getProductsById(int $product_id){
        $products = self::find($product_id);

        return $products;
    }

    public static function createProducts($data){
        $products = self::create($data);

        return $products;
    }

    public static function updateProducts(int $product_id, $data){
        $products = self::find($product_id);
        $products->update($data);

        return $products;
    }

    public static function destroyProducts(int $product_id){
        $products = self::find($product_id);
        $products->destroy($product_id);

        return $products;
    }

    public function category(){
        return $this->belongsTo(CategoriesModel::class, 'product_category_id', 'category_id');
    }
}
