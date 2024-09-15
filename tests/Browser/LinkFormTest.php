<?php

namespace Tests\Browser;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LinkFormTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_can_add_a_link()
    {
        $user = factory(User::class)->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser
                ->loginAs(1)
                ->visitRoute('link.create')
                ->assertSee('URL')
                ->type('@link-form-url', url()->route('home'))
                ->waitForText('Retrieving URL informations...', 10)
                ->waitUntilVueIsNot('parsing', true, '@link-form', 10)
                ->assertDontSee('Retrieving URL informations...')
                ->type('@link-form-title', 'Custom link')
                ->type('@link-form-content', 'This is my custom link')
                ->click('@link-form-tags')
                ->type('.multiselect__input', 'testtag')
                ->keys('.multiselect__input', '{enter}')
                ->click('@link-form') // Click away to close "Tags"
                ->click('@link-form-save-dropdown')
                ->click('@link-form-save-view')
                ->waitForText('Custom link', 10)
                ->assertSee('Custom link')
                ->assertSee('testtag')
                ->visitRoute('link.create')
                ->type('@link-form-url', url()->route('home'))
                ->waitForText('Retrieving URL informations...', 10)
                ->waitUntilVueIsNot('parsing', true, '@link-form', 10)
                ->assertSee('The url has already been taken.');
        });
    }

    /** @test */
    public function it_can_edit_a_link()
    {
        $user = factory(User::class)->create();
        $post = factory(Post::class)->state('link')->create();

        $this->browse(function (Browser $browser) use ($user, $post) {
            $browser
                ->loginAs(1)
                ->visitRoute('link.edit', $post->postable->id)
                ->assertInputValue('@link-form-url', $post->postable->url)
                ->assertInputValue('@link-form-title', $post->postable->title)
                ->assertInputValue('@link-form-content', $post->postable->content)
                ->type('@link-form-title', 'Edited link')
                ->click('@link-form-private')
                ->click('@link-form-tags')
                ->type('.multiselect__input', 'newtag')
                ->keys('.multiselect__input', '{enter}')
                ->click('@link-form') // Click away to close "Tags"
                ->click('@link-form-save-dropdown')
                ->click('@link-form-save-view')
                ->waitForText('Edited link', 10)
                ->assertSee('Edited link')
                ->assertSee('newtag')
                ->logout()
                ->visit('/')
                ->assertDontSee('Edited link');
        });
    }
}
