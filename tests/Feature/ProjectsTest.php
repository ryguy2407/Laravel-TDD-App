<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectsTest extends TestCase
{
	use WithFaker, RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testUserCanCreateProject()
    {
    	$this->actingAs(factory('App\User')->create());
        $attributes = [
        	'title' => $this->faker->sentence,
        	'description' => $this->faker->paragraph
        ];

        $this->post('/projects', $attributes)->assertRedirect('/projects');

        $this->assertDatabaseHas('projects', $attributes);

        $this->get('/projects')->assertSee($attributes['title']);
    }

    public function testProjectRequiresATitle()
    {
    	$this->actingAs(factory('App\User')->create());
    	$attributes = factory('App\Project')->raw(['title' => '']);
    	$this->post('/projects', $attributes)->assertSessionHasErrors('title');
    }

    public function testProjectRequiresADescription()
    {
    	$this->actingAs(factory('App\User')->create());
    	$attributes = factory('App\Project')->raw(['description' => '']);
    	$this->post('/projects', [])->assertSessionHasErrors('description');
    }

    public function testUserCanViewAProject()
    {
    	$this->withoutExceptionHandling();

    	$project = factory('App\Project')->create();
    	$this->get($project->path())
    		->assertSee($project->title)
    		->assertSee($project->description);
    }

    public function testOnlyAuthenticatedUserCanCreateAProject()
    {
    	$attributes = factory('App\Project')->raw();
    	$this->post('/projects', $attributes)->assertRedirect('login');
    }
}
