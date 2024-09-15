<?php

namespace Tests\Browser;

use App\Models\Post;
use App\Models\Share;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TempSharingTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_can_share_link()
    {
        $user = factory(User::class)->create();
        $post = factory(Post::class)->states('private', 'link')->create();

        $this->browse(function (Browser $browser) use ($user, $post) {
            $browser
                ->loginAs(1)
                ->visitRoute('home')
                ->assertSee($post->postable->title)
                ->click('@link-card-more')
                ->assertSee('Temp sharing')
                ->click('@link-card-temp-share')
                ->waitForText('Link expires in')
                ->press('Generate')
                ->waitForText('Link generated');

            $share = Share::first();

            $browser
                ->logout()
                ->visitRoute('share', [$share->id, $share->token])
                ->assertSee($post->postable->title);

            $share->expires_at = $share->expires_at->subHour();
            $share->save();

            $browser
                ->visitRoute('share', [$share->id, $share->token])
                ->assertSee('This shared content has expired');

        });
    }

    /** @test */
    public function it_can_share_chest()
    {
        $user = factory(User::class)->create();
        $post = factory(Post::class)->states('private', 'chest')->create();

        $this->browse(function (Browser $browser) use ($user, $post) {
            $browser
                ->loginAs(1)
                ->visitRoute('home')
                ->assertSee($post->postable->title)
                ->click('@chest-card-more')
                ->assertSee('Temp sharing')
                ->click('@chest-card-temp-share')
                ->waitForText('Link expires in')
                ->press('Generate')
                ->waitForText('Link generated');

            $share = Share::first();

            $browser
                ->logout()
                ->visitRoute('share', [$share->id, $share->token])
                ->assertSee($post->postable->title);

            $share->expires_at = $share->expires_at->subHour();
            $share->save();

            $browser
                ->visitRoute('share', [$share->id, $share->token])
                ->assertSee('This shared content has expired');

        });
    }

    /** @test */
    public function it_can_share_story()
    {
        $user = factory(User::class)->create();
        $post = factory(Post::class)->states('private', 'story')->create();

        $this->browse(function (Browser $browser) use ($user, $post) {
            $browser
                ->loginAs(1)
                ->visitRoute('home')
                ->assertSee($post->postable->title)
                ->click('@story-card-more')
                ->assertSee('Temp sharing')
                ->click('@story-card-temp-share')
                ->waitForText('Link expires in')
                ->press('Generate')
                ->waitForText('Link generated');

            $share = Share::first();

            $browser
                ->logout()
                ->visitRoute('share', [$share->id, $share->token])
                ->assertSee($post->postable->title);

            $share->expires_at = $share->expires_at->subHour();
            $share->save();

            $browser
                ->visitRoute('share', [$share->id, $share->token])
                ->assertSee('This shared content has expired');

        });
    }

    /** @test */
    public function it_can_share_album()
    {
        $user = factory(User::class)->create();
        $post = factory(Post::class)->states('private', 'album')->create();

        $this->browse(function (Browser $browser) use ($user, $post) {
            $browser
                ->loginAs(1)
                ->visitRoute('home')
                ->assertSee($post->postable->title)
                ->click('@album-card-more')
                ->assertSee('Temp sharing')
                ->click('@album-card-temp-share')
                ->waitForText('Link expires in')
                ->press('Generate')
                ->waitForText('Link generated');

            $share = Share::first();

            $browser
                ->logout()
                ->visitRoute('share', [$share->id, $share->token])
                ->assertSee($post->postable->title);

            $share->expires_at = $share->expires_at->subHour();
            $share->save();

            $browser
                ->visitRoute('share', [$share->id, $share->token])
                ->assertSee('This shared content has expired');

        });
    }
}
