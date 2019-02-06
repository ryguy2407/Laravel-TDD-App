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
