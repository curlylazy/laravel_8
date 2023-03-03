@extends('template')

@push('scripts')
    <script type="text/javascript">
		$(document).ready(function() {

			// DATA EDIT
			@if($aksi == "actedit")

				@php
					$mode = "UPDATE";
				@endphp

				// $("#useradmin").addClass("disable");
                $("#kodekategori").val("{{ $rows->kodekategori }}");
                $("#namakategori").val("{{ $rows->namakategori }}");
                $("#statuskategori").val("{{ intval($rows->statuskategori) }}");

			// DATA BARU
			@elseif($aksi == "acttambah")

				@php
					$mode = "ADD";
				@endphp

				$("#statuskategori").val("1");
			@endif

			// =========== jika ada error
			@if(session('pesaninfo'))
				$("#kodekategori").val("{{ old('kodekategori') }}");
                $("#namakategori").val("{{ old('namakategori') }}");
                $("#statuskategori").val("{{ old('statuskategori') }}");
			@endif

			// ========== initialize button
			$("#pesanwarning").addClass("hidden");
			$("#isipesanwarning").html("");

			$("#simpan").click(function() {

                 // jika data kosong
				var namakategori = $("#namakategori").val();

				if(namakategori == "")
				{
					swal("PERINGATAN", "nama kategori masih kosong", "warning");
				}
				else
				{
					$("#form1").submit();
				}
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
                    <label for="kodekategori">Kode Kategori</label>
                    <input class="form-control readonly" id="kodekategori" name="kodekategori" type="text" placeholder="AUTO">
                </div>

                <div class="form-group">
                    <label for="kodekategori">Nama Kategori</label>
                    <input class="form-control" id="namakategori" name="namakategori" type="text" placeholder="masukkan data..">
                </div>

				<div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="statuskategori">Status Kategori</label>
                        <select id="statuskategori" name="statuskategori" class="form-control">
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
