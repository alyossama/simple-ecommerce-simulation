<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Order\OrderProductStoreRequest;
use App\Models\Order;
use App\Models\Product;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use GeneralTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderProductStoreRequest $request)
    {
        try {
            $order = Order::create([
                'name' => auth()->user()->name,
                'address' => auth()->user()->address,
                'phone' => auth()->user()->phone,
                'user_id' => auth()->id()
            ]);

            $subtotal = 0;

            foreach ($request->items as $item) {

                $product = Product::find($item['product_id']);
                $order->products()->attach($product, ['quantity' => $item['quantity'], 'paid_price' => $product->price * $item['quantity']]);
                $subtotal += $product->price * $item['quantity'];
            }

            $order->subtotal = $subtotal;
            $order->save();

            return $this->returnSuccessMessage('Order has been placed successfully');
        } catch (\Exception $e) {
            return $this->returnErrorMessage('Something went wrong');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
    }
}
