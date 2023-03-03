@extends('template')

@push('scripts')

<script type="text/javascript">

$(document).ready(function() {

});

</script>

@endpush

@section('breadcumb')
    <ol class="breadcrumb border-0 m-0">
        {!! $breadcrumb !!}
    </ol>
@endsection


@section('content')

<div class="row">
    <div class="col-6 col-lg-4">
        <div class="card" style="border-radius: 10px">
            <div class="card-body p-3 d-flex align-items-center">
                <div class="bg-primary p-3 mfe-3" style="border-radius: 10px">
                    <i class="fa fa-cube"></i>
                </div>
                <div>
                    <div class="text-value text-primary"><a href="#">ITEM</a></div>
                    <div class="text-muted text-uppercase font-weight-bold small">total item : {{ $jml_item }} item</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-4">
        <div class="card" style="border-radius: 10px">
            <div class="card-body p-3 d-flex align-items-center">
                <div class="bg-primary p-3 mfe-3" style="border-radius: 10px">
                    <i class="fa fa-tag"></i>
                </div>
                <div>
                    <div class="text-value text-primary"><a href="#">KATEGORI</a></div>
                    <div class="text-muted text-uppercase font-weight-bold small">total kategori : {{ $jml_kategori }} kategori</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-4">
        <div class="card" style="border-radius: 10px">
            <div class="card-body p-3 d-flex align-items-center">
                <div class="bg-primary p-3 mfe-3" style="border-radius: 10px">
                    <i class="fa fa-users"></i>
                </div>
                <div>
                    <div class="text-value text-primary"><a href="#">PELANGGAN</a></div>
                    <div class="text-muted text-uppercase font-weight-bold small">total pelanggan : {{ $jml_pelanggan }} orang</div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
