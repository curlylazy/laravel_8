@extends('template')

@push('scripts')
    <script type="text/javascript">

        function onlyNumberKey(evt) {
            var ASCIICode = (evt.which) ? evt.which : evt.keyCode
            if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
                return false;
            return true;
        }

		$(document).ready(function() {

            $("#gambarview_item").attr("src", "{{ url('gambar/noimage.jpg') }}");

			// DATA EDIT
			@if($aksi == "actedit")

				@php
					$mode = "UPDATE";
					$keterangan = $rows->keterangan;
				@endphp

                @if($rows->gambar  != "")
                    $("#gambarview_item").attr("src", '{{ url("gambar/$rows->gambar") }}');
                @endif

                $("#kodeitem").val("{{ $rows->kodeitem }}");
                $("#kodekategori").val("{{ $rows->kodekategori }}");
                $("#satuan").val("{{ $rows->satuan }}");
                $("#namaitem").val("{{ $rows->namaitem }}");
                $("#stok").val("{{ $rows->stok }}");
                $("#stokminimum").val("{{ $rows->stokminimum }}");
                $("#statusitem").val("{{ intval($rows->statusitem) }}");

			// DATA BARU
			@elseif($aksi == "acttambah")

				@php
					$mode = "ADD";
                    $keterangan = "";
				@endphp

                $("#stok").val("0");
                $("#stokminimum").val("0");
                $("#statusitem").val("1");

			@endif

			// =========== jika ada error
			@if(session('erroract'))

                $("#kodeitem").val("{{ old('kodeitem') }}");
                $("#kodekategori").val("{{ old('kodekategori') }}");
                $("#satuan").val("{{ old('satuan') }}");
                $("#namaitem").val("{{ old('namaitem') }}");
                $("#stok").val("{{ old('stok') }}");
                $("#stokminimum").val("{{ old('stokminimum') }}");
                $("#keterangan").val("{{ old('keterangan') }}");
                $("#statusitem").val("{{ old('statusitem') }}");

			@endif

			// ========== initialize button
			$("#pesanwarning").addClass("hidden");
			$("#isipesanwarning").html("");

			$("#simpan").click(function() {

                 // jika data kosong
				var satuan = $("#satuan").val();
				var kodekategori = $("#kodekategori").val();
                var namaitem = $("#namaitem").val();

				if(satuan == "")
                {
                    swal("PERINGATAN", "nama [satuan] kosong", "warning");
                }
                else if(kodekategori == "")
				{
					swal("PERINGATAN", "nama [kodekategori] kosong", "warning");
                }
                else if(namaitem == "")
				{
					swal("PERINGATAN", "nama [namaitem] kosong", "warning");
                }
				else
				{
					$("#form1").submit();
				}
			});

            $('.decnumber').autoNumeric('init', {decimalPlacesOverride: '0'});
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
		<div class="col-md-12">
		    {!! session('pesaninfo') !!}
		</div>
	</div>
@endif


<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header" style="text-transform: uppercase; letter-spacing: 3px;">{{ $pagename }}</div>
            <div class="card-body">

                <form id="form1" enctype="multipart/form-data" method="post" action='{{ url("$prefix/$aksi") }}' id="form1" >

                @csrf

                <div class="form-group">
                    <label for="kodeitem">Kode Item</label>
                    <input class="form-control readonly" id="kodeitem" name="kodeitem" type="text" placeholder="AUTO">
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="satuan">Satuan</label>
                        <select id="satuan" name="satuan" class="form-control">
                            {!! App\Lib\Csql::DropDownSatuan() !!}
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="kodekategori">Kategori</label>
                        <select id="kodekategori" name="kodekategori" class="form-control">
                            {!! App\Lib\Csql::DropDownStatus("kodekategori", "namakategori", "tbl_kategori", "statuskategori") !!}
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="namaitem">Nama Item</label>
                        <input class="form-control" id="namaitem" name="namaitem" type="text" placeholder="masukkan data">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="stok">Stok</label>
                        <input class="form-control" id="stok" name="stok" type="text" placeholder="masukkan data" onkeypress="return onlyNumberKey(event)">
                    </div>
                     <div class="form-group col-md-6">
                        <label for="stokminimum">Stok Minimum</label>
                        <input class="form-control decnumber" id="stokminimum" name="stokminimum" type="text" placeholder="masukkan data" onkeypress="return onlyNumberKey(event)">
                    </div>
                </div>

                <div class="form-group">
                    <label for="keterangan">Keterangan</label>
                    <textarea class="form-control" id="keterangan" name="keterangan" rows="10">{{ $keterangan }}</textarea>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-3">
                        <img id="gambarview_item" style="width: 100%; height: 200px; object-fit: cover;" />
                        <input type="file" id="gambar" name="gambar" />
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="statusitem">Status Item</label>
                        <select id="statusitem" name="statusitem" class="form-control">
                            {!! App\Lib\Csql::DropDownStatusAktif() !!}
                        </select>
                    </div>
                </div>

                </form>

            </div>
            <div class="card-footer">
                <a class="btn btn-primary" href='{{ url("$prefix/list") }}'><i class="fa fa-backward"></i> KEMBALI</a>
                <button type="button" id="simpan" class="btn btn-info"><i class="fa fa-save"></i> SIMPAN</button>
            </div>
        </div>
    </div>
</div>

@endsection
