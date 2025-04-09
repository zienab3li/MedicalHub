<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>عيد فطر سعيد</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            text-align: center;
            background-color: #f8f9fa;
            color: #2c3e50;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: #28a745;
            color: white;
            padding: 15px;
            border-radius: 10px 10px 0 0;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 20px;
        }
        .content p {
            font-size: 18px;
            line-height: 1.6;
        }
        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #6c757d;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 15px;
            font-size: 16px;
            color: white;
            background-color: #28a745;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🌙 عيد فطر سعيد 🎉</h1>
        </div>
        <div class="content">
            <p>عزيزي <strong>{{ $user->name }}</strong>,</p>
            <p>نتمنى لك عيد فطر سعيد مليئًا بالسعادة والصحة.</p>
            <p>شكرًا لكونك جزءًا من <strong>MedicalHub</strong>!</p>
            <p>🌙 كل عام وأنت بخير! 🌙</p>
            <a href="{{ url('/') }}" class="btn">زيارة الموقع</a>
        </div>
        <div class="footer">
            <p>© 2025 MedicalHub | جميع الحقوق محفوظة</p>
        </div>
    </div>
</body>
</html>
