@extends('template')

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            $("#katakunci").val("{{ $katakunci }}");
            $("#tglorder_dari").val("{{ $tglorder_dari }}");
            $("#tglorder_sampai").val("{{ $tglorder_sampai }}");

        	$("#cari").click(function() {
                $("#form1").attr("action", "{{ url('laporan/itemmasuk') }}");
                $("#form1").submit();
            });

            $("#cetak").click(function() {
                $("#form1").attr("action", "{{ url('laporan/cetak/itemmasuk') }}");
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
                <form id="form1" enctype="multipart/form-data" method="post" id="form1" >

                @csrf

                <div class="form-row">
                    <div class="form-group col-4">
                        <label for="katakunci">Katakunci</label>
                        <input class="form-control" id="katakunci" name="katakunci" type="text" placeholder="masukkan kode item masuk..">
                    </div>
                    <div class="form-group col-4">
                        <label>Tanggal Order</label>
                        <input type="text" class="form-control datepicker" id="tglorder_dari" name="tglorder_dari" placeholder="masukkan tanggal order">
                    </div>

                    <div class="form-group col-4">
                        <label>s/d</label>
                        <input type="text" class="form-control datepicker" id="tglorder_sampai" name="tglorder_sampai" placeholder="masukkan tanggal order">
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
                            <th>Nama Supplier</th>
                            <th>Tanggal Item Masuk</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rows as $row)
                            <tr>
                                <td>{{ $row->kodeitemmasuk }}</td>
                                <td>{{ $row->namasupplier }}</td>
                                <td>{{ date('d F Y', strtotime($row->tanggalitemmasuk)) }}</td>
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
