<style>
    .account {
        display: flex;
        justify-content: space-between;
    }

    .account-billing,
    .account-shiping {
        display: grid;
    }

    .active {
        height: 30px;
        background: #2DC871;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 10px;
        margin-top: 10px;
        border-radius: 5px;
    }

    .account-input {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 3px 3px;
        background: #d8d8d8;
        border-radius: 4px;
        margin-top: 1.5px;
        margin-bottom: 1.5px;
    }

    .account-input .header {
        margin-right: 20px;
    }

    .products {
        display: flex;
        justify-content: space-between;
    }

    .product__add-input {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 3px 3px;
        background: #d8d8d8;
        border-radius: 4px;
        margin-top: 1.5px;
        margin-bottom: 1.5px;
    }

    input[type="submit"] {
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        font-weight: 700;
        border-bottom: 5px solid #01247a;
        background: #0045ee;
        color: #fff;
        cursor: pointer;
    }

    input[type="submit"]:hover {
        margin-top: 5px;
        border-bottom: none;
    }

    .account,
    .products {
        gap: 150px;
    }

    .account > div,
    .products > div,
    .products > ul {
        flex: 1;
    }

    .product__search {
        position: relative;
    }

    .product__search-panel {
        position: absolute;
        top: 0;
        right: 0;
        margin-top: 36px;
        border-radius: 5px;
        background: #fff;
    }

    .product__search-panel > li {
        padding: 5px 10px;
        cursor: pointer;
    }

    .product__search-panel > li:hover {
        background: #a1a1a1;
        color: #fff;
    }

    .product__search-panel > li > span {
        margin-left: 5px;
        font-style: italic;
    }

    .container__btn {
        display: flex;
        flex-direction: column;
        width: 15%;
    }

    .product__add-btn {
        margin-top: 10px;
        margin-bottom: 25px;
        padding: 8px;
        font-weight: 700;
        cursor: pointer;
    }

    .delete {
        cursor: pointer;
        text-decoration: underline;
        color: #6258ff;
    }

    .delete:hover {
        text-decoration: none;
        color: #0c3db3;
    }

    [data-error-message] {
        text-align: end;
    }
</style>
<div class="wrap" data-container>
    <h2>Add customer order</h2>
    <div class="order__status"></div>
    <div class="account" data-account>
        <?php
        $countries = WC()->countries->get_shipping_countries();
        $states = array_merge(WC()->countries->get_allowed_country_states(), WC()->countries->get_shipping_country_states());

        /*echo '<pre>';
        print_r($countries);
        echo '</pre>';

        echo '<pre>';
        print_r($states);
        echo '</pre>';*/
        ?>
        <script data-states type="application/ld+json">
            <?= wp_json_encode($states); ?>
        </script>
        <div class="account-billing">
            <h3>Billing</h3>
            <label class="account-input" data-error-name="billing_email">
                <span class="header">Email address</span>
                <input type="text" name="billing_email">
            </label>
            <span data-error-message></span>
            <label class="account-input" data-error-name="billing_phone">
                <span class="header">Phone</span>
                <input type="text" name="billing_phone">
            </label>
            <span data-error-message></span>
            <label class="account-input" data-error-name="billing_first_name">
                <span class="header">First name</span>
                <input type="text" name="billing_first_name">
            </label>
            <span data-error-message></span>
            <label class="account-input" data-error-name="billing_last_name">
                <span class="header">Last name</span>
                <input type="text" name="billing_last_name">
            </label>
            <span data-error-message></span>
            <label class="account-input" data-error-name="billing_address_1">
                <span class="header">Street address</span>
                <input type="text" name="billing_address_1">
            </label>
            <span data-error-message></span>
            <label class="account-input" data-error-name="billing_address_2">
                <span class="header">Apartment, suite, unit, etc. (optional)</span>
                <input type="text" name="billing_address_2">
            </label>
            <span data-error-message></span>
            <label class="account-input" data-error-name="billing_postcode">
                <span class="header">ZIP</span>
                <input type="text" name="billing_postcode">
            </label>
            <span data-error-message></span>
            <label class="account-input" data-error-name="billing_country">
                <span class="header">Country / Region</span>
                <select type="text" name="billing_country">
                    <?php foreach ($countries as $countryKey => $countryName): ?>
                        <option value="<?= $countryKey ?>"><?= $countryName ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <span data-error-message></span>
            <label class="account-input" data-error-name="billing_state">
                <span class="header">State</span>
                <select style="width: 44%;" type="text" name="billing_state">
                    <option value=""></option>
                </select>
            </label>
            <span data-error-message></span>
            <label class="account-input" data-error-name="billing_city">
                <span class="header">Town / City</span>
                <input type="text" name="billing_city">
            </label>
            <span data-error-message></span>
        </div>
        <div class="account-shiping">
            <h3>Shipping</h3>
            <label class="account-input" data-error-name="from_billing">
                <span class="header">From billing</span>
                <input type="checkbox" checked name="from_billing" value="true">
            </label>
            <span data-error-message></span>
            <label class="account-input" data-error-name="shipping_first_name">
                <span class="header">First name</span>
                <input type="text" name="shipping_first_name">
            </label>
            <span data-error-message></span>
            <label class="account-input" data-error-name="shipping_last_name">
                <span class="header">Last name</span>
                <input type="text" name="shipping_last_name">
            </label>
            <span data-error-message></span>
            <label class="account-input" data-error-name="shipping_address_1">
                <span class="header">Street address</span>
                <input type="text" name="shipping_address_1">
            </label>
            <span data-error-message></span>
            <label class="account-input" data-error-name="shipping_address_2">
                <span class="header">Apartment, suite, unit, etc. (optional)</span>
                <input type="text" name="shipping_address_2">
            </label>
            <span data-error-message></span>
            <label class="account-input" data-error-name="shipping_postcode">
                <span class="header">ZIP</span>
                <input type="text" name="shipping_postcode">
            </label>
            <span data-error-message></span>
            <label class="account-input" data-error-name="shipping_country">
                <span class="header">Country / Region</span>
                <select name="shipping_country">
                    <?php foreach ($countries as $countryKey => $countryName): ?>
                        <option value="<?= $countryKey ?>"><?= $countryName ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <span data-error-message></span>
            <label class="account-input" data-error-name="shipping_state">
                <span class="header">State</span>
                <select name="shipping_state">
                    <!--<option value="1">State Test1</option>
                    <option value="2">State Test2</option>
                    <option value="3">State Test3</option>-->
                </select>
            </label>
            <span data-error-message></span>
            <label class="account-input" data-error-name="shipping_city">
                <span class="header">Town / City</span>
                <input type="text" name="shipping_city">
            </label>
            <span data-error-message></span>
        </div>
    </div>
    <div class="products">
        <div class="products__add">
            <h3>Add product</h3>
            <div class="product__search">
                <label class="product__add-input">
                    <span class="header">Name:</span>
                    <input type="text" name="productName">
                </label>
                <ul class="product__search-panel" data-product-search></ul>
            </div>
            <div class="product__name__new"></div>
            <div class="product__count">
                <label class="product__add-input">
                    <span class="header">Count:</span>
                    <input type="text" name="productCount">
                </label>
            </div>
            <div class="product__size"></div>
        </div>
        <ul class="products__list" data-products-list>
            <h3 data-error-name="products">Products</h3>
            <span data-error-message></span>
            <script data-products-selected>[]</script>
        </ul>
    </div>
    <div class="container__btn">
        <button class="container__send product__add-btn">Add product</button>
        <input type="submit" value="Create order" class="container__send">
    </div>
</div>
<div class="clear"></div>

<script>
    jQuery(document).ready(function ($) {
        let selectors = {
            product: {
                search: "[data-product-search]"
            },
            products: {
                selected: "[data-products-selected]",
                list: "[data-products-list]",
                delete: "[data-products-item-delete]"
            },
            account: {
                main: "[data-account]"
            },
            main: "[data-container]",
            states: "[data-states]"
        };

        let container = document.querySelector(selectors.main);
        let productSearch = container.querySelector(selectors.product.search);
        let productSelected = container.querySelector(selectors.products.selected);
        let productsList = container.querySelector(selectors.products.list);
        let states = JSON.parse(container.querySelector(selectors.states).innerHTML);

        let searchPanelBlocked = false;

        var GlobProduct = []

        function getProductObject(id, html) {
            let name_options = html.replace('<span class="description">', ' | ').replace('</span>', '').split(' | ');
            let name = name_options[0];
            let optionsArray = name_options[1]?.split(', ');
            let options = [];

            if (optionsArray)
                optionsArray.forEach((option) => {
                    let optionArray = option.split(': ');
                    options.push({
                        name: optionArray[0],
                        value: optionArray[1]
                    });
                })

            return {
                id: id,
                name: name,
                count: 1,
                price: 0,
                options: options,
                customize: {}
            }
        }

        function generateTemplateProductItem(product, productKey) {
            let template = `<li class="products__item" data-product-key="${productKey}">`

            if (product.name.includes('CustomItem')) {
                template += `<div class="product__name">${product.customize.new_name}</div>`
            } else {
                template += `<div class="product__name">${product.name}</div>`
            }

            template += `<div class="product__quantity">${product.count} pcs.</div>
                <div class="product__options">`;
            product.options.forEach((option) => {
                template += `<div class="product__option">
                        <span class="product__option-name">` + option.name + `:</span>
                        <span class="product__option-value">` + option.value + `</span>
                    </div>`;
            })

            if (product.name.includes('CustomItem')) {
                let price = product.customize.new_price * product.count;
                template += `<div class="product__price">$${price}</div>`
            } else {
                template += ``
            }

            template += `</div><span class="delete" data-products-item-delete>Delete</span></li>`;

            return template;
        }

        function generateTemplateProductList() {
            let productsSelectedArray = JSON.parse($('[data-products-selected]').html());

            $('[data-products-list] .products__item').remove();
            productsSelectedArray.forEach((product, productKey) => {
                productsList.innerHTML += generateTemplateProductItem(product, productKey);
            })
            addEventProductsDelete();
        }

        function deletedProductSelected(productKey) {
            // console.log(`Deleted product key ${productKey}`)
            let productsSelectedArray = JSON.parse($('[data-products-selected]').html());

            // console.log('1', productsSelectedArray)
            productsSelectedArray.splice(productKey, 1);
            // console.log('2', productsSelectedArray)

            $('[data-products-selected]').html(JSON.stringify(productsSelectedArray));

            generateTemplateProductList();
        }

        function addEventProductsDelete() {
            let productsDelete = productsList.querySelectorAll(selectors.products.delete);

            productsDelete.forEach((productDelete) => {
                productDelete.addEventListener("click", (e) => {
                    let productKey = +e.target.closest("li").dataset.productKey;
                    deletedProductSelected(productKey);
                })
            });
        }

        function generateTemplateInputWidthHeight() {
            let template = `<label class="product__add-input">
                <span class="header">Height:</span>
                <input type="text" name="productHeight">
                </label>
                <label class="product__add-input">
                    <span class="header">Width:</span>
                    <input type="text" name="productWidth"">
                </label>`

            return template
        }

        function generateTemplateInputName() {
            let template = `
                <label class="product__add-input">
                    <span class="header">New Name:</span>
                    <input type="text" name="productNewName">
                </label>
                <label class="product__add-input">
                    <span class="header">Price:</span>
                    <input type="text" name="productPrice">
                </label>`;

            return template;
        }

        function searchAjax(productName) {
            searchPanelBlocked = true;

            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php', 'relative') ?>',
                method: 'GET',
                data: {
                    'action': 'woocommerce_json_search_products_and_variations',
                    'term': productName,
                    'security': '<?php echo wp_create_nonce('search-products'); ?>',
                    'exclude_type': 'variable',
                    'display_stock': 'true'
                },
                dataType: "json",
                success: function (data) {
                    productSearch.innerHTML = '';

                    for (let [productKey, product] of Object.entries(data)) {
                        productSearch.innerHTML += '<li data-product-id="' + productKey + '" data-product-add>' + product + '</li>';
                    }

                    searchPanelBlocked = false;
                }
            });

            return true;
        }

        $('input[name="productName"]').keyup(function (product) {
            let productName = product.target.value;

            if (!searchPanelBlocked && productName.length >= 3) {
                searchAjax(productName)
            }

        });

        productSearch.addEventListener('click', function (e) {
            let target = e.target;

            if (target.classList.contains('description'))
                target = target.closest('li')

            let productId = +target.dataset.productId;

            $('input[name="productName"]').val('')
            productSearch.innerHTML = '';

            clearError();
            let products = getProductObject(productId, target.innerHTML);

            document.dispatchEvent(
                new CustomEvent("products:selected", {
                    detail: products
                })
            );
        });

        document.addEventListener("products:selected", (e) => {
            let product = e.detail;
            GlobProduct.product = product

            $('input[name="productName"]').val(product.name)

            let customSize = product.options.find((option) => {
                if (option?.value?.toLowerCase()?.includes('custom')) {
                    return true
                }
            })

            if (customSize) {
                $('.product__size').html(generateTemplateInputWidthHeight())
            } else {
                $('.product__size').html('')
            }

            if (product.name.includes('CustomItem')) {
                $('.product__name__new').html(generateTemplateInputName())
            } else {
                $('.product__name__new').html('')
            }
        });

        $('.product__add-btn').click(function () {
            let product = GlobProduct.product
            let productCount = $('input[name="productCount"]').val()
            let productWidth = $('[name="productWidth"]').val()
            let productHeight = $('[name="productHeight"]').val()

            let productName = $('[name="productNewName"]')?.val()
            let productPrice = $('[name="productPrice"]')?.val()

            if (productName) {
                product.customize['new_name'] = productName;
            }
            if (productPrice) {
                product.customize['new_price'] = productPrice;
            }

            if (productCount) {
                product.count = productCount
            } else {
                product.count = 1
            }

            if (product.options.length) {
                if (product.options[0].value) {
                    let productSize = product.options[0].value.split(' x ')

                    if (productHeight) {
                        productSize[0] = productHeight
                        product.customize['height'] = +productHeight;
                    }

                    if (productWidth) {
                        productSize[1] = productWidth
                        product.customize['width'] = +productWidth;
                    }

                    productSize = productSize.join(' x ')

                    product.options[0].value = productSize
                }
            }

            let productsSelectedArray = JSON.parse($('[data-products-selected]').html());

            console.log('product', product);
            productsSelectedArray.push(product);

            //productSelected.innerHTML = JSON.stringify(productsSelectedArray);

            $('[data-products-selected]').html(JSON.stringify(productsSelectedArray))

            generateTemplateProductList();

            clearProductData();
        })

        function clearProductData() {
            let productInputs = $('.product__add-input input')

            for (let i = 0; i < productInputs.length; i++) {
                productInputs.val('')
            }
            $('.product__size').html('')
        }

        function checkSelect() {
            let billingCountry = $('[name="billing_country"]');
            let shippingCountry = $('[name="shipping_country"]');
            let billingState = $('[name="billing_state"]');
            let shippingState = $('[name="shipping_state"]');
            let fromBilling = $('[name="from_billing"]').val()

            let billingStateHtml = ``;
            let shippingStateHtml = ``;
            if (states[billingCountry.val()] && Object.keys(states[billingCountry.val()]).length) {
                let billingStatesInCountry = states[billingCountry.val()];

                for (let billingStatesKey in billingStatesInCountry) {
                    let stateName = billingStatesInCountry[billingStatesKey]
                    let newOption = `<option value="${billingStatesKey}" ${(billingState.val() === billingStatesKey) ? 'selected' : ''}>${stateName}</optionp>`

                    billingStateHtml += newOption
                }

                billingStateHtml = `<select type="text" name="billing_state">${billingStateHtml}</select>`;
            } else {
                billingStateHtml = `<input type="text" name="billing_state">`;
            }

            if (states[shippingCountry.val()] && Object.keys(states[shippingCountry.val()]).length) {
                let shippingStatesInCountry = states[shippingCountry.val()];

                for (let shippingStatesKey in shippingStatesInCountry) {
                    let stateName = shippingStatesInCountry[shippingStatesKey]
                    let newOption = `<option value="${shippingStatesKey}" ${(shippingState.val() === shippingStatesKey) ? 'selected' : ''}>${stateName}</optionp>`

                    shippingStateHtml += newOption
                }

                shippingStateHtml = `<select type="text" name="shipping_state">${shippingStateHtml}</select>`;
            } else {
                shippingStateHtml = `<input type="text" name="shipping_state">`;
            }

            if (fromBilling === 'true')
                shippingStateHtml = $(billingStateHtml).attr('name', 'shipping_state');


            billingState.replaceWith(billingStateHtml);
            shippingState.replaceWith(shippingStateHtml);

            if (fromBilling === 'true')
                shippingState.find(`option[value="${billingState.val()}"]`).prop('selected', true);
        }

        function getAccountData() {
            let inputs = $(selectors.account.main + ' input, ' + selectors.account.main + ' select');

            let account = [];
            inputs.each(function (index, element) {
                let name = $(element).attr('name');
                let value = $(element).val();

                account.push({
                    name: name,
                    value: (name === 'from_billing' && value === 'true') ? true : (name === 'from_billing' && value === 'false') ? false : value
                })
            });

            let statusFromBilling = !!(account.find(item => item.name === "from_billing" && item.value));

            if (statusFromBilling) {
                account.forEach((input) => {
                    let name = input.name;
                    if (name.includes('billing_')) {
                        let shippingName = name.replace('billing_', 'shipping_');
                        $(`[name="${shippingName}"]`).val(input.value)
                    }
                })
            }
        }

        function clearError() {
            $('[data-error-message]').animate({opacity: 0}, 1000, function () {
                $(this).css({display: 'none'})
            });
            return true;
        }

        function checkRules() {
            $('[data-error-message]').animate({opacity: 1}, 1000, function () {
                $(this).css({display: 'unset'})
                $(this).text('')
            });

            let inputs = $(selectors.account.main + ' input, ' + selectors.account.main + ' select');
            let products = JSON.parse($(selectors.products.selected).text());

            let account = [];
            inputs.each(function (index, element) {
                let name = $(element).attr('name');
                let value = $(element).val();

                account.push({
                    name: name,
                    value: (name === 'from_billing' && value === 'true') ? true : (name === 'from_billing' && value === 'false') ? false : value
                })
            });

            let error = [];
            let errorStatus = false;

            if (!products.length) {
                error.push({
                    name: "products",
                    value: "Список продуктов пустой."
                })
                errorStatus = true;
            }

            if ($('input[name="billing_email"]').val() == '') {
                error.push({
                    name: "billing_email",
                    value: "Не заполнен Email адрес"
                })
                errorStatus = true;
            }

            /*account.forEach((input) => {
                if (input.name === 'from_billing')
                    return true;

                if (!input.value.length) {
                    error.push({
                        name: input.name,
                        value: "Поле " + input.name + " не может быть пустое"
                    })
                    errorStatus = true;
                }
            })*/

            if (errorStatus)
                return {status: false, error: error};
            else
                return {
                    status: true, response: {
                        account: account,
                        products: products
                    }
                }
        }

        $(selectors.account.main).on('keyup', 'input', function () {
            getAccountData();
        })
        $(selectors.account.main).on('change', 'select', function () {
            checkSelect();

            getAccountData();
        })
        $(selectors.account.main + ' input[type="checkbox"]').click(function () {
            let value = $(this).prop('checked')
            $(this).prop('value', value);

            checkSelect();

            getAccountData();
        })
        $(selectors.main + ' input[type="submit"]').click(function () {
            let rules = checkRules();
            let email = $('input[name="billing_email"]').val()

            // console.log(rules)

            if (rules.status) {
                $.ajax({
                    url: '<?php echo admin_url('admin-ajax.php', 'relative') ?>',
                    method: 'POST',
                    data: {
                        'action': 'admin_generation_order',
                        'data': rules.response,
                        'mail': email,
                        'exclude_type': 'variable',
                        'display_stock': 'true'
                    },
                    dataType: "json",
                    success: function (data) {
                        // console.log(data)
                        $('div.order__status').addClass('active').html('<span>Message sent!</span>')
                    }
                });
            } else {
                rules.error.forEach((rule) => {
                    $(`[data-error-name="${rule.name}"]`).next('span[data-error-message]').text(rule.value);
                })

                setTimeout(clearError, 1500);
            }
        })

        checkSelect();
    });
</script>