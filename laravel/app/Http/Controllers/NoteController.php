<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;
use App\Models\Category;

class NoteController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('notes.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'    => 'required|string|max:255',
            'text'     => 'required|string',
            'category_id' => 'required|integer|exists:categories,id',
        ]);

        Note::create([
            'title'    => $request->title,
            'text'     => $request->text,
            'category_id' => $request->category_id,
            'user_id'  => auth()->id(),
        ]);

        return response()->json(['message' => 'Anotação criada com sucesso!']);
    }

    public function all()
    {
        $notes = Note::with('category')->where('user_id', auth()->id())->latest()->get();
        return view('notes.all', compact('notes'));
    }

}
