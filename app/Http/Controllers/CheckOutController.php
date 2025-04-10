<?php

namespace App\Http\Controllers;

use App\Events\OrderCreated;
use App\Models\Order;
use App\Models\OrderItem;
use App\Repositories\Cart\CartModelRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckOutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request ,CartModelRepository $cartRepository)
    {
        $cart = $cartRepository->get();
        DB::beginTransaction();
        try{
            $order=Order::create([
                'user_id' => Auth::id(),
               'payment_method' => $request->payment_method,
            ]);
            foreach($cartRepository->get() as $item){
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);
            }
            foreach($request->post('addr') as $type => $address){
                $address['type'] = $type;
                $order->addresses()->create( $address);
            }
            // $cartRepository->empty();
            DB::commit();
            // event("order.created", $order,Auth::user());
            event(new OrderCreated($order));
            return response()->json([
                'cart' => $cart,
                'message' => 'Cart retrieved successfully'
            ]);
           

        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'message' => 'Error creating order',
                'error' => $e->getMessage()
            ], 500);
        }
       
        
    }
    
        
    

    /**
     * Display the specified resource.
     */
    public function show(CartModelRepository $cartRepository)
    {
       
        $cart = $cartRepository->get();
        return response()->json([
            'cart' => $cart,
            'message' => 'Cart retrieved successfully'
        ]);
        
    
    
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
