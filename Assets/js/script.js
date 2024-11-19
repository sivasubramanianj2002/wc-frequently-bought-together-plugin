jQuery(document).ready(function ($) {
    $('#fbt-form').on('submit', function (event) {
        event.preventDefault();

        let product_ids = [];
        $('input[name="fbt_product_ids[]"]:checked').each(function () {
            product_ids.push($(this).val());
        });

        if (product_ids.length > 0) {
            $.ajax({
                url: fbt_ajax.ajax_url,
                method: 'POST',
                data: {
                    action: 'fbt_add_to_cart',
                    nonce: fbt_ajax.nonce,
                    product_ids: product_ids,
                },
                success: function (response) {
                    if (response.success) {
                        showToast(response.data.message);
                        location.reload();
                    } else {
                        showToast(response.data.error);
                    }
                },
                error: function () {
                    showToast("Something went wrong. Please try again.");
                },
            });
        } else {
            showToast(fbt_ajax.selectProductMessage);
        }
    });


    $('#fbt_product_search').on('keyup', function () {
        const searchText = $(this).val().toLowerCase();
        let hasResults = false;
        $('#fbt_product_dropdown').show();
        $('#fbt_product_dropdown .list-group-item').each(function () {
            const productName = $(this).data('name').toLowerCase();
            if (productName.startsWith(searchText)) {
                $(this).show();
                hasResults = true;
             
            } else {
                $(this).hide();
            }
        });

        if (!hasResults) {
            $('#fbt_product_dropdown').hide();
        }
    });

    
    $('#fbt_product_dropdown').on('click', '.list-group-item', function () {
        const productId = $(this).data('id');
       
        const productName = $(this).data('name');
        const selectedProduct = `
            <div class="selected-product" data-id="${productId}">
                ${productName} <span class="remove-product">&times;</span>
                <input type="hidden" name="fbt_products[]" value="${productId}">
            </div>
        `;
        $('#selected_products').append(selectedProduct);
        $('#fbt_product_dropdown').hide();
        $('#fbt_product_search').val('');
    });

    
    $('#selected_products').on('click', '.remove-product', function () {
        $(this).parent().remove();
    });

    $(document).on('click', function (e) {
        if (!$(e.target).closest('#fbt_product_data').length) {
            $('#fbt_product_dropdown').hide();
        }
    });
});

function showToast(message, type = 'success') {
    const container = document.getElementById('toast-container');
    
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.textContent = message;

    container.appendChild(toast);

    setTimeout(() => {
        toast.remove();
    }, 3000);
}


