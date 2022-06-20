@extends('layouts.app')
@section('header')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css" rel="stylesheet" type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/multi-select/0.9.12/css/multi-select.min.css" rel="stylesheet" type="text/css">
@endsection
@section('content')
    <div class="row page-title clearfix">
        <div class="page-title-left">
            <h6 class="page-title-heading mr-0 mr-r-5">Ürün <small> Kapak Düzenle</small></h6>


        </div>
        <!-- /.page-title-left -->
        <div class="page-title-right d-none d-sm-inline-flex">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Panel</a>
                </li>
                <li class="breadcrumb-item active">Ürün</li>
            </ol>
            <div class="d-none d-md-inline-flex justify-center align-items-center">
                <a href="{{ route('kapak.index') }}" class="btn btn-color-scheme btn-sm fs-11 fw-400 mr-l-40 pd-lr-10 mr-l-0-rtl mr-r-40-rtl hidden-xs hidden-sm ripple">Kapak Listesi</a>
            </div>
        </div>
        <!-- /.page-title-right -->
    </div>

    @if(session("status"))
        <div class="row" style="margin-top: 10px;">
            <div class="col-md-12">
                <div class="alert alert-success">{{ session("status") }}</div>
            </div>
        </div>

    @endif

    @if(session("statusDanger"))
        <div class="row" style="margin-top: 10px;">
            <div class="col-md-12">
                <div class="alert alert-danger">{{ session("statusDanger") }}</div>
            </div>
        </div>

    @endif

    <div class="widget-list">
        <div class="row">
            <div class="col-md-12 widget-holder">
                <div class="widget-bg">
                    <div class="widget-body clearfix">


                        <form action="{{ route('kapak.update',['id'=>$data[0]['id']]) }}" method="POST" enctype="multipart/form-data">
                            @csrf


                            <div class="form-group row firma--area">
                                <div class="col-md-4 px-3">
                                    <label class="col-form-label" for="l0">FİŞ SEÇİNİZ</label>
                                    <select required name="faturaNo" id="faturaNo" class="m-b-10 form-control fatura" data-placeholder="Fiş Seçiniz" data-toggle="select2">
                                        <option value="">Fiş Seçiniz</option>
                                        @foreach(\App\Models\Fatura::getList(FATURA_GELIR) as $k => $v)
                                            <option @if($v['faturaNo'] == $data[0]['faturaNo']) selected @endif value="{{ $v['faturaNo'] }}"> {{ $v['faturaNo']}} - {{ \App\Models\Musteriler::getPublicName($v['musteriId']) }} - {{ \App\Models\Fatura::getTotal($v['id']) }} TL</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4 px-3">
                                    <label class=" col-form-label" for="l0">Ürün Tarihi</label>
                                    <input class="form-control" id="urunTarih" required name="tarih" value="{{ $data[0]['tarih'] }}" type="date">
                                </div>

                            </div>

                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="urunData" class="table">
                                        <thead>
                                        <tr>
                                            <th>Boy</th>
                                            <th>En</th>
                                            <th>Adet</th>
                                            <th>Model</th>
                                            <th>Taç</th>
                                            <th>Parça</th>
                                            <th>Açıklama</th>
                                            <th>Kaldır</th>
                                        </tr>
                                        </thead>
                                        @foreach($dataIslem as $k => $v)
                                            <tr class="kapi_field">
                                                <td><input type="text" class="form-control" required name="kapak[{{ $k }}][boy]" value="{{$v['boy']}}"></td>
                                                <td><input type="text" class="form-control" required name="kapak[{{ $k }}][en]" value="{{$v['en']}}"></td>
                                                <td><input type="text" class="form-control" required name="kapak[{{ $k }}][adet]" value="{{$v['adet']}}"></td>
                                                <td><input type="text" class="form-control" required name="kapak[{{ $k }}][model]" value="{{$v['model']}}"></td>
                                                <td><input type="text" class="form-control" name="kapak[{{ $k }}][tac]" value="{{$v['tac']}}"></td>
                                                <td><input type="text" class="form-control" name="kapak[{{ $k }}][parca]" value="{{$v['parca']}}"></td>
                                                <td><input type="text" class="form-control" name="kapak[{{ $k }}][text]" value="{{$v['text']}}"></td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>
                            </div>

                            <button type="button" id="addRowBtn" class="btn btn-primary btn-rounded">+</button>



                            <div class="form-actions">
                                <div class="form-group row">
                                    <div class="col-md-12 ml-md-auto btn-list mt-5">
                                        <button id="kaydet" class="btn btn-primary btn-rounded" type="submit">Kaydet</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /.widget-body -->
                </div>
                <!-- /.widget-bg -->
            </div>
        </div>
    </div>


@endsection

@section('footer')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/multi-select/0.9.12/js/jquery.multi-select.min.js"></script>

    <script>
        $(document).ready(function () {

            $('[data-toggle=select2]').select2();
        });


        $("#addRowBtn").click(function (){

            var i = $(".kapi_field").length;

            var newRow =
                '<tr class="kapak_field">'+
                '<td><input type="text" class="form-control" required name="kapak['+i+'][boy]"></td>'+
                '<td><input type="text" class="form-control" required name="kapak['+i+'][en]"></td>'+
                '<td><input type="text" class="form-control" required name="kapak['+i+'][adet]"></td>'+
                '<td><input type="text" class="form-control" required name="kapak['+i+'][model]"></td>'+
                '<td><input type="text" class="form-control" name="kapak['+i+'][tac]"></td>'+
                '<td><input type="text" class="form-control" name="kapak['+i+'][parca]"></td>'+
                '<td><input type="text" class="form-control" name="kapak['+i+'][text]"></td>'+
                '<td><button id="removeButton" type="button" class="btn btn-danger">X</button></td>'+
                '</tr>';
            $("#urunData").append(newRow);
            i++;

        });


        $("body").on("click","#removeButton",function () {
            $(this).closest(".kapak_field").remove();
        });



    </script>
@endsection
