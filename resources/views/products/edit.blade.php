@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><h4 class="text-center">التعديل على المنتج</h4></div>

                <div class="card-body">

                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('products.update', $product->id) }}">
                        @method('PATCH')
                        @csrf


                        <div class="form-group row">
                            <label for="vehicle_id" class="col-md-2 col-form-label text-md-right">اسم المركبة</label>

                            <div class="col-md-10">

                                <select name="vehicle_id" class="form-control" id="">
                                    @foreach ($vehicles as $vehicle)
                                        <option value="{{ $vehicle->id }}" @if($vehicle->id == $product->vehicle_id) selected @endif>{{ $vehicle->name }}</option>
                                    @endforeach
                                </select>

                                @error('vehicle_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="hurdle_id" class="col-md-2 col-form-label text-md-right">اسم الحاجز</label>

                            <div class="col-md-10">

                                <select name="hurdle_id" class="form-control" id="">
                                    @foreach ($hurdles as $hurdle)
                                        <option value="{{ $hurdle->id }}" @if($hurdle->id == $product->hurdle_id) selected @endif>{{ $hurdle->type }}</option>
                                    @endforeach
                                </select>

                                @error('hurdle_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="active" class="col-md-2 col-form-label text-md-right">الطراز</label>

                            <div class="col-md-10">

                                <select name="model_id" class="form-control" id="">
                                    @foreach ($models as $model)
                                        <option value="{{ $model->id }}" @if($model->id == $product->model_id) selected @endif>{{ $model->name }}</option>
                                    @endforeach
                                </select>

                                @error('model_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="price" class="col-md-2 col-form-label text-md-right">سعر المنتج</label>

                            <div class="col-md-10">
                                <input id="price" type="price" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ $product->price }}" step="0.001" required autocomplete="price">

                                @error('price')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>



                        <div class="form-group row mb-0">
                            <button type="submit" class="btn btn-primary col-md-12">
                                التعديل على المنتج
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
