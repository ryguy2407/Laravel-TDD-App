<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTest extends TestCase
{
	use RefreshDatabase;

    public function testItHasAPath()
    {
    	$project = factory('App\Project')->create();

    	$this->assertEquals('/projects/'.$project->id, $project->path());

    }

    public function testProjectBelongsToAnOwner()
    {
    	$project = factory('App\Project')->create();
    	$this->assertInstanceOf('App\User', $project->owner);
    }

    public function testCanAddATask()
    {
    	$project = factory('App\Project')->create();
    	
    	$task = $project->addTask('Test task');
    	
    	$this->assertCount(1, $project->tasks);
    	$this->assertTrue($project->tasks->contains($task));
    }
}
