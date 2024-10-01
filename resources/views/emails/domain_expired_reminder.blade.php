<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengingat Expired Domain</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #ffc107;
            color: #ffffff;
            text-align: center;
            padding: 30px 20px;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }
        .content {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 4px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            margin-top: 10px;
        }
        .content p {
            font-size: 16px;
            line-height: 1.5;
            color: #333;
        }
        .btn {
            background-color: #ffc107;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            color: #888;
            font-size: 12px;
            padding: 10px 20px;
            margin-top: 20px;
            border-top: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Reminder : Domain "{{ $domain->nama_domain }}" Akan Segera Expired</h2>
        </div>
        <div class="content">
            <p>Dear Admin,</p>
            <p>Domain <strong>{{ $domain->nama_domain }}</strong> akan segera expired pada tanggal <strong>{{ $domain->tgl_expired->format('d M, Y') }}</strong>.</p>
            <p>Dimohon untuk segera memperpanjang domain <strong>{{ $domain->nama_domain }}</strong>.</p>
            <a href="#" class="btn">Perbarui Sekarang</a>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} App Reminder. All rights reserved.
        </div>
    </div>
</body>
</html>