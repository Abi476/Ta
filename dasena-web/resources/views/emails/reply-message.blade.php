<!DOCTYPE html>
<html>

<head>
  <title>Balasan dari Admin Dasena</title>
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
  <p>Halo <strong>{{ $originalMessage->name }}</strong>,</p>

  <p>Terima kasih telah memberikan pesan / masukan kepada kami. Berikut adalah tanggapan dari tim Admin Dasena:</p>

  <div style="padding: 15px; background-color: #f8f9fa; border-left: 4px solid #0d6efd; margin-bottom: 20px;">
    {!! nl2br(e($replyText)) !!}
  </div>

  <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">

  <p style="font-size: 12px; color: #777;">
    <strong>Pesan Anda sebelumnya:</strong><br>
    <em>Subjek: {{ $originalMessage->subject }}</em><br>
    <span style="white-space: pre-line;">{{ $originalMessage->message }}</span>
  </p>

  <br>
  <p>Salam Hormat,<br><strong>Admin Dasena</strong></p>
</body>

</html>