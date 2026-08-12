<!DOCTYPE html>
<html>
head>
    <meta charset="utf-8">
    <title>OTP Verification</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h2 style="color: #333; text-align: center;">University Complaints Portal</h2>
        <p style="font-size: 16px; color: #555;">Hello,</p>
        <p style="font-size: 16px; color: #555;">Your One-Time Password (OTP) for login is:</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <span style="font-size: 32px; font-weight: bold; background: #e0e7ff; color: #3730a3; padding: 12px 24px; letter-spacing: 5px; border-radius: 6px;">
                {{ $otp }}
            </span>
        </div>

        <p style="font-size: 14px; color: #777;">This OTP is valid for 10 minutes. Do not share it with anyone.</p>
        <p style="font-size: 14px; color: #777;">Regards,<br><strong>Admin Support Team</strong></p>
    </div>
</body>
</html>