<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بطاقة الرقم المميز للحاجز</title>
    <link rel="stylesheet" href="https://cdn.rtlcss.com/bootstrap/v4.5.3/css/bootstrap.min.css" integrity="sha384-JvExCACAZcHNJEc7156QaHXTnQL3hQBixvj5RV5buE7vgnNEzzskDtx9NQ4p6BJe" crossorigin="anonymous">
    <style>
        @media print {
            footer {page-break-after: always;}
        }
    </style>
</head>
<body>



    @foreach ($selling->items as $item)

    @php $theCode = str_split($item->product_code); $theCode = array_filter($theCode, fn($value) => !is_null($value) && $value !== '' && $value !== ' ');  @endphp


    <h1 class="text-center">بطاقة الرقم المميز للحاجز</h1>
    <h2 class="text-center">UniqueUnder-run Device Card</h2>
    <h3 class="text-center">معلومات المصنّع /Manufacturer’s Information</h3>

    <div class="container">
        <table class="table">
            <thead>
              <tr class="text-center">
                <th scope="col" style="background-color: rgb(191,191,191); color:#fff; width: 20%;">Manufacturer’s name:</th>
                <th scope="col" style="background-color: rgb(232,232,232); color:#000;">شركة سليمان الزهيري المحدودة</th>
                <th scope="col" style="background-color: rgb(191,191,191); color:#fff; width: 20%;"> : اسم المصنع/الورشة</th>
              </tr>
            </thead>
        </table>
    </div>


    <div class="container">
        <table class="table">
            <thead>
              <tr class="text-center">
                <th scope="col" style="background-color: rgb(191,191,191); color:#fff; width: 20%;">: Manufacturer Assigned Code (AXX)</th>
                <th scope="col" style="background-color: rgb(232,232,232); color:#000;">A12</th>
                <th scope="col" style="background-color: rgb(191,191,191); color:#fff; width: 20%;">: رمز المنشأة (AXX)</th>
              </tr>
            </thead>
        </table>
    </div>


    <div class="container">
        <table class="table">
            <thead>
              <tr class="text-center">
                <th scope="col" style="background-color: rgb(191,191,191); color:#fff; width: 20%;">Country of origin:</th>
                <th scope="col" style="background-color: rgb(232,232,232); color:#000;">المملكة العربية السعودية</th>
                <th scope="col" style="background-color: rgb(191,191,191); color:#fff; width: 20%;">: بلد المنشأ</th>
              </tr>
            </thead>
        </table>
    </div>


    <div class="container">
        <table class="table">
            <thead>
              <tr class="text-center">
                <th scope="col" style="background-color: rgb(191,191,191); color:#fff; width: 20%;">Date of manufacture:</th>
                <th scope="col" style="background-color: rgb(232,232,232); color:#000;">2011</th>
                <th scope="col" style="background-color: rgb(191,191,191); color:#fff; width: 20%;">: تاريخ الصنع</th>
              </tr>
            </thead>
        </table>
    </div>


    <div class="container">
        <table class="table">
            <thead>
              <tr class="text-center">
                <th scope="col" style="background-color: rgb(191,191,191); color:#fff; width: 20%;">Technical references:</th>
                <th scope="col" style="background-color: rgb(232,232,232); color:#000;">اللائحة الفنية للحواجز الأمامية والخلفية والجانبية للشاحنات والمقطورات</th>
                <th scope="col" style="background-color: rgb(191,191,191); color:#fff; width: 20%;">: المتطلبات الفنية</th>
              </tr>
            </thead>
        </table>
    </div>

    <h4 class="text-center">بيانات الشاحنة/المقطورة -Truck/trailer’s Information</h4>

    <div class="container">
        <table class="table">
            <thead>
              <tr class="text-center">
                <th scope="col" style="background-color: rgb(191,191,191); color:#fff; width: 20%;">Truck/trailer’s type:</th>
                <th scope="col" style="background-color: rgb(232,232,232); color:#000;">

                    {{ $item->product->model->name }} - {{ $item->product->model->type }}

                </th>
                <th scope="col" style="background-color: rgb(191,191,191); color:#fff; width: 20%;">طراز المقطورة/الشاحنة:</th>
              </tr>
            </thead>
        </table>
    </div>

    <div class="container">
        <table class="table">
            <thead>
              <tr class="text-center">
                <th scope="col" style="background-color: rgb(191,191,191); color:#fff; width: 20%;">Under-Run Type (Front, Side, Back):</th>
                <th scope="col" style="background-color: rgb(232,232,232); color:#000;">
                        {{ $item->product->hurdle->type }} - {{ $item->product->hurdle->slogan }}
                </th>
                <th scope="col" style="background-color: rgb(191,191,191); color:#fff; width: 20%;">نوع الحاجز (أمامي، جانبي، خلفي):</th>
              </tr>
            </thead>
        </table>
    </div>


    <div class="container">
        <table class="table">
            <thead>
              <tr class="text-center">
                <th scope="col" style="background-color: rgb(191,191,191); color:#fff; width: 20%;">Truck/trailer’s VIN:</th>
                <th scope="col" style="background-color: rgb(232,232,232); color:#000;">
                        {{ $item->vin }}
                </th>
                <th scope="col" style="background-color: rgb(191,191,191); color:#fff; width: 20%;">رقم هيكل الشاحنة/المقطورة (VIN):</th>
              </tr>
            </thead>
        </table>
    </div>


    <div class="container">
        <table class="table">
            <thead>
              <tr class="text-center">
                <th scope="col" style="background-color: rgb(191,191,191); color:#fff; width: 20%;">Distinguished Under-Run Number:</th>
                <th scope="col" style="background-color: rgb(232,232,232); color:#000;">


                        <table>
                            <thead>
                                <tr>
                                    <th></th>
                                    @foreach ($theCode as $code)
                                      <th>{{ $loop->iteration }}</th>
                                    @endforeach
                                    <th></th>
                                </tr>

                            </thead>
                            <tbody>
                                <tr>
                                    <th></th>
                                    @foreach ($theCode as $code)
                                        <th>{{ $code }}</th>
                                    @endforeach
                                    <th></th>
                                </tr>
                            </tbody>
                        </table>

                </th>
                <th scope="col" style="background-color: rgb(191,191,191); color:#fff; width: 20%;">الرقم المميز للحاجز:</th>
              </tr>
            </thead>
        </table>
    </div>

    <div class="container">
        <table class="table">
            <thead>
              <tr class="text-center">
                <th scope="col" style="background-color: rgb(191,191,191); color:#fff; width: 20%;">Manufacturer’s Stamp:</th>
                <th scope="col" style="background-color: rgb(232,232,232); color:#000;"></th>
                <th scope="col" style="background-color: rgb(191,191,191); color:#fff; width: 20%;">ختم المصنع/الورشة:</th>
              </tr>
            </thead>
        </table>
    </div>


    <div class="container">
        <table class="table">
            <thead>
              <tr class="text-center">
                <th scope="col" style="background-color: rgb(191,191,191); color:#fff; width: 20%;">Card’s issue date:</th>
                <th scope="col" style="background-color: rgb(232,232,232); color:#000;">{{ $selling->created_at->format('d/m/Y') }}</th>
                <th scope="col" style="background-color: rgb(191,191,191); color:#fff; width: 20%;">تاريخ إصدار البطاقة:</th>
              </tr>
            </thead>
        </table>
    </div>

    <footer></footer>


    @endforeach





    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3h56lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.rtlcss.com/bootstrap/v4.5.3/js/bootstrap.min.js" integrity="sha384-VmD+lKnI0Y4FPvr6hvZRw6xvdt/QZoNHQ4h5k0RL30aGkR9ylHU56BzrE2UoohWK" crossorigin="anonymous"></script>
</body>
</html>
