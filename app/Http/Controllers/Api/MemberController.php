<?php

namespace App\Http\Controllers\Api;

use App\Models\Member;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $members = Member::all();

        return response()->json([
            'data'    => $members
        ], 200);
    }
    

    public function show(string $id)
    {
        try {
            $member = Member::findOrFail($id);
            return response()->json([
                'data'    => $member
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Member not found'], 404);
        }
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:50',
            'position'   => 'required|string|max:50',
            'department' => 'required|string|max:50',
            'phone'      => 'string|max:50',
            'address'    => 'text'
        ]);

        try {
            $member = Member::create($validated);

            return response()->json([
                'message' => 'Successfully added member', 
                'data'    => $member
            ], 201);

        } catch (\Exception $e) {        
            return response()->json([
                'message' => 'An unexpected error occurred',
                'error'   => $e->getMessage()
            ], 500);
        }
    }


    public function update(Request $request, $id)
    {
        $member = Member::findOrFail($id);

            $validated = $request->validate([
            'name'       => 'required|string|max:50',
            'position'   => 'required|string|max:50',
            'department' => 'required|string|max:50',
            'phone'      => 'string|max:50',
            'address'    => 'text'
        ]);
        try {
            $member->update($validated);
    
            return response()->json([
                'message' => 'Successfully updated member',
                'data'    => $member
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating data',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
    

    public function destroy($id)
    {
        try {

            $member = Member::findOrFail($id);
            $member->delete();
            
            return response()->json([
                'message' => 'Member deleted successfully', 
                'data'    => null
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting data',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}