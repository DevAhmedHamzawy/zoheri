<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بطاقة الرقم المميز للحاجز</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <style>
        @media print {
            @page{
                size: "a4"
            }
            *{
                -webkit-print-color-adjust: exact !important;
                font-size: 16px !important;
            }
            footer {page-break-after: always;}

            .table .main-col {
                background-color: #bfbfbf!important;
                width:20% !important;
            }

            .table .mid-col {
                background-color: rgb(232 232 232)!important;

            }

        }

        .sp-container{
            width:100%;
            display: flex;

        }
        .splitter{
            display:flex;
            width: 10px;

        }
        .sp{
            padding: 12px;
        }
            .splitter-head{
                border-right: 1px solid #fbfbfb;
                border-bottom: 1px solid #fbfbfb;
                background-color: rgb(232 232 232)!important;
                color: #000;
            }

            .m-splitter-head{
                border-right: 1px solid #fff;
                border-bottom: 1px solid #ffff;

            }
        .my-container{
            width: 95% !important;
            margin: 0 auto !important;
        }
        .table .main-col {
                background-color: #bfbfbf!important;
                color: #fff !important;
            }

            .table .mid-col {
                background-color: rgb(232 232 232)!important;
                color: #000 !important;
            }
    </style>
</head>
<body>



    @foreach ($selling->items as $item)

    @php $theCode = str_split($item->product_code); $theCode = array_filter($theCode, fn($value) => !is_null($value) && $value !== '' && $value !== ' ');  @endphp


    <h1 class="text-center">بطاقة الرقم المميز للحاجز</h1>
    <h2 class="text-center">UniqueUnder-run Device Card</h2>
    <h3 class="text-center">معلومات المصنّع /Manufacturer’s Information</h3>

    <div class="my-container">
        <table class="table">
            <thead>
              <tr class="text-center">
                <th scope="col" class="main-col" >Manufacturer’s name:</th>
                <th scope="col" class="mid-col" >شركة سليمان الزهيري المحدودة</th>
                <th scope="col" class="main-col" > : اسم المصنع/الورشة</th>
              </tr>
            </thead>
        </table>
    </div>


    <div class="my-container">
        <table class="table">
            <thead>
              <tr class="text-center">
                <th scope="col" class="main-col" >: Manufacturer Assigned Code (AXX)</th>
                <th scope="col" class="mid-col" >A12</th>
                <th scope="col" class="main-col" >: رمز المنشأة (AXX)</th>
              </tr>
            </thead>
        </table>
    </div>


    <div class="my-container">
        <table class="table">
            <thead>
              <tr class="text-center">
                <th scope="col" class="main-col" >Country of origin:</th>
                <th scope="col" class="mid-col" >المملكة العربية السعودية</th>
                <th scope="col" class="main-col" >: بلد المنشأ</th>
              </tr>
            </thead>
        </table>
    </div>


    <div class="my-container">
        <table class="table">
            <thead>
              <tr class="text-center">
                <th scope="col" class="main-col" >Date of manufacture:</th>
                <th scope="col" class="mid-col" >2011</th>
                <th scope="col" class="main-col" >: تاريخ الصنع</th>
              </tr>
            </thead>
        </table>
    </div>


    <div class="my-container">
        <table class="table">
            <thead>
              <tr class="text-center">
                <th scope="col" class="main-col" >Technical references:</th>
                <th scope="col" class="mid-col" >اللائحة الفنية للحواجز الأمامية والخلفية والجانبية للشاحنات والمقطورات</th>
                <th scope="col" class="main-col" >: المتطلبات الفنية</th>
              </tr>
            </thead>
        </table>
    </div>

    <h4 class="text-center">بيانات الشاحنة/المقطورة -Truck/trailer’s Information</h4>

    <div class="my-container">
        <table class="table">
            <thead>
              <tr class="text-center">
                <th scope="col" class="main-col" >Truck/trailer’s type:</th>
                <th scope="col" class="mid-col" >

                    {{ $item->product->model->name }} - {{ $item->product->model->type }}

                </th>
                <th scope="col" class="main-col" >طراز المقطورة/الشاحنة:</th>
              </tr>
            </thead>
        </table>
    </div>

    <div class="my-container">
        <table class="table">
            <thead>
              <tr class="text-center">
                <th scope="col" class="main-col" >Under-Run Type (Front, Side, Back):</th>
                <th scope="col" class="mid-col" >
                        {{ $item->product->hurdle->type }} - {{ $item->product->hurdle->slogan }}
                </th>
                <th scope="col" class="main-col" >نوع الحاجز (أمامي، جانبي، خلفي):</th>
              </tr>
            </thead>
        </table>
    </div>


    <div class="my-container">
        <table class="table">
            <thead>
              <tr class="text-center">
                <th scope="col" class="main-col" >Truck/trailer’s VIN:</th>
                <th scope="col" class="mid-col" >
                        {{ $item->vin }}
                </th>
                <th scope="col" class="main-col" >رقم هيكل الشاحنة/المقطورة (VIN):</th>
              </tr>
            </thead>
        </table>
    </div>


    <div class="my-container">


        <table class="table">
            <thead>
              <tr class="text-center">
                <th scope="col" class="main-col"  >Distinguished Under-Run Number:</th>
                <th scope="col" class="main-col" width="60">
                        <center>
                             <div class="sp-container">
                            @foreach ($theCode as $code)
                            <div class="splitter main-col sp m-splitter-head" style="width: {{ 100 / sizeof($theCode)  }}%;">
                                <span style="width:100%">{{ $loop->iteration }}
                                    </span></div>
                          @endforeach
                        </div>
                        <div class="sp-container">
                            @foreach ($theCode as $code)
                            <div class="splitter splitter-head sp" style="width: {{ 100 / sizeof($theCode)  }}%;"> <span style="width:100%">{{ $code }}</span></div>
                          @endforeach
                        </center>

                        </div>
                </th>
                <th scope="col" class="main-col" >الرقم المميز للحاجز:</th>
              </tr>
            </thead>
        </table>
    </div>

    <div class="my-container">
        <table class="table">
            <thead>
              <tr class="text-center">
                <th scope="col" class="main-col" >Manufacturer’s Stamp:</th>
                <th scope="col" class="mid-col" ></th>
                <th scope="col" class="main-col" >ختم المصنع/الورشة:</th>
              </tr>
            </thead>
        </table>
    </div>


    <div class="my-container">
        <table class="table">
            <thead>
              <tr class="text-center">
                <th scope="col" class="main-col" >Card’s issue date:</th>
                <th scope="col" class="mid-col" >{{ $selling->created_at->format('d/m/Y') }}</th>
                <th scope="col" class="main-col" >تاريخ إصدار البطاقة:</th>
              </tr>
            </thead>
        </table>
    </div>

    <footer></footer>


    @endforeach





    <script src="{{ asset('js/jquery-3.4.1.min.js') }}"  ></script>
    <script src="{{ asset('js/bootstrap.min.js') }}" ></script>




    <script>
        var all = 0;

        $(document).find('.splitter').each(function(){
            // var w = $(this).width();
            var w =this.clientWidth

            console.log(w)
            all += w;
        })
        $(document).find('.mid-col').width(all);

    </script>

</body>
</html>
