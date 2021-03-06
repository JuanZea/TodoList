<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Todo\StoreAndUpdateRequest;
use App\Http\Resources\Api\TodoResource;
use App\Models\Todo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

class ToDoController extends Controller
{
    public function index(): ResourceCollection
    {
        return TodoResource::collection(Todo::paginate());
    }

    public function store(StoreAndUpdateRequest $request): JsonResponse
    {
        $todo = new Todo();
        $validatedData = $request->validated();

        $todo->title = $validatedData['title'];
        $request->has('description') && $todo->description = $validatedData['description'];

        $todo->save();

        return TodoResource::make($todo)->response()->setStatusCode(201);
    }

    public function show(Todo $todo): TodoResource
    {
        return TodoResource::make($todo);
    }

    public function update(StoreAndUpdateRequest $request, Todo $todo): TodoResource
    {
        $validatedData = $request->validated();

        $todo->title = $validatedData['title'];
        $todo->description = $validatedData['description'];
        $todo->done = $validatedData['done'];

        $todo->save();

        return TodoResource::make($todo);
    }

    public function destroy(Todo $todo): Response
    {
        $todo->delete();
        return response(null, 204);
    }

    public function toggle(Todo $todo): TodoResource
    {
        $todo->done = !$todo->done;

        $todo->save();

        return TodoResource::make($todo);
    }
}
