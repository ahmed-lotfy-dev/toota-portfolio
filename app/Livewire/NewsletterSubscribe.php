<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Subscriber;
use Illuminate\Validation\ValidationException;

class NewsletterSubscribe extends Component
{
    public $email;

    protected $rules = [
        'email' => 'required|email|unique:subscribers,email',
    ];

    protected $messages = [
        'email.unique' => 'You are already subscribed.',
    ];

    public function subscribe()
    {
        $this->validate();

        Subscriber::create(['email' => $this->email]);

        session()->flash('message', 'Thank you for subscribing!');

        $this->reset('email');
    }

    public function render()
    {
        return view('livewire.newsletter-subscribe');
    }
}
