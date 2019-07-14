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

	protected static function boot() {
		parent::boot();

		static::created(function($task){
			$task->project->recordActivity('created_task');
		});

		static::updated(function($task) {
			if (! $task->completed) return;

			$task->project->recordActivity('completed_task');
		});
	}

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
	}

	public function incomplete()
	{
		$this->update(['completed' => false]);
	}
}
