<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Undangan</title>
    <style>
        @page {
            margin: 20mm 25mm 20mm 25mm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.6;
            color: #333;
        }
        
        /* Header Kop Surat */
        .header {
            border-bottom: 3px solid #1a365d;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }
        .header-content {
            display: table;
            width: 100%;
        }
        .logo-section {
            display: table-cell;
            width: 80px;
            vertical-align: middle;
        }
        .logo {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 50%;
            text-align: center;
            line-height: 70px;
            color: white;
            font-size: 24px;
            font-weight: bold;
        }
        .institution-section {
            display: table-cell;
            vertical-align: middle;
            padding-left: 15px;
        }
        .institution-name {
            font-size: 18pt;
            font-weight: bold;
            color: #1a365d;
            margin: 0;
        }
        .faculty-name {
            font-size: 14pt;
            font-weight: bold;
            color: #2d3748;
            margin: 3px 0;
        }
        .address {
            font-size: 10pt;
            color: #4a5568;
            margin: 3px 0;
        }
        .contact {
            font-size: 9pt;
            color: #718096;
        }
        
        /* Nomor Surat */
        .letter-info {
            margin-bottom: 25px;
        }
        .letter-info table {
            width: auto;
        }
        .letter-info td {
            padding: 2px 10px 2px 0;
            vertical-align: top;
        }
        .letter-info td:first-child {
            width: 80px;
        }
        
        /* Tanggal */
        .date-section {
            text-align: right;
            margin-bottom: 20px;
        }
        
        /* Penerima */
        .recipient {
            margin-bottom: 25px;
        }
        
        /* Isi Surat */
        .content {
            text-align: justify;
            margin-bottom: 20px;
        }
        .content p {
            margin: 10px 0;
            text-indent: 40px;
        }
        .content p:first-child {
            text-indent: 0;
        }
        
        /* Detail Acara */
        .event-details {
            margin: 20px 0 20px 40px;
        }
        .event-details table td {
            padding: 3px 10px 3px 0;
            vertical-align: top;
        }
        .event-details table td:first-child {
            width: 80px;
        }
        
        /* Penutup */
        .closing {
            margin-top: 30px;
        }
        
        /* Tanda Tangan */
        .signature {
            margin-top: 30px;
            width: 250px;
            float: right;
            text-align: center;
        }
        .signature-space {
            height: 70px;
        }
        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }
        .signature-nip {
            font-size: 10pt;
        }
        
        /* Tembusan */
        .tembusan {
            clear: both;
            margin-top: 120px;
            font-size: 10pt;
        }
        .tembusan ul {
            margin: 5px 0;
            padding-left: 20px;
        }
    </style>
</head>
<body>
    <!-- Header Kop Surat -->
    <div class="header">
        <div class="header-content">
            <div class="logo-section">
                <div class="logo">FT</div>
            </div>
            <div class="institution-section">
                <p class="institution-name">UNIVERSITAS AIRLANGGA</p>
                <p class="faculty-name">FAKULTAS TEKNOLOGI</p>
                <p class="address">Jl. Pendidikan No. 123, Jakarta 12345</p>
                <p class="contact">Telp: (021) 1234567 | Email: fakultas.teknologi@utn.ac.id | Website: www.ft.utn.ac.id</p>
            </div>
        </div>
    </div>

    <!-- Tanggal -->
    <div class="date-section">
        Jakarta, {{ now()->format('d F Y') }}
    </div>

    <!-- Info Surat -->
    <div class="letter-info">
        <table>
            <tr>
                <td>Nomor</td>
                <td>:</td>
                <td>{{ $nomor_surat }}</td>
            </tr>
            <tr>
                <td>Lampiran</td>
                <td>:</td>
                <td>-</td>
            </tr>
            <tr>
                <td>Perihal</td>
                <td>:</td>
                <td><strong>{{ $perihal }}</strong></td>
            </tr>
        </table>
    </div>

    <!-- Penerima -->
    <div class="recipient">
        <p>Kepada {{ $nama_penerima }},<br>
        di Tempat</p>
    </div>

    <!-- Isi Surat -->
    <div class="content">
        <p>Dengan hormat,</p>
        
        <p>Sehubungan dengan pelaksanaan kegiatan akademik di Fakultas Teknologi Universitas Teknologi Nusantara, 
        dengan ini kami mengundang Bapak/Ibu untuk hadir dalam acara yang akan diselenggarakan pada:</p>

        <div class="event-details">
            <table>
                <tr>
                    <td>Hari/Tanggal</td>
                    <td>:</td>
                    <td>{{ $tanggal_acara }}</td>
                </tr>
                <tr>
                    <td>Waktu</td>
                    <td>:</td>
                    <td>{{ $waktu }} - selesai</td>
                </tr>
                <tr>
                    <td>Tempat</td>
                    <td>:</td>
                    <td>{{ $tempat }}</td>
                </tr>
                <tr>
                    <td>Acara</td>
                    <td>:</td>
                    <td>{{ $perihal }}</td>
                </tr>
            </table>
        </div>

        <p>Mengingat pentingnya acara tersebut, kami berharap Bapak/Ibu dapat hadir tepat waktu. 
        Kehadiran Bapak/Ibu sangat kami harapkan demi kelancaran acara ini.</p>
    </div>

    <!-- Penutup -->
    <div class="closing">
        <p>Demikian undangan ini kami sampaikan. Atas perhatian dan kehadiran Bapak/Ibu, kami ucapkan terima kasih.</p>
    </div>

    <!-- Tanda Tangan -->
    <div class="signature">
        <p>Hormat kami,<br>Dekan Fakultas Teknologi</p>
        <div class="signature-space"></div>
        <p class="signature-name">Prof. Dr. Budi Santoso, M.T.</p>
        <p class="signature-nip">NIP. 197001011995011001</p>
    </div>

    <!-- Tembusan -->
    <div class="tembusan">
        <p><strong>Tembusan:</strong></p>
        <ul>
            <li>Rektor Universitas Teknologi Nusantara</li>
            <li>Arsip</li>
        </ul>
    </div>
</body>
</html>
