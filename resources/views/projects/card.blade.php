
	<div class="card" style="display: flex;flex-direction: column;">
		<h3 class="font-normal text-default text-xl mb-6 py-4 -ml-5 border-l-4 border-blue pl-4">
			<a href="{{ $project->path() }}" class="text-default no-underline">{{ $project->title }}</a>
		</h3>
		<div class="text-default mb-4 flex-1">{{ str_limit($project->description, 100) }}</div>

		@can('manage', $project)
			<footer>
				<form method="POST" action="{{ route('projects.destroy', $project->id) }}" class="text-right">
					@csrf
					@method('DELETE')
					<button type="submit" class="text-xs text-default">Delete</button>
				</form>
			</footer>
		@endcan
	</div>
