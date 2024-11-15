@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><h4 class="text-center">الضريبة</h4></div>

                <div class="card-body">

                    @if (session('message'))
                        <div class="alert alert-success" role="alert">
                            {{ session('message') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('settings.update', 1) }}">
                        @method('PATCH')
                        @csrf

                        <input type="hidden" name="type" value="settings">

                        <div class="input-group mb-3">
                            <label for="vat" class="col-md-2 col-form-label text-md-right">ضريبة القيمة المضافة</label>

                            <input id="vat" type="number" min="0" class="form-control @error('vat') is-invalid @enderror" name="vat" value="{{ $settings->vat }}" step="0.01" required autocomplete="vat" autofocus>
                            <div class="input-group-append">
                              <span class="input-group-text">%</span>
                            </div>
                            @error('vat')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>


                        <div class="form-group row mb-0">
                            <button type="submit" class="btn btn-primary col-md-12">
                                تعديل الإعدادات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
