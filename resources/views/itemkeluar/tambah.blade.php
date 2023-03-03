@extends('template')

@push('scripts')
    <script type="text/javascript">

        var noRow = {{ $noRow }};
        var noRowDel = 0;
        var totalData = {{ $noRow }};
        var selectedRowIndex = 0;

        function onlyNumberKey(evt) {
            var ASCIICode = (evt.which) ? evt.which : evt.keyCode
            if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
                return false;
            return true;
        }

        function onClick_row(row)
        {
            var rowItem = JSON.parse(row);
            var body = "";

            noRow++;
            totalData++;

            body = "<td class='hidden'><input class='form-control input-sm' name='rowindex_"+ noRow +"' id='rowindex_"+ noRow +"' value='"+ noRow +"' /></td>";
            body += "<td class='hidden'><input class='form-control input-sm' name='kodeitem_"+ noRow +"' id='kodeitem_"+ noRow +"' value='"+ rowItem.kodeitem +"' /></td>";
            body += "<td class='hidden'><input class='form-control input-sm' name='jumlah_"+ noRow +"' id='jumlah_"+ noRow +"' value='1' /></td>";
            body += "<td class='hidden'><input class='form-control input-sm' name='stok_"+ noRow +"' id='stok_"+ noRow +"' value='"+ rowItem.stok +"' /></td>";
            body += "<td class='hidden'><input class='form-control input-sm' name='status_"+ noRow +"' id='status_"+ noRow +"' value='**new' /></td>";

            body += "<td>"+ rowItem.kodeitem +"</td>";
            body += "<td>"+ rowItem.namakategori +"</td>";
            body += "<td>"+ rowItem.namaitem +"</td>";
            body += "<td id='disp_jumlah_"+ noRow +"'>1</td>";
            body += "<td>"+ rowItem.satuan +"</td>";

            $("#jml").val(noRow);
            $("#totaldata").val(totalData);
            $("#tbody_detail_item").append("<tr id='tr_"+ noRow +"' onclick='onClick_row_edit("+ noRow +")'>"+ body +"<tr>");

            $('#modalAddItem').modal('toggle');
        }

        function onClick_row_edit(index)
        {
            selectedRowIndex = index;
            var jml = $("#jumlah_"+index).val();
            console.log($("#jumlah_1").val());
            $("#jml_edit").val(jml);
            $('#modalEditItem').modal('toggle');
        }

		$(document).ready(function() {

            var myTable = $('#myTable').DataTable({
                "ordering": false,
                "paging": false,
                "info": false,
                "searching": false,
            });

            $("#update").click(function() {
                $("#disp_jumlah_"+selectedRowIndex).html($("#jml_edit").val());
                $("#jumlah_"+selectedRowIndex).val($("#jml_edit").val());
                $("#status_"+selectedRowIndex).val("**upd");
                $('#modalEditItem').modal('toggle');
            });

            $("#hapus").click(function() {

                noRowDel++;
                totalData--;

                var body = "";
                body += "<td><input class='form-control input-sm' name='del_kodeitemkeluardt_"+ noRowDel +"' id='del_kodeitemkeluardt_"+ noRowDel +"' value='"+ $("#kodeitemkeluardt_"+selectedRowIndex).val() +"' /></td>";
                body += "<td><input class='form-control input-sm' name='del_kodeitem_"+ noRowDel +"' id='del_kodeitem_"+ noRowDel +"' value='"+ $("#kodeitem_"+selectedRowIndex).val() +"' /></td>";
                body += "<td><input class='form-control input-sm' name='del_jumlah_"+ noRowDel +"' id='del_jumlah_"+ noRowDel +"' value='"+ $("#jumlah_"+selectedRowIndex).val() +"' /></td>";

                $("#myTableDelete").append("<tr>"+ body +"<tr>");
                $("#jmldel").val(noRowDel);
                $("#totaldata").val(totalData);
                $('#tr_'+selectedRowIndex).remove();
                $('#modalEditItem').modal('toggle');
            });

            $('#myTableItem').DataTable({"ordering": false,});

			// DATA EDIT
			@if($aksi == "actedit")

				@php
					$mode = "UPDATE";
				@endphp

				// $("#useradmin").addClass("disable");
                $("#kodeitemkeluar").val("{{ $rows->kodeitemkeluar }}");
                $("#kodepelanggan").val("{{ $rows->kodepelanggan }}");
                $("#kodeuser").val("{{ $rows->kodeuser }}");
                $("#tanggalitemkeluar").val("{{ $rows->tanggalitemkeluar }}");
                $("#keteranganitemkeluar").val("{{ $rows->keteranganitemkeluar }}");
                $("#jml").val("{{ $noRow }}");
                $("#totaldata").val("{{ $noRow }}");

			// DATA BARU
			@elseif($aksi == "acttambah")

				@php
					$mode = "ADD";
				@endphp

                $("#kodeuser").val("{{ session('kodeuser') }}");
                $("#tanggalitemkeluar").val("{{ date('Y-m-d') }}");
                $("#totaldata").val("0");

			@endif

			// =========== jika ada error
			@if(session('erroract'))
                $("#kodeitemkeluar").val("{{ old('kodeitemkeluar') }}");
                $("#kodepelanggan").val("{{ old('kodepelanggan') }}");
                $("#kodeuser").val("{{ old('kodeuser') }}");
                $("#tanggalitemkeluar").val("{{ old('tanggalitemkeluar') }}");
                $("#keteranganitemkeluar").val("{{ old('keteranganitemkeluar') }}");
			@endif

			// ========== initialize button
			$("#pesanwarning").addClass("hidden");
			$("#isipesanwarning").html("");

			$("#simpan").click(function() {

                 // jika data kosong

                // alert($("#jumlah_1").val());
				if($("#kodepelanggan").val() == "")
				{
					swal("PERINGATAN", "nama [usersupplier] kosong", "warning");
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

                <input class="form-control hidden" id="jml" name="jml" type="text">
                <input class="form-control hidden" id="jmldel" name="jmldel" type="text">
                <input class="form-control hidden" id="json_item_detail" name="json_item_detail" type="text">

                <div class="form-group">
                    <label for="kodeitemkeluar">Kode Item Keluar</label>
                    <input class="form-control readonly" id="kodeitemkeluar" name="kodeitemkeluar" type="text" placeholder="AUTO">
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4 disable">
                        <label for="kodeuser">Petugas</label>
                        <select id="kodeuser" name="kodeuser" class="form-control">
                            {!! App\Lib\Csql::DropDown("kodeuser", "nama", "tbl_user") !!}
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="kodepelanggan">Pelanggan</label>
                        <select id="kodepelanggan" name="kodepelanggan" class="form-control">
                            {!! App\Lib\Csql::DropDownStatus("kodepelanggan", "namapelanggan", "tbl_pelanggan", "statuspelanggan") !!}
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="tanggalitemkeluar">Tanggal Item Keluar</label>
                        <input type="text" class="form-control datepicker" id="tanggalitemkeluar" name="tanggalitemkeluar" placeholder="masukkan data">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <button type="button" id="additem" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalAddItem"><i class="fa fa-plus"></i> ADD ITEM</button>
                        <table class="table table-responsive-sm table-sm mt-2">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Kategori</th>
                                    <th>Nama Item</th>
                                    <th style="width: 55px;">Jumlah</th>
                                    <th>Satuan</th>
                                </tr>
                            </thead>
                            <tbody id="tbody_detail_item" style="cursor: pointer;">
                                @if($aksi == 'actedit')
                                    @php
                                        $noRow = 1;
                                    @endphp
                                    @foreach ($rows_detail as $row)
                                        <tr id='tr_{{ $noRow }}' onclick='onClick_row_edit({{ $noRow }})'>

                                            <td class='hidden'><input class='form-control input-sm' name='rowindex_{{ $noRow }}' id='rowindex_{{ $noRow }}' value='{{ $noRow }}' /></td>
                                            <td class='hidden'><input class='form-control input-sm' name='kodeitemkeluardt_{{ $noRow }}' id='kodeitemkeluardt_{{ $noRow }}' value='{{ $row->kodeitemkeluardt }}' /></td>
                                            <td class='hidden'><input class='form-control input-sm' name='kodeitem_{{ $noRow }}' id='kodeitem_{{ $noRow }}' value='{{ $row->kodeitem }}' /></td>
                                            <td class='hidden'><input class='form-control input-sm' name='jumlah_{{ $noRow }}' id='jumlah_{{ $noRow }}' value='{{ $row->jumlah }}' /></td>
                                            <td class='hidden'><input class='form-control input-sm' name='status_{{ $noRow }}' id='status_{{ $noRow }}' value='**upd' /></td>

                                            <td>{{ $row->kodeitem }}</td>
                                            <td>{{ $row->namakategori }}</td>
                                            <td>{{ $row->namaitem }}</td>
                                            <td id='disp_jumlah_{{ $noRow }}'>{{ $row->jumlah }}</td>
                                            <td>{{ $row->satuan }}</td>
                                        </tr>

                                        @php
                                            $noRow++;
                                        @endphp
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        <hr />
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-8">
                        <label for="keteranganitemkeluar">Keterangan Item Keluar</label>
                        <input class="form-control" id="keteranganitemkeluar" name="keteranganitemkeluar" type="text">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Total Data</label>
                        <input class="form-control readonly" id="totaldata" name="totaldata" type="text">
                    </div>
                </div>

                <!-- delete table -->
                <table id="myTableDelete" class="hidden"></table>

                </form>

            </div>
            <div class="card-footer">
                <a class="btn btn-primary" href='{{ url("$prefix/list") }}'><i class="fa fa-backward"></i> KEMBALI</a>
                <button type="button" id="simpan" class="btn btn-info"><i class="fa fa-save"></i> SIMPAN</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL ITEM -->
<div class="modal fade" id="modalAddItem" tabindex="-1" role="dialog" aria-labelledby="modalAddItemLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAddItemLabel">Daftar Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-sm table-bordered" id="myTableItem">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Kategori</th>
                            <th>Satuan</th>
                            <th style="width: 250px;">Nama</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rows_item as $row)
                            <tr style="cursor: pointer" onClick="onClick_row('{{ $row }}');">
                                <td>{{ $row->kodeitem }}</td>
                                <td>{{ $row->namakategori }}</td>
                                <td>{{ $row->satuan }}</td>
                                <td>{{ $row->namaitem }}</td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditItem" tabindex="-1" role="dialog" aria-labelledby="modalEditItemLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAddItemLabel">Edit Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="jml_edit">Jumlah Item</label>
                    <input class="form-control" id="jml_edit" name="jml_edit" type="text" onkeypress="return onlyNumberKey(event)">
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <button type="button" id="hapus" class="btn btn-danger btn-block"><i class="fa fa-trash"></i> HAPUS</button>
                    </div>
                    <div class="form-group col-md-6">
                        <button type="button" id="update" class="btn btn-info btn-block"><i class="fa fa-save"></i> UPDATE</button>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection
