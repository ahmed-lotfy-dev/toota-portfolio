<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #f8f9fa; padding: 20px; text-align: center; border-bottom: 1px solid #eee; }
        .content { padding: 20px; }
        .footer { margin-top: 20px; font-size: 0.9em; color: #777; text-align: center; }
        .label { font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>New Contact Form Submission</h2>
        </div>
        <div class="content">
            <p>You have received a new message from the contact form.</p>
            
            <p><span class="label">Name:</span> {{ $data['name'] }}</p>
            <p><span class="label">Email:</span> {{ $data['email'] }}</p>
            <p><span class="label">Phone:</span> {{ $data['phone'] ?? 'N/A' }}</p>
            
            <p><span class="label">Message:</span></p>
            <div style="background: #f9f9f9; padding: 15px; border-radius: 5px;">
                {{ $data['message'] }}
            </div>
        </div>
        <div class="footer">
            <p>Sent from {{ config('app.name') }}</p>
        </div>
    </div>
</body>
</html>
