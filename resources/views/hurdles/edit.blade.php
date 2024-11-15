@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><h4 class="text-center">التعديل على بيانات الحاجز</h4></div>

                <div class="card-body">

                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('hurdles.update', $hurdle->id) }}">
                        @method('PATCH')
                        @csrf


                        <div class="form-group row">
                            <label for="type" class="col-md-2 col-form-label text-md-right">النوع</label>

                            <div class="col-md-10">
                                <input id="type" type="text" class="form-control @error('type') is-invalid @enderror" name="type" value="{{ $hurdle->type }}" required autocomplete="type"   >

                                @error('type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>



                        <div class="form-group row">
                            <label for="slogan" class="col-md-2 col-form-label text-md-right">الرمز</label>

                            <div class="col-md-10">
                                <input id="slogan" type="text" pattern="^[\x20-\x7F]+$" class="form-control @error('slogan') is-invalid @enderror" name="slogan" value="{{ $hurdle->slogan }}" required autocomplete="slogan"   >

                                @error('slogan')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>



                        <div class="form-group row mb-0">
                            <button type="submit" class="btn btn-primary col-md-12">
                                التعديل على بيانات الحاجز
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
