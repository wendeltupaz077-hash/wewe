<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Password Reset - SmartBlood</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f6f8; margin:0; padding:0;">
  <table align="center" width="100%" cellpadding="0" cellspacing="0">
    <tr>
      <td align="center">
        <table width="500" style="background:#ffffff; margin-top:40px; border-radius:10px; overflow:hidden;">
          <tr>
            <td align="center" style="background:#b30000; padding:20px;">
              <img src="{{ $logoUrl ?? '' }}" alt="SmartBlood Logo" width="120" style="display:block;">
              <h2 style="color:#ffffff; margin:10px 0 0 0;">SmartBlood</h2>
            </td>
          </tr>
          <tr>
            <td style="padding:30px; color:#333;">
              <h3>Hello, {{ $userName ?? 'User' }}!</h3>
              <p>You (or someone using your email) requested a password reset for your SmartBlood account.</p>
              <p>Click the button below to reset your password. This link will expire shortly.</p>

              <div style="text-align:center; margin:30px 0;">
                <a href="{{ $resetLink ?? url('/') }}" 
                   style="background:#b30000; color:#ffffff; padding:12px 25px; text-decoration:none; border-radius:5px; display:inline-block;">
                   Reset Password
                </a>
              </div>

              <p>If you did not request a password reset, you can safely ignore this email.</p>

              <p style="margin-top:30px;">Regards,<br><strong>SmartBlood Team</strong></p>
            </td>
          </tr>
          <tr>
            <td align="center" style="background:#f1f1f1; padding:15px; font-size:12px; color:#777;">
              © {{ date('Y') }} SmartBlood System. All rights reserved.
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
