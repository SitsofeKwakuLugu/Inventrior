<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #0b2040; color: white; padding: 20px; border-radius: 5px 5px 0 0; }
        .content { background-color: #f0f4f8; padding: 20px; border-radius: 0 0 5px 5px; }
        .button { background-color: #0ea5a5; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome to Inventrior</h1>
        </div>
        <div class="content">
            <p>Hi {{ $user->name }},</p>
            
            <p>Please verify your email address to complete your registration and access your Inventrior account.</p>
            
            <a href="{{ $verificationUrl }}" class="button">Verify Email Address</a>
            
            <p>Or copy this link:</p>
            <p style="word-break: break-all; background-color: #fff; padding: 10px; border-left: 3px solid #0ea5a5;">
                {{ $verificationUrl }}
            </p>
            
            <p>This link will expire in 24 hours.</p>
            
            <p>If you did not create this account, no action is needed.</p>
            
            <p>
                Best regards,<br>
                The Inventrior Team
            </p>
        </div>
    </div>
</body>
</html>
