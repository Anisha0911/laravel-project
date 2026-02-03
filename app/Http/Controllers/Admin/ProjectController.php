<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;


class ProjectController extends Controller
{
    // List Projects
    public function index()
{
    $projects = Project::with('user')->latest()->get();
    return view('admin.projects.index', compact('projects'));
}

// create projects
public function create()
{
    $users = User::all();
    return view('admin.projects.create', compact('users'));
}


// store project logic 
public function store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'user_id' => 'required|exists:users,id',
    ]);

    Project::create($request->all());

    return redirect()->route('admin.projects.index')->with('success', 'Project created');
}

// edit project
public function edit(Project $project)
{
    $users = User::all();
    return view('admin.projects.edit', compact('project', 'users'));
}

//update porject
public function update(Request $request, Project $project)
{
    $request->validate([
        'name' => 'required',
        'user_id' => 'required'
    ]);

    $project->update($request->all());

    return redirect()->route('admin.projects.index')->with('success', 'Project updated');
}

public function destroy(Project $project)
{
    $project->delete();

    return redirect()
        ->route('admin.projects.index')
        ->with('success', 'Project deleted successfully');
}
}
