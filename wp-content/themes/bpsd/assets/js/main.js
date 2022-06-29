jQuery(document).ready(function($) {
    header_padding();






    $(window).on('resize',function (e) {
        header_padding();
    })

    $('#home-hello').slick({
        infinite: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: true,
        prevArrow: '<button type="button" class="slick-prev"></button>',
        nextArrow: '<button type="button" class="slick-next"</button>',

    });

    $('#banner-slider').slick({
        infinite: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: true,
        dots:true,
        prevArrow: '<button type="button" class="slick-prev"></button>',
        nextArrow: '<button type="button" class="slick-next"</button>',

    });


    $('#product-slider').slick({
        infinite: false,
        mobileFirst:true,
        slidesToScroll: 1,
        variableWidth: true,
        arrows: true,
        prevArrow: '<button type="button" class="slick-prev"></button>',
        nextArrow: '<button type="button" class="slick-next"</button>',
    });

    $('.header-marketing__cross').on('click',function () {
        $(this).parents('.header-marketing').slideUp({
            complete: function () {
                header_padding();
            }
        });
    })
    $('#subscription').on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            type: 'POST',
            url: ajax_data.url,
            data: 'action=subscription&' + $(this).serialize(),
            success: function (data) {
                alert(data);
            }
        });
    });
    $('.footer-menu .menu-item-has-children').on('click',function (e) {

            if($(e.target).parents('.sub-menu').length===0){
                e.preventDefault();
                if($( window ).width()<768){
                    let parent=$(this);
                    $(this).find('> .sub-menu').slideToggle({
                        duration: 500,
                        start: function () {
                            parent.toggleClass('is-active');
                        }
                    });
                }
            }

        })
    $('.main-menu__toggle').on('click',function (e) {
        $('.main-menu').slideDown()
        $('#chatra').css({'display': 'none'})
        let menuListItems = $('.main-menu__category-list').children('.main-menu__category-list-item')
        $(menuListItems).each(function (e) {
            let subMenu = $(menuListItems[e]).children('.sub-menu__category')
            let prevArrow = $(subMenu).children('li').children('.main-menu__category-prev')

            $(prevArrow).remove()

            if (subMenu.length == 0) {
                $(menuListItems[e]).children('.main-menu__category-next').remove()
            }
        })
    })
    $('.main-menu__close').on('click',function (e) {
        $('.main-menu').slideUp()
        $('#chatra').css({'display': 'block'})
    })

    if ($(window).width()<768) {
        $('.main-menu__category-next').click(function () {
            let parent = $(this).closest('.main-menu__category-list-item').find('.sub-menu__category')
            $(parent).addClass('visible')

            $('.main-menu__category-next').each(function () {
                $(this).addClass('is_close')
            })

            $('.main-menu__category').css('padding', '0')

            $('.main-menu__category-list').css('visibility', 'hidden').children('li.main-menu__category-list-item').css({'position' : 'absolute'})

            $('.main-menu__category-prev').removeClass('is_close')
            $('.main-menu__category-prev').addClass('is_open')
        })

        $('.main-menu__category-prev').click(function () {
            let parent = $('.sub-menu__category')
            $('.main-menu__category-prev').removeClass('is_open')
            $('.main-menu__category-prev').addClass('is_close')

            $('.main-menu__category-next').each(function () {
                $(this).removeClass('is_close')
            })

            $(parent).removeClass('visible').slideToggle({
                duration: 500,
                complete: function () {
                    $('.main-menu__category-list').css('visibility', 'visible')
                        .children('li.main-menu__category-list-item')
                        .css({'position' : 'relative'})
                    $(parent).css({
                        'display': 'block',
                        'position': 'absolute',
                        'left': '0'
                    })
                }
            })
        })
    }

    $('.user-nav__button-search').click(function() {
        $('.user-header__dropdown-search').css('display', 'block')
    })

    $('.user-header__dropdown-container span').click(function () {
        $('.user-header__dropdown-search').css('display', 'none')
    })

    /*if($( window ).width()<768){
        $('.main-nav .main-nav__arrow ').on('click',function (e) {
            let parent=$(this).closest('.main-nav__item');
            if($(this).hasClass('main-nav__next')){
                $(this).parents('.main-menu').addClass('is-active');
            } else{
                $(this).parents('.main-menu').removeClass('is-active');
            }
            parent.find('> .main-nav__list').slideToggle({
                duration: 600,
                complete: function () {
                    parent.toggleClass('is-open');
                }
            });

        })
    }*/

    $('#form-upload__btn').click(function (e) {
        e.preventDefault()
        $('input#form-upload__actual-btn').click()
    })

    $('input#form-upload__actual-btn').on('change', function () {
        let files = $('input#form-upload__actual-btn')[0].files
        let data = new FormData()

        $.each(files, function(key, value){
            data.append(key, value)
        });

        try {
            $('.form-upload__image-status').append("<div class='cart__file-list-indicator'><span style='width: 0'></span></div>")

            $.ajax({
                url: '/wp-admin/admin-ajax.php/?action=post_upload_custom_file',
                type: 'POST',
                data: data,
                dataType: 'json',
                cache: false,
                processData: false,
                contentType: false,
                xhr: function(){
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
                success: function(response){
                    console.log(response)
                    $.each(files, function (key, singleFile) {
                        console.log(singleFile)
                        $('.popup-load__inner').before(`
                            <div class="product-options__loaded-files-item">
                                <img class="product-options__loaded-files-img" src=https://bannerprintingsandiego.com/wp-content/themes/bpsd/assets/img/icons/${singleFile.name.split('.')[1]}.svg /> 
                                <span class="product-options__loaded-files-title">${singleFile.name}</span>
                                <div class="product-options__loaded-files-status"></div>
                            </div>`
                        )
                    })
                    $.each($(".product-options__loaded-files-status"), (key, item) => item.innerHTML = `<div class="product-options__loaded-files-loader__done"></div>`)
                    $('.cart__file-list-indicator').remove()
                },
                error: function( jqXHR, textStatus){
                    alert('Ошибка загрузки файла: ' + jqXHR)
                    console.error(textStatus);
                }
            })
        } catch (e) {
            alert(e)
        }

        $.each(files, function (key, singleImage) {
            console.log(singleImage)

            $('.form-upload__image').append(`
                <div  class="product-options__loaded-files-item">
                    <img class="product-options__loaded-files-img" src=https://bannerprintingsandiego.com/wp-content/themes/bpsd/assets/img/icons/${singleImage.name.split('.')[1]}.svg /> 
                    <span class="product-options__loaded-files-title">${singleImage.name}</span>
                    <div class="product-options__loaded-files-status"></div>
                </div>`)
        })
    })

    $('#get-a-quote').submit(function (e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: ajax_data.url,
            data: 'action=form_send&' + $(this).serialize(),
            success: function (data) {
                console.log(data)

                if (data == 'ok') {
                    $('.form-field__success').css('display', 'flex')
                        .html('<p style="font-size: 22px;">The message has been sent!</p>')
                    $('.form-group input').val('')
                    $('.form-upload__image').html('')
                    $('.form-group__textarea textarea').val('')
                }
            }
        });
    })

    var prevScrollpos = window.pageYOffset;
    $(window).on('scroll',function () {
        var currentScrollPos = window.pageYOffset;
        if (prevScrollpos > currentScrollPos) {
          $("#page-up").css('display', 'block');
        } else {
            $("#page-up").css('display', 'none');
        }
        prevScrollpos = currentScrollPos;
    });

    $('#page-up').click(function (e) {
        e.preventDefault();
        $('body,html').animate({
            scrollTop: 0
        }, 400);
        return false;
    });

    function header_padding() {
        $('body').css('padding-top', $('.header').height())
    }

    // zE('webWidget', 'hide');
    // zE('webWidget', 'close');

    $('.main-menu__category-list-item').on('mouseover', function () {
        let menuListItem = $(this).children('ul.sub-menu__category').find('li').first()
        menuListItem.addClass('show')

        let lastMenu = $('.main-menu__category-list').children('.main-menu__category-list-item').last()
        lastMenu = lastMenu.children('ul.sub-menu__category')

        if (document.body.clientWidth > 766) {
            let subMenu = $(this).children('ul.sub-menu__category')
            if (subMenu.offset()) {
                if (subMenu.offset().left > 550) {
                    lastMenu.css('left', '-500px')
                }
            }
        }
    })

    if (document.body.clientWidth < 768) {
        $('.main-menu__category-list-item > ul > li').each(function () {
            $(this).removeClass('show')
        })
    }

    $('.main-menu__category-list-item > ul > li').on('mouseover', function() {
        if (document.body.clientWidth > 768) {
            $(this).addClass('show').siblings().removeClass('show')
        }
    });


      $(function(){

        if($(window).width() > 770){

        $(".user-nav__button-cart").hover(function(){
        $(".cart-popup").prependTo(".user-nav__button-cart").show("slow", "linear");
        },function(){$(".cart-popup").hide("slow", "linear");
    });

}
});


$(function(){

    if($(window).width() > 770){

    $(".user-nav__button-login").hover(function(){
    $(".user-popup").prependTo(".user-nav__button-login").show("slow", "linear");
    },function(){$(".user-popup").hide("slow", "linear");
});
    }
});

    // let checkout_boxes= $('.checkout__box .checkout__inputs')
    // $('.checkout__button').on('click',function (e) {
    //     cur_index=checkout_boxes.index( $(this).parents('.checkout__inputs') )
    //     if(cur_index<=checkout_boxes.length-2){
    //         checkout_boxes.hide();
    //         $(checkout_boxes[cur_index+1]).show();
    //     }
    //
    // })

});
