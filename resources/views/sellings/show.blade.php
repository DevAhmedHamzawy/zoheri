@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><h4 class="text-center">طلب رقم {{ $selling->id }}</h4></div>

                <div class="card-body">


                        <div class="form-group row">
                            <div class="col-md-12">
                                <input type="text" class="form-control" value="{{ $selling->branch->name }}" disabled>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-4">
                                <input type="text" class="form-control" value="{{ $selling->client_name }}" disabled>
                            </div>


                            <div class="col-md-4">
                                <input type="text" class="form-control" value="{{ $selling->client_tax_no }}" disabled>
                            </div>

                            <div class="col-md-4">
                                <input type="text" class="form-control" value="{{ $selling->client_telephone }}" disabled>
                            </div>
                        </div>

                        <h1 class="text-center">بيانات المنتج</h1>


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
                                    <tr>
                                        <td>1</td>
                                        <td>{{ $item->product_code }}</td>
                                        <td>{{ $item->product_price }}</td>
                                        <td>{{ $item->qty }}</td>
                                        <td>{{ $item->discount }}</td>
                                        <td class="prices_after_discount">{{ $item->sub_total }}</td>
                                        <td class="vat_values">{{ $item->vat }}</td>
                                        <td class="total_prices">{{ $item->total }}</td>
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
                                    <div id="subtotal">{{ $selling->subtotal }}</div>
                                <br>
                                    <div id="tax">{{ $selling->vat }}</div>
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
                                <td><div id="total">{{ $selling->total }}</div></td>
                              </tr>

                            </tbody>
                          </table>


                          <div class="form-group row">
                            <div class="col-md-12">
                                <h6>ملاحظات</h6>
                                {{ $selling->notes }}
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <h6>تاريخ اصدار الفاتورة</h6>
                                {{ $selling->created_at->format('Y-m-d') }}
                            </div>
                        </div>




                </div>
            </div>
        </div>
    </div>
</div>
@endsection
