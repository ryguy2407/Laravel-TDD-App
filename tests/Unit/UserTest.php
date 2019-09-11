<?php

namespace Tests\Unit;

use App\User;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
	use RefreshDatabase;

    public function testAUserHasProjects()
    {
    	$user = factory('App\User')->create();

    	$this->assertInstanceOf(Collection::class, $user->projects);
    }

	/** @test */
	public function a_user_has_accessible_projects()
	{
		$john = $this->signIn();
	    ProjectFactory::ownedBy($john)->create();
		$this->assertCount(1, $john->accessibleProjects());

		$sally = factory(User::class)->create();
		$nick = factory(User::class)->create();

		ProjectFactory::ownedBy($sally)->create()->invite($nick);

		$this->assertCount(1, $john->accessibleProjects());
	}
}
