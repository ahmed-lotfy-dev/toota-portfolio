<?php

namespace App\Livewire\Landing;

use Livewire\Component;
use App\Models\Subscriber;
use Illuminate\Validation\ValidationException;

class NewsletterSubscribe extends Component
{
    public $email;

    protected $rules = [
        'email' => 'required|email|unique:subscribers,email',
    ];

    public function messages()
    {
        return [
            'email.required' => __('footer.newsletter.validation.email_required'),
            'email.email' => __('footer.newsletter.validation.email_email'),
            'email.unique' => __('footer.newsletter.validation.email_unique'),
        ];
    }

    public function subscribe()
    {
        $this->validate();

        Subscriber::create(['email' => $this->email]);

        session()->flash('message', __('footer.newsletter.success'));

        $this->reset('email');
    }

    public function render()
    {
        return view('livewire.landing.newsletter-subscribe');
    }
}
