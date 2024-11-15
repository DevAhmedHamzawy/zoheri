@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><h4 class="text-center">التعديل على الخصم</h4></div>

                <div class="card-body">

                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('discounts.update' , $discount->id) }}">
                        @method('PATCH')
                        @csrf

                        <div class="form-group row">
                            <label for="sort" class="col-md-2 col-form-label text-md-right">نوع الخصم</label>

                            <div class="col-md-10">
                                <select name="sort" class="form-control" onchange="checkDiscountAmount(this)">
                                    <option @if($discount->sort == 'نسبة') selected @endif>نسبة</option>
                                    <option @if($discount->sort == 'مبلغ') selected @endif>مبلغ</option>
                                </select>

                                @error('sort')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="input-group row mb-3" id="discount_amount">
                            <label for="amount" class="col-md-2 col-form-label text-md-right">الكمية</label>

                                <input id="amount" type="number" min="0" class="form-control @error('amount') is-invalid @enderror ml-4" name="amount" value="{{ $discount->amount }}" required autocomplete="amount">

                                @if($discount->sort == 'نسبة')
                                    <div class="input-group-append">
                                        <span class="input-group-text">%</span>
                                    </div>
                                @endif


                                @error('amount')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>



                        <div class="form-group row mb-0">
                            <button type="submit" class="btn btn-primary col-md-12">
                                التعديل على الخصم
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('footer')

    <script>
        function checkDiscountAmount(item)
        {
            if(item.value == 'نسبة')
            {
                $('#discount_amount').append('<div class="input-group-append"><span class="input-group-text">%</span></div>')
            }else{
                $('.input-group-append').remove()
            }
        }
    </script>

@endsection
