<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Note;


class CategoryController extends Controller
{
    public function destroy(Category $category)
    {
        $default = Category::firstOrCreate(
            ['name' => 'Sem categoria', 'user_id' => auth()->id()]
        );

        Note::where('category_id', $category->id)->update(['category_id' => $default->id]);

        $category->delete();

        return response()->json(['message' => 'Categoria excluída com sucesso.']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = auth()->user()->categories()->create([
            'name' => $request->name,
        ]);

        return response()->json([
            'message' => 'Categoria criada com sucesso!',
            'category' => $category,
        ]);
    }

    public function create()
    {
        $categories = Category::all();
        return view('notes.create', compact('categories'));
    }

}
