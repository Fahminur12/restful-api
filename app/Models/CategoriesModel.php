<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriesModel extends Model
{
    use HasFactory;

    protected $table ='categories';
    protected $primaryKey = 'category_id';
    protected $fillable = array(
        'category_nama'
    );

    public static function getCategories(){
        $categories = self::all();

        return $categories;
    }

    public static function getCategoriesById(int $category_id){
        $categories = self::find($category_id);

        return $categories;
    }

    public static function createCategories($data){
        $categories = self::create($data);

        return $categories;
    }

    public static function updateCategories(int $category_id, $data){
        $categories = self::find($category_id);
        $categories->update($data);

        return $categories;
    }

    public static function deleteCategories(int $category_id){
        $categories = self::find($category_id);
        $categories->destroy($category_id);

        return $categories;
    }
}
