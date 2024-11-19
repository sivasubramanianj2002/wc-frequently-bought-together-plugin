<?php
namespace Fbt\App;
defined("ABSPATH") or die();
use Fbt\App\Controller\Product;

class Router
{
    public function init()
    {
        if(is_admin()){
            add_filter('woocommerce_product_data_tabs', [Product::class, 'addProductTab']);

            add_action('woocommerce_product_data_panels', [Product::class, 'addProductTabView']);
    
            add_action('woocommerce_process_product_meta', [Product::class, 'saveProducts']);
    
            add_action('admin_enqueue_scripts', [Product::class, 'enqueueAdminScripts']);
    
        }
            add_action('wp_enqueue_scripts',[Product::class,'enqueueFrontendScripts']);
            add_action('woocommerce_after_single_product', [Product::class, 'displayProducts']);

        if (wp_doing_ajax()) {
            add_action('wp_ajax_fbt_add_to_cart', [Product::class, 'addToCart']);
        }
    }
}