@extends('layouts.app')
@section('content')
	<header class="flex items-center mb-3 py-4">
		<div class="flex justify-between w-full items-center">
			<p class="text-grey text-sm font-normal items-end">
				<a class="text-grey text-sm font-normal no-underline" href="/projects">My Projects</a> / {{ $project->title }}
			</p>

			<div class="flex items-center">
				@foreach($project->members as $member)
					<img class="rounded-full w-8 mr-2"
						 src="{{ gravatar_url($member->email) }}"
						 alt="{{ $member->name }}'s avatar">
				@endforeach

					<img class="rounded-full w-8 mr-2"
						 src="{{ gravatar_url($project->owner->email) }}"
						 alt="{{ $project->owner->name }}'s avatar">

				<a href="{{ $project->path().'/edit' }}" class="button ml-4">Edit project</a>
			</div>
		</div>
	</header>

	<main>
		<div class="lg:flex -mx-3">
			<div class="lg:w-3/4 px-3 mb-6">
				<div class="mb-8">
					<h2 class="text-lg text-grey font-normal mb-3">Tasks</h2>
					@forelse($project->tasks as $task)
						<div class="card mb-3">
							<form method="POST" action="{{ $task->path() }}">
								@method('PATCH')
								@csrf
								<div class="flex">
									<input name="body" value="{{ $task->body }}" class="w-full {{ $task->completed ? 'text-grey' : '' }}">
									<input name="completed" type="checkbox" onChange="this.form.submit()" {{ $task->completed ? 'checked' : '' }}>
								</div>
							</form>
						</div>
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
					<form action="{{ $project->path() }}" method="POST">
						@csrf
						@method('PATCH')
						<textarea name="notes" class="card w-full mb-4" style="min-height: 200px;" placeholder="Type any other notes or info here...">{{ $project->notes }}</textarea>
						<button type="submit" class="button">Save</button>
					</form>

					@include('errors')
				</div>		
			</div>
			<div class="lg:w-1/4 px-3">
				@include('projects.card')

				@include('projects.activity.card')

				@can('manage', $project)
					@include('projects.invite')
				@endcan

			</div>
		</div>
	</main>
@endsection