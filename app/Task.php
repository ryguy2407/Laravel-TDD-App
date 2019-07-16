<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $guarded = [];

    protected $touches = ['project'];

	protected $casts = [
		'completed' => 'boolean'
	];

	public function project()
    {
    	return $this->belongsTo(Project::class);
    }

    public function path()
    {
    	return "/projects/{$this->project->id}/task/{$this->id}";
    }

	public function completed()
	{
		$this->update(['completed' => true]);
		$this->recordActivity('completed_task');
	}

	public function incomplete()
	{
		$this->update(['completed' => false]);
		$this->recordActivity('incompleted_task');
	}

	public function activity()
	{
		return $this->morphMany(Activity::class, 'subject')->latest();
	}

	public function recordActivity($description)
	{
		$this->activity()->create([
			'project_id' => $this->project_id,
			'description' => $description
		]);
	}
}
