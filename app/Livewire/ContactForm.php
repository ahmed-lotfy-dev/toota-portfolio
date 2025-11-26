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

    public function submitForm()
    {
        $validatedData = $this->validate();

        // Send email
        try {
            Mail::to(config('mail.from.address'))->send(new ContactFormSubmitted($validatedData));
        } catch (\Exception $e) {
            Log::error('Contact Form Email Error: ' . $e->getMessage());
            // Optionally flash an error message to the user, but for now we'll just log it
            // and let the success message show (or handle it differently based on requirements)
        }

        Log::info('Contact Form Submission:', [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'message' => $this->message,
        ]);

        Session::flash('message', __('messages.contact.success_message'));

        $this->reset(['name', 'email', 'phone', 'message']);
    }

    public function render()
    {
        return view('livewire.contact-form.index');
    }
}
