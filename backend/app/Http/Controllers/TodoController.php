<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TodoController extends Controller
{
    public function index() {
        try {
            $data = auth()->user()->todos;

            return ResponseHelper::make(200, 'success', 'Todos fetched successfully', $data);
        } catch (\Throwable $th) {
            return ResponseHelper::make(400, 'error', 'Something went wrong');
        }
    }

    public function store(Request $request) {
        try {
            $validatedData = $request->validate([
                'title' => 'required',
                'description' => 'required',
            ]);

            $todo = Todo::create([
                'user_id' => $request->user()->id,
                ...$validatedData
            ]);

            return ResponseHelper::make(200, 'success', 'Todo created successfully', $todo);
        } catch (ValidationException $th) {
            return ResponseHelper::make(400, 'error', $th->getMessage());
        } catch (\Throwable $th) {
            return ResponseHelper::make(400, 'error', 'Something went wrong');
        }
    }

    public function show(Todo $todo) {
        try {
            return ResponseHelper::make(200, 'success', 'Todo fetched successfully', $todo);
        } catch (\Throwable $th) {
            return ResponseHelper::make(400, 'error', 'Something went wrong');
        }
    }

    public function update(Request $request, Todo $todo) {
        try {
            $validatedData = $request->validate([
                'title' => 'required',
                'description' => 'required',
                'completed' => 'required|boolean',
            ]);

            $todo->update($validatedData);

            return ResponseHelper::make(200, 'success', 'Todo updated successfully', $todo);
        } catch (ValidationException $th) {
            return ResponseHelper::make(400, 'error', $th->getMessage());
        } catch (\Throwable $th) {
            return ResponseHelper::make(400, 'error', 'Something went wrong');
        }
    }

    public function destroy(Todo $todo) {
        try {
            $todo->delete();

            return ResponseHelper::make(200, 'success', 'Todo deleted successfully');
        } catch (\Throwable $th) {
            return ResponseHelper::make(400, 'error', 'Something went wrong');
        }
    }
}
