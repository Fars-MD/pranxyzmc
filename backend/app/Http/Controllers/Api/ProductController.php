<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->featured) {
            $query->where('featured', true);
        }

        if ($request->sort) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'newest':
                    $query->latest();
                    break;
                case 'oldest':
                    $query->oldest();
                    break;
                default:
                    $query->latest();
            }
        } else {
            $query->latest();
        }

        $products = $query->paginate($request->per_page ?? 12);

        return response()->json($products);
    }

    public function show($slug)
    {
        $product = Product::with('category')->where('slug', $slug)->firstOrFail();
        $related = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', true)
            ->take(4)
            ->get();

        return response()->json([
            'product' => $product,
            'related' => $related,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|max:255',
            'description' => 'nullable',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|string',
            'featured' => 'boolean',
            'status' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $product = Product::create($validated);

        return response()->json($product, 201);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'category_id' => 'exists:categories,id',
            'name' => 'max:255',
            'description' => 'nullable',
            'price' => 'numeric|min:0',
            'stock' => 'integer|min:0',
            'image' => 'nullable|string',
            'featured' => 'boolean',
            'status' => 'boolean',
        ]);

        if (isset($validated['name'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $product->update($validated);

        return response()->json($product);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['message' => 'Product deleted']);
    }
}
