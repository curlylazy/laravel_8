@extends('template')

@push('scripts')
    <script type="text/javascript">
		$(document).ready(function() {

			// DATA EDIT
			@if($aksi == "actedit")

				@php
					$mode = "UPDATE";
				@endphp

                $("#kodepelanggan").val("{{ $rows->kodepelanggan }}");
                $("#userpelanggan").val("{{ $rows->userpelanggan }}");
                $("#namapelanggan").val("{{ $rows->namapelanggan }}");
                $("#noteleponpelanggan").val("{{ $rows->noteleponpelanggan }}");
                $("#alamatpelanggan").val("{{ $rows->alamatpelanggan }}");
                $("#statuspelanggan").val("{{ intval($rows->statuspelanggan) }}");

			// DATA BARU
			@elseif($aksi == "acttambah")

				@php
					$mode = "ADD";
				@endphp

                $("#statuspelanggan").val("1");

			@endif

			// =========== jika ada error
			@if(session('erroract'))
                $("#kodepelanggan").val("{{ old('kodepelanggan') }}");
                $("#userpelanggan").val("{{ old('userpelanggan') }}");
                $("#namapelanggan").val("{{ old('namapelanggan') }}");
                $("#noteleponpelanggan").val("{{ old('noteleponpelanggan') }}");
                $("#alamatpelanggan").val("{{ old('alamatpelanggan') }}");
                $("#statuspelanggan").val("{{ old('statuspelanggan') }}");
			@endif

			// ========== initialize button
			$("#pesanwarning").addClass("hidden");
			$("#isipesanwarning").html("");

			$("#simpan").click(function() {

                 // jika data kosong

				if($("#namapelanggan").val() == "")
				{
					swal("PERINGATAN", "nama [userpelanggan] kosong", "warning");
                }
                else if($("#passwordpelanggan").val() == "")
				{
					swal("PERINGATAN", "nama [passwordpelanggan] kosong", "warning");
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
                    <label for="kodepelanggan">Kode Pelanggan</label>
                    <input class="form-control readonly" id="kodepelanggan" name="kodepelanggan" type="text" placeholder="AUTO">
                </div>

                <div class="form-group">
                    <label for="namapelanggan">Nama Pelanggan</label>
                    <input type="text" class="form-control" id="namapelanggan" name="namapelanggan" placeholder="masukkan data">
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="noteleponpelanggan">No Telepon</label>
                        <input type="text" class="form-control" id="noteleponpelanggan" name="noteleponpelanggan" placeholder="masukkan data">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="alamatpelanggan">Alamat Pelanggan</label>
                        <input type="text" class="form-control" id="alamatpelanggan" name="alamatpelanggan" placeholder="masukkan data">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="statuspelanggan">Status Pelanggan</label>
                        <select id="statuspelanggan" name="statuspelanggan" class="form-control">
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
