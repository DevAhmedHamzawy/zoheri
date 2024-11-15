<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.rtlcss.com/bootstrap/v4.5.3/css/bootstrap.min.css" integrity="sha384-JvExCACAZcHNJEc7156QaHXTnQL3hQBixvj5RV5buE7vgnNEzzskDtx9NQ4p6BJe" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ url('panel/panel/css/invoice.css') }}">
</head>
<body>

    <div class="row" style="background-color: #f2f2f2;margin-top: 20px;">

        <div class="col-md-6">
                <div style="margin-right: 25%;">
                    <h2 class="mt-3" style="font-weight: bold;">شركة سليمان الزهيري المحدودة</h2>
                    <h5>القصيم, بريده</h5>
                    <h5>السعودية</h5>
                    <h5>966505135455</h5>
                    <h5>310451998500003</h5>
                </div>

                <img src="{{ asset('panel/panel/img/logo.png') }}" style="margin-top: -240px;" width="20%" height="130" alt="" srcset="">
        </div>

        <div class="col-md-6 text-center" style="margin-top: 150px;">
            <h1 style="color: grey;font-weight: 700;">فاتورة مبيعات ضريبية</h1>
            <br>
            <h3 style="font-weight: bold">INV1010{{ $selling->id }}</h3>
            <hr style="width:200px;margin-top:-10px;bselling: 1px solid black;">
            <h3 style="font-weight: bold;">{{ $selling->created_at->format('Y-m-d') }}</h3>
            <hr style="width:200px;margin-top:-10px;bselling: 1px solid black;">
        </div>

    </div>


    <div class="row">
        <div class="col-md-6">
            <div style="margin-right: 20px;">
              <h1 style="font-weight: bold;">بيانات العميل</h1>
              <hr style="bselling: 1px solid black;">


              <h5>{{ $selling->client_name }}</h5>
              <h5>{{ $selling->client_tax_no }}</h5>

            </div>

        </div>
        <div class="col-md-6">
           <div style="margin-left: 20px;">

            <h1 style="font-weight: bold;">فاتورة مبيعات</h1>
            <hr style="bselling: 1px solid black;">

            <h5>موافق عليه</h5>
            <h5>المركز الرئيسي</h5>
            <h5>{{ $selling->created_at->format('Y-m-d') }}</h5>

           </div>

        </div>
    </div>


    <table class="table" style="width: 98%; margin-right: 20px; ">
        <thead style="background-color: #0099ff;color: #fff;">
          <tr>
            <th>#</th>
            <th>المنتج</th>
            <th>سعر الواحدة</th>
            <th>الكمية</th>
            <th>الخصم</th>
            <th>الاجمالى قبل الضريبة</th>
            <th>الضريبة</th>
            <th>الاجمالى</th>
          </tr>
        </thead>
        <tbody>
            @foreach ($selling->items as $item)
                @php $n = rand(0,333)  @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->product_code }}</td>
                    <td>{{ $item->product_price }} ر.س</td>
                    <td>{{ $item->qty }}</td>

                    @php $item->discount_sort == 'نسبة' ? $specialChar = "%" : $specialChar = "ر.س" @endphp
                    <td>{{ $item->discount }}{{ $specialChar }}</td>

                    <td>{{ $item->sub_total }} ر.س</td>
                    <td> {{ \App\Models\Setting::whereId(1)->first()->vat }}% ({{ $item->vat }} ر.س)  </td>
                    <td>{{ $item->total }} ر.س</td>
                </tr>
            @endforeach
          <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>الاجمالى قبل الضريبة
                <br>
                قيمة الضريبة
            </td>
            <td></td>
            <td></td>
            <td>{{ $selling->subtotal }} ر.س
                <br>
                {{ $selling->vat }} ر.س
            </td>
          </tr>
          <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>المجموع
                <br>
                <b>المبلغ المستحق</b>
            </td>
            <td></td>
            <td></td>
            <td>{{ $selling->total }} ر.س
                <br>
                {{ $selling->total }} ر.س
            </td>
          </tr>
        </tbody>
      </table>

      <div style="margin-right: 20px;">
        <h1 style="font-weight: bold;">الشروط والأحكام</h1>
        <h1 style="font-weight: bold;">الملاحظات</h1>
        <p>{{ $selling->notes }}</p>
      </div>

      {{-- <img src="qr.jpg" style="margin-right: 40%;" width="250" alt="" srcset=""> --}}
      <center>

        <div class="qrcode">
            </div>

      </center>


    <script src="{{ asset('js/jquery.min.js') }}" ></script>
    <script src="{{ asset('js/bootstrap.min.js') }}" ></script>


    <script>
        $(document).ready(function(){

            generateQRcode();
        });

        function generateQRcode(){
                  $.get('{{ route('generate-QR-code', ['bill' => $selling->id]) }}', function($res){
                $('.qrcode').html($res);
            });

        }

    </script>


</body>
</html>
