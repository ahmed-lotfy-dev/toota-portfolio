<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormSubmitted;

class ContactForm extends Component
{
    public $name;
    public $email;
    public $phone;
    public $message;

    protected $rules = [
        'name' => 'required|min:3',
        'email' => 'required|email',
        'phone' => 'nullable|string|max:20',
        'message' => 'required|min:10',
    ];

    public function messages()
    {
        return [
            'name.required' => __('contact.validation.name_required'),
            'name.min' => __('contact.validation.name_min'),
            'email.required' => __('contact.validation.email_required'),
            'email.email' => __('contact.validation.email_email'),
            'message.required' => __('contact.validation.message_required'),
            'message.min' => __('contact.validation.message_min'),
            'phone.max' => __('contact.validation.phone_max'),
        ];
    }

    public function submitForm()
    {
        $validatedData = $this->validate();

        try {
            Mail::to(config('mail.contact_email', config('mail.from.address')))->send(new ContactFormSubmitted($validatedData));

            $this->dispatch('show-notification', [
                'status' => 'success',
                'message' => __('messages.contact.success_message'),
            ]);

            Log::info('Contact Form Submission:', [
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'message' => $this->message,
            ]);
            
            $this->reset(['name', 'email', 'phone', 'message']);

        } catch (\Exception $e) {
            Log::error('Contact Form Email Error: ' . $e->getMessage());

            $this->dispatch('show-notification', [
                'status' => 'danger',
                'message' => __('messages.contact.error_message'),
            ]);
        }
    }

    public function render()
    {
        return view('livewire.contact-form.index');
    }
}
