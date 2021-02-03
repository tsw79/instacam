<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Searchable\Search;

/**
 * UserSearchController
 * Adds Search features to the User model
 */
class UserSearchController extends Controller
{
    /**
     * Configures a Searchable Search model
     */
    public function index(Request $request)
    {
        $results = (new Search())
            ->registerModel(User::class, ['name', 'username'])
            ->search($request->input('query'));

        return response()->json($results);
    }
}
