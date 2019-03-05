<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Facades\Tests\Setup\ProjectFactory;
use Tests\TestCase;

class ActivityFeatureTest extends TestCase
{
    use RefreshDatabase;

    function test_creating_a_project_generates_activity()
    {
    	$project = ProjectFactory::create();

    	$this->assertCount(1, $project->activity);
    	$this->assertEquals('created', $project->activity[0]->description);
    }

    function test_updating_a_project_generates_activity()
    {
		$project = ProjectFactory::create();
		$project->update([
			'title' => 'changed'
		]);
		$this->assertCount(2, $project->activity);    
		$this->assertEquals('updated', $project->activity->last()->description);	
    }
}
