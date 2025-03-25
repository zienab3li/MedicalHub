<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to MedicalHub</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h1 {
            color: #2c3e50;
        }
        p {
            color: #555;
            font-size: 16px;
        }
        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        {{-- <h1>Welcome, {{ $user->name }}!</h1> --}}
        <p>Thank you for registering at <strong>MedicalHub</strong>. We are excited to have you on board!</p>
        <p>Feel free to explore our services and get the best medical care at your convenience.</p>
        <p class="footer">If you have any questions, contact us at support@medicalhub.com</p>
    </div>
</body>
</html>
