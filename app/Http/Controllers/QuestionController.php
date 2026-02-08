<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Question::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('content', 'like', "%{$search}%")
                  ->orWhere('topic', 'like', "%{$search}%");
            });
        }

        if ($request->filled('topic')) {
            $query->where('topic', $request->topic);
        }

        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->difficulty);
        }

        $questions = $query->latest()->paginate(10)->withQueryString();

        return view('teacher.questions', compact('questions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:255',
            'topic' => 'required|string|max:50',
            'difficulty' => 'required|in:easy,medium,hard',
            'correct_answer' => 'required|string',
            'choices' => 'required|array|min:2',
            'choices.*' => 'required|string',
        ]);

        Question::create($validated);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Question created successfully']);
        }

        return redirect()->route('teacher.questions.index')->with('success', 'Question created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $question = Question::findOrFail($id);
        
        if (request()->wantsJson()) {
            return response()->json($question);
        }
        
        // Fallback if accessed directly, though we intend to use modals
        return view('teacher.questions.edit', compact('question'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $question = Question::findOrFail($id);

        $validated = $request->validate([
            'content' => 'required|string|max:255',
            'topic' => 'required|string|max:50',
            'difficulty' => 'required|in:easy,medium,hard',
            'correct_answer' => 'required|string',
            'choices' => 'required|array|min:2',
            'choices.*' => 'required|string',
        ]);

        $question->update($validated);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Question updated successfully']);
        }

        return redirect()->route('teacher.questions.index')->with('success', 'Question updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $question = Question::findOrFail($id);
        $question->delete();

        return redirect()->route('teacher.questions.index')->with('success', 'Question deleted successfully');
    }
}
