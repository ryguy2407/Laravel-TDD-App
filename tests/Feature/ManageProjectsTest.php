<?php

namespace Tests\Feature;

use App\Project;
use Facades\Tests\Setup\ProjectFactory;
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
    	$project = ProjectFactory::create();

    	$this->actingAs($project->owner)->patch($project->path(),
    		$attributes = [
    			'title' => 'changed', 
    			'description' => 'changed', 
    			'notes' => 'changed'
    		]
    	)->assertRedirect($project->path());

    	$this->get($project->path().'/edit')->assertOk();

    	$this->assertDatabaseHas('projects', $attributes);

    }

    public function testUserCanUpdateGeneralNotes()
    {
    	$project = ProjectFactory::create();

    	$this->actingAs($project->owner)->patch($project->path(),
    		$attributes = [
    			'notes' => 'changed'
    		]
    	)->assertRedirect($project->path());

    	$this->assertDatabaseHas('projects', $attributes);
    }
   

    public function testGuestsMayNotViewProjects()
    {
    	$this->get('/projects')->assertRedirect('login');
    }

    public function testGuestsMayNotCreateProjects()
    {
    	$this->get('/projects/create')->assertRedirect('login');
    }

    public function testGuestsMayNotEditProjects()
    {
    	$project = ProjectFactory::create();
    	$this->get($project->path().'/edit')->assertRedirect('login');
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
    	$this->post('/projects', $attributes)->assertSessionHasErrors('description');
    }

    public function testUserCanViewTheirProject()
    {
    	$project = ProjectFactory::create();
    	$this->actingAs($project->owner)->get($project->path())
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
