<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Reset Kata Sandi</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f8fafc;
      margin: 0;
      padding: 0;
    }

    .email-wrapper {
      width: 100%;
      padding: 40px 0;
      background-color: #f8fafc;
    }

    .email-content {
      max-width: 500px;
      margin: 0 auto;
      background: #ffffff;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .email-header {
      background-color: #d32f2f;
      color: white;
      padding: 25px;
      text-align: center;
    }

    .email-header h1 {
      margin: 0;
      font-size: 24px;
      font-weight: bold;
    }

    .email-body {
      padding: 30px;
      color: #334155;
      line-height: 1.6;
    }

    .email-body p {
      margin-bottom: 20px;
      font-size: 16px;
    }

    .btn {
      display: inline-block;
      background-color: #d32f2f;
      color: #ffffff;
      text-decoration: none;
      padding: 12px 30px;
      border-radius: 50px;
      font-weight: bold;
      margin-top: 10px;
      margin-bottom: 20px;
      text-align: center;
    }

    .email-footer {
      padding: 20px;
      text-align: center;
      font-size: 13px;
      color: #94a3b8;
      background-color: #f1f5f9;
    }

    .url-text {
      font-size: 12px;
      color: #64748b;
      word-break: break-all;
    }
  </style>
</head>

<body>
  <div class="email-wrapper">
    <div class="email-content">
      <div class="email-header">
        <h1>Dasena</h1>
      </div>

      <div class="email-body">
        <p>Halo <strong>{{ $user->name }}</strong>,</p>
        <p>Kami menerima permintaan untuk mereset kata sandi akun Dasena Anda. Silakan klik tombol di bawah ini
          untuk membuat kata sandi baru:</p>

        <div style="text-align: center;">
          <a href="{{ $url }}" class="btn">Reset Kata Sandi</a>
        </div>

        <p>Tautan ini hanya berlaku selama 5 menit. Jika Anda tidak pernah meminta reset kata sandi, abaikan
          saja email ini.</p>

        <p>Salam hangat,<br>Tim Dasena</p>

        <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 30px 0;">
        <p class="url-text">Jika tombol di atas tidak berfungsi, salin dan tempel URL berikut ke browser
          Anda:<br> {{ $url }}</p>
      </div>

      <div class="email-footer">
        &copy; {{ date('Y') }} Dasena - Lab KSI Politeknik Negeri Jember.
      </div>
    </div>
  </div>
</body>

</html>