@extends('template')

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            $("#katakunci").val("{{ $katakunci }}");

        	$("#cari").click(function() {
                $("#form1").attr("action", "{{ url('laporan/pelanggan') }}");
                $("#form1").submit();
            });

            $("#cetak").click(function() {
                $("#form1").attr("action", "{{ url('laporan/cetak/pelanggan') }}");
                $("#form1").submit();
            });
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
            <div class="card-body" style="padding-bottom: 0px !important;">
                <form id="form1" enctype="multipart/form-data" method="post" action='{{ url("$prefix/pelanggan") }}' id="form1" >

                @csrf

                <div class="form-row">
                    <div class="form-group col-4">
                        <label for="katakunci">Katakunci</label>
                        <input class="form-control" id="katakunci" name="katakunci" type="text" placeholder="masukkan kodepelanggan, atau namapelanggan">
                    </div>
                </div>

                <div class="form-group">
                    <button class="btn btn-primary" type="button" id="cari"><i class="fa fa-search"></i> CARI</button>
                    <button class="btn btn-warning" type="button" id="cetak"><i class="fa fa-print"></i> CETAK</button>
                </div>

                </form>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <table class="table table-responsive-sm table-sm" id="myTable">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama Pelanggan</th>
                            <th>Telepon</th>
                            <th>Alamat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rows as $row)
                            <tr>
                                <td>{{ $row->kodepelanggan }}</td>
                                <td>{{ $row->namapelanggan }}</td>
                                <td>{{ $row->noteleponpelanggan }}</td>
                                <td>{{ $row->alamatpelanggan }}</td>
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
