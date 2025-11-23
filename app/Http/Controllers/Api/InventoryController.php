<?php

namespace App\Http\Controllers\Api;

use App\Models\Member;
use App\Models\Category;
use App\Models\Inventory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $inventories = Inventory::with(['category', 'member'])->latest()->get();

        $inventories->each(function($item) {
            $item->append('kode_inventaris');
        });

        return response()->json([
            'message' => 'List Data Inventory',
            'data'    => $inventories
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_barang'   => 'required|string|max:100',
            'category_id'   => 'required|exists:categories,id',
            'member_id'     => 'nullable|exists:members,id',
            'serial_number' => 'nullable|string|unique:inventories,serial_number',
            'spesification' => 'nullable|string',
            'status'        => 'required|string',
            'quantity'      => 'required|integer|min:0',
            'department'    => 'required|string'
        ]);

        try {
            $inventory = Inventory::create($validated);

            $inventory->load(['category', 'member']); 
            $inventory->append('kode_inventaris');

            return response()->json([
                'message' => 'Successfully added inventory', 
                'data'    => $inventory
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
            $inventory = Inventory::with(['category', 'member'])->findOrFail($id);
            $inventory->append('kode_inventaris');

            return response()->json([
                'message' => 'Detail Data Inventory',
                'data'    => $inventory
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Data not found'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $inventory = Inventory::findOrFail($id);
        
        $validated = $request->validate([
            'name_barang'   => 'required|string|max:100',
            'category_id'   => 'required|exists:categories,id',
            'member_id'     => 'nullable|exists:members,id',
            'serial_number' => 'nullable|string|unique:inventories,serial_number,' . $id,
            'spesification' => 'nullable|string',
            'status'        => 'required|string',
            'quantity'      => 'required|integer|min:0',
            'department'    => 'required|string'
        ]);

        try {
            $inventory->update($validated);
            
            $inventory->refresh(); 
            $inventory->load(['category', 'member']);
            $inventory->append('kode_inventaris');

            return response()->json([
                'message' => 'Successfully updated inventory',
                'data'    => $inventory
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
            $inventory = Inventory::findOrFail($id);
            $inventory->delete();

            return response()->json([
                'message' => 'Inventory deleted successfully',
                'data'    => null 
            ], 200);

        } catch (\Exception $e) {        
            return response()->json([
                'message' => 'An unexpected error occurred or data not found',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}