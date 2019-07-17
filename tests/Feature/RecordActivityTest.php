<?php

namespace Tests\Feature;

use App\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Facades\Tests\Setup\ProjectFactory;
use Tests\TestCase;

class RecordActivityTest extends TestCase
{
    use RefreshDatabase;

    function test_creating_a_project()
    {
    	$project = ProjectFactory::create();

    	$this->assertCount(1, $project->activity);
    	$this->assertEquals('created_project', $project->activity[0]->description);

	    tap($project->activity->last(), function($activity) {
		    $this->assertEquals('created_project', $activity->description);
		    $this->assertNull($activity->changes);
	    });
    }

    function test_updating_a_project()
    {
		$project = ProjectFactory::create();
	    $originalTitle = $project->title;
		$project->update([
			'title' => 'changed'
		]);

		$this->assertCount(2, $project->activity);

	    tap($project->activity->last(), function($activity) use ($originalTitle) {
		    $this->assertEquals('updated_project', $activity->description);

		    $expected = [
			    'before' => ['title' => $originalTitle],
			    'after' => ['title' => 'changed']
		    ];
		    $this->assertEquals($expected, $activity->changes);
	    });

    }

	function test_creating_a_new_task()
	{
		$project = ProjectFactory::create();

		$project->addTask('some task');

		$this->assertCount(2, $project->activity);

		tap($project->activity->last(), function($activity){
			$this->assertEquals('created_task', $activity->description);
			$this->assertInstanceOf(Task::class, $activity->subject);
			$this->assertEquals('some task', $activity->subject->body);
		});
	}

	function test_completing_a_task()
	{
		$project = ProjectFactory::withTasks(1)->create();

		$this->actingAs($project->owner)->patch($project->tasks[0]->path(), [
			'body' => 'foo',
			'completed' => true
		]);

		$this->assertCount(3, $project->activity);

		tap($project->activity->last(), function($activity){
			$this->assertEquals('completed_task', $activity->description);
			$this->assertInstanceOf(Task::class, $activity->subject);
		});

	}

	/** @test */
	function incompleting_a_task()
	{
		$project = ProjectFactory::withTasks(1)->create();

		$this->actingAs($project->owner)->patch($project->tasks[0]->path(), [
			'body' => 'foo',
			'completed' => true
		]);

		$this->assertCount(3, $project->activity);

		$this->patch($project->tasks[0]->path(), [
			'body' => 'foo',
			'completed' => false
		]);

		$this->assertCount(4, $project->fresh()->activity);
		$this->assertEquals('incompleted_task', $project->fresh()->activity->last()->description);

	}

	/** @test */
	function deleting_a_task()
	{
		$project = ProjectFactory::withTasks(1)->create();

		$project->tasks[0]->delete();

		$this->assertCount(3, $project->activity);
	}


}
