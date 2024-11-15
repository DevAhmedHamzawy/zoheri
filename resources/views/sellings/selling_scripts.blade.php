<script>


    $(document).on('change', '#model_id,#hurdle_id,#vehicle_id', function(){
        getProduct();
    })


    $(document).on('keyup', '#qty', function(){
        calculateTotalPrice()
    })

    $(document).on('change', '#discount_amount', function(){
        calculateTotalPrice()
    })

    function calculateTotalPrice(){

        let vehicle_id_ = $('#vehicle_id').val(),
            hurdle_id_ = $('#hurdle_id').val(),
            model_id_ = $('#model_id').val(),
            qty_ = $('#qty').val(),
            price_ = $('#unit_price').val()
            vehicle_id = Number(vehicle_id_),
            hurdle_id = Number(hurdle_id_),
            model_id = Number(model_id_),
            qty = Number(qty_),
            price = Number(price_);

        if(price > 0 && vehicle_id > 0 && hurdle_id > 0 && model_id > 0) {
            var total = price * qty;
            var discount_sort = $('#discount_sort :selected').val();
            var discount_amount = $('#discount_amount :selected').val();

            $('#price').val(total);

            discount_sort = Number(discount_sort)
            if(isNaN(discount_amount) || discount_amount == undefined){
                discount_amount = 0;
            }
            discount_amount = Number(discount_amount)

            console.log({discount_sort, discount_amount})

            if(discount_sort == 0) // نسبة
            {
                discount_amount = discount_amount / 100 * total;
            }
            total -= discount_amount;

            $('#price_after_discount').val(total);
        }
    }

    function getProduct() {
        let vehicle_id_ = $('#vehicle_id').val(),
            hurdle_id_ = $('#hurdle_id').val(),
            model_id_ = $('#model_id').val(),
            qty = $('#qty').val(),
            vehicle_id = Number(vehicle_id_),
            hurdle_id = Number(hurdle_id_),
            model_id = Number(model_id_),
            url = '{{ url('get_product_data') }}',
            body = {vehicle_id, hurdle_id, model_id, qty};

        if(vehicle_id > 0 && hurdle_id > 0 && model_id > 0){
            axios.get(url, { params:body })
                .then((data) => {

                    if (data.data == false) {
                        alert('لا يوجد .... الرجاء اضافة المنتج فى صفحة تسعير المنتجات');
                        return;
                    }

                    $('#price').empty();
                    $('#price').val(data.data.price_qty)
                    $('#unit_price').empty();
                    $('#unit_price').val(data.data.price)

                    calculateTotalPrice()

                })
        }

    }

    function getDiscountPercentage() {
        let discount_sort = $('#discount_sort').val();


        axios.get('{{ url('get_discount_amounts') }}' + '/' + discount_sort)
            .then((data) => {

                if (data.data == false) {
                    alert('لا يوجد ..... الرجاء اضافة الخصم فى صفحة الخصومات');
                    return;
                }

                $('#discount_amount').empty();
                $('#discount_amount').append('<option value="0">نسبة الخصم</option>')
                for (d of data.data) {
                    $('#discount_amount').append('<option value="'+ d.amount +'">' + d.amount + '</option>')
                }

                calculateTotalPrice()
            })
    }


    // index for table
    var index = 0;

    function addItem() {

        if($('#vehicle_id :selected').val() == 'اسم المركبة') { alert('برجاء اختيار المركبة'); return;  }
        if($('#hurdle_id :selected').val() == 'اسم الحاجز') { alert('برجاء اختيار الحاجز'); return;  }
        if($('#model_id :selected').val() == 'اسم الطراز') { alert('برجاء اختيار الطراز'); return;  }
        if($('#vin').val() == '') { alert('برجاء كتابة رقم الشاسيه'); return;  }

        // increment index for table
        index++;

        var vehicle_id = $('#vehicle_id :selected').val();
        var hurdle_id = $('#hurdle_id :selected').val();
        var model_id = $('#model_id :selected').val();
        var vin = $('#vin').val();
        var unit_price = $('#unit_price').val();
        var qty = $('#qty').val();
        var discount_sort = $('#discount_sort :selected').text();
        var discount_amount = $('#discount_amount :selected').text();
        var price_after_discount = $('#price_after_discount').val();

        // check if this no discount then assign price_after_discount to regular price
        if ($('#discount_amount :selected').val() == 0) discount_amount = 0
        if (price_after_discount == '') price_after_discount = $('#price').val()

        // check discount_sort
        discount_sort == 'نسبة' ? discount_amount += "%" : discount_amount += "ر.س"


        $('.table tbody').append('<input type="hidden" name="vins[]" id="vin_' + index + '" value="' + vin + '" >');
        $('.table tbody').append('<input type="hidden" name="unit_prices[]" id="unit_price_' + index + '" value="' + unit_price + '" >');
        $('.table tbody').append('<input type="hidden" name="discount_sorts[]" id="discount_sort_' + index + '" value="' + discount_sort + '" >');
        $('.table tbody').append('<input type="hidden" name="discount_amounts[]" id="discount_amount_' + index + '" value="' + discount_amount + '" >');
        $('.table tbody').append('<input type="hidden" name="prices_after_discount[]" id="price_after_discount_' + index + '"  value="' + price_after_discount + '" >');
        $('.table tbody').append('<input type="hidden" name="qtys[]" id="qty_' + index + '"  value="' + qty + '" >');


        axios.get('{{ url('get_product_code') }}' + '/' + vehicle_id + '/' + hurdle_id + '/' + model_id)
            .then((data) => {

                $('.table tbody').append('<input type="hidden" name="product_codes[]" id="product_code_' + index + '" value="' + data.data['product_code'] + '" >');
                $('.table tbody').append('<input type="hidden" name="product_ids[]" id="product_id_' + index + '" value="' + data.data['product_id'] + '" >');


                var vatToPay = parseFloat((price_after_discount / 100) * $('#settings_vat').val()).toPrecision(5);
                var totalPrice = parseFloat(price_after_discount) + parseFloat(vatToPay);
                $('.table tbody').append('<input type="hidden" name="vats_to_pay[]" id="vat_to_pay_' + index + '" value="' + vatToPay + '" >');
                $('.table tbody').append('<input type="hidden" name="total_prices[]" id="total_price_' + index + '" value="' + totalPrice + '" >');

                $('.table tbody').prepend('<tr id="r' + index + '"><td>1</td><td>' + data.data['product_code'] + '</td><td>' + unit_price + '</td><td>' + qty + '</td><td>' + discount_amount + '</td><td class="prices_after_discount" id="price_after_discount_' + index + '">' + price_after_discount + '</td><td class="vat_values" id="vat_value_' + index + '">' + vatToPay + '</td><td class="total_prices" id="total_price_' + index + '">' + totalPrice + '<div class="btn btn-danger" onclick="delete_item(' + index + ')">حذف</div></td></tr>')

                // add index number to items
                $(".table tr").each(function () {
                    $(this).find("td").first().html($(this).index() + 1);
                });
                $('.total_data').each(function () {
                    $(this).find("td").first().html('');
                });

                getPricesAfterDiscount();

                getVatValues();

                getTotalPrices();

            })

        $('#vehicle_id').val('اسم المركبة');
        $('#hurdle_id').val('اسم الحاجز');
        $('#model_id').val('اسم الطراز');
        $('#vin').val('');
        $('#qty').val('');
        $('#price').val('');
        $('#unit_price').val('');
        $('#discount_sort').val(-1);
        $('#discount_amount').val(0);
        $('#price_after_discount').val('');


    }


    function delete_item(item) {
        var price_after_discount = $('#price_after_discount_' + item).text();
        var vat_value = $('#vat_value_' + item).text();
        var total_price = parseFloat($('#total_price_' + item).text())

        $('#subtotal').text($('#subtotal').text() - price_after_discount);
        $('#tax').text($('#tax').text() - vat_value);
        $('#total').text($('#total').text() - total_price);

        $('.table tbody').append('<input type="hidden" name="subtotal" value="' + $('#subtotal').text() + '" >');
        $('.table tbody').append('<input type="hidden" name="vat" value="' + $('#tax').text() + '" >');
        $('.table tbody').append('<input type="hidden" name="total" value="' + $('#total').text() + '" >');


        $('#r' + item).remove();
        $('#vin_' + item).remove();
        $('#unit_price_' + item).remove();
        $('#discount_sort_' + item).remove();
        $('#discount_amount_' + item).remove();
        $('#price_after_discount_' + item).remove();
        $('#qty_' + item).remove()
        $('#product_code_' + item).remove()
        $('#product_id_' + item).remove()
        $('#vat_to_pay_' + item).remove()
        $('#total_price_' + item).remove()
    }


    // Get Prices After Discount
    function getPricesAfterDiscount() {
        // prices after discount
        var sum = 0;
        $('.prices_after_discount').each(function () {
            sum += parseFloat($(this).text());
        });

        $('#subtotal').text(sum);
        $('.table tbody').append('<input type="hidden" name="subtotal" value="' + sum + '" >');
    }


    // Get Vat Values
    function getVatValues() {
        // vat values
        var sum = 0;
        $('.vat_values').each(function () {
            sum += parseFloat($(this).text());
        });

        $('#tax').text(sum);
        $('.table tbody').append('<input type="hidden" name="vat" value="' + sum + '" >');
    }


    // Get Total Prices
    function getTotalPrices() {
        // total prices
        var sum = 0;
        $('.total_prices').each(function () {
            sum += parseFloat($(this).text());
        });

        $('#total').text(sum);
        $('.table tbody').append('<input type="hidden" name="total" value="' + sum + '" >');
    }


</script>
