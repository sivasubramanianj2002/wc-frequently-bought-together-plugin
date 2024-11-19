<?php
namespace Fbt\App\Controller;
use Elementor\Core\Admin\Admin;
use Fbt\App\Model\Model;
defined("ABSPATH") or die();
class Product
{
    /**
     * 
     */
    public static function addProductTab($tabs)
    {
        if(!is_array($tabs)){
            return $tabs;
        }
        //param check
        //sanitize
        //validate
        //operation
        $tabs['fbt'] = ['label'    => __('Frequently Bought Together', 'frequently-bought-together'),
            'target'   => 'fbt_product_data',
            ];
        return $tabs;
    }


    public static function addProductTabView(): void
    {
        global $post;
        $fbt_products= Model::getFbtProducts($post->ID);
        $products= Model::getProducts();
        $args=[
            'fbt_products' => $fbt_products,
            'products' => $products,
        ];
        $template_path = 'products-view.php';
        if (file_exists(FBT_PATH . 'App/View/' . $template_path)) {
            //add the model code through here
            wc_get_template($template_path,$args,'',FBT_PATH.'App/View/');
        }
    }


    public static function enqueueAdminScripts():void{
        $screen= get_current_screen();
        if('product' === $screen->post_type){
            wp_enqueue_style('fbt-admin-style',FBT_URL.'Assets/css/style.css');
            wp_enqueue_script('fbt-admin-script',FBT_URL.'Assets/js/script.js',['jquery'],'1.0.1','true');
            wp_localize_script('fbt-admin-script','fbt_admin_ajax',[
                'admin_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('fbt_admin_nonce')
            ]);
        }
    }

    public static function enqueueFrontendScripts():void{
       if(is_product()){
            wp_enqueue_style('fbt-frontend-style',FBT_URL.'Assets/css/style.css');
            wp_enqueue_script('fbt-frontend-script',FBT_URL.'Assets/js/script.js',['jquery'],'1.0.1','true');
            wp_localize_script('fbt-frontend-script','fbt_ajax',[
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('fbt_add_to_cart_nonce'),
                'selectedProductMessage' => esc_html__('Please select atleast one product', 'frequently-bought-together'),
            ]);
        }
    }

    
    public static function saveProducts($post_id)
    {
        if (isset($_POST['fbt_products'])) {
            $fbt_products = array_map('sanitize_text_field', $_POST['fbt_products']);
            update_post_meta($post_id, '_fbt_products', implode(',', $fbt_products));
        } else {
            delete_post_meta($post_id, '_fbt_products');
        }
    }

    public static function displayProducts()
    {
        global $post;
        $fbt_products= Model::getFbtProducts($post->ID);
        $args=[
            'fbt_products' => $fbt_products,
        ];
        $template_path = 'single-product.php';
        if (file_exists(FBT_PATH . 'App/View/' . $template_path)) {
            wc_get_template($template_path, $args, '', FBT_PATH . 'App/View/');
        }
    }


    public static function addToCart()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'fbt_add_to_cart_nonce')) {
            wp_send_json_error(['message' => esc_html__('Nonce verification failed', 'frequently-bought-together')]);
        }

        if (isset($_POST['product_ids']) && is_array($_POST['product_ids'])) {
            foreach ($_POST['product_ids'] as $product_id) {
                WC()->cart->add_to_cart($product_id);
            }
            wp_send_json_success(['message' => esc_html__('Product added to the cart', 'frequently-bought-together')]);
        }
        wp_send_json_error(['message' => esc_html__('No products selected', 'frequently-bought-together')]);
        
    }
}