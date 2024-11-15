@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><h4 class="text-center">التعديل على الطلب</h4></div>

                <div class="card-body">

                    @if (session('message'))
                        <div class="alert alert-success" role="alert">
                            {{ session('message') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('sellings.update' , $selling->id) }}">
                        @method('PATCH')
                        @csrf


                        <input type="hidden" id="settings_vat" value="{{ $settings->vat }}">

                        <div class="form-group row">
                            <div class="col-md-12">
                                <select name="branch_id" class="form-control" required>
                                    <option selected disabled value="">اسم الفرع</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}" @if($branch->id == $selling->branch_id) selected @endif>{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-4">
                                <input name="client_name" type="text" value="{{ $selling->client_name }}" placeholder="اسم العميل" class="form-control" required>
                            </div>


                            <div class="col-md-4">
                                <input name="client_tax_no" type="number" value="{{ $selling->client_tax_no }}" placeholder="الرقم الضريبى للعميل" class="form-control">
                            </div>

                            <div class="col-md-4">
                                <input name="client_telephone" maxlength="10" value="{{ $selling->client_telephone }}" type="text" placeholder="رقم الهاتف" class="form-control">
                            </div>
                        </div>







                        <h1 class="text-center">بيانات المنتج</h1>

                        <div class="form-group row">
                            <div class="col-md-2">
                                <select name="vehicle_id" class="form-control" id="vehicle_id">
                                    <option selected disabled>اسم المركبة</option>
                                    @foreach ($vehicles as $vehicle)
                                        <option value="{{ $vehicle->id }}">{{ $vehicle->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="hurdle_id" class="form-control" id="hurdle_id">
                                    <option selected disabled>اسم الحاجز</option>
                                    @foreach ($hurdles as $hurdle)
                                        <option value="{{ $hurdle->id }}">{{ $hurdle->type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="model_id" class="form-control" id="model_id">
                                    <option selected disabled>اسم الطراز</option>
                                    @foreach ($models as $model)
                                        <option value="{{ $model->id }}">{{ $model->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input id="vin" type="text" placeholder="رقم الشاسيه" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <input id="qty" type="text" placeholder="الكمية" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">
                                <input id="unit_price" type="text" placeholder="سعر الوحدة" class="form-control" disabled>
                            </div>
                            <div class="col-md-3">
                                <input id="price" type="text" placeholder="السعر" class="form-control" disabled>
                            </div>
                            <div class="col-md-2">
                                <select name="discount_sort" onchange="getDiscountPercentage()" class="form-control" id="discount_sort">
                                    <option value="-1" selected disabled>نوع الخصم</option>
                                    <option value="0">نسبة</option>
                                    <option value="1">مبلغ</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="discount_amount" class="form-control" id="discount_amount">
                                    <option value="0" selected disabled>نسبة الخصم</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input id="price_after_discount" type="text" placeholder="السعر بعد الخصم" class="form-control" disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div onclick="addItem()" class="btn btn-primary col-md-10" style="margin-right: 9%;">اضافة</div>
                        </div>


                        <table class="table" style="width: 98%; margin-right: 20px; ">
                            <thead style="background-color: #0099ff;color: #fff;">
                              <tr>
                                <th>#</th>
                                <th>المنتج</th>
                                <th>سعر الوحدة</th>
                                <th>الكمية</th>
                                <th>الخصم</th>
                                <th>الإجمالى قبل الضريبة</th>
                                <th>الضريبة</th>
                                <th>الإجمالى</th>
                              </tr>
                            </thead>
                            <tbody>

                              @foreach ($selling->items as $item)
                                    @php $n = rand(0,333)  @endphp
                                    <tr id="r{{ $n }}">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->product_code }}</td>
                                        <td>{{ $item->product_price }}</td>
                                        <td>{{ $item->qty }}</td>

                                        @php $item->discount_sort == 'نسبة' ? $specialChar = "%" : $specialChar = "ر.س" @endphp
                                        <td>{{ $item->discount }}{{ $specialChar }}</td>


                                        <td class="prices_after_discount" id="price_after_discount_{{ $n }}">{{ $item->sub_total }}</td>
                                        <td class="vat_values" id="vat_value_{{ $n }}">{{ $item->vat }}</td>
                                        <td class="total_prices" id="total_price_{{ $n }}">{{ $item->total }} <div class="btn btn-danger" onclick="delete_item({{ $n }})">حذف</div></td>

                                        <input type="hidden" name="vins[]" id="vin_{{ $n }}" value="{{ $item->vin }}" >
                                        <input type="hidden" name="unit_prices[]" id="unit_price_{{ $n }}" value="{{ $item->product_price }}" >
                                        <input type="hidden" name="discount_sorts[]" id="discount_sort_{{ $n }}"  value="{{ $item->discount_sort }}" >
                                        <input type="hidden" name="discount_amounts[]" id="discount_amount_{{ $n }}"  value="{{ $item->discount }}" >
                                        <input type="hidden" name="prices_after_discount[]" id="price_after_discount_{{ $n }}"  value="{{ $item->sub_total }}" >
                                        <input type="hidden" name="qtys[]" id="qty_{{ $n }}"  value="{{ $item->qty }}" >

                                        <input type="hidden" name="product_codes[]" id="product_code_{{ $n }}" value="{{ $item->product_code }}" >
                                        <input type="hidden" name="product_ids[]" id="product_id_{{ $n }}" value="{{ $item->product->id }}" >

                                        <input type="hidden" name="vats_to_pay[]" id="vat_to_pay_{{ $n }}" value="{{ $item->vat }}" >
                                        <input type="hidden" name="total_prices[]" id="total_price_{{ $n }}" value="{{ $item->total }}" >

                                    </tr>
                              @endforeach

                              <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td style="width: 16%;">الاجمالى قبل الضريبة
                                    <br><br>
                                    قيمة الضريبة
                                </td>
                                <td></td>
                                <td></td>
                                <td>
                                    <div id="subtotal">{{ $selling->subtotal }} <input type="hidden" name="subtotal" value="{{ $selling->subtotal }}" ></div>
                                <br>
                                    <div id="tax">{{ $selling->vat }} <input type="hidden" name="vat" value="{{ $selling->vat }}" ></div>
                                </td>
                              </tr>
                              <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>المجموع</td>
                                <td></td>
                                <td></td>
                                <td><div id="total">{{ $selling->total }} <input type="hidden" name="total" value="{{ $selling->total }}" ></div></td>
                              </tr>

                            </tbody>
                          </table>


                          <div class="form-group row">
                            <div class="col-md-12">
                                <textarea name="notes" placeholder="ملاحظات" class="form-control" id="" cols="30" rows="10">
                                    {{ $selling->notes }}
                                </textarea>

                                @error('notes')
                                    <span class="invalid-feedback" role="alert">
                                       <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>



                        <div class="form-group row mb-0">
                            <button type="submit" class="btn btn-primary col-md-12">
                                التعديل على الطلب
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
    @include('sellings.selling_scripts')
@endsection
