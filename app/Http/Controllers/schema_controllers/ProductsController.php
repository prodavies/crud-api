<?php

namespace App\Http\Controllers\schema_controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\schema_models\Product;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //fetch the products
        $products = Product::get();

        return response()->json([
            'success'=>true,
            'products'=>$products
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validate
        $validator = $request->validate([
            'name'=>'required|string|max:45',
            'description'=>'required|string|max:45',
            'quantity'=>"required|string|max:45"
        ]);
        //create new product instance
        $product = new Product();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->quantity = $request->quantity;
        
        //check if there is success or failure in saving data
        if($product->save()){
            return response()->json([
                'success'=>true,
                'added_product'=>$product->toArray()
            ]);
        }
        else{
            return response()->json([
                'success'=>false,
                'success_message'=>'product  added successifully!',
                'error_message'=>'Error in adding new product'
            ],500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //get the product id
        $product = Product::findOrFail($id);
        if(!$product){
            return response()->json([
                'success'=>false,
                'warning_message'=>'product not found'
            ],400);
        }
        else{
            return response()->json([
                'success'=>true,
                'product_details'=>$product->toArray()
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //get the product id
        $product = Product::findOrFail($id);
        if(!$product){
            return response()->json([
                'success'=>false,
                'warning_message'=>'product not found'
            ],400);
        }
        else{
            return response()->json([
                'success'=>true,
                'product'=>$product
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        //get the product id
        $product = Product::findOrFail($id);
    
        //in case product item found or not found
        if(!$product){
            return response()->json([
                'success'=>false,
                'warning_message'=>'product not found'
            ],400);
        }
        else{
            $product->name = $request->name;
            $product->description = $request->description;
            $product->quantity = $request->quantity;
            $is_updated = $product->update();

            //check if it is updated successifully
            if($is_updated){
                return response()->json([
                    'success'=>true,
                    'success_message'=>'Product updated successifully!',
                    'updated_product'=>$product
                ]);
            }
            else{
                return response()->json([
                    'success'=>false,
                    'error_message'=>'Error, product not updated'
                ],500);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //get the product id
        $product = Product::findOrFail($id);

        if(!$product){
            return response()->json([
                'success'=>false,
                'error_message'=>'Error, product not found'
            ],400);
        }
        else{
            if($product->delete()){
                return response()->json([
                    'success'=>true,
                    'success_message'=>'Product deleted'
                ]);
            }
            else{
                return response()->json([
                    'success'=>false,
                    'error_message'=>'product can not be deleted'
                ],500);
            }
        }
    }

    public function updateproduct(Request $request,$id){
              //get the product id
              $product = Product::findOrFail($id);
    
              //in case product item found or not found
              if(!$product){
                  return response()->json([
                      'success'=>false,
                      'warning_message'=>'product not found'
                  ],400);
              }
              else{
                  $product->name = $request->name;
                  $product->description = $request->description;
                  $product->quantity = $request->quantity;
                  $is_updated = $product->update();
      
                  //check if it is updated successifully
                  if($is_updated){
                      return response()->json([
                          'success'=>true,
                          'success_message'=>'Product updated successifully!',
                          'updated_product'=>$product
                      ]);
                  }
                  else{
                      return response()->json([
                          'success'=>false,
                          'error_message'=>'Error, product not updated'
                      ],500);
                  }
              }
    }
}
