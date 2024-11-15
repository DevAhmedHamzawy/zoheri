@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><h4 class="text-center">رمز تسجيل المنشأة</h4></div>

                <div class="card-body">

                    @if (session('message'))
                        <div class="alert alert-success" role="alert">
                            {{ session('message') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('code.update', 1) }}">
                        @method('PATCH')
                        @csrf

                        <input type="hidden" name="type" value="code">

                        <div class="form-group row">
                            <label for="code" class="col-md-2 col-form-label text-md-right">كود تسجيل المنشأة</label>

                            <div class="col-md-10">
                                <input id="code" type="text" pattern="^[\x20-\x7F]+$" class="form-control @error('code') is-invalid @enderror" name="code" value="{{ $settings->code }}" required autocomplete="code" autofocus>

                                @error('code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="form-group row mb-0">
                            <button type="submit" class="btn btn-primary col-md-12">
                                تعديل رمز تسجيل المنشأة
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
