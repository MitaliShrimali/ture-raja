<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt - Tour Raja</title>
    <style>
        body { font-family: 'Poppins', Arial, sans-serif; background-color: #f5f6f8; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .header { background: #b13c0b; padding: 35px 20px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 26px; font-weight: 800; letter-spacing: -0.5px; }
        .content { padding: 40px 35px; color: #333333; line-height: 1.7; }
        .content h2 { margin-top: 0; color: #1a1a1a; font-size: 20px; font-weight: 700; }
        .receipt-box { background: #FFF5F2; border: 1px solid #fed7aa; border-radius: 12px; padding: 20px; margin: 25px 0; }
        .btn { display: inline-block; background: #b13c0b; color: #ffffff !important; text-decoration: none; padding: 14px 32px; border-radius: 12px; font-weight: 700; margin: 20px 0; text-transform: uppercase; font-size: 13px; letter-spacing: 1px; }
        .footer { background: #fafafa; padding: 20px; text-align: center; font-size: 12px; color: #888888; border-top: 1px solid #eeeeee; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Tour Raja</h1>
        </div>
        <div class="content">
            <h2>Payment Confirmation & Receipt</h2>
            <p>Dear {{ $name }},</p>
            <p>Thank you for your purchase on Tour Raja! Your payment has been successfully processed.</p>

            <div class="receipt-box">
                <table style="width:100%; border-collapse: collapse;">
                    <tr style="border-bottom: 1px dashed #fdba74;">
                        <td style="padding: 8px 0; color: #666; font-size: 13px;">Item / Plan</td>
                        <td style="padding: 8px 0; text-align: right; font-weight: bold; font-size: 13px; color: #1a1a1a;">{{ $itemName }}</td>
                    </tr>
                    <tr style="border-bottom: 1px dashed #fdba74;">
                        <td style="padding: 8px 0; color: #666; font-size: 13px;">Transaction ID</td>
                        <td style="padding: 8px 0; text-align: right; font-weight: bold; font-size: 13px; color: #1a1a1a;">{{ $paymentId }}</td>
                    </tr>
                    @if(!empty($invoiceNumber))
                    <tr style="border-bottom: 1px dashed #fdba74;">
                        <td style="padding: 8px 0; color: #666; font-size: 13px;">Invoice Number</td>
                        <td style="padding: 8px 0; text-align: right; font-weight: bold; font-size: 13px; color: #1a1a1a;">{{ $invoiceNumber }}</td>
                    </tr>
                    @endif
                    <tr style="border-bottom: 1px dashed #fdba74;">
                        <td style="padding: 8px 0; color: #666; font-size: 13px;">Date</td>
                        <td style="padding: 8px 0; text-align: right; font-size: 13px; color: #1a1a1a;">{{ $date }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 12px 0 4px 0; color: #1a1a1a; font-weight: bold; font-size: 14px;">Total Paid</td>
                        <td style="padding: 12px 0 4px 0; text-align: right; color: #b13c0b; font-weight: bold; font-size: 16px;">₹{{ number_format($amount, 2) }}</td>
                    </tr>
                </table>
            </div>

            <div style="text-align: center;">
                <a href="{{ url('/agent/dashboard') }}" class="btn">Go to Agent Dashboard</a>
            </div>

            <p>If you have any questions regarding this invoice or payment, please feel free to contact us at <a href="mailto:info@tourraja.com" style="color: #b13c0b;">info@tourraja.com</a>.</p>

            <p>Best regards,<br><strong>The Tour Raja Team</strong></p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Tour Raja. All rights reserved.
        </div>
    </div>
</body>
</html>
