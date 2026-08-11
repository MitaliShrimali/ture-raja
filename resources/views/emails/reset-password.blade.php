<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        body { font-family: 'Poppins', Arial, sans-serif; background-color: #f5f6f8; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .header { background: #E8460A; padding: 30px 20px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 24px; font-weight: 700; }
        .content { padding: 40px 30px; color: #333333; line-height: 1.6; }
        .content h2 { margin-top: 0; color: #1a1a1a; font-size: 20px; }
        .btn { display: inline-block; background: #E8460A; color: #ffffff; text-decoration: none; padding: 14px 28px; border-radius: 8px; font-weight: 600; margin: 20px 0; }
        .footer { background: #f9f9f9; padding: 20px; text-align: center; font-size: 12px; color: #888888; border-top: 1px solid #eeeeee; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Tour Raja</h1>
        </div>
        <div class="content">
            <h2>Reset Your Password</h2>
            <p>Hello,</p>
            <p>We received a request to reset your password for your Tour Raja account. You can do this by clicking the button below:</p>
            <div style="text-align: center;">
                <a href="{{ $resetUrl }}" class="btn">Reset Password</a>
            </div>
            <p>If you did not request a password reset, please ignore this email or contact support if you have questions.</p>
            <p>Thanks,<br>The Tour Raja Team</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Tour Raja Private Limited, India. All rights reserved.
        </div>
    </div>
</body>
</html>
