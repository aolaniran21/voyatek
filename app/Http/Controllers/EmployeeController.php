<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Employee;
use App\Jobs\SendWelcomeEmail;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class EmployeeController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Project $project)
    {
        //
        return response()->json($project->employees()->paginate(10));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Project $project)
    {
        //
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees',
            'position' => 'nullable|string|max:255',
        ]);

        $employee = $project->employees()->create($validated);

        SendWelcomeEmail::dispatch($employee);
        return response()->json($employee, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project, Employee $employee)
    {
        //
        $this->authorize('view', $employee);
        if ($employee->project_id !== $project->id) {
            abort(404);
        }

        return response()->json($employee);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project, Employee $employee)
    {
        //
        if ($employee->project_id !== $project->id) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:employees,email,' . $employee->id,
            'position' => 'nullable|string|max:255',
        ]);

        $employee->update($validated);
        return response()->json($employee);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project, Employee $employee)
    {
        //

        if ($employee->project_id !== $project->id) {
            abort(404);
        }

        $employee->delete();
        return response()->noContent();
    }










    public function restore($id)
    {
        $employee = Employee::onlyTrashed()->findOrFail($id);
        $employee->restore();

        return response()->json($employee);
    }
}
