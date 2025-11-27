<?php

namespace Tests\Feature;

use App\Livewire\NewsletterSubscribe;
use App\Models\Subscriber;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class NewsletterSubscribeTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function can_subscribe_with_valid_email()
    {
        Livewire::test(NewsletterSubscribe::class)
            ->set('email', 'test@example.com')
            ->call('subscribe')
            ->assertHasNoErrors()
            ->assertSee('Thank you for subscribing!');

        $this->assertDatabaseHas('subscribers', ['email' => 'test@example.com']);
    }

    #[Test]
    public function cannot_subscribe_with_invalid_email()
    {
        Livewire::test(NewsletterSubscribe::class)
            ->set('email', 'not-an-email')
            ->call('subscribe')
            ->assertHasErrors(['email']);
    }

    #[Test]
    public function cannot_subscribe_twice()
    {
        Subscriber::create(['email' => 'test@example.com']);

        Livewire::test(NewsletterSubscribe::class)
            ->set('email', 'test@example.com')
            ->call('subscribe')
            ->assertHasErrors(['email'])
            ->assertSee('You are already subscribed.');
    }
}
