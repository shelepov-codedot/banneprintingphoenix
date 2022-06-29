jQuery(document).ready(function($) {
    let status_shipping_address = false;
    let taxJson = null;

    if (location.hash == '#step-2' && location.pathname.includes('/checkout/')) {
        let dataArr = localStorage.getItem('dataArr')
        $('.checkout__inputs.checkout__step-two input[name="dataArr"]').val(dataArr)
        $('#checkout').removeClass('d-flex')
        $('.checkout__step-two').addClass('d-flex')
        $('.shipping_method_js .total__point[value="'+localStorage.getItem('shipping_method')+'"]').click()
        let billing_name = $('input[name="billing_first_name"]').val()+' '+$('input[name="billing_last_name"]').val().substr(0, 1)
        let billingaddress2 = $('input[name="billing_address_2"]').val()
        let billing_address = $('input[name="billing_address_1"]').val()+', ';
        billing_address += (billingaddress2) ? billingaddress2 +', ' : '';
        billing_address += $('input[name="billing_city"]').val()+', '+$('select[name="billing_state"]').val()+', '+$('input[name="billing_postcode"]').val()

        let shipping_name = $('input[name="shipping_first_name"]').val()+' '+$('input[name="shipping_last_name"]').val().substr(0, 1)
        let shippingaddress2 = $('input[name="shipping_address_2"]').val()
        let shipping_address = $('input[name="shipping_address_1"]').val()+', ';
        shipping_address += (shippingaddress2) ? shippingaddress2 +', ' : '';
        shipping_address += $('input[name="shipping_city"]').val()+', '+$('select[name="shipping_state"]').val()+', '+$('input[name="shipping_postcode"]').val()
        let shipping_email = $('input[name="billing_email"]').val()

        let text_shipping_title = '1. Shipping'
        if (!status_shipping_address) {
            text_shipping_title += ' and Billing';
        } else {
            $('.checkout__text.shipping_title_two').closest('.checkout__step-header').css('display', 'block');
        }
        text_shipping_title += ' Address';
        $('.checkout__text.shipping_title_one').html(text_shipping_title)

        $('.checkout__inputs.checkout__step-two .shipping .checkout__name').html(shipping_name)
        $('.checkout__inputs.checkout__step-two .shipping .checkout__address').html(shipping_address)
        $('.checkout__inputs.checkout__step-two .shipping .checkout__email').html(shipping_email)
        $('.checkout__inputs.checkout__step-tree .shipping .checkout__name').html(shipping_name)
        $('.checkout__inputs.checkout__step-tree .shipping .checkout__address').html(shipping_address)
        $('.checkout__inputs.checkout__step-tree .shipping .checkout__email').html(shipping_email)

        $('.checkout__inputs.checkout__step-two .shipping').css('display', 'block')
        $('.checkout__inputs.checkout__step-tree .shipping').css('display', 'block')

        $('.checkout__inputs.checkout__step-two .billing .checkout__name').html(billing_name)
        $('.checkout__inputs.checkout__step-two .billing .checkout__address').html(billing_address)
        $('.checkout__inputs.checkout__step-tree .billing .checkout__name').html(billing_name)
        $('.checkout__inputs.checkout__step-tree .billing .checkout__address').html(billing_address)

        $('.checkout__inputs.checkout__step-two input[name="dataArr"]').val(localStorage.getItem('dataArr'))
    }

    if (localStorage.getItem('step') && location.pathname.includes('/checkout/')) {
        if (localStorage.getItem('step') == 2) {
            $('#checkout').removeClass('d-flex')
            $('.checkout__step-two').addClass('d-flex')

            $('.shipping_method_js .total__point[value="'+localStorage.getItem('shipping_method')+'"]').click()

            location.href = '#step-2'
        } else if (localStorage.getItem('step') == 3) {
            $('#checkout').removeClass('d-flex');
            $('.checkout__step-two').removeClass('d-flex')
            $('.shipping_method_js .total__point[value="'+localStorage.getItem('shipping_method')+'"]').click()
            $('.checkout__step-tree').addClass('d-flex')
            $('.checkout__inputs.checkout__step-tree .total__prioity-title').html($('.shipping_method_js .total__point[value="'+localStorage.getItem('shipping_method')+'"]').next().find('.total__prioity-title').text())
            location.href = '#step-3'
        }

        let billing_name = $('input[name="billing_first_name"]').val()+' '+$('input[name="billing_last_name"]').val().substr(0, 1)
        let billingaddress2 = $('input[name="billing_address_2"]').val()
        let billing_address = $('input[name="billing_address_1"]').val()+', ';
        billing_address += (billingaddress2) ? billingaddress2 +', ' : '';
        billing_address += $('input[name="billing_city"]').val()+', '+$('select[name="billing_state"]').val()+', '+$('input[name="billing_postcode"]').val()

        let shipping_name = $('input[name="shipping_first_name"]').val()+' '+$('input[name="shipping_last_name"]').val().substr(0, 1)
        let shippingaddress2 = $('input[name="shipping_address_2"]').val()
        let shipping_address = $('input[name="shipping_address_1"]').val()+', ';
        shipping_address += (shippingaddress2) ? shippingaddress2 +', ' : '';
        shipping_address += $('input[name="shipping_city"]').val()+', '+$('select[name="shipping_state"]').val()+', '+$('input[name="shipping_postcode"]').val()
        let shipping_email = $('input[name="billing_email"]').val()

        let text_shipping_title = '1. Shipping'
        if (!status_shipping_address) {
            text_shipping_title += ' and Billing';
        } else {
            $('.checkout__text.shipping_title_two').closest('.checkout__step-header').css('display', 'block');
        }
        text_shipping_title += ' Address';
        $('.checkout__text.shipping_title_one').html(text_shipping_title)

        $('.checkout__inputs.checkout__step-two .shipping .checkout__name').html(shipping_name)
        $('.checkout__inputs.checkout__step-two .shipping .checkout__address').html(shipping_address)
        $('.checkout__inputs.checkout__step-two .shipping .checkout__email').html(shipping_email)
        $('.checkout__inputs.checkout__step-tree .shipping .checkout__name').html(shipping_name)
        $('.checkout__inputs.checkout__step-tree .shipping .checkout__address').html(shipping_address)
        $('.checkout__inputs.checkout__step-tree .shipping .checkout__email').html(shipping_email)

        $('.checkout__inputs.checkout__step-two .shipping').css('display', 'block')
        $('.checkout__inputs.checkout__step-tree .shipping').css('display', 'block')

        $('.checkout__inputs.checkout__step-two .billing .checkout__name').html(billing_name)
        $('.checkout__inputs.checkout__step-two .billing .checkout__address').html(billing_address)
        $('.checkout__inputs.checkout__step-tree .billing .checkout__name').html(billing_name)
        $('.checkout__inputs.checkout__step-tree .billing .checkout__address').html(billing_address)

        $('.checkout__inputs.checkout__step-two input[name="dataArr"]').val(localStorage.getItem('dataArr'))
    }

    croshka()

    $('div.total__box div.total__apply').click(function () {
        let coupon_code = $('div.total__box input.total__discount').val()

        set_coupon(coupon_code);
    });

    $('div.total-checkout__box button.total-checkout__apply').click(function () {
        let coupon_code = $('div.total-checkout__box input.total-checkout__discount').val()

        set_coupon(coupon_code, '.total-checkout__price-small');
    });

    function set_coupon(coupon = '', total = 'span.total__sum') {
        let data = {};
        let url = '/';
        if (typeof wc_cart_params != 'undefined') {
            data = {
                security: wc_cart_params.apply_coupon_nonce,
                coupon_code: coupon
            };

            url = wc_cart_params['ajax_url']+'/?action=get_cart_total';
        } else {
            data = {
                security: wc_checkout_params.apply_coupon_nonce,
                coupon_code: coupon
            };

            url = wc_checkout_params['ajax_url']+'/?action=get_cart_total';
        }

        $.ajax( {
            type: 'POST',
            url: '/?wc-ajax=apply_coupon',
            data: data,
            dataType: 'html',
            success: function( response ) {
                // console.log(response)
                $.ajax({
                    type: 'POST',
                    url: url,
                    success: function(data) {
                        $(total).text('$'+data)
                    }
                });
            },
            complete: function() {
                console.log('Загрузка')
            }
        });
    }

    $('.total__button').on('click', function (e) {
        let customer_note = $("textarea[name='cart-customer-note']" ).val()
        let mobile_customer_note = $("textarea[name='cart-mobile-customer-note']" ).val()
        if (customer_note.length > 0) {
            localStorage.setItem('customer-note', customer_note)
        } else if (mobile_customer_note.length > 0) {
            localStorage.setItem('customer-note', mobile_customer_note)
        }
    })

    if (localStorage.getItem('customer-note')) {
        $("textarea[name='checkout-customer-note']" ).val(localStorage.getItem('customer-note'))
        $("textarea[name='checkout-mobile-customer-note']" ).val(localStorage.getItem('customer-note'))
    }

    $("textarea[name='checkout-mobile-customer-note']" ).on('change', function () {
        console.log(localStorage.getItem('customer-note'))
    })

    $('.checkout__inputs').on('click', '.checkout__button', function (){
        let box = $(this).closest('.checkout__inputs');
        $('.checkout__field').removeClass("checkout__field-error");
        $('.checkout__field-error-message').remove();
        box.removeClass('d-flex')
        if (!box.hasClass('checkout__step-two') && !box.hasClass('checkout__step-tree')) {

            // console.log(box.find('input, select'))
            let inputs = box.find('input, select');
            console.log(inputs)

            let data = {}
            let post_data = {}
            let errorStatus = false;
            let errorInfo = [];
            for (let i=0; i<inputs.length; i++) {
                let input = $(inputs[i]);
                let key = input.attr('name');
                let requered = input.data('required');
                let error = input.data('error');
                let pravilo = input.data('prav');

                if (input.attr('name').includes('shipping_') && !status_shipping_address) {
                    let name_billing = input.attr('name').replace('shipping_', 'billing_')
                    if ($('[name="'+name_billing+'"]') && input.val() !== $('[name="'+name_billing+'"]').val()) {
                        $('[name="'+name_billing+'"]').val(input.val())
                        $('select#'+name_billing+' option[value="'+input.val()+'"]').attr('selected', true)

                        if (name_billing == 'billing_country') {
                            $('select#'+name_billing).change()
                        }
                        if (name_billing == 'billing_state') {
                            $('select#'+name_billing).change()
                        }
                    }
                }

                if (requered) {
                    let prav = true
                    if (pravilo) {
                        let tes = new RegExp(pravilo);
                        prav = tes.test(input.val())
                    }

                    if (!prav || !input.val().length) {
                        errorStatus = true;
                        errorInfo[key] = error;
                    }
                }

                post_data[key] = input.val();
                if (input.attr('name') == 'shipping_country') {
                    data['s_country'] = input.val();
                }
                if (input.attr('name') == 'shipping_state') {
                    if (input.val() != 'billiNGCity') {
                        val = $('[name="shipping_state"]').val()
                    } else {
                        val = input.val();
                    }
                    data['s_state'] = val;
                }
                if (input.attr('name') == 'shipping_postcode') {
                    data['s_postcode'] = input.val();
                }
                if (input.attr('name') == 'shipping_address_1') {
                    data['s_address'] = input.val();
                }
                if (input.attr('name') == 'shipping_address_2') {
                    data['s_address_2'] = input.val();
                }
                if (input.attr('name') == 'shipping_city') {
                    data['s_city'] = input.val();
                }
            }

            if (errorStatus) {
                console.log(errorInfo)
                for (var key in errorInfo) {
                    if (key.includes('billing_') && !status_shipping_address) {
                        $('input[name="checked_billing_address"][value="on"]').click()
                    }
                    $(`#${key}`).addClass("checkout__field-error")
                    $(`#${key}`).closest(".checkout__field-wrap").append(`<span class="checkout__field-error-message">${errorInfo[key]} </span>`)
                    $(`#${key}`).closest(".checkout__field-mail").append(`<span class="checkout__field-error-message">${errorInfo[key]} </span>`)
                }
                $(box[0]).addClass('d-flex')
                return false;
            }

            data['has_full_address'] = true
            data['security'] = wc_checkout_params.update_order_review_nonce

            post_data['billing_company'] = post_data['shipping_company'] = post_data['order_comments'] = ''
            post_data['woocommerce-process-checkout-nonce'] = wc_checkout_params.apply_coupon_nonce
            post_data['_wp_http_referer'] = '/?wc-ajax=update_order_review'

            post_data_keys = Object.keys(post_data)
            let postData = post_data_keys[0]+'='+post_data[post_data_keys[0]]
            for (var i = 1, l = post_data_keys.length; i < l; i++) {
                postData += '&'+post_data_keys[i]+'='+post_data[post_data_keys[i]]
            }
            data['post_data'] = postData

            console.error('===')
            console.log(post_data)
            console.log(data)
            console.error('===')

            // return false;
            console.warn('ajax запрос!')
            $.ajax( {
                type: 'POST',
                url: '/?wc-ajax=update_order_review',
                data: data,
                dataType: 'json',
                beforeSend: function () {
                    location.hash = '#step-2'
                    croshka()
                    $('.checkout__inputs.checkout__step-two').addClass('d-flex')

                    let html = '<style>\n' +
                        '.windows8 {\n' +
                        '\tposition: relative;\n' +
                        '\twidth: 78px;\n' +
                        '\theight:78px;\n' +
                        '\tmargin:auto;\n' +
                        '}\n' +
                        '\n' +
                        '.windows8 .wBall {\n' +
                        '\tposition: absolute;\n' +
                        '\twidth: 74px;\n' +
                        '\theight: 74px;\n' +
                        '\topacity: 0;\n' +
                        '\ttransform: rotate(225deg);\n' +
                        '\t\t-o-transform: rotate(225deg);\n' +
                        '\t\t-ms-transform: rotate(225deg);\n' +
                        '\t\t-webkit-transform: rotate(225deg);\n' +
                        '\t\t-moz-transform: rotate(225deg);\n' +
                        '\tanimation: orbit 6.96s infinite;\n' +
                        '\t\t-o-animation: orbit 6.96s infinite;\n' +
                        '\t\t-ms-animation: orbit 6.96s infinite;\n' +
                        '\t\t-webkit-animation: orbit 6.96s infinite;\n' +
                        '\t\t-moz-animation: orbit 6.96s infinite;\n' +
                        '}\n' +
                        '\n' +
                        '.windows8 .wBall .wInnerBall{\n' +
                        '\tposition: absolute;\n' +
                        '\twidth: 10px;\n' +
                        '\theight: 10px;\n' +
                        '\tbackground: rgb(0,0,0);\n' +
                        '\tleft:0px;\n' +
                        '\ttop:0px;\n' +
                        '\tborder-radius: 10px;\n' +
                        '}\n' +
                        '\n' +
                        '.windows8 #wBall_1 {\n' +
                        '\tanimation-delay: 1.52s;\n' +
                        '\t\t-o-animation-delay: 1.52s;\n' +
                        '\t\t-ms-animation-delay: 1.52s;\n' +
                        '\t\t-webkit-animation-delay: 1.52s;\n' +
                        '\t\t-moz-animation-delay: 1.52s;\n' +
                        '}\n' +
                        '\n' +
                        '.windows8 #wBall_2 {\n' +
                        '\tanimation-delay: 0.3s;\n' +
                        '\t\t-o-animation-delay: 0.3s;\n' +
                        '\t\t-ms-animation-delay: 0.3s;\n' +
                        '\t\t-webkit-animation-delay: 0.3s;\n' +
                        '\t\t-moz-animation-delay: 0.3s;\n' +
                        '}\n' +
                        '\n' +
                        '.windows8 #wBall_3 {\n' +
                        '\tanimation-delay: 0.61s;\n' +
                        '\t\t-o-animation-delay: 0.61s;\n' +
                        '\t\t-ms-animation-delay: 0.61s;\n' +
                        '\t\t-webkit-animation-delay: 0.61s;\n' +
                        '\t\t-moz-animation-delay: 0.61s;\n' +
                        '}\n' +
                        '\n' +
                        '.windows8 #wBall_4 {\n' +
                        '\tanimation-delay: 0.91s;\n' +
                        '\t\t-o-animation-delay: 0.91s;\n' +
                        '\t\t-ms-animation-delay: 0.91s;\n' +
                        '\t\t-webkit-animation-delay: 0.91s;\n' +
                        '\t\t-moz-animation-delay: 0.91s;\n' +
                        '}\n' +
                        '\n' +
                        '.windows8 #wBall_5 {\n' +
                        '\tanimation-delay: 1.22s;\n' +
                        '\t\t-o-animation-delay: 1.22s;\n' +
                        '\t\t-ms-animation-delay: 1.22s;\n' +
                        '\t\t-webkit-animation-delay: 1.22s;\n' +
                        '\t\t-moz-animation-delay: 1.22s;\n' +
                        '}\n' +
                        '\n' +
                        '\n' +
                        '\n' +
                        '@keyframes orbit {\n' +
                        '\t0% {\n' +
                        '\t\topacity: 1;\n' +
                        '\t\tz-index:99;\n' +
                        '\t\ttransform: rotate(180deg);\n' +
                        '\t\tanimation-timing-function: ease-out;\n' +
                        '\t}\n' +
                        '\n' +
                        '\t7% {\n' +
                        '\t\topacity: 1;\n' +
                        '\t\ttransform: rotate(300deg);\n' +
                        '\t\tanimation-timing-function: linear;\n' +
                        '\t\torigin:0%;\n' +
                        '\t}\n' +
                        '\n' +
                        '\t30% {\n' +
                        '\t\topacity: 1;\n' +
                        '\t\ttransform:rotate(410deg);\n' +
                        '\t\tanimation-timing-function: ease-in-out;\n' +
                        '\t\torigin:7%;\n' +
                        '\t}\n' +
                        '\n' +
                        '\t39% {\n' +
                        '\t\topacity: 1;\n' +
                        '\t\ttransform: rotate(645deg);\n' +
                        '\t\tanimation-timing-function: linear;\n' +
                        '\t\torigin:30%;\n' +
                        '\t}\n' +
                        '\n' +
                        '\t70% {\n' +
                        '\t\topacity: 1;\n' +
                        '\t\ttransform: rotate(770deg);\n' +
                        '\t\tanimation-timing-function: ease-out;\n' +
                        '\t\torigin:39%;\n' +
                        '\t}\n' +
                        '\n' +
                        '\t75% {\n' +
                        '\t\topacity: 1;\n' +
                        '\t\ttransform: rotate(900deg);\n' +
                        '\t\tanimation-timing-function: ease-out;\n' +
                        '\t\torigin:70%;\n' +
                        '\t}\n' +
                        '\n' +
                        '\t76% {\n' +
                        '\topacity: 0;\n' +
                        '\t\ttransform:rotate(900deg);\n' +
                        '\t}\n' +
                        '\n' +
                        '\t100% {\n' +
                        '\topacity: 0;\n' +
                        '\t\ttransform: rotate(900deg);\n' +
                        '\t}\n' +
                        '}\n' +
                        '\n' +
                        '@-o-keyframes orbit {\n' +
                        '\t0% {\n' +
                        '\t\topacity: 1;\n' +
                        '\t\tz-index:99;\n' +
                        '\t\t-o-transform: rotate(180deg);\n' +
                        '\t\t-o-animation-timing-function: ease-out;\n' +
                        '\t}\n' +
                        '\n' +
                        '\t7% {\n' +
                        '\t\topacity: 1;\n' +
                        '\t\t-o-transform: rotate(300deg);\n' +
                        '\t\t-o-animation-timing-function: linear;\n' +
                        '\t\t-o-origin:0%;\n' +
                        '\t}\n' +
                        '\n' +
                        '\t30% {\n' +
                        '\t\topacity: 1;\n' +
                        '\t\t-o-transform:rotate(410deg);\n' +
                        '\t\t-o-animation-timing-function: ease-in-out;\n' +
                        '\t\t-o-origin:7%;\n' +
                        '\t}\n' +
                        '\n' +
                        '\t39% {\n' +
                        '\t\topacity: 1;\n' +
                        '\t\t-o-transform: rotate(645deg);\n' +
                        '\t\t-o-animation-timing-function: linear;\n' +
                        '\t\t-o-origin:30%;\n' +
                        '\t}\n' +
                        '\n' +
                        '\t70% {\n' +
                        '\t\topacity: 1;\n' +
                        '\t\t-o-transform: rotate(770deg);\n' +
                        '\t\t-o-animation-timing-function: ease-out;\n' +
                        '\t\t-o-origin:39%;\n' +
                        '\t}\n' +
                        '\n' +
                        '\t75% {\n' +
                        '\t\topacity: 1;\n' +
                        '\t\t-o-transform: rotate(900deg);\n' +
                        '\t\t-o-animation-timing-function: ease-out;\n' +
                        '\t\t-o-origin:70%;\n' +
                        '\t}\n' +
                        '\n' +
                        '\t76% {\n' +
                        '\topacity: 0;\n' +
                        '\t\t-o-transform:rotate(900deg);\n' +
                        '\t}\n' +
                        '\n' +
                        '\t100% {\n' +
                        '\topacity: 0;\n' +
                        '\t\t-o-transform: rotate(900deg);\n' +
                        '\t}\n' +
                        '}\n' +
                        '\n' +
                        '@-ms-keyframes orbit {\n' +
                        '\t0% {\n' +
                        '\t\topacity: 1;\n' +
                        '\t\tz-index:99;\n' +
                        '\t\t-ms-transform: rotate(180deg);\n' +
                        '\t\t-ms-animation-timing-function: ease-out;\n' +
                        '\t}\n' +
                        '\n' +
                        '\t7% {\n' +
                        '\t\topacity: 1;\n' +
                        '\t\t-ms-transform: rotate(300deg);\n' +
                        '\t\t-ms-animation-timing-function: linear;\n' +
                        '\t\t-ms-origin:0%;\n' +
                        '\t}\n' +
                        '\n' +
                        '\t30% {\n' +
                        '\t\topacity: 1;\n' +
                        '\t\t-ms-transform:rotate(410deg);\n' +
                        '\t\t-ms-animation-timing-function: ease-in-out;\n' +
                        '\t\t-ms-origin:7%;\n' +
                        '\t}\n' +
                        '\n' +
                        '\t39% {\n' +
                        '\t\topacity: 1;\n' +
                        '\t\t-ms-transform: rotate(645deg);\n' +
                        '\t\t-ms-animation-timing-function: linear;\n' +
                        '\t\t-ms-origin:30%;\n' +
                        '\t}\n' +
                        '\n' +
                        '\t70% {\n' +
                        '\t\topacity: 1;\n' +
                        '\t\t-ms-transform: rotate(770deg);\n' +
                        '\t\t-ms-animation-timing-function: ease-out;\n' +
                        '\t\t-ms-origin:39%;\n' +
                        '\t}\n' +
                        '\n' +
                        '\t75% {\n' +
                        '\t\topacity: 1;\n' +
                        '\t\t-ms-transform: rotate(900deg);\n' +
                        '\t\t-ms-animation-timing-function: ease-out;\n' +
                        '\t\t-ms-origin:70%;\n' +
                        '\t}\n' +
                        '\n' +
                        '\t76% {\n' +
                        '\topacity: 0;\n' +
                        '\t\t-ms-transform:rotate(900deg);\n' +
                        '\t}\n' +
                        '\n' +
                        '\t100% {\n' +
                        '\topacity: 0;\n' +
                        '\t\t-ms-transform: rotate(900deg);\n' +
                        '\t}\n' +
                        '}\n' +
                        '\n' +
                        '@-webkit-keyframes orbit {\n' +
                        '\t0% {\n' +
                        '\t\topacity: 1;\n' +
                        '\t\tz-index:99;\n' +
                        '\t\t-webkit-transform: rotate(180deg);\n' +
                        '\t\t-webkit-animation-timing-function: ease-out;\n' +
                        '\t}\n' +
                        '\n' +
                        '\t7% {\n' +
                        '\t\topacity: 1;\n' +
                        '\t\t-webkit-transform: rotate(300deg);\n' +
                        '\t\t-webkit-animation-timing-function: linear;\n' +
                        '\t\t-webkit-origin:0%;\n' +
                        '\t}\n' +
                        '\n' +
                        '\t30% {\n' +
                        '\t\topacity: 1;\n' +
                        '\t\t-webkit-transform:rotate(410deg);\n' +
                        '\t\t-webkit-animation-timing-function: ease-in-out;\n' +
                        '\t\t-webkit-origin:7%;\n' +
                        '\t}\n' +
                        '\n' +
                        '\t39% {\n' +
                        '\t\topacity: 1;\n' +
                        '\t\t-webkit-transform: rotate(645deg);\n' +
                        '\t\t-webkit-animation-timing-function: linear;\n' +
                        '\t\t-webkit-origin:30%;\n' +
                        '\t}\n' +
                        '\n' +
                        '\t70% {\n' +
                        '\t\topacity: 1;\n' +
                        '\t\t-webkit-transform: rotate(770deg);\n' +
                        '\t\t-webkit-animation-timing-function: ease-out;\n' +
                        '\t\t-webkit-origin:39%;\n' +
                        '\t}\n' +
                        '\n' +
                        '\t75% {\n' +
                        '\t\topacity: 1;\n' +
                        '\t\t-webkit-transform: rotate(900deg);\n' +
                        '\t\t-webkit-animation-timing-function: ease-out;\n' +
                        '\t\t-webkit-origin:70%;\n' +
                        '\t}\n' +
                        '\n' +
                        '\t76% {\n' +
                        '\topacity: 0;\n' +
                        '\t\t-webkit-transform:rotate(900deg);\n' +
                        '\t}\n' +
                        '\n' +
                        '\t100% {\n' +
                        '\topacity: 0;\n' +
                        '\t\t-webkit-transform: rotate(900deg);\n' +
                        '\t}\n' +
                        '}\n' +
                        '\n' +
                        '@-moz-keyframes orbit {\n' +
                        '\t0% {\n' +
                        '\t\topacity: 1;\n' +
                        '\t\tz-index:99;\n' +
                        '\t\t-moz-transform: rotate(180deg);\n' +
                        '\t\t-moz-animation-timing-function: ease-out;\n' +
                        '\t}\n' +
                        '\n' +
                        '\t7% {\n' +
                        '\t\topacity: 1;\n' +
                        '\t\t-moz-transform: rotate(300deg);\n' +
                        '\t\t-moz-animation-timing-function: linear;\n' +
                        '\t\t-moz-origin:0%;\n' +
                        '\t}\n' +
                        '\n' +
                        '\t30% {\n' +
                        '\t\topacity: 1;\n' +
                        '\t\t-moz-transform:rotate(410deg);\n' +
                        '\t\t-moz-animation-timing-function: ease-in-out;\n' +
                        '\t\t-moz-origin:7%;\n' +
                        '\t}\n' +
                        '\n' +
                        '\t39% {\n' +
                        '\t\topacity: 1;\n' +
                        '\t\t-moz-transform: rotate(645deg);\n' +
                        '\t\t-moz-animation-timing-function: linear;\n' +
                        '\t\t-moz-origin:30%;\n' +
                        '\t}\n' +
                        '\n' +
                        '\t70% {\n' +
                        '\t\topacity: 1;\n' +
                        '\t\t-moz-transform: rotate(770deg);\n' +
                        '\t\t-moz-animation-timing-function: ease-out;\n' +
                        '\t\t-moz-origin:39%;\n' +
                        '\t}\n' +
                        '\n' +
                        '\t75% {\n' +
                        '\t\topacity: 1;\n' +
                        '\t\t-moz-transform: rotate(900deg);\n' +
                        '\t\t-moz-animation-timing-function: ease-out;\n' +
                        '\t\t-moz-origin:70%;\n' +
                        '\t}\n' +
                        '\n' +
                        '\t76% {\n' +
                        '\topacity: 0;\n' +
                        '\t\t-moz-transform:rotate(900deg);\n' +
                        '\t}\n' +
                        '\n' +
                        '\t100% {\n' +
                        '\topacity: 0;\n' +
                        '\t\t-moz-transform: rotate(900deg);\n' +
                        '\t}\n' +
                        '}\n' +
                        '</style>\n' +
                        '<div class="windows8">\n' +
                        '\t<div class="wBall" id="wBall_1">\n' +
                        '\t\t<div class="wInnerBall"></div>\n' +
                        '\t</div>\n' +
                        '\t<div class="wBall" id="wBall_2">\n' +
                        '\t\t<div class="wInnerBall"></div>\n' +
                        '\t</div>\n' +
                        '\t<div class="wBall" id="wBall_3">\n' +
                        '\t\t<div class="wInnerBall"></div>\n' +
                        '\t</div>\n' +
                        '\t<div class="wBall" id="wBall_4">\n' +
                        '\t\t<div class="wInnerBall"></div>\n' +
                        '\t</div>\n' +
                        '\t<div class="wBall" id="wBall_5">\n' +
                        '\t\t<div class="wInnerBall"></div>\n' +
                        '\t</div>\n' +
                        '</div>';

                    $('.checkout__inputs.checkout__step-two .total__prioity').html(html)
                },
                success: function( response ) {
                    console.log(response)

                    taxJson = response.tax_totals

                    let html = response.fragments['.woocommerce-checkout-review-order-table'];
                    // console.log(html)

                    const regex = /<shipping_method_preparce>(.*?)<\/shipping_method_preparce>/ugs;

                    // console.log(html.match(regex)[0])

                        let dataArr = JSON.stringify(response)
                    localStorage.setItem('dataArr', dataArr)

                    let billing_name = $('input[name="billing_first_name"]').val()+' '+$('input[name="billing_last_name"]').val()?.substr(0, 1)
                    let billingaddress2 = $('input[name="billing_address_2"]').val()
                    let billing_address = $('input[name="billing_address_1"]').val()+', ';
                    billing_address += (billingaddress2) ? billingaddress2 +', ' : '';
                    billing_address += $('input[name="billing_city"]').val()+', '+$('select[name="billing_state"]').val()+', '+$('input[name="billing_postcode"]').val()

                    let shipping_name = $('input[name="shipping_first_name"]').val()+' '+$('input[name="shipping_last_name"]').val().substr(0, 1)
                    let shippingaddress2 = $('input[name="shipping_address_2"]').val()
                    let shipping_address = $('input[name="shipping_address_1"]').val()+', ';
                    shipping_address += (shippingaddress2) ? shippingaddress2 +', ' : '';
                    shipping_address += $('input[name="shipping_city"]').val()+', '+$('select[name="shipping_state"]').val()+', '+$('input[name="shipping_postcode"]').val()
                    let shipping_email = $('input[name="billing_email"]').val()

                    let text_shipping_title = '1. Shipping'
                    if (!status_shipping_address) {
                        text_shipping_title += ' and Billing';
                    } else {
                        $('.checkout__text.shipping_title_two').closest('.checkout__step-header').css('display', 'block');
                    }
                    text_shipping_title += ' Address';
                    $('.checkout__text.shipping_title_one').html(text_shipping_title)

                    $('.checkout__inputs.checkout__step-two .shipping .checkout__name').html(shipping_name)
                    $('.checkout__inputs.checkout__step-two .shipping .checkout__address').html(shipping_address)
                    $('.checkout__inputs.checkout__step-two .shipping .checkout__email').html(shipping_email)
                    $('.checkout__inputs.checkout__step-tree .shipping .checkout__name').html(shipping_name)
                    $('.checkout__inputs.checkout__step-tree .shipping .checkout__address').html(shipping_address)
                    $('.checkout__inputs.checkout__step-tree .shipping .checkout__email').html(shipping_email)

                    $('.checkout__inputs.checkout__step-two .shipping').css('display', 'block')
                    $('.checkout__inputs.checkout__step-tree .shipping').css('display', 'block')

                    $('.checkout__inputs.checkout__step-two .billing .checkout__name').html(billing_name)
                    $('.checkout__inputs.checkout__step-two .billing .checkout__address').html(billing_address)
                    $('.checkout__inputs.checkout__step-tree .billing .checkout__name').html(billing_name)
                    $('.checkout__inputs.checkout__step-tree .billing .checkout__address').html(billing_address)

                    $('.checkout__inputs.checkout__step-two input[name="dataArr"]').val(dataArr)

                    if (status_shipping_address) {
                        $('.checkout__inputs.checkout__step-two .billing').css('display', 'block')
                        $('.checkout__inputs.checkout__step-tree .billing').css('display', 'block')
                    }
                    // $('div.message').html(response['messages'])
                    // console.log(response['shippments'])
                    $('.checkout__inputs.checkout__step-two .total__prioity').html(response['shippments'])

                    let message = response.messages;

                    $('div.message').html('')
                    if (response['shippments'].length == 0 && response['messages'].length != 0)
                        $('div.message').html(message)
                },
                complete: function() {
                    console.log('Загрузка')
                }
            });
        } else if (box.hasClass('checkout__step-two')) {
            let data = JSON.parse(box.find('input[name="dataArr"]').val())
            data['shipping_method[0]'] = box.find('input:checked').val()

            $('.checkout__inputs.checkout__step-tree .total__prioity-title').html(box.find('input:checked').next().find('span').text())
            console.log(box.find('input:checked').next().find('span').text())
            // console.log(data)
            location.hash = '#step-3'
            croshka()
            $('.checkout__inputs.checkout__step-tree').addClass('d-flex')
        }
    })

    $('.checkout__inputs input').keyup(function () {
        let inputName = $(this).attr('name');
        let inputValue = $(this).val()

        if (!status_shipping_address) {
            let inputShippingName = inputName.replace('shipping_', 'billing_')
            $('input[name="'+inputShippingName+'"]').val(inputValue)
        }
    });

    $('body').on('click', '.shipping_method_js .total__point', function (){
        //$(this).val()

        let data = {}
        let post_data = {}

        localStorage.setItem('shipping_method', $(this).val());
        post_data['shipping_method'] = $(this).val()
        post_data['woocommerce-process-checkout-nonce'] = wc_checkout_params.apply_coupon_nonce
        post_data['_wp_http_referer'] = '/?wc-ajax=update_order_review'

        data['has_full_address'] = true
        data['security'] = wc_checkout_params.update_order_review_nonce

        // console.log(post_data)
        post_data_keys = Object.keys(post_data)
        let postData = post_data_keys[0]+'='+post_data[post_data_keys[0]]
        for (var i = 1, l = post_data_keys.length; i < l; i++) {
            postData += '&'+post_data_keys[i]+'='+post_data[post_data_keys[i]]
        }
        data['post_data'] = postData
        data['shipping_method'] = $(this).val()

        console.log(data)
        $.ajax( {
            type: 'POST',
            url: '/?wc-ajax=update_shipping_method',
            data: data,
            dataType: 'json',
            success: function( response ) {
                console.log(response)

                $('.shipping_price_right').parent().css('display', 'flex').find('.total-checkout__price-small').html('$'+response.totals.shipping_total)

                let html_tax = ''
                response.tax_totals.forEach(function (item, key, array) {
                    html_tax += '<div>\n' +
                        '   <span class="total-checkout__subtotal">'+item.label+' </span>\n' +
                        '   <span class="total-checkout__price-small">'+item.formatted_amount+'</span>\n' +
                        '</div>'
                })
                $('div.tax_cart').css('display', 'block').html(html_tax)

                $('.total-checkout__grand').css('display', 'flex')
                $('.total-checkout__grand bdi').html('$'+response.totals.total)
            },
            complete: function() {
                console.log('Загрузка')
            }
        });

        // console.log($(this).data('price'))
        // let price_shipping = $(this).next().next().text()
        // $('.shipping_price_right').parent().css('display', 'flex').find('.total-checkout__price-small').html('$'+price_shipping)

    })

    if (localStorage.getItem('step') == '2' || localStorage.getItem('step') == '3') {
        console.log(localStorage.getItem('step'), localStorage.getItem('shipping_method'))
        $('.shipping_method_js .total__point[value="'+localStorage.getItem('shipping_method')+'"]').click()
    }

    $('button#place_order').click(function () {
        $('.checkout__inputs.checkout__step-four').addClass('d-flex')
        setTimeout(checkout_cart, 1500);
    })

    function checkout_cart() {
        let customer_note = $("textarea[name='checkout-customer-note']" ).val()
        let inputs = $('input, select');

        let data = {}
        for (let i=0; i<inputs.length; i++) {
            let input = $(inputs[i]);
            let key = input.attr('name');

            // data[key] = input.val();
            switch (key) {
                case 'billing_first_name':
                case 'billing_last_name':
                case 'billing_company':
                case 'billing_country':
                case 'billing_address_1':
                case 'billing_address_2':
                case 'billing_city':
                case 'billing_state':
                case 'billing_postcode':
                case 'shipping_first_name':
                case 'shipping_last_name':
                case 'shipping_company':
                case 'shipping_country':
                case 'shipping_address_1':
                case 'shipping_address_2':
                case 'shipping_city':
                case 'shipping_state':
                case 'shipping_postcode':
                case 'billing_phone':
                case 'billing_email':
                case 'woocommerce-process-checkout-nonce':
                case 'stripe_source':
                    data[key] = input.val();
            }
        }
        data['shipping_method[0]'] = $('input[name="shipping_method[0]"]:checked').val()
        data['payment_method'] = $('input[name="payment_method"]:checked').val()
        data['order_comments'] = customer_note
        data['_wp_http_referer'] = '/?wc-ajax=update_order_review'
        data['ship_to_different_address'] = true

        console.log('===CheckBilling', data)

        // return;
        $.ajax( {
            type: 'POST',
            url: '/?wc-ajax=checkout',
            data: data,
            dataType: 'json',
            success: function( response ) {
                console.log('процесс чекаут', response)
                if (response['result'] == 'success') {
                    location = response['redirect']
                } else {
                    $('div.message').html(response['message'])
                }
            },
            complete: function() {
                console.log('Загрузка')
            }
        });
    }

    $('.cart div[for="uploadFiles"]').click(function (){
        $(this).closest('li').find('input#uploadFiles').click()
    })

    $('.cart input#uploadFiles').change(function () {
        console.log('загрузка файла')
        uploadFile(this)
    })

    function uploadFile(e) {
        let fileUpload = $(e).get(0).files
        let productId = $(e).data('product-id')
        $(e).closest(".cart__list").find(".cart__file-list").find(".cart__file-without-items").remove()
        $.each(fileUpload, function (key, singleFile) {
            let add = true;

            const elems = $(e).closest(".cart__list").find(".cart__file-list").find(".cart__file-item")

            for (const elem of elems) {
                if (elem.children[1].textContent === singleFile.name){
                    add = false;
                }
            }

            if (add) {
            $(e).closest(".cart__list").find(".cart__file-list").append(`
                <div  class="cart__file-item">
                    <img class="cart__file-img" src=https://bannerprintingphoenix.com/wp-content/themes/bpsd/assets/img/icons/${singleFile.name.split('.')[1]}.svg /> 
                    <span class="cart__file-title">${singleFile.name}</span>
                    <div class="product-options__loaded-files-status"><div class="product-options__loaded-files-loader"><div class="ldio-oe31sprnm4">
            <div></div><div><div></div></div>
        </div></div></div>
                </div>`)
            }
        })


        let data = new FormData();
        let countSizeFile = 0
        $.each( fileUpload, function( key, value ){
            countSizeFile += value.size;
            data.append( key, value );
        });

        countSizeFile = (countSizeFile/1024/1024).toFixed(4) //MB

        let url = '/';
        if (typeof wc_cart_params != 'undefined') {
            url = wc_cart_params['ajax_url']+'/?action=post_upload_file&key='+productId;
        } else {
            url = wc_checkout_params['ajax_url']+'/?action=post_upload_file&key='+productId;
        }

        if (fileUpload.length > 0 && countSizeFile < 5000) {
            $('.cart__file-list').append("<div class='cart__file-list-indicator'><span style='width: 0'></span></div>")
            $.ajax({
                url: url,
                type: 'POST',
                data: data,
                cache: false,
                dataType: 'json',
                processData: false,
                contentType: false,
                xhr: function(){
                    var xhr = $.ajaxSettings.xhr(); // получаем объект XMLHttpRequest
                    xhr.upload.addEventListener('progress', function(evt){ // добавляем обработчик события progress (onprogress)
                        if(evt.lengthComputable) { // если известно количество байт
                            // высчитываем процент загруженного
                            var percentComplete = Math.ceil(evt.loaded / evt.total * 100);
                            // устанавливаем значение в атрибут value тега <progress>
                            // и это же значение альтернативным текстом для браузеров, не поддерживающих <progress>
                            console.log('Загружено ' + percentComplete + '%');
                            $('.cart__file-list-indicator').find("span").css(`width`, percentComplete+"%")
                            console.log($('.cart__file-list-indicator').find("span"))
                        }
                    }, false);
                    return xhr;
                },
                success: function (response) {
                    // console.log(response)
                    const items = $(e).closest(".cart__list").find(".product-options__loaded-files-status")
                            $.each(items, (key, item) => item.innerHTML = `<div class="product-options__loaded-files-loader__done"></div>`)
                    $('.cart__file-list-indicator').remove()
                },
                error: function (jqXHR, textStatus) {
                    console.error(textStatus);
                }
            });
        }
    }

    $('div.cart__file-btn-del').click(function () {
        let data = {
            file: $(this).data('file')
        }

        let elem = $(this).closest('.cart__file-item')

        $.ajax( {
            type: 'POST',
            url: '/wp-admin/admin-ajax.php/?action=post_upload_file',
            data: data,
            dataType: 'json',
            success: function(response) {
                if (response['status'] == 'ok') {
                    // console.log(response)
                    // console.log(elem)

                    const elems = $(elem).closest(".cart__list").find(".cart__file-list").find(".cart__file-item")
                    if (elems.length < 2){
                        $(elem).closest(".cart__list").find(".cart__file-list").append(`
                          <div class="cart__file-without-items">
                                        <span>NO DOCUMENT ATTACHED TO PRODUCT</span>
                          </div>
                        `)
                    }
                    elem.remove()
                }
            },
            complete: function() {
            }
        });
    })

    $('input[name="payment_method"]').click(function () {
        $('input[name="payment_method"]').attr('checked', false);
        $(this).attr('checked', 'checked');

        if ($(this).val() == 'paypal') {
            $('#stripe-payment-data').css({'opacity': '0'})
            $('.checkout__button-paypal-mob').css({'z-index':'1'})
        }
        if ($(this).val() == 'stripe') {
            $('#stripe-payment-data').css({'opacity': '1'})
            $('.checkout__button-paypal-mob').css({'z-index':'0'})
        }
    })

    $('input[name="billing_email1"]').keyup(function () {
        $('input[name="billing_email"]').val($('input[name="billing_email1"]').val())
    })

    $('input[name="billing_email"]').keyup(function () {
        $('input[name="billing_email1"]').val($('input[name="billing_email"]').val())
    })

    $('ol.checkout__breadcrumb').on('click', 'li.checkout__breadcrumb-item a', function () {
        let route = $(this).attr('href')

        let box = $('div.checkout__inputs')

        box.removeClass('d-flex')

        if (route == '#')
            $(box[0]).addClass('d-flex')
        if (route == '#step-2')
            $(box[1]).addClass('d-flex')

        if (route == '#step-3')
            $(box[2]).addClass('d-flex')

        console.log(route)
        if (route == '#')
            localStorage.removeItem('step')
        else
            localStorage.setItem('step', route.slice(-1))

        location.href = route
        croshka()
    })

    $('body').on('click', 'a.editchekoutdata', function () {
        let route = $(this).attr('href')

        let box = $('div.checkout__inputs')

        box.removeClass('d-flex')

        if (route == '#')
            $(box[0]).addClass('d-flex')

        if (route == '#step-2')
            $(box[1]).addClass('d-flex')

        if (route == '#step-3')
            $(box[2]).addClass('d-flex')

        location.href = route
        croshka()
    })

    $('a.back_checkout').click(function () {
        let route = location.hash

        console.log(route)

        let box = $('div.checkout__inputs')

        box.removeClass('d-flex')

        if (route == '#step-2') {
            $(box[0]).addClass('d-flex')
            location.hash = '#'
        } else if (route == '#step-3') {
            $(box[1]).addClass('d-flex')
            location.hash = '#step-2'
        } else {
            window.history.back()
        }
    })

    function croshka() {
        console.log(location)
        let data = []

        if (location.pathname == '/cart/') {
            data.push({
                'name': 'Cart',
                'href': null
            })
        }
        if (location.pathname == '/checkout/') {
            data.push({
                'name': 'Cart',
                'href': '/cart/'
            })
            data.push({
                'name': 'Shipping Address',
                'href': null
            })

        }

        if (location.pathname == '/checkout/' && location.hash != '#step-2' && location.hash != '#step-3') {
            console.log('edit grant total')
            $('.total-checkout__grand').css('display', 'none')
            $('.shipping_price_right').parent().css('display', 'none')
            $('.total-checkout__grand-price').html($('input[name="subtotalinfo"]').val())

            if (location.search != '?removed_item=1')
                localStorage.removeItem('step');
        }

        if (location.hash == '#step-2') {
            data[1] = {
                'name': 'Shipping Address',
                'href': '#',
            }
            data.push({
                'name': 'Shipping Method',
                'href': null
            })

            localStorage.setItem('step', '2');
        }

        if (location.hash == '#step-3') {
            data[1] = {
                'name': 'Shipping Address',
                'href': '#',
            }
            data.push({
                'name': 'Shipping Method',
                'href': '#step-2'
            })
            data.push({
                'name': 'Payment Method',
                'href': null
            })

            localStorage.setItem('step', '3');
        }

        let html = ''
        data.forEach((elem) => {
            html += '<li class="checkout__breadcrumb-item"><a';
            if (elem.href)
                html += ' href="'+ elem.href +'"';

            html += '>'+ elem.name +'</a></li>';
        })

        console.log(data)
        $('ol.checkout__breadcrumb').html(html)
    }

    $('button.checkout__button.checkout__button-payment').click(function () {
        $('[name="woocommerce_checkout_place_order"]').click()
    })

    $(".checkout__inputs").on('change', 'select#shipping_state', function() {
        $('select#billing_state option').attr('selected', false)
        $('select#billing_state option[value="'+$(this).val()+'"]').attr('selected', true)
    })

    $( "select#shipping_country" ).change(function() {
        let url = ''
        if (typeof wc_cart_params != 'undefined') {
            url = wc_cart_params['ajax_url'];
        } else {
            url = wc_checkout_params['ajax_url'];
        }
        url = url+'/?action=get_states'+'&country='+$(this).val();

        let country = $(this).val();
        if (!status_shipping_address) {
            $('select#billing_country option').attr('selected', false)
            $('select#billing_country option[value="'+country+'"]').attr('selected', true)
        }

        let country_name = $('select#shipping_country option[value="'+country+'"]').text();

        $.ajax( {
            type: 'POST',
            url: url,
            dataType: 'json',
            success: function( response ) {
                console.log(response)
                let options = ''
                let bil_html = ''
                let shi_html = ''
                if (response) {
                    let keys = Object.keys(response)
                    keys.forEach(function(item, i, keys) {
                        options += '<option value="'+item+'">'+response[item]+'</option>';
                    });

                    bil_html = '<select class="checkout__field checkout__field-big" name="shipping_state" type="text" id="shipping_state">'+options+'</select>';
                    if (!status_shipping_address) {
                        shi_html = '<select class="checkout__field checkout__field-big" name="billing_state" type="text" id="billing_state">'+options+'</select>';
                    }
                } else {
                    bil_html = '<select class="checkout__field checkout__field-big" name="shipping_state" type="text" id="shipping_state"><option value="'+country+'">'+country_name+'</option></select>';
                    if (!status_shipping_address) {
                        shi_html = '<select class="checkout__field checkout__field-big" name="billing_state" type="text" id="billing_state"><option value="'+country+'">'+country_name+'</option></select>';
                    }
                }



                $('div.bil_state').html(shi_html)
                if (!status_shipping_address) {
                    $('div.bil_ship_state').html(bil_html)
                }
            }
        });
    });

    $( "select#billing_country" ).change(function() {
        let url = ''
        if (typeof wc_cart_params != 'undefined') {
            url = wc_cart_params['ajax_url'];
        } else {
            url = wc_checkout_params['ajax_url'];
        }
        url = url+'/?action=get_states'+'&country='+$(this).val();

        let country = $(this).val();
        let country_name = $('select#billing_country option[value="'+country+'"]').text();
        $.ajax( {
            type: 'POST',
            url: url,
            dataType: 'json',
            success: function( response ) {
                console.log(response)
                let html = ''
                if (response) {
                    let keys = Object.keys(response)
                    html += '<select class="checkout__field checkout__field-big" name="billing_state" type="text" id="billing_state">';
                    keys.forEach(function(item, i, keys) {
                        html += '<option value="'+item+'">'+response[item]+'</option>';
                    });
                    html += '</select>';
                } else {
                    html = '<select class="checkout__field checkout__field-big" name="billing_state" type="text" id="billing_state"><option value="'+country+'">'+country_name+'</option></select>';
                }

                $('div.bil_state').html(html)
            }
        });
    });

    $('.checkout__button.checkout__button-paypal').click(function (){
        $('input[name="payment_method"]').attr('checked', 'checked');

        $('.checkout__button.checkout__button-payment').click()
    });

    $('input[name="checked_billing_address"]').change(function (e) {
        e.preventDefault()

        $('input[name="checked_billing_address"]').prop('checked', false)
        $(this).prop('checked', true)

        if ($(this).val() == 'on') {
            status_shipping_address = true
            $('.checkout__shipping-address').css('display', 'block')
        } else {
            status_shipping_address = false
            $('.checkout__shipping-address').css('display', 'none')
        }
    })
})
