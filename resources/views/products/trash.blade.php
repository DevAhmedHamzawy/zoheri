@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <h3 class="card-header">
                   المنتجات المحذوفة

                   @can('view_product')
                        <div style="float:left">
                            <button type="button" class="btn btn-primary">
                                <a href="{{ route('products.index') }}" style="color:#fff;">المنتجات </a>
                            </button>
                        </div>
                    @endcan
                </h3>

                <div class="card-body">
                    @if(session()->has('message'))
                        <div class="alert {{session('alert') ?? 'alert-info'}}">
                            {{ session('message') }}
                        </div>
                    @endif

                    <table class="table  data-table">

                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">اسم المركبة</th>
                                <th scope="col">اسم الحاجز</th>
                                <th scope="col">الطراز</th>
                                <th scope="col">سعر المنتج</th>
                                <th scope="col">العمليات</th>
                            </tr>
                        </thead>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('footer')
    <script type="text/javascript">

$(function () {

    var scroll_x=false;
    if($( window ).width()<=750) {
        scroll_x=true
    }
    var table = $('.data-table').DataTable({
         processing: true,
        serverSide: true,
        'scrollX': scroll_x,
        responsive: true,

        ajax: "{{ route('products.trash') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'vehicle.name', name: 'vehicle.name'},
            {data: 'hurdle.type', name: 'hurdle.type'},
            {data: 'model.name', name: 'model.name'},
            {data: 'price', name: 'price'},
            {data: 'actionone', name: 'actionone', orderable: false, searchable: false},
        ],
        dom: 'lBfrtip',
    });
  });


  $.fn.dataTable.ext.errMode = 'none';



    </script>
@endsection
