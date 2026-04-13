<!DOCTYPE html>
<html>
<head>
    <title>Struk Parkir</title>
    <style>
        body {
            font-family: monospace;
            width: 300px;
            margin: auto;
        }
        hr { border:1px dashed #000; }
    </style>
</head>
<body>

<center>
    <h4>E-PARKIR</h4>
    <small>Struk Parkir</small>
</center>

<hr>

Plat      : {{ $data->plat_nomor }} <br>
Masuk     : {{ $data->waktu_masuk }} <br>
Keluar    : {{ $data->waktu_keluar }} <br>
Durasi    : {{ $data->durasi_jam }} Jam <br>
Biaya     : Rp {{ number_format($data->biaya_total,0,',','.') }}

<hr>

<center>Terima Kasih</center>

<script>
window.print();
</script>

</body>
</html>