<?php
defined("ABSPATH") or die();
$fbt_products= isset($args['fbt_products']) ? $args['fbt_products']:[];
$products= isset($args['products']) ? $args['products']:[]

?>

    <div id="fbt_product_data" class="panel woocommerce_options_panel">
        <div class="options_group">
            <label for="fbt_product_search"><?php esc_html_e('Search Frequently Bought Together Products', 'frequently-bought-together'); ?></label>

            <!-- Search bar -->
            <div class="form-group">
                <input type="text" id="fbt_product_search" class="form-control search" placeholder="<?php esc_attr_e('Search products...', 'frequently-bought-together'); ?>">
            </div>

            <!-- Dropdown list -->
            <ul id="fbt_product_dropdown" class="list-group list" style="display: none;">
                <?php
                if (!empty($products)) {
                    foreach ($products as $product_id) {
                        $product_name = get_the_title($product_id);
                        ?>
                        <li class="list-group-item" data-id="<?php echo esc_attr($product_id); ?>" data-name="<?php echo esc_html($product_name); ?>">
                            <?php echo esc_html__($product_name,'frequently-bought-together'); ?>
                        </li>
                        <?php
                    }
                } else {
                    echo '<li class="list-group-item">' . esc_html__('No products found', 'frequently-bought-together') . '</li>';
                }
                ?>
            </ul>

            <!-- Selected products list -->
            <div id="selected_products" class="selected-products">
                <?php
                if (!empty($fbt_products)) {
                    foreach ($fbt_products as $product_id) {
                        $product_name = get_the_title($product_id);
                        ?>
                        <div class="selected-product" data-id="<?php echo esc_attr($product_id); ?>">
                            <?php echo esc_html__($product_name,'frequently-bought-together'); ?> <span class="remove-product">&times;</span>
                            <input type="hidden" name="fbt_products[]" value="<?php echo esc_attr($product_id); ?>">
                        </div>
                        <?php
                    }
                }
                ?>
            </div>

            <p class="description"><?php esc_html_e('Select products that are frequently bought together.', 'frequently-bought-together'); ?></p>
        </div>
    </div>

    <?php wp_reset_postdata(); ?>