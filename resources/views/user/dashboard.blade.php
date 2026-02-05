@extends('layouts.admin')

@section('content')
<h2>User Dashboard</h2>

<div style="display:flex; gap:20px">

<div style="border:1px solid #ccc; padding:20px">
    <h4>My Projects</h4>
    <p>{{ $projects }}</p>
</div>

<div style="border:1px solid #ccc; padding:20px">
    <h4>My Tasks</h4>
    <p>{{ $tasks }}</p>
</div>

<div style="border:1px solid #ccc; padding:20px">
    <h4>Completed Tasks</h4>
    <p>{{ $completedTasks }}</p>
</div>


Project::whereHas('tasks', function($q){
    $q->where('assigned_to', auth()->id());
})->get();



</div>
@endsection
