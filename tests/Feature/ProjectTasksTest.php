<?php

namespace Tests\Feature;

use App\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectTasksTest extends TestCase
{
	use RefreshDatabase;

	public function testAProjectCanHaveTasks()
	{
		$this->signIn();

		$project = factory(Project::class)->create(['owner_id' => auth()->id()]);

		$this->post($project->path() . '/task', ['body' => 'Test task']);

		$this->get($project->path())
			->assertSee('Test task');
	}

	public function testAProjectCanBeUpdated()
	{
		$this->withoutExceptionHandling();

		$this->signIn();

		$project = factory(Project::class)->create(['owner_id' => auth()->id()]);

		$task = $project->addTask('test task');

		$this->patch($project->path() . '/task/' . $task->id, [
			'body' => 'changed',
			'completed' => true
		]);

		$this->assertDatabaseHas('tasks', [
			'body' => 'changed',
			'completed' => true
		]);
	}

	public function testOnlyTheOwnerOfAProjectMayUpdateTask()
	{
		$this->signin();
		
		$project = factory(Project::class)->create();
		
		$task = $project->addTask('test task');
		
		$this->patch($task->path(), [
			'body' => 'changed',
			'completed' => true
		])->assertStatus(403);

		$this->assertDatabaseMissing('tasks', ['body' => 'changed']);

	}

	public function testTaskRequiresABody()
	{
		$this->signIn();

		$project = factory(Project::class)->create(['owner_id' => auth()->id()]);

    	$attributes = factory('App\Task')->raw(['body' => '']);
    	$this->post($project->path() . '/task', [])->assertSessionHasErrors('body');
	}

	public function testOnlyOwnerCanAddTasks()
	{
		$this->signIn();

		$project = factory('App\Project')->create();

		$this->post($project->path() . '/task', ['body' => 'Test task'])
			->assertStatus(403);

		$this->assertDatabaseMissing('tasks', ['body' => 'Test task']);
	}
}
