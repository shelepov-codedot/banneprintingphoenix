jQuery(document).ready(function($) {
    const settingsPopUp = {
        popUp: '#myModal',
        overlayPopUp: '#myOverlay',
        id: '#myModal [data-product-id]',
        bodyImage: '#myModal [data-bodyImage]',
        image: '#myModal [data-image]',
        title: '#myModal [data-tite]',
        bodyAttributes: '#myModal [data-attributes]',
        quantity: '#myModal [data-quantity]',
        quantityMinus: '#myModal [data-quantity-minus]',
        quantityPlus: '#myModal [data-quantity-plus]',
        closePopUp: '#myModal [data-popup-close]',
        buttonRedirect: '#myModal [data-redirect]',
    }

    let customWidth = 0;
    let customHeight = 0;
    let customWeight = 1;

    var single_price=$($('.product-action__price span')[0]).text();
    var single_sale_price=$($('.product-action__price_sale')[0]).text();

    let new_discount_price = single_price;

    tabs();

    let qqq=$('.product-action').offset();



    $(window).on('resize',function (e) {
        tabs();
    });

    $(window).on('scroll',function (e) {
        if($(this).scrollTop() + window.innerHeight>qqq.top){
            $('.product-action').css('position','sticky');
        } else{
            $('.product-action').css('position','fixed');
        }

    })

    function tabs(){

        if($( window ).width()<1200){
            $(".product-description__title:first").addClass("is-active");

            $(".product-description__title2").click(function(event){
                $(".product-description__title:first").removeClass("is-active");
                $(this).addClass("is-active");
                $(".product-description__text").hide();
                $(".product-description__upload").slideDown();


            });

            $(".product-description__title1").click(function(event){
                $(".product-description__title2").removeClass("is-active");
                $(this).addClass("is-active");
                $(".product-description__upload").hide();
                $(".product-description__text").slideDown();
            });
        } else if ($( window ).width()>1200) {
            $(".product-description__upload").show();
            $(".product-description__text").show();
        };

    }

    // var $button = $('.product__plus');
    // var $minus = $('.product__minus');
    // var $counter = $('.product__numbers');
    //
    // $button.click(function(){
    //     $counter.val( parseInt($counter.val()) + 1 );
    //
    // });
    //
    // $minus.click(function(){
    //     $counter.val( parseInt($counter.val()) - 1 );
    //
    // });

    // console.log(counter.val);

    $('.review__button').click(function(){
        $(".review-new").slideToggle(); 
    });
    
    $('.review-new__cancel').click(function(event){
        $(".review-new").slideUp(); 
        event.preventDefault();
    });

    $("#validate").validate({
        rules: {
            name:{
                required: true,
                minlength: 3
            },
            theme:{
                required: true,
                minlength: 3
            },
            text:{
                required: true
            },
            submitHandler: function(form) {
                form.submit();
            }
          }
      });

      $("#quantity").validate({
        rules: {
            quantity:{
                required: true,
                digits: true,
                min:1
            }
          }
      });
     
      $("#checkout").validate({
        rules: {
            name:{
                required: true,
                minlength: 1
            }
          }
      });

    // $('.comment-form-rating__stars a').mouseover(function (e) {
    //     let stars_block=$(this).parents('.comment-form-rating');
    //     let stars= stars_block.find('a');
    //     let stars_active_index=stars.index( $( this ) );
    //     stars.removeClass('is-active');
    //     stars.each(function( index ) {
    //         if(index===stars_active_index+1){
    //             return false;
    //         } else {
    //             $(this).addClass('is-active');
    //         }
    //
    //     });
    // })

    $('.comment-form-rating__stars a').on('click',function (e) {
        e.preventDefault();
        let stars_block=$(this).parents('.comment-form-rating');
        let stars_select=stars_block.find('.comment-form-rating__select');
        let stars= stars_block.find('a');
        let stars_active_index=stars.index( $( this ) );
        stars.removeClass('is-active');
        stars.each(function( index ) {
            if(index===stars_active_index+1){
                return false;
            } else {
                $(this).addClass('is-active');
            }

        });
        $(this).text();
        $(stars_select).val( $(this).text());
        $(stars_select).change();
    })


    $('.review-form').on('submit',function (e) {
        e.preventDefault();

        if($(this).valid()){
            $.ajax({
                type: 'POST',
                url: ajax_url['url'],
                data: {
                    'action': 'product_review',
                    'data' : $(this).serialize()
                },
                success: function(data) {
                    if(data){
                        document.location.reload();
                    }
                }
            });
        }

    })

    $('#variations-form').on('change',function (e) {
        let selects=$(this).find('select');
        let variations=JSON.parse(ajax_url['variations']);
        let price_block=$('.product-action__price span');
        let price_is_set= false;

        //console.log('Size', $('select[name="attribute_pa_set-size"]').val())
        if($('select[name="attribute_pa_set-size"]')?.val()?.includes('custom')){
            $('#custom-size').show();
        } else {
            $('.product-action__add').css('background', '').prop('disabled', false);
            $('#custom-size').hide();
        }

        if (Number.isInteger(variations) == false) {
            $(variations).each(function( index ) {
                let current_variation=this;
                let cur_attribute=this['attributes'];
                let attr_max=Object.keys(cur_attribute).length;
                let attr_counter=0;
                selects.each(function( index ) {

                    if(cur_attribute[ $(this).attr('name')]){
                        if(cur_attribute[ $(this).attr('name')].toLowerCase()===$(this).val().toLowerCase()){
                            attr_counter++;
                        }
                    }

                });

                if(attr_counter===attr_max){
                    price_block.text(current_variation['display_price']);
                    single_price=current_variation['display_price'];
                    price_is_set=true;

                }

                if(!price_is_set){
                    price_block.text(ajax_url['default_price']);
                    single_price=ajax_url['default_price'];
                }
                price_counter();
            });
        } else {
            let product_id = $('input[name="product_id"]').val()

            $.ajax({
                url: '/wp-admin/admin-ajax.php/?action=get_variants_of_product&key='+product_id,
                type: 'POST',
                dataType: 'json',
                beforeSend: function () {
                    $('.product-options__preload').html('<p>Data requested</p>');

                    $('button').each(function () {
                        $(this).attr('disabled', '')
                    })
                },
                success: function(response) {
                    let attributes = JSON.parse(response);

                    $('.product-options__preload p').remove();

                    $('button').each(function () {
                        $(this).removeAttr('disabled')
                    })

                    $(attributes).each(function( index ) {
                        let current_variation = this;
                        let cur_attribute = this['attributes'];
                        let attr_max = Object.keys(cur_attribute).length;
                        let attr_counter = 0;

                        selects.each(function( index ) {

                            if(cur_attribute[ $(this).attr('name')]){
                                if(cur_attribute[ $(this).attr('name')].toLowerCase()===$(this).val().toLowerCase()){
                                    attr_counter++;
                                }
                            }

                        });
                        if(attr_counter===attr_max){
                            price_block.text(current_variation['display_price']);
                            single_price=current_variation['display_price'];
                            price_is_set=true;
                        }

                        if(!price_is_set){
                            price_block.text(ajax_url['default_price']);
                            single_price=ajax_url['default_price'];
                        }
                        price_counter();
                    });
                },
                error: function(textStatus){
                    console.error(textStatus);
                }
            })
        }
    })

    $('.product__plus').click(function(){
        let input= $(this).parents('.product__button').find('.product__numbers');
        input.val(parseInt(input.val())+1);
        input.change();
    });

    $('.product__minus').click(function(){
        let input= $(this).parents('.product__button').find('.product__numbers');
        input.val(parseInt(input.val())-1);
        input.change();
    });


    $('.product__numbers').on('change',function (e) {
        let curnt_val=$(this).val();
        if(curnt_val<1){
            $(this).val(1);
        } else{
            $('.product__numbers').val($(this).val());
        }
        price_counter();
    })

    var attributes_values = {};

    var desc_var = ''
    var weight_var = 0.01;

    $('#variations-form').on('change', 'select', function (e) {
        let attributes = $('#variations-form .product-options__box')

        attributes_values = {};
        for (let attribute of attributes) {
            attributes_values[$(attribute).attr('name')] = $(attribute).val()
        }

        // console.log(attributes_values)

        let variantes = JSON.parse($('#product_variants').val())

        let variant = variantes.find(function(item, index, array) {
            return JSON.stringify(item.attributes)===JSON.stringify(attributes_values);
        })

        desc_var = variant.variation_description
        weight_var = variant.weight;//*1

        $('input.variation_id[name="variation_id"]').val(variant.variation_id)
    })

    $($('#variations-form select')[0]).change()

    $('button[for="uploadFiles"]').click(function (e){
        // alert('Нажата кнопка. Должно остановить событие')
        e.preventDefault()
        $('input#uploadFiles').click()
    })



    $('input#uploadFiles').on('change', ()=>{
        const files = $('input#uploadFiles')[0].files
        let product_id = $('input[name="product_id"]').val()
        let data = new FormData()
        let countSizeFile = 0

        $.each(files, function (key, value) {
            countSizeFile += value.size
            data.append(key, value)
        });

        countSizeFile = (countSizeFile / 1024 / 1024).toFixed(4) //MB

        console.log(files)

        $('.product-options__loaded-files-wrap').append("<div class='cart__file-list-indicator'><span style='width: 0'></span></div>")
        $.ajax({
            url: '/wp-admin/admin-ajax.php/?action=post_upload_file&key=' + product_id,
            type: 'POST',
            cache: false,
            contentType: false,
            processData: false,
            data: data,
            dataType: 'json',
            xhr: function () {
                var xhr = $.ajaxSettings.xhr();
                xhr.upload.addEventListener('progress', function (evt) {
                    var percentComplete = Math.ceil(evt.loaded / evt.total * 100);
                    console.log('Загружено ' + percentComplete + '%');
                    console.log('Загружено ' + percentComplete + '%');
                    $('.cart__file-list-indicator').find("span").css(`width`, percentComplete + "%")
                    console.log($('.cart__file-list-indicator').find("span"))
                }, false);
                return xhr;
            },
            success: function (response) {
                console.log(response)
                alert('The file has been successfully uploaded')
                $.each(files, function (key, singleFile) {
                    console.log(singleFile)
                    $('.product-options__loaded-files').append(`
                            <div class="product-options__loaded-files-item">
                                <img class="product-options__loaded-files-img" src=https://bannerprintingsandiego.com/wp-content/themes/bpsd/assets/img/icons/${singleFile.name.split('.')[1]}.svg /> 
                                <span class="product-options__loaded-files-title">${singleFile.name}</span>
                                <div class="product-options__loaded-files-status product-options__loaded-files-status-modal"></div>
                            </div>`
                    )
                })
                $.each($(".product-options__loaded-files-status-modal"), (key, item) => item.innerHTML = `<div class="product-options__loaded-files-loader__done"></div>`)
                $('.cart__file-list-indicator').remove()
            },
            error: function (jqXHR, textStatus) {
                alert('Ошибка загрузки файла')
                console.error(jqXHR);
                console.error(textStatus);
            }
        });
    })

    $('.modal-load__close').click(function () {
        $('#myOverlay').css('display', 'none')
        $('.modal-load').removeClass('popup-load__visible')
    })

    $('.product-action__add').on('click', function () {
        add_to_cart()
    });

    function uploadFile(e, key) {
        let fileUpload = $(e).get(0).files
        let productId = key

        console.log(fileUpload)

        let data = new FormData();
        let countSizeFile = 0
        $.each( fileUpload, function( key, value ){
            countSizeFile += value.size;
            data.append( key, value );
        });

        countSizeFile = (countSizeFile/1024/1024).toFixed(4) //MB

        if (fileUpload.length > 0 && countSizeFile < 5000) {
            $('.product-options__loaded-files-wrap').append("<div class='cart__file-list-indicator'><span style='width: 0'></span></div>")
            $.ajax({
                url: '/wp-admin/admin-ajax.php/?action=post_upload_file&key='+productId,
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
                            console.log('Загружено ' + percentComplete + '%');
                            $('.cart__file-list-indicator').find("span").css(`width`, percentComplete+"%")
                            console.log($('.cart__file-list-indicator').find("span"))
                        }
                    }, false);
                    return xhr;
                },
                success: function(response){
                    console.log(response)
                    $.each($(".product-options__loaded-files-status"), (key, item) => item.innerHTML = `<div class="product-options__loaded-files-loader__done"></div>`)
                    $('.cart__file-list-indicator').remove()
                },
                error: function( jqXHR, textStatus){
                    console.error(textStatus);
                }
            });
        }
    }

    function price_counter() {
        let price_block = $('.product-action__price span');
        let counter = $('.product__numbers')[0];
        let discount_price=single_price;


        if($('select[name="attribute_pa_set-size"]')?.val()?.includes('custom')){
            new_discount_price = discount_price = checkCustomPrice()
        }

        let in_range=false;
        let dop_price = 0;

        //console.log(attributes_values)
        if (typeof(add_dop_price) != "undefined" && add_dop_price !== null) {
            $(add_dop_price['data']).each(function (index) {
                for (let attr_in in attributes_values) {
                    if (attributes_values[attr_in] == add_dop_price['data'][index]['option'])
                        dop_price += add_dop_price['data'][index]['price']*1;
                }
            });
        }

        //console.log(dop_price)


        if (typeof(discount) != "undefined" && discount !== null) {
            $(discount['data']).each(function (index) {
                if (parseInt($(counter).val()) >= this['from']) {
                    discount_price = parseFloat((single_price-dop_price) * (1 - this['discount'] / 100)+dop_price);
                    console.log(discount_price)
                    in_range=true;

                }

            });


        }
        price_block.text((parseFloat(discount_price) * parseInt($(counter).val())).toFixed(2));
    }

    $('.add_to_cart_and_checkout').on('click', function () {
        add_to_cart('/cart/')
    })

    function add_to_cart(redirect = null) {
        $.each($(".product-options__loaded-files-status"), (key, item) => item.innerHTML = `<div class="product-options__loaded-files-loader"><div class="ldio-oe31sprnm4">
            <div></div><div><div></div></div>
        </div></div>`)

        let data = {
            'product_id': $('input[name="product_id"]').val(),
            'quantity': $('input#number-two').val()*1,
            'variation_id': $('input.variation_id').val()*1,
        }

        if (customWidth > 0 && customHeight > 0 && $('select[name="attribute_pa_set-size"]').val().includes('custom')) {
            data['variant'] = customWidth+'-x-'+customHeight+''
            data['custom_variant'] = true;
            data['custom_weight'] = customWeight;
            data['new_price'] = new_discount_price;
        }

        console.log('add_to_cart', data)
        // return;
        $.ajax({
            type: 'POST',
            url: ajax_url['url']+'/?action=post_add_to_cart',
            data: data,
            success: function(data) {
                let info = JSON.parse(data);
                console.log(info)

                if (info['status'] == 'ok') {
                    let product_key = ''
                    for (key in info.wc) {
                        if (info.wc[key].variation_id == info.post_vars.variation_id && info.wc[key].product_id == info.post_vars.product_id) {
                            product_key = key
                            break
                        }
                    }
                    console.log('Загружаем файл: '+product_key+'_'+$('input.variation_id').val())
                    //uploadFile('input[name="files[]"]', product_key+'_'+$('input.variation_id').val()*1)

                    let count_str = $('a.user-nav__button.user-nav__button-cart p.user-nav__text').html();
                    let count = count_str.replace(' items', '')*1
                    $('a.user-nav__button.user-nav__button-cart p.user-nav__text').html(info.cart_count + ' items')
                    $('a.user-nav__button.user-nav__button-cart span.user-nav__cart-count').html(info.cart_count)

                    setCartProduct(info.products)


                    popUp_setImage(info.product_new_add_cart.img);
                    popUp_setTitle(info.product_new_add_cart.name);
                    popUp_setAttrubytes(info.product_new_add_cart.attributes);
                    popUp_setQuantity(info.product_new_add_cart.quantity);
                    popUp_setId(info.product_new_add_cart.id);
                    popUp_show()


                    $('span.total-popup__sum').html(info.totals)

                    if (redirect) {
                        location.href=redirect
                    }
                }
            }
        });
    }
    $('input[type="number"][name^="custom"]').keyup(function () {
        // console.log('ввод данных', $(this).val())
        price_counter()
    })

    function checkCustomPrice() {
        $('span.error-custom-size').html('')
        customWidth = $('input[type="number"][name="custom-width"]').val()*1
        customHeight = $('input[type="number"][name="custom-height"]').val()*1
        if (customWidth < 0) {
            customWidth = 0.1
            $('input[type="number"][name="custom-width"]').val(customWidth)
        }
        if (customHeight < 0) {
            customHeight = 0.1
            $('input[type="number"][name="custom-height"]').val(customHeight)
        }


        let min_price = desc_var.split('\n')[0].replace(/\D+/g,"")*1;
        let new_price = customWidth*customHeight*single_price;
        //customWeight = weight_var*customHeight*customWidth;

        $('.product-action__add').css('background', '#3c3c3c').prop('disabled', true);

        if (customWidth == 0 || customHeight == 0) {
            $('span.error-custom-size').html('Incorrect data')
            return 0;
        }


        if (min_price > new_price) {
            // $('span.error-custom-size').html('Минимальная сумма за 1 единицу товара должна быть не меньше '+min_price+'$')

            // return min_price;
            new_price = min_price;
        }

        $('.product-action__add').css('background', '').prop('disabled', false);

        return new_price;
    }
    $(document).on('click', settingsPopUp.closePopUp+','+settingsPopUp.overlayPopUp, function () {
        popUp_close()
        $('.modal-load').removeClass('popup-load__visible ')
    })

    $(document).on('click', settingsPopUp.quantityMinus, function () {
        let quantity = $(settingsPopUp.quantity).val();
        popUp_updateQuantity(quantity*1-1);
    })

    $(document).on('click', settingsPopUp.quantityPlus, function () {
        let quantity = $(settingsPopUp.quantity).val();
        popUp_updateQuantity(quantity*1+1);
    })

    $(document).on('click', settingsPopUp.buttonRedirect, function () {
        location.href = $(this).data('rlink');
    })

    function popUp_setImage(url) {
        $(settingsPopUp.image).attr('src', url)
    }

    function popUp_setTitle(title) {
        $(settingsPopUp.title).text(title)
    }

    function popUp_setAttrubytes(attributes) {
        let html = '';
        for(attribute of attributes) {
            html += '<p>'+attribute.name+': '+attribute.value+'</p>';
        }

        $(settingsPopUp.bodyAttributes).html(html)
    }

    function popUp_setQuantity(quantity) {
        $(settingsPopUp.quantity).val(quantity)
    }

    function popUp_updateQuantity(new_quantity) {

        let data = {
            'product_id': $(settingsPopUp.id).val(),
            'quantity': new_quantity
        }

        $.ajax({
            type: 'POST',
            url: ajax_url['url'] + '/?action=post_update_quantity_product_to_cart',
            data: data,
            success: function (data) {
                let info = JSON.parse(data);

                if (info.status === 'ok') {
                    $('a.user-nav__button.user-nav__button-cart p.user-nav__text').html(info.cart_count+' items');

                    setCartProduct(info.cart_products)
                    $('span.total-popup__sum').html(info.totals)
                }
            }
        })

        $(settingsPopUp.quantity).val(new_quantity);
    }

    function popUp_setId(id) {
        $(settingsPopUp.id).val(id)
    }

    function popUp_show() {
        $(settingsPopUp.overlayPopUp).fadeIn(297,	function(){
            $(settingsPopUp.popUp)
                .css('display', 'block')
                .animate({opacity: 1}, 198);
        });
    }

    function popUp_close() {
        $(settingsPopUp.popUp).animate({opacity: 0}, 198,
            function(){
                $(this).css('display', 'none');
                $(settingsPopUp.overlayPopUp).fadeOut(297);
            });
    }

    function setCartProduct(products) {
        let html = '';

        console.log(products)

        for (key in products) {
            let attribute = '';
            for (akey in products[key].attributes) {
                if (products[key].attributes[akey].value) {
                    attribute += `
                                <div class="cart__item-box">
                                    <div class="cart__item-parameters">`+products[key].attributes[akey].name+`: `+products[key].attributes[akey].value+`</div>
                                </div>
                            `;
                }
            }

            html += `
                            <li>
                                <div class="cart-popup__item">
                                    <img class="cart-popup__item-img"
                                        src="`+products[key].img+`">
                                    <div class="cart-popup__item-wrap">
                                        <div class="cart-popup__item-box">
                                            <div class="cart-popup__item-text cart-popup__item-title">`+products[key].name+`</div>
                                            <div class="cart-popup__item-text cart-popup__item-count">`+products[key].quantity+`</div>
                                            <div class="cart-popup__item-text cart-popup__item-price">`+products[key].price+`</div>
                                        </div>
                                        `+attribute+`
                                    </div>
                                </div>
                                <div class="cart-popup__item-button">
                                    <a class="cart-popup__item-edit" href="`+products[key].action.edit+`">
                                        <span>Edit PRODUCT</span>
                                    </a>
                                    <a class="cart-popup__item-delete" href="`+products[key].action.delete+`">
                                        <span>DELETE</span>
                                    </a>
                                </div>
                            </li>
                        `;
        }

        $('div.cart-popup ul.cart-popup__list').html(html);
    }

    if (window.performance && window.performance.navigation.type === window.performance.navigation.TYPE_BACK_FORWARD) {
        price_counter()
    }
})