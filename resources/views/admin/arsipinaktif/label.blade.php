<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 0;
        }

        .label-container {
            border: 3px solid #000;
            padding: 20px;
            text-align: center;
            width: 100%;
            height: 100%;
            box-sizing: border-box;
        }

        .instansi {
            font-size: 16px;
            font-weight: bold;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .box-number {
            font-size: 35px;
            font-weight: bold;
            margin: 15px 0;
            background-color: #f0f0f0;
            padding: 5px;
            border: 1px dashed #333;
        }

        .details {
            font-size: 14px;
            text-align: left;
            line-height: 1.6;
            margin-top: 15px;
        }
    </style>
</head>

<body>
    <div class="label-container">
        <div class="instansi">BADAN INFORMASI GEOSPASIAL<br>PUSAT DATA DAN INFORMASI</div>

        <div class="box-number">
            BOKS NO: {{ $arsip->folder_boks ?: '.....' }}
        </div>

        <div class="details">
            <table width="100%">
                <tr>
                    <td width="35%"><b>Asal Unit</b></td>
                    <td width="5%">:</td>
                    <td>{{ $arsip->asal_unit ?: '-' }}</td>
                </tr>
                <tr>
                    <td><b>Kurun Waktu</b></td>
                    <td>:</td>
                    <td>{{ $arsip->kurun_waktu ?: '-' }}</td>
                </tr>
                <tr>
                    <td><b>Klasifikasi</b></td>
                    <td>:</td>
                    <td>{{ $arsip->kode_klasifikasi ?: '-' }}</td>
                </tr>
                <tr>
                    <td><b>Lokasi Ruang</b></td>
                    <td>:</td>
                    <td>{{ $arsip->lokasi_ruangan ?: '-' }} (Rak: {{ $arsip->tempat ?: '-' }})</td>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>
