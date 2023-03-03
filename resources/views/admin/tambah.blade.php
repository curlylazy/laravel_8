@extends('template')

@push('scripts')
    <script type="text/javascript">

        var isViewPass = false;

        function toogle_password()
        {
            isViewPass = !isViewPass;

            if(isViewPass)
            {
                $('#password').attr('type','text');
                $('#iconpass').html('<i class="fa fa-eye"></i>');
            }
            else
            {
                $('#password').attr('type','password');
                $('#iconpass').html('<i class="fa fa-eye-slash"></i>');
            }

        }

		$(document).ready(function() {

			// DATA EDIT
			@if($aksi == "actedit")

				@php
					$mode = "UPDATE";
				@endphp

                $("#kodeuser").val("{{ $rows->kodeuser }}");
                $("#username").val("{{ $rows->username }}");
                $("#username_old").val("{{ $rows->username }}");
                $("#password").val("{{ $password }}");
                $("#nama").val("{{ $rows->nama }}");
                $("#akses").val("{{ $rows->akses }}");
                $("#email").val("{{ $rows->email }}");
                $("#nohp").val("{{ $rows->nohp }}");
                $("#statususer").val("{{ intval($rows->statususer) }}");

			// DATA BARU
			@elseif($aksi == "acttambah")

				@php
					$mode = "ADD";
				@endphp

                $("#statususer").val("1");
			@endif

			// =========== jika ada error
			@if(session('erroract'))
                $("#kodeuser").val("{{ old('kodeuser') }}");
                $("#username").val("{{ old('username') }}");
                $("#username_old").val("{{ old('username') }}");
                $("#password").val("{{ old('password') }}");
                $("#nama").val("{{ old('nama') }}");
                $("#akses").val("{{ old('akses') }}");
                $("#email").val("{{ old('email') }}");
                $("#nohp").val("{{ old('nohp') }}");
                $("#jk").val("{{ old('jk') }}");
                $("#statususer").val("{{ old('statususer') }}");
			@endif

			// ========== initialize button
			$("#pesanwarning").addClass("hidden");
			$("#isipesanwarning").html("");

			$("#simpan").click(function() {

                 // jika data kosong
				var namauser = $("#namauser").val();
				var username = $("#username").val();
				var password = $("#password").val();

				if(username == "")
				{
					swal("PERINGATAN", "nama [username] kosong", "warning");
                }
                else if(nama == "")
				{
					swal("PERINGATAN", "nama [nama] kosong", "warning");
                }
                else if(password == "")
				{
					swal("PERINGATAN", "nama [password] kosong", "warning");
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

                <input type="hidden" class="form-control" id="username_old" name="username_old" placeholder="masukkan data">

                <div class="form-group">
                    <label for="kodeuser">Kode User</label>
                    <input class="form-control readonly" id="kodeuser" name="kodeuser" type="text" placeholder="AUTO">
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="masukkan data">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="password">Password</label>
                        <div class="input-group mb-3">
                            <input type="password" class="form-control" id="password" name="password" placeholder="masukkan data">
                            <div class="input-group-prepend">
                                <button class="btn btn-outline-secondary" type="button" onclick="toogle_password();" id="iconpass"><i class="fa fa-eye"></i></button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="nama">Nama User</label>
                    <input type="text" class="form-control" id="nama" name="nama" placeholder="masukkan data">
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="email">Email</label>
                        <input type="text" class="form-control" id="email" name="email" placeholder="masukkan data">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="nohp">No Telepon</label>
                        <input type="text" class="form-control" id="nohp" name="nohp" placeholder="masukkan data">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="jk">JK</label>
                        <select type="text" class="form-control" id="jk" name="jk" placeholder="masukkan data">
                            <option value="L">Laki Laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="statususer">Status User</label>
                        <select id="statususer" name="statususer" class="form-control">
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
