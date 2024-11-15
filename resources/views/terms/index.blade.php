@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <h3 class="card-header">
                   بنود المصروفات

                   @can('add_term')
                        <div style="float:left">
                            <button type="button" class="btn btn-primary">
                                <a href="{{ route('terms.create') }}" style="color:#fff;">إضافة </a>
                            </button>
                        </div>
                    @endcan

                    @can('view_term')
                        <div style="float:left" class="pr-2">
                            <button type="button" class="btn btn-primary">
                                <a href="{{ route('terms.trash') }}" style="color:#fff;">البنود المحذوفة</a>
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
                                <th scope="col">الإسم</th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                                <th scope="col">الحالة</th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                                <th scope="col"></th>
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

        ajax: "{{ route('terms.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'name', name: 'name'},
            {data: 'blank', name: 'blank', orderable: false, searchable: false},
            {data: 'blank', name: 'blank', orderable: false, searchable: false},
            {data: 'blank', name: 'blank', orderable: false, searchable: false},
            {data: 'status', name: 'status', orderable: false, searchable: false},
            {data: 'blank', name: 'blank', orderable: false, searchable: false},
            {data: 'blank', name: 'blank', orderable: false, searchable: false},
            {data: 'blank', name: 'blank', orderable: false, searchable: false},
            {data: 'actions', name: 'actions', orderable: false, searchable: false},
        ],
        dom: 'lBfrtip',
    });

  });


  $.fn.dataTable.ext.errMode = 'none';



    </script>
@endsection
