<?php

namespace App\Http\Controllers\schema_controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\schema_models\Supplier;

class SuppliersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
          //fetch suppliers
          $suppliers = Supplier::with('products')->get();

          return response()->json([
              'success'=>true,
              'suppliers'=>$suppliers
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
            'name'=>'required|string',
        ]);
        //create new supplier instance
        $supplier = new Supplier();
        $supplier->name = $request->name;
        $supplier->created_at = date("Y-m-d H:i:s");

        //check if there is success or failure in saving data
        if($supplier->save()){
            //attach the product supplied by this supplier
            $supplier->products()->attach($request['product_id']);
            return response()->json([
                'success'=>true,
                'success_message'=>'New supplier added successifully',
                'data'=>$supplier->toArray()
            ]);
        }
        else{
            return response()->json([
                'success'=>false,
                'error_message'=>'Error in adding new supplier'
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
        //get the supplier id
        $supplier = Supplier::with('products')->findOrFail($id);
        if(!$supplier){
            return response()->json([
                'success'=>false,
                'warning_message'=>'supplier not found'
            ],400);
        }
        else{
            return response()->json([
                'success'=>true,
                'data'=>$supplier->toArray()
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
        //get supplier id
        $supplier = Supplier::with('products')->findOrFail($id);
        if(!$supplier){
            return response()->json([
                'success'=>false,
                'warning_message'=>'supplier not found'
            ],400);
        }
        else{
            return response()->json([
                'success'=>true,
                'supplier'=>$supplier
            ]);
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
        //get id
        $supplier = Supplier::with('products')->findOrFail($id);

        if(!$supplier){
            return response()->json([
                'success'=>false,
                'error_message'=>'Error, supplier not found'
            ],400);
        }
        else{
           
            if($supplier->delete()){
                 //detach the products from supplier
                 $supplier->products()->detach();

                return response()->json([
                    'success'=>true,
                    'success_message'=>'Supplier deleted'
                ]);
            }
            else{
                return response()->json([
                    'success'=>false,
                    'error_message'=>'Supplier can not be deleted'
                ],500);
            }
        }
    }

     public function updatesupplier(Request $request,$id){
           //get id
           $supplier = Supplier::with('products')->findOrFail($id);

           //in case product item found or not found
           if(!$supplier){
               return response()->json([
                   'success'=>false,
                   'warning_message'=>'supplier not found'
               ],400);
           }
           else{
               $supplier->name = $request->name;
               $supplier->updated_at = date('Y-m-d H:i:s');
              
               //check if it is updated successifully
               if($supplier->update()){
                   //update the products supplied by supplier
                   $supplier->products()->sync($request['product_id']);
                   return response()->json([
                       'success'=>true,
                       'success_message'=>'Supplier updated successifully!'
                   ]);
               }
               else{
                   return response()->json([
                       'success'=>false,
                       'error_message'=>'Error, supplier not updated'
                   ],500);
               }
           }
     }
    }

