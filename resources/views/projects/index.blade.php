@extends('layouts.app')
@section('content')
	<header class="flex items-center mb-3 py-4">
		<div class="flex justify-between w-full items-center">
			<h2 class="text-grey text-sm font-normal items-end">My Projects</h2>
			<a href="/projects/create" class="button">New project</a>
		</div>
	</header>

	<div class="lg:flex py-2 lg:flex-wrap -mx-3">
		@forelse($projects as $project)
			<div class="lg:w-1/3 px-3 pb-6" style="height:200px;">
				@include('projects.card')
			</div>
		@empty
			<div>No projects yet</div>
		@endforelse
	</div>
@endsection