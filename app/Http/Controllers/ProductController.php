<?php

namespace App\Http\Controllers;

use App\Models\ProductsModel;
use Cache;
use Exception;
use Illuminate\Http\Request;
use Validator;

class ProductController extends Controller
{
    public function index(){
        try {
            $products = Cache::remember('products', 60*60*24, function(){
                return ProductsModel::getProducts();
            });
            $response = [
                'success' => true,
                'message' => "Successfuly get products data",
                'data' => $products
            ];

            return response()->json($response, 200)->header('Cache-Control', 'public, max-age=300');
        } catch (Exception $error) {
            $response = array(
                'success' => false,
                'message' => "Sorry, these error in internal server",
                'data' => null,
                'error' => $error->getMessage()
            );

            return response()->json($response, 500);
        }
    }

    public function show(int $product_id){
        try {
            $cacheKey = "product_".$product_id;
            $products = Cache::remember($cacheKey, 60*60*24, function () use($product_id){
                return ProductsModel::getProductsById($product_id);
            });
            $response = array(
                'success' => true,
                'message' => "Successfully get products data",
                'data' => $products
            );

            return response()->json($response, 200);
        } catch (Exception $error) {
            $response = array(
                'success' => false,
                'message' => "Sorry, these error in internal server",
                'data' => null,
                'error' => $error->getMessage()
            );

            return response()->json($response, 500);
        }
    }

    public function store(Request $request){
        try {
            $validator =Validator::make($request->all(), [
                'product_name' => 'required|string|max:100',
                'product_stock' => 'required|numeric',
                'product_price' => 'required|numeric',
                'product_category_id' => 'required|exists:categories,category_id'
            ]);
            if($validator->fails()){
                $response = array(
                    'success' => false,
                    'message' => "Failed to create data products, data not completed, please check your data",
                    'data' => null,
                    'error' => $validator->errors()
                );

                return response()->json($response, 400);
            }

            $products = ProductsModel::createProducts($validator->validate());
            Cache::put('products', ProductsModel::getProducts(), 60*60*24);
            $response = array(
                'success' => true,
                'message' => "Successfully create products data",
                'data' => $products
            );

            return response()->json($response, 201);
        } catch (Exception $error) {
            $response = array(
                'success' => false,
                'message' => "Sorry, these error in internal server",
                'data' => null,
                'error' => $error->getMessage()
            );

            return response()->json($response, 500);
        }
    }

    public function update(Request $request, int $product_id){
        try {
            $validator = Validator::make($request->all(), [
                'product_name' => 'required|string|max:100',
                'product_stock' => 'required|numeric',
                'product_price' => 'required|numeric',
                'product_category_id' => 'required|exists:categories,category_id'
            ]);
            if($validator->fails()){
                $response = array(
                    'success' => false,
                    'message' => "Failed to update data products, data not completed, please check your data",
                    'data' => null,
                    'error' => $validator->errors()
                );

                return response()->json($response, 400);
            }

            $products = ProductsModel::updateProducts($product_id, $validator->validate());
            $response = array(
                'success' => true,
                'message' => 'Successfully update product data',
                'data' => $products,
            );

            return response()->json($response, 200);
        } catch (Exception $error) {
            $response = array(
                'success' => false,
                'message' => "Sorry, these error in internal server",
                'data' => null,
                'error' => $error->getMessage()
            );

            return response()->json($response, 500);
        }
    }

    public function destroy(int $product_id){
        try {
            $products = ProductsModel::destroyProducts($product_id);
            $response = array(
                'success' => true,
                'message' => "Successfully delete products data",
                'data' => $products
            );

            return response()->json($response, 200);
        } catch (Exception $error) {
            $response = array(
                'success' => false,
                'message' => "Sorry, these error in internal server",
                'data' => null,
                'error' => $error->getMessage()
            );

            return response()->json($response, 500);
        }
    }
}
