<?php

namespace Tests\Feature;

use App\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Facades\Tests\Setup\ProjectFactory;
use Tests\TestCase;

class ProjectTasksTest extends TestCase
{
	use RefreshDatabase;

	public function testAProjectCanHaveTasks()
	{
		$project = ProjectFactory::create();

		$this->actingAs($project->owner)->post($project->path() . '/task', ['body' => 'Test task']);

		$this->get($project->path())
			->assertSee('Test task');
	}

	public function testAProjectCanBeUpdated()
	{
		$project = ProjectFactory::withTasks(1)->create();

		$this->actingAs($project->owner)
			->patch($project->tasks->first()->path(), [
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

		$project = ProjectFactory::withTasks(1)->create();
				
		$this->patch($project->tasks[0]->path(), [
			'body' => 'changed',
			'completed' => true
		])->assertStatus(403);

		$this->assertDatabaseMissing('tasks', ['body' => 'changed']);

	}

	public function testTaskRequiresABody()
	{
		$project = ProjectFactory::create();

    	$attributes = factory('App\Task')->raw(['body' => '']);
    	
    	$this->actingAs($project->owner)->post($project->path() . '/task', [])->assertSessionHasErrors('body');
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
