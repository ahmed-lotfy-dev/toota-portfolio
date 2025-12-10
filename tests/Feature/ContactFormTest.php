<?php

namespace Tests\Feature;

use App\Livewire\ContactForm;
use App\Mail\ContactFormSubmitted;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ContactFormTest extends TestCase
{
    #[Test]
    public function contact_form_sends_email_on_submission()
    {
        Mail::fake();

        $adminEmail = 'admin@example.com';
        config(['mail.contact_email' => $adminEmail]);

        Livewire::test(ContactForm::class)
            ->set('name', 'John Doe')
            ->set('email', 'john@example.com')
            ->set('phone', '1234567890')
            ->set('message', 'This is a test message.')
            ->call('submitForm')
            ->assertHasNoErrors()
            ->assertDispatched('show-notification', [
                'status' => 'success',
                'message' => __('messages.contact.success_message'),
            ]);

        Mail::assertSent(ContactFormSubmitted::class, function ($mail) use ($adminEmail) {
            return $mail->hasTo($adminEmail) &&
                   $mail->data['name'] === 'John Doe' &&
                   $mail->data['email'] === 'john@example.com';
        });
    }

    #[Test]
    public function contact_form_validation_works()
    {
        Livewire::test(ContactForm::class)
            ->set('name', '')
            ->set('email', 'not-an-email')
            ->set('message', '')
            ->call('submitForm')
            ->assertHasErrors(['name', 'email', 'message']);
    }
}
