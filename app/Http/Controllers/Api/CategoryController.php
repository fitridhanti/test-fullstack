<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'message' => 'List Categories',
            'data'    => Category::latest()->get()
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50'
        ]);

        try {
            $category = Category::create($validated);

            return response()->json([
                'message' => 'Successfully added category', 
                'data'    => $category
            ], 201);

        } catch (\Exception $e) {        
            return response()->json([
                'message' => 'An unexpected error occurred',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            return response()->json([
                'message' => 'Detail Category',
                'data'    => Category::findOrFail($id)
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Category not found'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $category = Category::findOrFail($id);
            
            $validated = $request->validate([
                'name' => 'required|string|max:50'
            ]);
    
            $category->update($validated);
    
            return response()->json([
                'message' => 'Successfully updated category',
                'data'    => $category
            ], 200);

        } catch (\Exception $e) {        
            return response()->json([
                'message' => 'An unexpected error occurred',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Pakai findOrFail($id) agar konsisten
            $category = Category::findOrFail($id);
            $category->delete();
            
            return response()->json([
                'message' => 'Category deleted successfully',
                'data'    => null
            ], 200);

        } catch (\Exception $e) {        
            return response()->json([
                'message' => 'An unexpected error occurred',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
