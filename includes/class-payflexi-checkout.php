<?php

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 */
class PayFlexi_Checkout {

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.1.0
     * @access   protected
     * @var      Payflexi_Checkout_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;
    /**
     * The current version of the plugin.
     *
     * @since    1.1.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    public $version;
    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct() {
		$this->version = '1.1.0';
        $this->load_dependencies();
		$this->woo_gateway_hooks();
		$prefix = is_network_admin() ? 'network_admin_' : '';
		add_filter("{$prefix}plugin_action_links_" . PAYFLEXI_CHECKOUT_INIT, array($this, 'woocommerce_payflexi_checkout_plugin_action_links'), 10, 4);
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Payflexi_Paylater_Loader. Orchestrates the hooks of the plugin.
     * - Payflexi_Paylater_Admin. Defines all hooks for the admin area.
     * - Payflexi_Paylater_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.1.0
     * @access   private
     */
    private function load_dependencies() {

        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-payflexi-checkout-loader.php';

        if (class_exists('WC_Payment_Gateway')) {
            require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-payflexi-checkout-gateway.php';
		}

		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-payflexi-checkout-helper.php';

		$this->loader = new Payflexi_Checkout_Loader();

		$this->payflexi_checkout_helper = new PayFlexi_Checkout_Helper();
    }

    private function woo_gateway_hooks() {
		if ( ! function_exists( 'WC' ) ) {
			add_action( 'admin_notices', array($this, 'payflexi_checkout_woocommerce_admin_notice'));
			return;
		} 
        add_action('admin_notices', array($this, 'payflexi_checkout_testmode_notice'));
        
        if ($this->payflexi_checkout_helper->payflexi_checkout_get_option('popup_information_enabled') == "yes") {
            add_action('woocommerce_before_add_to_cart_form', array($this, 'print_payflexi_info_for_product_detail_page'), 22);
        }
        
        add_filter('woocommerce_payment_gateways', array($this, 'woocommerce_add_payflexi_checkout_gateway'), 10, 1);

        add_action('wp_enqueue_scripts', array($this, 'init_website_assets'));
    }
    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run() {
        $this->loader->run();
    }
    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Payflexi_Checkout_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader() {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version() {
        return $this->version;
    }

   	/**
    * Add Payflexi Gateway to WC
    **/
    public function woocommerce_add_payflexi_checkout_gateway($methods) {
		$methods[] = 'Payflexi_Checkout_Gateway';
        return $methods;
	}
	/**
	* Note: Hooked onto the "wp_enqueue_scripts" Action to avoid the Wordpress Notice warnings
	*
	* @since	1.0.0
	* @see		self::__construct()		For hook attachment.
	*/
    public function init_website_assets() {

        $payflexi_checkout_params = array(
            'key'  => $this->payflexi_checkout_helper->payflexi_checkout_public_key()
        );

        if (is_product()) {
            $suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
            wp_enqueue_script( 'payflexi_checkout_sdk', 'https://payflexi.co/js/v1/bnpl-payflexi.js', array(), null, false);
            wp_enqueue_script(
                'payflexi_checkout_frontend',
                PAYFLEXI_CHECKOUT_ASSETS_URL . '/js/pf-checkout-frontend' . $suffix . '.js',
                array(
                    'jquery',
                    'payflexi_checkout_sdk',
                    ),
                PAYFLEXI_CHECKOUT_VERSION,
                true
            );
            wp_localize_script('payflexi_checkout_frontend', 'payflexi_checkout_params', $payflexi_checkout_params );
        }


        if (is_checkout_pay_page()) {

            $order_key = urldecode( $_GET['key'] );
            $order_id  = absint( get_query_var( 'order-pay' ) );

            $order = wc_get_order( $order_id );
    
            $payment_method = method_exists( $order, 'get_payment_method' ) ? $order->get_payment_method() : $order->payment_method;
    
            $suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
            wp_enqueue_script( 'payflexi_checkout_sdk', 'https://payflexi.co/js/v1/bnpl-payflexi.js', array(), null, false);
            wp_enqueue_script(
                'payflexi_checkout_frontend',
                PAYFLEXI_CHECKOUT_ASSETS_URL . '/js/pf-checkout-frontend' . $suffix . '.js',
                array(
                    'jquery',
                    'payflexi_checkout_sdk',
                    ),
                PAYFLEXI_CHECKOUT_VERSION,
                true
            );            
        
            if ( is_checkout_pay_page() && get_query_var( 'order-pay' ) ) {

                $email = method_exists( $order, 'get_billing_email' ) ? $order->get_billing_email() : $order->billing_email;
                $first_name = method_exists( $order, 'get_billing_first_name' ) ? $order->get_billing_first_name() : $order->billing_first_name;
                $last_name  = method_exists( $order, 'get_billing_last_name' ) ? $order->get_billing_last_name() : $order->billing_last_name;
                $amount = $order->get_total();
                $txnref = $order_id . '_' .time();
    
                $line_items = $order->get_items();
                $product_names = '';
                $product_descriptions = '';
                $product_url = '';
                $product_urls = array();
                $product_image = '';
                $product_images = array();

                foreach ( $line_items as $item_id => $item ) {

                    $product = $item->get_product();

                    $name  = empty($product->get_name()) ? $product->get_title() : $item['name'];
                    $quantity = $item['qty'];
                    $product_names .= $name . ' (Qty: ' . $quantity . ')';
                    $product_names .= ' | ';

                    $description = $product->get_description();
                    $product_descriptions .= $description . '</br></br>';

                    $url = get_permalink( $product->get_id());
                    $product_url = $url;
                    $product_urls[] = array('name' => $name, 'url' => $url);

                    $image = wp_get_attachment_url($product->get_image_id());
                    $product_image = $image;
                    $product_images[] = $image;

                }

                $product_names = rtrim($product_names, ' | ' );
                $product_descriptions = rtrim($product_descriptions, '</br></br>');
    
                $the_order_id   = method_exists( $order, 'get_id' ) ? $order->get_id() : $order->id;
                $the_order_key  = method_exists( $order, 'get_order_key' ) ? $order->get_order_key() : $order->order_key;
    
                if ( $the_order_id == $order_id && $the_order_key == $order_key ) {
                    $payflexi_checkout_params['email']  = $email;
                    $payflexi_checkout_params['name'] = $first_name . ' ' . $last_name;
                    $payflexi_checkout_params['amount'] = $amount;
                    $payflexi_checkout_params['txnref']  = $txnref;
                    $payflexi_checkout_params['currency'] = get_woocommerce_currency();
                    $payflexi_checkout_params['product_names'] = $product_names;
                    $payflexi_checkout_params['product_descriptions'] = $product_descriptions;
                    $payflexi_checkout_params['product_url'] = $product_url;
                    $payflexi_checkout_params['product_urls'] = $product_urls;
                    $payflexi_checkout_params['product_image'] = $product_image;
                    $payflexi_checkout_params['product_images'] = $product_images;
                }

                $payflexi_checkout_params['meta_order_id'] = $order_id;
                $first_name = method_exists( $order, 'get_billing_first_name' ) ? $order->get_billing_first_name() : $order->billing_first_name;
                $last_name  = method_exists( $order, 'get_billing_last_name' ) ? $order->get_billing_last_name() : $order->billing_last_name;
                $payflexi_checkout_params['meta_name'] = $first_name . ' ' . $last_name;
                $payflexi_checkout_params['meta_email'] = $email;
                $billing_phone = method_exists( $order, 'get_billing_phone' ) ? $order->get_billing_phone() : $order->billing_phone;
                $payflexi_checkout_params['meta_phone'] = $billing_phone;
                $billing_address = $order->get_formatted_billing_address();
                $billing_address = esc_html( preg_replace( '#<br\s*/?>#i', ', ', $billing_address ) );
                $payflexi_checkout_params['meta_billing_address'] = $billing_address;
                $shipping_address = $order->get_formatted_shipping_address();
                $shipping_address = esc_html( preg_replace( '#<br\s*/?>#i', ', ', $shipping_address ) );
                if ( empty( $shipping_address ) ) {
                    $billing_address = $order->get_formatted_billing_address();
                    $billing_address = esc_html( preg_replace( '#<br\s*/?>#i', ', ', $billing_address ) );
                    $shipping_address = $billing_address;
                }
                $payflexi_checkout_params['meta_shipping_address'] = $shipping_address;

                update_post_meta( $order_id, '_payflexi_txn_ref', $txnref );
            }
            // in most payment processors you have to use PUBLIC KEY to obtain a token
            wp_localize_script('payflexi_checkout_frontend', 'payflexi_checkout_params', $payflexi_checkout_params );
        }

        return true;
    }
    /**
	* Print a paragraph of Payflexi info onto the individual product pages if enabled and the product is valid.
	*
	 * Note:	Hooked onto the "woocommerce_single_product_summary" Action.
	*/
	public function print_payflexi_info_for_product_detail_page() {
		?>
		<div class="what-is-payflexi-container">
			<a href="#" id="payflexi_popup_info_link">
				<?php echo $this->payflexi_checkout_helper->payflexi_checkout_get_option('popup_trigger_text') ?>
			</a>
		</div>
	<?php }
	 /**
    * Add Settings link to the plugin entry in the plugins menu
    **/
    public function woocommerce_payflexi_checkout_plugin_action_links( $links ) {
        $settings_link = array(
            'settings' => '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=checkout&section=payflexi-checkout' ) . '" title="View Payflexi Checkout Settings">Settings</a>'
        );
        return array_merge( $links, $settings_link );
    }
	/**
	* Print an admin notice if woocommerce is deactivated
	* @return void
	* @use    admin_notices hooks
	 */
	public function payflexi_checkout_woocommerce_admin_notice() { 
		echo '<div class="error"><p><strong>' . sprintf( __( 'Payflexi Checkout requires WooCommerce to be installed and active. Click %s to install WooCommerce.', 'payflexi-checkout' ), '<a href="' . admin_url( 'plugin-install.php?tab=plugin-information&plugin=woocommerce&TB_iframe=true&width=772&height=539' ) . '" class="thickbox open-plugin-details-modal">here</a>' ) . '</strong></p></div>';
	} 
	/**
	* Display the test mode notice on admin end
	**/
	public function payflexi_checkout_testmode_notice(){
		$payflexi_checkout_settings = get_option( 'woocommerce_payflexi-checkout_settings' );
		$test_mode  = isset( $payflexi_checkout_settings['env'] ) ? $payflexi_checkout_settings['env'] : '';
		if ( 'test' == $test_mode ) {
		?>
			<div class="update-nag">
				Payflexi Checkout test mode is still enabled, Click <a href="<?php echo admin_url('admin.php?page=wc-settings&tab=checkout&section=payflexi-checkout' ) ?>">here</a> to disable it when you want to start accepting live payment on your site.
			</div>
		<?php
		}
    }
    	

}