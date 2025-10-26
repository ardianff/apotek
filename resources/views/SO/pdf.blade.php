<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan SO {{$cabang->nama_cabang}}</title>

    <link rel="stylesheet" href="{{ asset('/AdminLTE-2/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <style type="text/css">
		table tr td,
		table tr th{
			font-size: 9pt;
		}
	@media print {
   .btn-print {
                display: none;
            }
  }
        .text-center {
            text-align: center;
        }
        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
        }
        .table th,
        .table td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.05);
        }
    </style>
		
</head>
<body onload="window.print()">
    <h3 class="text-center">Laporan SO {{$cabang->nama_cabang}}</h3>
   
    <button class="btn-print" style="position: absolute; right: 1px; top: 1px;" onclick="window.print()">Print</button>

    <table class="table table-striped">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Bulan</th>
                <th  width="5%">Kode Obat</th>
                <th  width="35%">Nama Obat</th>
                <th >ED</th>
                <th >Batch</th>
                <th>Stok Awal</th>
                <th>Stok Setelah SO</th>
                <th>Selisih</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $row)
                <tr>
                    <td>{{$loop->iteration}}</td>
                   <td>{{$row->bulan}}</td>
                   <td>{{$row->produk->kode_obat??0}}</td>
                   <td>{{$row->produk->nama_obat??0}}</td>
                   <td>{{$row->obat->ed ?? ''}}</td>
                   <td>{{$row->obat->batch ?? ''}}</td>
                   <td>{{$row->stok_awal}} </td>
                   <td>{{$row->stokSO}}</td>
                   <td>{{$row->selisih}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>