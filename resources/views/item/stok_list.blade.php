@extends('template')

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
        	$('#myTable').DataTable({"ordering": false});
        });
    </script>
@endpush

@section('breadcumb')
    <ol class="breadcrumb border-0 m-0">
        {!! $breadcrumb !!}
    </ol>
@endsection

@section('content')

<!-- cek apakah informasi -->
@if (session('pesaninfo'))
	<div class="row">
		<div class="col-12 col-md-12">
		    {!! session('pesaninfo') !!}
		</div>
	</div>
@endif

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header" style="text-transform: uppercase; letter-spacing: 3px;">{{ $pagename }}</div>
            <div class="card-body">
                <table class="table table-responsive-sm table-sm" id="myTable">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Kategori</th>
                            <th>Satuan</th>
                            <th>Nama Item</th>
                            <th>Stok</th>
                            <th>Stok Minimum</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rows as $row)

                            @php
                                if($row->stok > $row->stokminimum)
                                {
                                    $color = 'bg-success';
                                    $ket = 'Stok Aman';
                                }
                                elseif($row->stok == 0)
                                {
                                    $color = 'bg-danger';
                                    $ket = 'Stok Habis';
                                }
                                else
                                {
                                    $color = 'bg-warning';
                                    $ket = 'Stok Hampir Habis';
                                }
                            @endphp

                            <tr class='{{ $color }}'>
                                <td>{{ $row->kodeitem }}</td>
                                <td>{{ $row->namakategori }}</td>
                                <td>{{ $row->satuan }}</td>
                                <td>{{ $row->namaitem }}</td>
                                <td>{{ $row->stok }}</td>
                                <td>{{ $row->stokminimum }}</td>
                                <td>{{ $ket }}</td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <a class="btn btn-primary" href='{{ url("dashboard") }}'><i class="fa fa-backward"></i> KEMBALI</a>
            </div>
        </div>
    </div>
</div>

@endsection
