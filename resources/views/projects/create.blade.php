@extends('layouts.app')
@section('content')
	<h1>Create a project</h1>
	<form action="/projects" method="POST" accept-charset="utf-8">
		@csrf
		<div class="form-group">
			<label for="title">Title</label>
			<input class="form-control" type="text" name="title" id="title" value="{{ old('title') }}">
		</div>
		<div class="form-group">
			<label for="description">Description</label>
			<textarea class="form-control" type="text" name="description" id="description"></textarea>
		</div>
		<div class="form-group">
			<button type="submit" class="btn btn-success">Create project</button>
		</div>
		<a href="/projects">Cancel</a>
	</form>
@endsection