@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h2>{{ $task->title }}</h2>
        </div>
        <div class="card-body">
            <p><strong>Project:</strong> {{ $task->project->name ?? 'N/A' }}</p>
            <p><strong>Status:</strong> {{ $task->status }}</p>
            <p><strong>Priority:</strong> {{ $task->priority }}</p>
            <p><strong>Due Date:</strong> {{ $task->due_date }}</p>
            <p><strong>Assigned To:</strong> {{ $task->assignedUser->name ?? 'Unassigned' }}</p>
            <p><strong>Created By:</strong> {{ $task->creator->name ?? 'N/A' }}</p>
            <p><strong>Description:</strong> {{ $task->description }}</p>
            
            <h4>Comments</h4>
            @foreach($task->comments as $comment)
                <div class="comment">
                    <p>{{ $comment->user->name }}: {{ $comment->content }}</p>
                </div>
            @endforeach
            
            <h4>Activity Log</h4>
            @foreach($task->activities as $activity)
                <div class="activity">
                    <p>{{ $activity->user->name }} - {{ $activity->description }}</p>
                    <small>{{ $activity->activity_date }}</small>
                </div>
            @endforeach
        </div>
    </div>
</div>

@endsection