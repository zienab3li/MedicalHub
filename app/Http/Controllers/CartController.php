<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Repositories\Cart\CartModelRepository;
use Illuminate\Http\Request;

class CartController extends Controller
{

    protected $repository;
    public function __construct(CartModelRepository $cartModelRepository){
        $this->repository=$cartModelRepository;
        // $this->middleware('auth:api');

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $item = $this->repository->get();
        $total = $this->repository->total();
        return response()->json([
            'success'=>"get sucess",
            'data'=>[
                'items'=>$item,
                'total'=>$total
            ]
            ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $request->validate([
        //     'product_id'=>'required,integer,exists:products,id',
        //     'quantity' => 'nullable,integer,min:1'
        // ]);
        // $product=Product::findOrFail($request->product_id);
        // $quantity = $request->quantity;
        // $cartItem = $this->repository->add($product,$quantity);
        // return response()->json([
        //     'success' => true,
        //     'message' => 'add to cart',
        //     'data' => $cartItem
        // ], 201);
        $validatedData = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'nullable|integer|min:1'
        ]);
    
        
        $product = Product::findOrFail($validatedData['product_id']);
        
        $quantity = $validatedData['quantity'] ?? 1; 
    
        $cartItem = $this->repository->add($product, $quantity);
    
        return response()->json([
            'success' => true,
            'message' => 'تمت الإضافة إلى السلة بنجاح',
            'data' => $cartItem
        ], 201);
    }
    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'product_id'=>'required|integer|exists:products,id',
            'quantity' => 'nullable|integer|min:1'
        ]);
        $product=Product::findOrFail($request->product_id);
        
        $this->repository->update($product, $request->quantity);

        return response()->json([
            'success' => true,
            'message' => 'update to cart',
           
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        $this->repository->delete($id);
    }
}
