@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <h3 class="card-header">
                   الحواجز المحذوفة

                   @can('view_hurdle')
                        <div style="float:left">
                            <button type="button" class="btn btn-primary">
                                <a href="{{ route('hurdles.index') }}" style="color:#fff;">الحواجز </a>
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
                                <th scope="col">النوع</th>
                                <th scope="col">الرمز</th>
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

        ajax: "{{ route('hurdles.trash') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'type', name: 'type'},
            {data: 'slogan', name: 'slogan'},
            {data: 'actionone', name: 'actionone', orderable: false, searchable: false},
        ],
        dom: 'lBfrtip',
    });
  });


  $.fn.dataTable.ext.errMode = 'none';



    </script>
@endsection
