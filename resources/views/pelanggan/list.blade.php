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
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>No Telepon</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>

                        @php
                            $no = 1;
                        @endphp

                        @foreach ($rows as $row)

                            <tr>
                                <td>{{ $no }}</td>
                                <td>{{ $row->kodepelanggan }}</td>
                                <td>{{ $row->namapelanggan }}</td>
                                <td>{{ $row->alamatpelanggan }}</td>
                                <td>{{ $row->noteleponpelanggan }}</td>
                                <td>{{ App\Lib\Csql::cekStatusAktif($row->statuspelanggan) }}</td>
                                <td>
                                    <a class="btn btn-info btn-sm" href='{{ url("$prefix/edit/$row->kodepelanggan") }}'><i class="fa fa-edit"></i></a>
                                    {{-- <a class="btn btn-danger btn-sm" onclick="return confirm('Hapus data {{ $row->kodepelanggan }} ? ')" href='{{ url("$prefix/acthapus/$row->kodepelanggan") }}'><i class="fa fa-trash"></i></a> --}}
                                </td>
                            </tr>

                            @php
                                $no++;
                            @endphp

                        @endforeach

                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <a class="btn btn-primary" href='{{ url("dashboard") }}'><i class="fa fa-backward"></i> KEMBALI</a>
				<a class="btn btn-info" href='{{ url("$prefix/tambah") }}'><i class="fa fa-plus"></i> TAMBAH</a>
            </div>
        </div>
    </div>
</div>

@endsection
