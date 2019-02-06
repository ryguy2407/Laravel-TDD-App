@extends('layouts.app')
@section('content')
	<header class="flex items-center mb-3 py-4">
		<div class="flex justify-between w-full items-center">
			<p class="text-grey text-sm font-normal items-end">
				<a class="text-grey text-sm font-normal no-underline" href="/projects">My Projects</a> / {{ $project->title }}
			</p>
			<a href="/projects/create" class="button">New project</a>
		</div>
	</header>

	<main>
		<div class="lg:flex -mx-3">
			<div class="lg:w-3/4 px-3 mb-6">
				<div class="mb-8">
					<h2 class="text-lg text-grey font-normal mb-3">Tasks</h2>
					@forelse($project->tasks as $task)
						<div class="card mb-3">{{ $task->body }}</div>
					@empty
						
					@endforelse
					<div class="card mb-3">
						<form action="{{ $project->path() . '/task' }}" method="POST">
							@csrf
							<input placeholder="add a new task" class="w-full" name="body">
						</form>
					</div>
				</div>
				
				<div>
					<h2 class="text-lg text-grey font-normal mb-3">General Notes</h2>
					<textarea class="card w-full" style="min-height: 200px;">Lorem ipsum</textarea>
				</div>		
			</div>
			<div class="lg:w-1/4 px-3">
				@include('projects.card')
			</div>
		</div>
	</main>
@endsection