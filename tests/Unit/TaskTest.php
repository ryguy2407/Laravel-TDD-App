<?php

namespace Tests\Unit;

use App\Project;
use App\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    function testATaskBelongsToAProject()
    {
    	$task = factory(Task::class)->create();

    	$this->assertInstanceOf(Project::class, $task->project);
    }

    function testItHasAPath()
    {
    	$task = factory(Task::class)->create();

    	$this->assertEquals('/projects/' . $task->project->id . '/task/'. $task->id, $task->path());
    }

	function test_it_can_be_completed()
	{
		$task = factory(Task::class)->create();

		$task->completed();

		$this->assertTrue($task->fresh()->completed);
	}
}
