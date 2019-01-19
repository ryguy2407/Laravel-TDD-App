<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

use App\Project;

class ProjectsController extends Controller
{
    public function index()
    {
    	$projects = Project::all();
		return view('projects.index', compact('projects'));
    }

    public function show(Project $project)
    {
    	return view('projects.show', compact('project'));
    }

    public function store()
    {
    	$attributes = request()->validate([
    		'title' => 'required', 
    		'description' => 'required'
    	]);

    	auth()->user()->projects()->create($attributes);


    	return redirect('/projects');
    }
}
