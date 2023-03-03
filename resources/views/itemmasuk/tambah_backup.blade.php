@extends('template')

@push('scripts')
    <script type="text/javascript">

        var noRow = 1;
        var selectedRowIndex = 0;

        function onClick_row(row)
        {
            var rowItem = JSON.parse(row);
            var body = "";

            var myTable = $('#myTable').DataTable();
            myTable.row.add([
                rowItem.kodeitem,
                rowItem.namakategori,
                rowItem.namaitem,
                1,
                rowItem.satuan,
                '**new'
            ]).draw(false);

            $('#modalAddItem').modal('toggle');

            noRow++;

            // body = "<td><input class='form-control input-sm disable' name='kodeitem_"+ noRow +"' value='"+ rowItem.kodeitem +"' /></td>";
            // body += "<td><input class='form-control input-sm disable' name='namakategori_"+ noRow +"' value='"+ rowItem.namakategori +"' /></td>";
            // body += "<td><input class='form-control input-sm disable' name='namaitem_"+ noRow +"' value='"+ rowItem.namaitem +"' /></td>";
            // body += "<td><input class='form-control input-sm' name='jumlah_"+ noRow +"' value='1' /></td>";
            // body += "<td><input class='form-control input-sm disable' name='satuan_"+ noRow +"' value='"+ rowItem.satuan +"' /></td>";
            // body += "<td><a class='btn btn-danger btn-sm'><i class='fa fa-trash'></i></a></td>";

            // body = "<input class='form-control input-sm hidden' name='kodeitem_"+ noRow +"' value='"+ rowItem.kodeitem +"' />";
            // body += "<input class='form-control input-sm hidden' name='status_"+ noRow +"' value='**new' />";

            // body += "<td>"+ rowItem.kodeitem +"</td>";
            // body += "<td>"+ rowItem.namakategori +"</td>";
            // body += "<td>"+ rowItem.namaitem +"</td>";
            // body += "<td><input class='form-control input-sm' name='jumlah_"+ noRow +"' value='1' /></td>";
            // body += "<td>"+ rowItem.satuan +"</td>";
            // body += "<td><a class='btn btn-danger btn-sm'><i class='fa fa-trash'></i></a></td>";

            // $("#jml").val(noRow);
            // $("#tbody_detail_item").append("<tr>"+ body +"<tr>");


        }

        function tableToJSON(tblObj){
            var data = [];
            var $headers = $(tblObj).find("th");
            var $rows = $(tblObj).find("tbody tr").each(function(index) {
                $cells = $(this).find("td");
                data[index] = {};
                $cells.each(function(cellIndex) {
                    data[index][$($headers[cellIndex]).html()] = $(this).html();
                });
            });
            return data;
        }

        function convertTableToArrayObject() {
            var employeeObjects = [];
            var table = $('#myTable').DataTable();
            var data = table.rows().data();

            for (var i = 0; i < data.length; i++) {
                console.log(data[i]);
                employeeObjects.push(data[i]);
            }

            return employeeObjects;
        }

		$(document).ready(function() {

            var myTable = $('#myTable').DataTable({
                "ordering": false,
                "paging": false,
                "info": false,
                "searching": false,
            });

            $('#myTable tbody').on('click', 'tr', function () {
                selectedRowIndex = myTable.row(this).index();
                var data = myTable.row(selectedRowIndex).data();
                $("#jml_edit").val(data[3]);
                $('#modalEditItem').modal('toggle');
            });

            $("#update").click(function() {
                var data = myTable.row(selectedRowIndex).data();
                data[3] = $("#jml_edit").val();
                myTable.row(selectedRowIndex).invalidate().draw();
                $('#modalEditItem').modal('toggle');
            });

            $('#myTableItem').DataTable({"ordering": false,});

			// DATA EDIT
			@if($aksi == "actedit")

				@php
					$mode = "UPDATE";
				@endphp

				// $("#useradmin").addClass("disable");
                $("#kodeitemmasuk").val("{{ $rows->kodeitemmasuk }}");
                $("#kodesupplier").val("{{ $rows->kodesupplier }}");
                $("#kodeuser").val("{{ $rows->kodeuser }}");
                $("#tanggalitemmasuk").val("{{ $rows->tanggalitemmasuk }}");
                $("#keteranganitemmasuk").val("{{ $rows->keteranganitemmasuk }}");

			// DATA BARU
			@elseif($aksi == "acttambah")

				@php
					$mode = "ADD";
				@endphp

                $("#kodeuser").val("{{ session('kodeuser') }}");
                $("#tanggalitemmasuk").val("{{ date('Y-m-d') }}");

			@endif

			// =========== jika ada error
			@if(session('erroract'))
                $("#kodeitemmasuk").val("{{ old('kodeitemmasuk') }}");
                $("#kodesupplier").val("{{ old('kodesupplier') }}");
                $("#kodeuser").val("{{ old('kodeuser') }}");
                $("#tanggalitemmasuk").val("{{ old('tanggalitemmasuk') }}");
                $("#keteranganitemmasuk").val("{{ old('keteranganitemmasuk') }}");
			@endif

			// ========== initialize button
			$("#pesanwarning").addClass("hidden");
			$("#isipesanwarning").html("");

			$("#simpan").click(function() {

                var json = JSON.stringify(tableToJSON($("#myTable")));
                $("#json_item_detail").val(json);

                 // jika data kosong

				if($("#kodesupplier").val() == "")
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
                <input class="form-control hidden" id="json_item_detail" name="json_item_detail" type="text">

                <div class="form-group">
                    <label for="kodeitemmasuk">Kode Item Masuk</label>
                    <input class="form-control readonly" id="kodeitemmasuk" name="kodeitemmasuk" type="text" placeholder="AUTO">
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4 disable">
                        <label for="kodeuser">Petugas</label>
                        <select id="kodeuser" name="kodeuser" class="form-control">
                            {!! App\Lib\Csql::DropDown("kodeuser", "nama", "tbl_user") !!}
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="kodesupplier">Supplier</label>
                        <select id="kodesupplier" name="kodesupplier" class="form-control">
                            {!! App\Lib\Csql::DropDown("kodesupplier", "namasupplier", "tbl_supplier") !!}
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="tanggalitemmasuk">Tanggal Item Masuk</label>
                        <input type="text" class="form-control datepicker" id="tanggalitemmasuk" name="tanggalitemmasuk" placeholder="masukkan data">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <button type="button" id="additem" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalAddItem"><i class="fa fa-plus"></i> ADD ITEM</button>
                        <table class="table table-responsive-sm table-sm mt-2" id="myTable">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Kategori</th>
                                    <th>Nama Item</th>
                                    <th style="width: 55px;">Jumlah</th>
                                    <th>Satuan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="tbody_detail_item" style="cursor: pointer;">
                            </tbody>
                        </table>
                        <hr />
                    </div>
                </div>

                <div class="form-group">
                    <label for="keteranganitemmasuk">Keterangan Item Masuk</label>
                    <input class="form-control readonly" id="keteranganitemmasuk" name="keteranganitemmasuk" type="text">
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

<!-- MODAL ITEM -->
<div class="modal fade" id="modalAddItem" tabindex="-1" role="dialog" aria-labelledby="modalAddItemLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAddItemLabel">Modal title</h5>
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
                    <input class="form-control" id="jml_edit" name="jml_edit" type="text">
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <button type="button" class="btn btn-danger btn-block"><i class="fa fa-trash"></i> HAPUS</button>
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
