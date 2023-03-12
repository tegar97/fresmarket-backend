<?php

namespace App\Http\Controllers;

use App\Models\SearchHistory;
use Illuminate\Http\Request;

class SearchHistoryController extends Controller
{
    public function store(Request $request)
    {
        $user = auth()->user();
        $searchTerm = $request->input('search_term');

        // Buat record baru untuk search history
        $searchHistory = new SearchHistory([
            'user_id' => $user->id,
            'search_term' => $searchTerm
        ]);
        $searchHistory->save();

        return response()->json(['success' => true]);
    }
}
