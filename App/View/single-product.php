<?php
$fbt_products= isset($args['fbt_products'])?$args['fbt_products']:[];

if (!empty($fbt_products) && is_array($fbt_products)) {
    ?>
    <div class="fbt-products">
        <br><br>
        <h2 class="fbt-title">Frequent Bought Together</h2>
        <form id="fbt-form" method="post">
            <div class="Products">
                <?php
                foreach ($fbt_products as $fbt_product_id) {
                    $fbt_product = wc_get_product($fbt_product_id);
                    if ($fbt_product && 'publish' === get_post_status($fbt_product_id)) {
                        ?>
                        <div class="fbt-form">
                            <a href="<?php echo esc_url(get_permalink($fbt_product_id)); ?>" class="fbt-product-img">
                                <img src="<?php echo esc_url(get_the_post_thumbnail_url($fbt_product_id)); ?>" alt="<?php echo esc_attr($fbt_product->get_name()); ?>">
                            </a>
                            <div class="fbt-content">
                                <div class="fbt-item">
                                    <label>
                                        <input type="checkbox" name="fbt_product_ids[]" value="<?php echo esc_attr($fbt_product_id); ?>" checked class="check">
                                    </label>
                                    <a href="<?php echo esc_url(get_permalink($fbt_product_id)); ?>"><?php echo esc_html($fbt_product->get_name()); ?></a>
                                </div>
                            </div>
                            <div class="fbt-content">
                                <span class="price"><?php echo wp_kses_post($fbt_product->get_price_html()); ?></span>
                            </div>
                        </div>
                        <?php
                    } else {
                        echo '<p>Product not found or not published for ID: ' . esc_html($fbt_product_id) . '</p>';
                    }
                }
                ?>
            </div>
            <button id="fbt-add-all-to-cart" type="submit">
                <?php echo esc_html__('Add to Cart', 'frequently-bought-together') ?>
            </button>
        </form>
        <div id="toast-container"></div>
    </div>
    <?php
} else {
    echo '<h3>No frequently bought together products found.</h3>';
}
?>