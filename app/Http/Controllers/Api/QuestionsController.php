<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AskQuestionRequest;
use App\Http\Resources\QuestionResource;
use App\Question;
use Illuminate\Http\Request;

class QuestionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $questions = Question::with('user')->latest()->paginate(10);

        return QuestionResource::collection($questions);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AskQuestionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(AskQuestionRequest $request)
    {
        $question = $request->user()->questions()->create($request->only('title', 'body'));

        return response()->json([
            'message' => "Your question has been submitted",
            'question' => new QuestionResource($question),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Question $question)
    {
        return response()->json([
            'title' => $question->title,
            'body' => $question->body,
            'body_html' => $question->body_html,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AskQuestionRequest $request
     * @param \App\Question $question
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(AskQuestionRequest $request, Question $question)
    {
        $this->authorize("update", $question);

        $question->update($request->only('title', 'body'));

        return response()->json([
            'message' => "Your question has been updated",
            'body_html' => $question->body_html,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Question $question
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Question $question)
    {
        $this->authorize("delete", $question);

        $question->delete();

        return response()->json([
            'message' => "Your question has been deleted",
        ]);
    }
}
