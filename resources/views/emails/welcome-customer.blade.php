<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Tour Raja</title>
    <style>
        body { font-family: 'Poppins', Arial, sans-serif; background-color: #f5f6f8; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .header { background: #b13c0b; padding: 35px 20px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 26px; font-weight: 800; letter-spacing: -0.5px; }
        .content { padding: 40px 35px; color: #333333; line-height: 1.7; }
        .content h2 { margin-top: 0; color: #1a1a1a; font-size: 20px; font-weight: 700; }
        .btn { display: inline-block; background: #b13c0b; color: #ffffff !important; text-decoration: none; padding: 14px 32px; border-radius: 12px; font-weight: 700; margin: 25px 0; text-transform: uppercase; font-size: 13px; letter-spacing: 1px; }
        .footer { background: #fafafa; padding: 20px; text-align: center; font-size: 12px; color: #888888; border-top: 1px solid #eeeeee; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Tour Raja</h1>
        </div>
        <div class="content">
            <h2>Welcome to Tour Raja, {{ $name }}! ✈️</h2>
            <p>Dear {{ $name }},</p>
            <p>We are thrilled to welcome you to <strong>Tour Raja</strong> — your ultimate destination for discovering incredible tour packages and unforgettable travel experiences!</p>
            <p>Your account is ready. Explore top-rated domestic and international packages, save your favorite destinations to your wishlist, and start planning your next journey with verified travel partners.</p>
            <div style="text-align: center;">
                <a href="{{ url('/') }}" class="btn">Explore Tour Packages</a>
            </div>
            <p>If you ever need help, feel free to reach out to us anytime.</p>
            <p>Happy Traveling!<br><strong>The Tour Raja Team</strong></p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Tour Raja. All rights reserved.
        </div>
    </div>
</body>
</html>
