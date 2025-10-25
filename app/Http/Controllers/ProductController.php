<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // ✅ Product List
    public function index()
    {
        $products = Product::latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    // ✅ Store Product
    public function store(Request $request)
    {
 
        $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|integer',
            'price'       => 'required|numeric|min:0',
            'unit'        => 'required|string|max:50',
            'status'      => 'required|string',
            'image'       => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = fileUpload($request->file('image'), 'uploads/products');
        }

        Product::create([
            'name'        => $request->name,
            'category_id' => $request->category_id,
            'price'       => $request->price,
            'unit'        => $request->unit,
            'status'      => $request->status,
            'description' => $request->description,
            'image'       => $imagePath,
        ]);

        return redirect()->back()->with('success', '✅ পণ্যটি সফলভাবে সংরক্ষণ করা হয়েছে!');
    }

    // ✅ Edit (AJAX use করলে JSON ফেরত দেবে)
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return response()->json($product);
    }

    // ✅ Update
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name'   => 'required|string|max:255',
            'price'  => 'required|numeric|min:0',
            'stock'  => 'required|integer|min:0',
            'unit'   => 'required|string|max:50',
            'status' => 'required|string',
            'image'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only([
            'name', 'category_id', 'price', 'stock', 'unit', 'status', 'description'
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('uploads/products', 'public');
        }

        $product->update($data);

        return redirect()->back()->with('success', '✅ পণ্যের তথ্য আপডেট করা হয়েছে!');
    }

    // ✅ Delete
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        if ($product->image && file_exists(public_path('storage/' . $product->image))) {
            unlink(public_path('storage/' . $product->image));
        }
        $product->delete();

        return response()->json(['message' => '✅ পণ্যটি সফলভাবে মুছে ফেলা হয়েছে!']);
    }
}
