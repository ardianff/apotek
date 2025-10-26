<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Nota Tutup Kasir</title>

    <?php
    $style = '
    <style>
        * {
            font-family: "consolas", sans-serif;
        }
        p {
            display: block;
            margin: 3px;
            font-size: 10pt;
        }
        table td {
            font-size: 9pt;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }

        @media print {
            @page {
                margin: 0;
                size: 75mm 
    ';
    ?>
    <?php 
    $style .= 
        ! empty($_COOKIE['innerHeight'])
            ? $_COOKIE['innerHeight'] .'mm; }'
            : '}';
    ?>
    <?php
    $style .= '
            html, body {
                width: 70mm;
            }
            .btn-print {
                display: none;
            }
        }
    </style>
    ';
    ?>

    {!! $style !!}
</head>
<body onload="window.print()">
    <button class="btn-print" style="position: absolute; right: 1rem; top: rem;" onclick="window.print()">Print</button>
    <div class="text-center">
        <h3 style="margin-bottom: 5px;">{{ strtoupper($setting->nama_perusahaan) }}</h3>
        <p>{{ strtoupper($setting->alamat) }}</p>
        <p class="text-center">No Telepon : {{ strtoupper($setting->telepon) }}</p>
        <p class="text-center">No Whatsapp : {{ strtoupper($setting->wa) }}</p>
    </div>
    <br>
    <p class="text-center"> * * * * Laporan Tutup Kasir * * * * </p>
    <br>

    <table width="100%" style="border: 0;">
        <tr>
            <td>Shift :</td>
            <td class="text-right">{{ $kasir->shift->nama_shift }}</td>
        </tr>
        <tr>
            <td>Petugas :</td>
            <td class="text-right">{{ $kasir->petugas }}</td>
        </tr>
        <tr>
            <td>Dibuka Oleh :</td>
            <td class="text-right">{{ $kasir->buka->name }}</td>
        </tr>
        <tr>
            <td>Waktu Buka :</td>
            <td class="text-right">{{ $kasir->waktu_buka }}</td>
        </tr>
        <tr>
            <td>Ditutup Oleh :</td>
            <td class="text-right">{{ $kasir->tutup->name }}</td>
        </tr>
        <tr>
            <td>Waktu tutup :</td>
            <td class="text-right">{{ $kasir->waktu_tutup }}</td>
        </tr>
    </table>
   
    <p class="text-center">============================</p>
    <table width="100%" class="table-bordered table-striped" >
        <tr>
            <td>Modal:</td>
            <td class="text-right">{{ format_uang($kasir->saldo_awal) }}</td>
        </tr>
        <tr>
            <td>Total Penjualan Tunai</td>
            <td class="text-right">{{ format_uang($kasir->tunai) }}</td>
        </tr>
        <tr>
            <td>Total Penjualan NonTunai </td>
            <td class="text-right">{{ format_uang($kasir->nontunai) }}</td>
        </tr>
        
        @foreach ($metode as $me)
        <tr>
            <td>{{$me->metode}} :</td>
            <td class="text-right">{{format_uang($me->id)}}</td>
        </tr>
        @endforeach
        
        <tr>
            <td>Total Semua Penjualan</td>
            <td class="text-right">{{ format_uang($kasir->total_penjualan) }}</td>
        </tr>
        <tr>
            <td>Total Pengeluaran:</td>
            <td class="text-right">{{ format_uang($kasir->pengeluaran) }}</td>
        </tr>
        <tr>
            <td>Total Saldo </td>
            <td class="text-right">{{ format_uang($kasir->saldo_akhir) }}</td>
        </tr>
        {{-- <tr>
            <td>Diskon:</td>
            <td class="text-right">{{ format_uang($penjualan->diskon) }}</td>
        </tr> --}}
       
       
    </table>
    
     <p class="text-center">============================</p>
    <br>
     <p class="text-center">Obat Yang Terjual</p>

    <table width="100%" style="border: 1px;" class="table-striped table-bordered">
        
        <thead>
            <tr>
                <td>obat</td>
                <td>harga</td>
                <td>jumlah</td>
                <td>total</td>
            </tr>
        </thead>
        <tbody>
                   @foreach ($penjualan as $item)
                   <tr class="text-center">
                       
                       <td>
                    {{$item['nama_obat']}}
                </td>
                <td>
                    {{format_uang($item['harga_jual'])}}
                </td>
                <td>
                    {{$item['jumlah']}}
                </td>
                <td>
                    {{format_uang($item['total_penjualan'])}}
                </td>
               
            </tr>
            @endforeach
            </tbody> 
            <tr>
                <td></td>
                <td></td>
                <td>Total :</td>
                <td>Rp. {{format_uang($totali)}}</td> 
            </tr>
    </table>
    <p class="text-center">========== TERIMA KASIH ==========</p>
    <p class="text-center">Di print pada {{$waktu}}</p>
   

    <script>
        let body = document.body;
        let html = document.documentElement;
        let height = Math.max(
                body.scrollHeight, body.offsetHeight,
                html.clientHeight, html.scrollHeight, html.offsetHeight
            );

        document.cookie = "innerHeight=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        document.cookie = "innerHeight="+ ((height + 50) * 0.264583);
    </script>
</body>
</html>