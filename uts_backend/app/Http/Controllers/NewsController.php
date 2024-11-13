<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\news;
use Illuminate\Support\Facades\Validator;

class NewsController extends Controller
{
    // Method index
    public function index()
    {
        // Retrieve all news data
        $news = news::all();

        // Check if data is empty
        if ($news->isEmpty()) {
            return response()->json(['message' => 'No news data added yet'], 404);
        }

        // Response if data is found
        $response = [
            'data' => $news,
            'message' => 'Successfully retrieved all news data'
        ];

        return response()->json($response, 200);
    }

    // Method to add news data
    public function store(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'author' => 'required|string',
            'description' => 'required|string',
            'content' => 'required|string',
            'url' => 'required|url|unique:news',
            'url_image' => 'required|url',
            'published_at' => 'required|date',
            'category' => 'required|string'
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['message' => 'Incomplete or invalid data', 'errors' => $validator->errors()], 400);
        }

        // Create news data
        $news = News::create($request->all());

        $response = [
            'message' => 'Successfully created new news entry',
            'data' => $news
        ];

        return response()->json($response, 201);
    }

    // Method to update news data
    public function update(Request $request, $id)
    {
        // Find news by ID
        $news = News::find($id);

        // If news not found, return 404 response
        if (!$news) {
            return response()->json(['message' => 'News not found'], 404);
        }

        // Validate input for PUT (all fields required)
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'author' => 'required|string',
            'description' => 'required|string',
            'content' => 'required|string',
            'url' => 'required|url|unique:news,url,' . $id,
            'url_image' => 'required|url',
            'published_at' => 'required|date',
            'category' => 'required|string'
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['message' => 'Incomplete data. Provide complete data!', 'errors' => $validator->errors()], 400);
        }

        // Replace all news data
        $news->update($request->all());

        $response = [
            'message' => 'Successfully replaced news data',
            'data' => $news
        ];

        return response()->json($response, 200);
    }

    // Method to partially update news data
    public function partialUpdate(Request $request, $id)
    {
        // Find news by ID
        $news = News::find($id);

        // If news not found, return 404 response
        if (!$news) {
            return response()->json(['message' => 'News not found'], 404);
        }

        // Validate input for PATCH (not all fields required)
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string',
            'author' => 'sometimes|required|string',
            'description' => 'sometimes|required|string',
            'content' => 'sometimes|required|string',
            'url' => 'sometimes|required|url|unique:news,url,' . $id,
            'url_image' => 'sometimes|required|url',
            'published_at' => 'sometimes|required|date',
            'category' => 'sometimes|required|string'
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['message' => 'Incomplete data. Provide complete data!', 'errors' => $validator->errors()], 400);
        }

        // Update only provided news data
        $news->update($request->only(['title', 'author', 'description', 'content', 'url', 'url_image', 'published_at', 'category']));

        $response = [
            'message' => 'Successfully updated news data partially',
            'data' => $news
        ];

        return response()->json($response, 200);
    }

    // Method to delete news data
    public function destroy($id)
    {
        // Find news by ID
        $news = News::find($id);

        // If news not found, return 404 response
        if (!$news) {
            return response()->json(['message' => 'News not found'], 404);
        }

        // Delete news data
        $news->delete();

        $response = [
            'message' => 'Successfully deleted news',
            'data' => $news
        ];

        return response()->json($response, 200);
    }
}