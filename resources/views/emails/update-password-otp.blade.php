<!DOCTYPE html>
<html>
<head>
    <title>Your Password Update OTP Code</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 40px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
        <h2 style="color: #333333; margin-top: 0;">Password Update OTP</h2>
        <p style="color: #555555; font-size: 16px; line-height: 1.5;">
            You recently requested to update your password for your Tour Raja agent account.
        </p>
        <p style="color: #555555; font-size: 16px; line-height: 1.5;">
            Please enter the following 6-digit OTP code to verify your identity and complete the password update. <strong>This code will expire in 5 minutes.</strong>
        </p>
        
        <div style="background-color: #f8f9fa; border: 1px dashed #ccc; border-radius: 8px; padding: 20px; text-align: center; margin: 30px 0;">
            <span style="font-size: 32px; font-weight: bold; letter-spacing: 5px; color: #ea580c;">{{ $otp }}</span>
        </div>

        <p style="color: #777777; font-size: 14px; line-height: 1.5; margin-top: 30px;">
            If you did not request this password change, please ignore this email or contact support if you have concerns.
        </p>
        
        <p style="color: #999999; font-size: 12px; margin-top: 40px; text-align: center;">
            &copy; {{ date('Y') }} Tour Raja. All rights reserved.
        </p>
    </div>
</body>
</html>
