<?php

namespace App\Http\Controllers;

use App\Models\CategoriesModel;
use Cache;
use Exception;
use Illuminate\Http\Request;
use Validator;

class CategoriesController extends Controller
{
    public function index () {
        try {
            $categories = Cache::remember('categories', 60*5, function(){
                return CategoriesModel::getCategories();
            });
            $response = array(
                'success' => true,
                'message' => "Succesfully, get categories data",
                'data' => $categories
            );

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

	public function show (int $category_id) {
        try {
            $cacheKey = 'category_'.$category_id;
            $categories = Cache::remember($cacheKey, 60*5, function() use($category_id){
                return CategoriesModel::getCategoriesById($category_id);
            });
            $response = array(
                'success' => true,
                'message' => "Successfully, get categories data",
                'data' => $categories
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

	public function store (Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'category_nama' => 'required|string|max:100'
            ]);
            if($validator->fails()){
                $response = array(
                    'success' => false,
                    'message' => "Failed to create data products, data not completed, please check your data",
                    'data' => null,
                    'error' => $validator->errors(),
                );
            };
            $categories = CategoriesModel::createCategories($validator->validate());
            Cache::put('categories', CategoriesModel::getCategories(), 60*5);
            $response = array(
                'success' => true,
                'message' => "Successfully, create categories data",
                'data' => $categories
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

	public function update (Request $request, int $category_id) {
        try {
            $validator = Validator::make($request->all(), [
                'category_nama' => 'required|string|max:100'
            ]);
            if($validator->fails()){
                $response = array(
                    'success' => false,
                    'message' => "Failed to create data products, data not completed, please check your data",
                    'data' => null,
                    'error' => $validator->errors(),
                );
            };
            $categories = CategoriesModel::updateCategories($category_id ,$validator->validate());
            Cache::put('categories', CategoriesModel::getCategories(), 60*5);
            $response = array(
                'success' => true,
                'message' => "Successfully, create categories data",
                'data' => $categories
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

	public function destroy (int $category_id) {
        try {
            $categories = CategoriesModel::deleteCategories($category_id);
            Cache::put('categories', CategoriesModel::getCategories(), 60*5);
            $response = array(
                'success' => true,
                'message' => "Succesfully, delete categories data",
                'data' => $categories
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
