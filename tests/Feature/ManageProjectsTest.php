<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
        	'description' => $this->faker->paragraph
        ];

        $this->post('/projects', $attributes);

        $this->assertDatabaseHas('projects', $attributes);

        $this->get('/projects')->assertSee($attributes['title']);
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

    	$this->get($project->path())->assertStatus(403);
    }

    public function testOnlyAuthenticatedUserCanCreateAProject()
    {
    	$attributes = factory('App\Project')->raw();
    	$this->post('/projects', $attributes)->assertRedirect('login');
    }
}
