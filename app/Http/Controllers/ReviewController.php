<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Book;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('throttle:reviews')->only(['store']);
    }
    public function index()
    {
        //
    }


    public function create(Book $book)
    {
        return view('books.reviews.create', ['book' => $book]);
    }


    public function store(Request $request, Book $book)
    {
        $data = $request->validate([
            'review' => 'required|min:15',
            'rating' => 'required|min:1|max:5|integer'
        ]);

        $book->reviews()->create($data);

        return redirect()->route('books.show', $book);
    }


    public function show(string $id)
    {
        //
    }


    public function edit(string $id)
    {
        //
    }


    public function update(Request $request, string $id)
    {
        //
    }


    public function destroy(string $id)
    {
        //
    }
}
