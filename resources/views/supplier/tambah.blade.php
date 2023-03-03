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
                $("#kodesupplier").val("{{ $rows->kodesupplier }}");
                $("#usersupplier").val("{{ $rows->usersupplier }}");
                $("#namasupplier").val("{{ $rows->namasupplier }}");
                $("#noteleponsupplier").val("{{ $rows->noteleponsupplier }}");
                $("#alamatsupplier").val("{{ $rows->alamatsupplier }}");
                $("#statussupplier").val("{{ intval($rows->statussupplier) }}");

			// DATA BARU
			@elseif($aksi == "acttambah")

				@php
					$mode = "ADD";
				@endphp

                $("#statussupplier").val("1");

			@endif

			// =========== jika ada error
			@if(session('erroract'))
                $("#kodesupplier").val("{{ old('kodesupplier') }}");
                $("#usersupplier").val("{{ old('usersupplier') }}");
                $("#namasupplier").val("{{ old('namasupplier') }}");
                $("#noteleponsupplier").val("{{ old('noteleponsupplier') }}");
                $("#alamatsupplier").val("{{ old('alamatsupplier') }}");
                $("#statussupplier").val("{{ old('statussupplier') }}");
			@endif

			// ========== initialize button
			$("#pesanwarning").addClass("hidden");
			$("#isipesanwarning").html("");

			$("#simpan").click(function() {

                 // jika data kosong

				if($("#namasupplier").val() == "")
				{
					swal("PERINGATAN", "nama [usersupplier] kosong", "warning");
                }
                else if($("#passwordsupplier").val() == "")
				{
					swal("PERINGATAN", "nama [passwordsupplier] kosong", "warning");
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
                    <label for="kodesupplier">Kode supplier</label>
                    <input class="form-control readonly" id="kodesupplier" name="kodesupplier" type="text" placeholder="AUTO">
                </div>

                <div class="form-group">
                    <label for="namasupplier">Nama supplier</label>
                    <input type="text" class="form-control" id="namasupplier" name="namasupplier" placeholder="masukkan data">
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="noteleponsupplier">No Telepon</label>
                        <input type="text" class="form-control" id="noteleponsupplier" name="noteleponsupplier" placeholder="masukkan data">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="alamatsupplier">Alamat Supplier</label>
                        <input type="text" class="form-control" id="alamatsupplier" name="alamatsupplier" placeholder="masukkan data">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="statussupplier">Status Supplier</label>
                        <select id="statussupplier" name="statussupplier" class="form-control">
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
