@extends('template')

@push('scripts')
    <script type="text/javascript">

        function chartDefault()
        {
            $('#grafik').highcharts({
                title: {
                    text: 'Info Grafik',
                    x: -20 //center
                },
                chart: {
                    type: '{{ $bentuk }}'
                },
                subtitle: {
                    text: "Data Grafik Item Keluar {{ $tahun }}",
                    x: -20
                },
                xAxis: {
                    categories: [{!! $categories !!}]
                },
                yAxis: {
                    allowDecimals: false,
                    title: {
                        text: 'Transaksi'
                    },
                    plotLines: [{
                        value: 0,
                        width: 1,
                        color: '#808080'
                    }]
                },
                tooltip: {
                    valueSuffix: ' Transaksi'
                },
                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'middle',
                    borderWidth: 0
                },
                series:
                [
                    {
                        name: 'Grafik Item Keluar',
                        data: [<?=  $series ?>]
                    }
                ]
            });
        }

        $(document).ready(function() {

            $("#tahun").val("{{ $tahun }}");
            $("#bentuk").val("{{ $bentuk }}");

            chartDefault();

            $("#cari").click(function() {
                $("#form1").attr("action", '{{ url("laporan/grafikitemkeluar") }}');
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
                    <div class="form-group col-6">
                        <label>Bentuk</label>
                        <select type="text" class="form-control" id="bentuk" name="bentuk">
                            <option value="line">Line</option>
                            <option value="bar">Bar</option>
                            <option value="column">Column</option>
                        </select>
                    </div>
                    <div class="form-group col-6">
                        <label>Tahun</label>
                        <select type="text" class="form-control" id="tahun" name="tahun">
                            @for ($i = 2019; $i <= date('Y'); $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <button class="btn btn-primary" type="button" id="cari"><i class="fa fa-search"></i> CARI</button>
                </div>

                </form>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="col-md-12" style="width: 98%;">
                    <div id="grafik"></div>
                </div>
            </div>
            <div class="card-footer">
                <a class="btn btn-primary" href='{{ url("dashboard") }}'><i class="fa fa-backward"></i> KEMBALI</a>
            </div>
        </div>
    </div>
</div>

@endsection
