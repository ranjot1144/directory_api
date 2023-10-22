<?php

namespace App\Http\Controllers;

use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function approveWebsite(Request $request, $id)
    {
        //To find data from table
        $website = Website::find($id);

        if (!$website) {
            return response()->json(['message' => 'Website not found'], 404);
        }
        if ($website->approved) {
            return response()->json(['message' => 'Website is already approved'], 400);
        }

        // Implement approval logic here
        $website->update(['approved' => true]);

        return response()->json(['message' => 'Website approved']);
    }

    public function removeWebsite(Request $request, $id)
    {
        $website = Website::find($id);

        if (!$website) {
            return response()->json(['message' => 'Website not found'], 404);
        }

        // Implement removal logic here

        return response()->json(['message' => 'Website removed']);
    }
}
