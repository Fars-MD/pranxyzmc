<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::latest()->get();
        return response()->json($testimonials);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'avatar' => 'nullable|string',
            'rating' => 'required|integer|min:1|max:5',
            'message' => 'required',
        ]);

        $testimonial = Testimonial::create($validated);

        return response()->json($testimonial, 201);
    }

    public function update(Request $request, $id)
    {
        $testimonial = Testimonial::findOrFail($id);

        $validated = $request->validate([
            'name' => 'max:255',
            'avatar' => 'nullable|string',
            'rating' => 'integer|min:1|max:5',
            'message' => 'required',
        ]);

        $testimonial->update($validated);

        return response()->json($testimonial);
    }

    public function destroy($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        $testimonial->delete();

        return response()->json(['message' => 'Testimonial deleted']);
    }
}
