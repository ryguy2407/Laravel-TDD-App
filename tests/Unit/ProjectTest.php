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
}
