<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
</head>
<body>
    <p>Hello,</p>
    <p>Click the button below to reset your password:</p>
    <a href="{{ $resetLink }}" style="display:inline-block; padding:10px 20px; background-color:#007bff; color:white; text-decoration:none; border-radius:5px;">
        Reset Password
    </a>
    <p>If you did not request this, please ignore this email.</p>
</body>
</html>
