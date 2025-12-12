<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #0b2040; color: white; padding: 20px; border-radius: 5px 5px 0 0; }
        .header h1 { margin: 0; font-size: 24px; }
        .content { background-color: #f0f4f8; padding: 30px; border-radius: 0 0 5px 5px; line-height: 1.6; }
        .button { background-color: #0ea5a5; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block; margin: 20px 0; }
        .footer { color: #666; font-size: 12px; margin-top: 20px; border-top: 1px solid #ddd; padding-top: 10px; }
        .code { background-color: #fff; padding: 15px; border-left: 3px solid #0ea5a5; margin: 20px 0; word-break: break-all; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome to Inventrior</h1>
        </div>
        <div class="content">
            <p>Hello {{ $company->name }},</p>
            
            <p>Thank you for registering with <strong>Inventrior</strong>! We're excited to help you manage your company's inventory efficiently.</p>
            
            <p>To complete your registration and verify your company, please click the button below:</p>
            
            <a href="{{ $verificationUrl }}" class="button">Verify Your Company</a>
            
            <p>Or copy and paste this link in your browser:</p>
            <div class="code">
                {{ $verificationUrl }}
            </div>
            
            <p><strong>What happens next?</strong></p>
            <ol>
                <li>Click the verification link above</li>
                <li>You'll be taken to register your company administrator</li>
                <li>The admin will receive an email to verify their account</li>
                <li>Once verified, your company can start using Inventrior</li>
            </ol>
            
            <p style="color: #666; font-size: 12px;">This link will expire in 72 hours. If it expires, you can request a new verification link during admin registration.</p>
            
            <p>If you did not register this company, please ignore this email.</p>
            
            <div class="footer">
                <p>
                    Best regards,<br>
                    <strong>The Inventrior Team</strong><br>
                    <em>Enterprise Inventory Management System</em>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
