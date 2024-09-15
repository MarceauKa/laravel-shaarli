<?php

namespace Tests\Browser;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class BrowseTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_tests_links_browsing()
    {
        $user = factory(User::class)->create();
        $post1 = factory(Post::class)->states('link')->create();
        $post2 = factory(Post::class)->states('link', 'private')->create();

        $this->browse(function (Browser $browser) use ($user, $post1, $post2) {
            $browser
                ->visit('/')
                ->assertSee("Login")
                ->assertSee($post1->postable->title)
                ->assertDontSee($post2->postable->title)
                ->loginAs(1)
                ->visit('/')
                ->assertSee($user->name)
                ->assertSee($post1->postable->title)
                ->assertSee($post2->postable->title)
                ->logout()
                ->assertGuest()
                ->visitRoute('link.view', [$post1->postable->hash_id])
                ->assertSee($post1->postable->title)
                ->visitRoute('link.view', [$post2->postable->hash_id])
                ->assertSee("404")
                ->loginAs(1)
                ->visitRoute('link.view', [$post2->postable->hash_id])
                ->assertSee($post2->postable->title)
                ->logout();
        });
    }

    /** @test */
    public function it_tests_stories_browsing()
    {
        $user = factory(User::class)->create();
        $post1 = factory(Post::class)->states('story')->create();
        $post2 = factory(Post::class)->states('story', 'private')->create();

        $this->browse(function (Browser $browser) use ($user, $post1, $post2) {
            $browser
                ->visit('/')
                ->assertSee("Login")
                ->assertSee($post1->postable->title)
                ->assertDontSee($post2->postable->title)
                ->loginAs(1)
                ->visit('/')
                ->assertSee($user->name)
                ->assertSee($post1->postable->title)
                ->assertSee($post2->postable->title)
                ->logout()
                ->assertGuest()
                ->visitRoute('story.view', [$post1->postable->slug])
                ->assertSee($post1->postable->title)
                ->visitRoute('story.view', [$post2->postable->slug])
                ->assertSee("404")
                ->loginAs(1)
                ->visitRoute('story.view', [$post2->postable->slug])
                ->assertSee($post2->postable->title)
                ->logout();
        });
    }

    /** @test */
    public function it_tests_chests_browsing()
    {
        $user = factory(User::class)->create();
        $post1 = factory(Post::class)->states('chest')->create();
        $post2 = factory(Post::class)->states('chest', 'private')->create();

        $this->browse(function (Browser $browser) use ($user, $post1, $post2) {
            $browser
                ->visit('/')
                ->assertSee("Login")
                ->assertSee($post1->postable->title)
                ->assertDontSee($post2->postable->title)
                ->loginAs(1)
                ->visit('/')
                ->assertSee($user->name)
                ->assertSee($post1->postable->title)
                ->assertSee($post2->postable->title)
                ->logout()
                ->assertGuest()
                ->visitRoute('chest.view', [$post1->postable->hash_id])
                ->assertSee($post1->postable->title)
                ->visitRoute('chest.view', [$post2->postable->hash_id])
                ->assertSee("404")
                ->loginAs(1)
                ->visitRoute('chest.view', [$post2->postable->hash_id])
                ->assertSee($post2->postable->title)
                ->logout();
        });
    }
}
