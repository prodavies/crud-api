<?php

namespace App\Http\Controllers\schema_controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\schema_models\Order;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //fetch the orders
        $orders = Order::with('products')->get();

        return response()->json([
            'success'=>true,
            'orders'=>$orders
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
                'order_number'=>'required|string',
            ]);
            //create new order instance
            $order = new Order();
            $order->order_number = $request->order_number;

            //check if there is success or failure in saving data
            if($order->save()){
                $order->products()->attach($request['product_id']);
                return response()->json([
                    'success'=>true,
                    'success_message'=>'New order added successifully',
                    'order'=>$order->toArray()
                ]);
            }
            else{
                return response()->json([
                    'success'=>false,
                    'error_message'=>'Error in adding new order'
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
         //get order id
         $order = Order::with('products')->findOrFail($id);
         if(!$order){
             return response()->json([
                 'success'=>false,
                 'warning_message'=>'order not found'
             ],400);
         }
         else{
             return response()->json([
                 'success'=>true,
                 'order_details'=>$order->toArray()
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
         //get order id
         $order = Order::with('products')->findOrFail($id);
         if(!$order){
             return response()->json([
                 'success'=>false,
                 'warning_message'=>'order not found'
             ],400);
         }
         else{
             return response()->json([
                 'success'=>true,
                 'order'=>$order
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
    public function updateorder(Request $request, $id)
    {
         //get id
         $order = Order::with('products')->findOrFail($id);

         //in case order found or not found
         if(!$order){
             return response()->json([
                 'success'=>false,
                 'warning_message'=>'order not found'
             ],400);
         }
         else{
             $order->order_number = $request->order_number;
             $order->updated_at = date("Y-m-d H:i:s");
 
             //check if it is updated successifully
             if($order->update()){
                 $order->products()->sync($request['product_id']);
                 return response()->json([
                     'success'=>true,
                     'success_message'=>'Order updated successifully!'
                 ]);
             }
             else{
                 return response()->json([
                     'success'=>false,
                     'error_message'=>'Error, order not updated'
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
         $order = Order::with('products')->findOrFail($id);

         if(!$order){
             return response()->json([
                 'success'=>false,
                 'error_message'=>'Error, order not found'
             ],400);
         }
         else{
             if($order->delete()){
                $order->products()->detach();
                 return response()->json([
                     'success'=>true,
                     'success_message'=>'Order deleted'
                 ]);
             }
             else{
                 return response()->json([
                     'success'=>false,
                     'error_message'=>'Order can not be deleted'
                 ],500);
             }
         }
     }


    }
