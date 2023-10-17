<?php

namespace App\Http\Controllers;

use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;

class WebsiteController extends Controller
{
    //
    public function index()
    {
        $websites = cache()->remember('websites', now()->addMinutes(30), function () {
            $websites = Website::all();
        });
        return response()->json(['websites' => $websites]);
    }

    public function show($id)
    {
        // Find the website by its ID
        $website = Website::find($id);

        if (!$website) {
            return response()->json(['message' => 'Website not found'], 404);
        }
        // Return the website details as a JSON response
        return response()->json($website);
    }

    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'string|max:500',
            'url' => 'required|url|unique:websites', // Assuming the websites are stored in a 'websites' table
        ]);

        // Create a new website based on the validated data
        $website = Website::create([
            'user_id' => auth()->user()->id,
            'name' => $request->input('name'),
            'title' => $request->input('title'),
            'url' => $request->input('url'),
            'description' => $request->input('description'),
        ]);

        // Sync website categories
        $website->categories()->sync($request->input('categories'));

        // Respond with a success message
        return response()->json(['message' => 'Website added successfully'], 201);
    }

    public function vote(Request $request, $id)
    {
        $website = Website::find($id);

        if (!$website) {
            return response()->json(['message' => 'Website not found'], 404);
        }

        if ($website->hasVoted($request->user())) {
            return response()->json(['message' => 'You have already voted for this website'], 400);
        }

        $vote = $website->votes()->create(['user_id' => $request->user()->id]);

        return response()->json(['message' => 'Vote added successfully', 'vote_id' => $vote->id]);
    }

    public function update(Request $request, Website $website, $id)
    {
        $website = Website::find($id);

        if (!$website) {
            return response()->json(['message' => 'Website not found'], 404);
        }
        if (Gate::allows('update-website', $website)) {

            $request->validate([
                'name' => 'string|max:255',
                'url' => 'url',
                'categories' => 'array',
                'categories.*' => [
                    'exists:categories,id',
                    Rule::unique('category_website', 'category_id')->where(function ($query) use ($website) {
                        return $query->where('website_id', $website->id);
                    }),
                ],
            ]);

            $website->update($request->only('name', 'url'));
            $website->categories()->sync($request->input('categories'));

            return response()->json(['message' => 'Website updated successfully']);
        }else{
            // Unauthorized action
            return response()->json(['message' => 'Unauthorized'], 403);
        }
    }

    public function delete(Request $request, Website $website)
    {
        if (Gate::allows('delete-website', $website)) {
            // User is authorized to delete the website
            $website->delete(); // Delete the website
            return response()->json(['message' => 'Website deleted successfully']);
        } else {
            // Unauthorized action
            return response()->json(['message' => 'Unauthorized'], 403);
        }
    }

}
