
	<div class="card">
		<h3 class="font-normal text-xl mb-6 py-4 -ml-5 border-l-4 border-blue pl-4">
			<a href="{{ $project->path() }}" class="text-black no-underline">{{ $project->title }}</a>
		</h3>
		<div class="text-grey mb-4">{{ str_limit($project->description, 100) }}</div>

		<footer>
			<form method="POST" action="{{ route('projects.destroy', $project->id) }}" class="text-right">
				@csrf
				@method('DELETE')
				<button type="submit" class="text-xs">Delete</button>
			</form>
		</footer>
	</div>
