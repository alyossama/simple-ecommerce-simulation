<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Product\ProductStoreRequest;
use App\Models\Color;
use App\Models\Image;
use App\Models\Product;
use App\Traits\GeneralTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    use GeneralTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with(['images', 'colors'])->get();
        return $this->returnData($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductStoreRequest $request)
    {
        $timestamp = Carbon::now();
        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'description' => $request->description
        ]);

        if ($request->has('color')) {
            $this->storeColor($request, $product, $timestamp);
        }

        if ($request->hasFile('image_url')) {
            $this->storeImage($request, $product, $timestamp);
        }

        return $this->returnSuccessMessage('Product added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = Product::with(['images', 'colors'])->findOrFail($id);
        return $this->returnData($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if ($product->images) {
            foreach ($product->images as $image) {
                if (File::exists(public_path('Images/Products/' . $image->image_url))) {
                    File::delete(public_path('Images/Products/' . $image->image_url));
                }
            }
        }
        $product->delete();

        return $this->returnSuccessMessage('Product deleted successfully!');
    }

    public function storeImage($request, $product, $timestamp)
    {
        $imageData = [];
        $images = $request->file('image_url');

        foreach ($images as $image) {
            $imageName = $this->uploadImage($image, "Products\\");
            $imageData[] = [
                'image_url' => $imageName,
                'product_id' => $product->id,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ];
        }

        Image::insert($imageData);
    }

    public function storeColor($request, $product, $timestamp)
    {
        $colorData = [];
        $colors = $request->color;

        foreach ($colors as $color) {
            $colorData[] = [
                'color' => $color,
                'product_id' => $product->id,
                'created_at' => $timestamp,
                'updated_at' => $timestamp
            ];
        }

        Color::insert($colorData);
    }
}
