<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reset Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .container {
            background-color: #f9fafb;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #0284c7;
            margin: 0;
            padding: 0;
        }
        .content {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            border: 1px solid #e5e7eb;
        }
        .button {
            display: inline-block;
            background-color: #0284c7;
            color: #ffffff;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>PPIC System</h1>
            <p>PT. MTU Indonesia</p>
        </div>
        
        <div class="content">
            <p>Halo <?= $name ?>,</p>
            
            <p>Kami menerima permintaan untuk mereset password akun PPIC System Anda. Jika Anda tidak meminta reset password, Anda dapat mengabaikan email ini.</p>
            
            <p>Untuk mereset password Anda, silakan klik tombol di bawah ini:</p>
            
            <p style="text-align: center;">
                <a href="<?= $resetLink ?>" class="button">Reset Password</a>
            </p>
            
            <p>Link reset password ini akan kadaluarsa dalam 1 jam.</p>
            
            <p>Jika tombol di atas tidak berfungsi, Anda dapat menyalin dan menempelkan URL berikut ke browser Anda:</p>
            <p style="word-break: break-all;"><?= $resetLink ?></p>
        </div>
        
        <div class="footer">
            <p>Email ini dikirim secara otomatis. Mohon tidak membalas email ini.</p>
            <p>&copy; <?= date('Y') ?> PT. MTU Indonesia. All rights reserved.</p>
        </div>
    </div>
</body>
</html> 