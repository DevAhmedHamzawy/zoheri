@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><h4 class="text-center">التعديل على بيانات المصروف</h4></div>

                <div class="card-body">

                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('expenses.update' , $expense->id) }}" enctype="multipart/form-data">
                        @method('PATCH')
                        @csrf

                        <div class="form-group row">
                            <label for="term_id" class="col-md-2 col-form-label text-md-right">اسم البند</label>

                            <div class="col-md-10">

                                <select name="term_id" class="form-control" id="">
                                    @foreach ($terms as $term)
                                        <option value="{{ $term->id }}" @if($term->id == $expense->term_id) selected @endif>{{ $term->name }}</option>
                                    @endforeach
                                </select>

                                @error('term_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="cost" class="col-md-2 col-form-label text-md-right">تكلفة المصروف</label>

                            <div class="col-md-10">
                                <input id="cost" type="number" class="form-control @error('cost') is-invalid @enderror" name="cost" value="{{ $expense->cost }}" step="0.001" required autocomplete="cost">

                                @error('cost')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="main_image" class="col-md-2 col-form-label text-md-right">الفاتورة</label>

                            <div class="col-md-10">
                                <input id="main_image" type="file" class="form-control @error('main_image') is-invalid @enderror" name="main_image" value="{{ old('main_image') }}" required autocomplete="main_image">

                                @error('main_image')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="date" class="col-md-2 col-form-label text-md-right">التاريخ</label>

                            <div class="col-md-10">
                                <input id="date" type="date" class="form-control @error('date') is-invalid @enderror" name="date" value="{{ $expense->date }}" required autocomplete="date">

                                @error('date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="form-group row mb-0">
                            <button type="submit" class="btn btn-primary col-md-12">
                                التعديل على بيانات المصروف
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
