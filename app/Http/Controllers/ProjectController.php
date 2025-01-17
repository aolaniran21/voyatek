<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json(Project::with('employees')->get());
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
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'name' => 'required|unique:projects',
            'description' => 'nullable',
            'status' => 'in:not started,in progress,completed',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        $project = Project::create($validated);
        return response()->json($project, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        //
        $this->authorize('view', $project);
        return response()->json($project->load('employees'));
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
    public function update(Request $request, Project $project)
    {
        //
        $this->authorize('update', $project);

        $validated = $request->validate([
            'name' => 'sometimes|required|unique:projects,name,' . $project->id,
            'status' => 'in:not started,in progress,completed',
        ]);

        $project->update($validated);
        return response()->json($project);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        //
        $this->authorize('delete', $project);
        $project->delete();
        return response()->noContent();
    }

    public function dashboard()
    {
        $summary = [
            'total_projects' => Project::count(),
            'total_employees' => Employee::count(),
            'projects_by_status' => Project::select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->get(),
        ];

        $projects = Project::with('employees')->get();

        return response()->json(compact('summary', 'projects'));
    }

    public function search(Request $request)
    {
        $query = Project::query();

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        return $query->with('employees')->paginate(10);
    }
}
