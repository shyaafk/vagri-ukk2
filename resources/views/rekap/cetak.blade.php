<!DOCTYPE html>
<html>
<head>
    <title>Cetak Laporan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
            margin: 40px;
            color: #000;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .header h2 {
            margin: 0;
        }

        .header p {
            margin: 2px 0;
            font-size: 12px;
        }

        hr {
            border: 1px solid #000;
            margin: 10px 0 20px 0;
        }

        .info {
            margin-bottom: 15px;
        }

        .info span {
            display: inline-block;
            min-width: 120px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background-color: #f2f2f2;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .total-row td {
            font-weight: bold;
        }

        .footer {
            margin-top: 60px;
            text-align: right;
        }

        @media print {
            @page {
                margin: 20mm;
            }
        }
    </style>
</head>
<body>

<div class="header">
    <h2>SISTEM PARKIR PARKIZO</h2>
    <p>Jl. Contoh Alamat No. 123</p>
    <p>Telp: 08xxxxxxxxxx</p>
</div>

<hr>

<div class="header">
    <h3>LAPORAN REKAP {{ strtoupper($tipe ?? 'HARIAN') }}</h3>
</div>

<div class="info">
    <div><span>Tanggal</span>: {{ $tanggal }}</div>
    <div><span>Dicetak Pada</span>: {{ date('d-m-Y H:i:s') }}</div>
    <div><span>Dicetak Oleh</span>: {{ session('nama_lengkap') ?? 'Owner' }}</div>
</div>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Waktu Masuk</th>
            <th>Waktu Keluar</th>
            <th>Plat Nomor</th>
            <th>Durasi</th>
            <th>Biaya (Rp)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $index => $d)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $d->waktu_masuk }}</td>
            <td>{{ $d->waktu_keluar }}</td>
            <td>{{ $d->plat_nomor }}</td>
            <td>{{ $d->durasi_jam }} Jam</td>
            <td class="text-right">
                {{ number_format($d->biaya_total,0,',','.') }}
            </td>
        </tr>
        @endforeach

        <tr class="total-row">
            <td colspan="5" class="text-right">TOTAL PENDAPATAN</td>
            <td class="text-right">
                {{ number_format($total,0,',','.') }}
            </td>
        </tr>
    </tbody>
</table>

<div class="footer">
    Pangkalpinang, {{ date('d F Y') }}<br><br><br><br>
    ___________________________<br>
    Owner
</div>

<script>
window.print();
</script>

</body>
</html>