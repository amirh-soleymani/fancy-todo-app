<?php

namespace App\Http\Controllers;

use App\Http\Requests\TodoRequest;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $user = auth('sanctum')->user();
        $userTodos = Todo::where('user_id', $user->id)
            ->get();

        return Response::successResponse('Done', $userTodos);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(TodoRequest $todoRequest)
    {
        $user = auth('sanctum')->user();
        //return $user->id;

        $todo = Todo::create([
            'user_id' => $user->id,
            'category_id' => $todoRequest->category_id,
            'body' => $todoRequest->body,
            'due_date' => $todoRequest->due_date
        ]);

        return Response::successResponse('Todo Created Successfully', $todo);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $todo = Todo::find($id);
        if (auth('sanctum')->user()->id != $todo->user_id) {
            return Response::errorResponse('Access denied', [], 403);
        }
        return Response::successResponse('Done', $todo);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(TodoRequest $todoRequest, $id)
    {
        $todo = Todo::find($id);

        if (auth('sanctum')->user()->id != $todo->user_id) {
            return Response::errorResponse('Access denied', [], 403);
        }

        $todo->body = $todoRequest->body;
        $todo->category_id = $todoRequest->category_id;
        $todo->due_date = $todoRequest->due_date;
        $todo->save();

        return Response::successResponse('Todo Updated Successfully', $todo);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $todo = Todo::find($id);

        if (auth('sanctum')->user()->id != $todo->user_id) {
            return Response::errorResponse('Access denied', [], 403);
        }

        $todo->delete();

        return Response::successResponse('Todo Deleted Successfully', $todo);
    }
}
