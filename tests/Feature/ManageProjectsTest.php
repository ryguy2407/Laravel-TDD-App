<?php

namespace Tests\Feature;

use App\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ManageProjectsTest extends TestCase
{
	use WithFaker, RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGuestCannotCreateProjects()
    {
    	$this->withoutExceptionHandling();

    	$this->signIn();
        $attributes = [
        	'title' => $this->faker->sentence,
        	'description' => $this->faker->sentence,
        	'notes' => 'General notes here.'
        ];

        $response = $this->post('/projects', $attributes);

        $project = Project::where($attributes)->first();

        $response->assertRedirect($project->path());

        $this->assertDatabaseHas('projects', $attributes);

        $this->get($project->path())
        	->assertSee($attributes['title'])
        	->assertSee($attributes['description'])
        	->assertSee($attributes['notes']);
    }

    public function testAUserCanUpdateAProject()
    {
    	$this->signIn();

    	$this->withoutExceptionHandling();

    	$project = factory('App\Project')->create(['owner_id' => auth()->id()]);

    	$this->patch($project->path(), [
    		'notes' => 'changed'
    	])->assertRedirect($project->path());

    	$this->assertDatabaseHas('projects', ['notes' => 'changed']);

    }

    public function testGuestsMayNotViewProjects()
    {
    	$this->get('/projects')->assertRedirect('login');
    }

    public function testGuestsMayNotCreateProjects()
    {
    	$this->get('/projects/create')->assertRedirect('login');
    }

    public function testGuestsMayNotViewASingleProject()
    {
    	$project = factory('App\Project')->create();

    	$this->get($project->path())->assertRedirect('login');
    }

    public function testProjectRequiresATitle()
    {
    	$this->signIn();
    	$attributes = factory('App\Project')->raw(['title' => '']);
    	$this->post('/projects', $attributes)->assertSessionHasErrors('title');
    }

    public function testProjectRequiresADescription()
    {
    	$this->signIn();
    	$attributes = factory('App\Project')->raw(['description' => '']);
    	$this->post('/projects', [])->assertSessionHasErrors('description');
    }

    public function testUserCanViewTheirProject()
    {
    	$this->withoutExceptionHandling();

    	$this->signIn();

    	$project = factory('App\Project')->create(['owner_id' => auth()->id()]);
    	$this->get($project->path())
    		->assertSee($project->title);    
    }

    public function testAnAuthenticatedUserCannotViewOtherProjects()
    {

    	$this->signIn();

    	$project = factory('App\Project')->create();

    	$this->patch($project->path(), [])->assertStatus(403);
    }

    public function testAnAuthenticatedUserCannotUpdateOtherProjects()
    {
    	$this->signIn();

    	$project = factory('App\Project')->create();

    	$this->get($project->path())->assertStatus(403);
    }

    public function testOnlyAuthenticatedUserCanCreateAProject()
    {
    	$attributes = factory('App\Project')->raw();
    	$this->post('/projects', $attributes)->assertRedirect('login');
    }
}
