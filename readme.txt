=== PayFlexi Checkout for WooCommerce  ===
Contributors: stanwarri
Plugin URI: https://payflexi.co
Tags: payment in multiple installments, buy now pay later, pay in 4 installments, paystack one-click checkout, flutterwave one-click checkout, stripe one-click checkout, payflexi, splits payment, woocommerce, express checkout, recurring payments
Requires at least: 4.7
Tested up to: 5.9.3
Requires PHP: 7.0
Stable tag: 1.1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

PayFlexi Buy Now Pay Later checkout for WooCommerce is a payment solution that allows you to accept installment payments from your customers.

= Use Case for PayFlexi =

The opportunity to split the payment into several parts can increase the number of orders and facilitate the conversion of doubting customers, especially if you are selling an high value products. 
PayFlexi allows your customers to buy products just by paying down payment at the time of purchase and remaining amount to be paid later in easy installments. Here are some benefits;

* Sell your high-value items at the right price without looking expensive.
* Increase the average order and motivate your customers to pay for more high-value items.
* Builds a trustworthy relationship between your business and the customers.
* Immediate cash flow

== Note ==

This plugin is meant to be used by merchants in Nigeria. More countrues would be added soon.

* To signup for a PayFlexi Merchant account visit the website by clicking [here](https://merchant.payflexi.co)

== Installation ==

= Automatic Installation =
* 	Login to your WordPress Admin area
* 	Go to "Plugins > Add New" from the left hand menu
* 	In the search box type __PayFlexi Checkout for WooCommerce__
*	From the search result you will see __PayFlexi Checkout for WooCommerce__ click on __Install Now__ to install the plugin
*	A popup window will ask you to confirm your wish to install the Plugin.
*	After installation, activate the plugin.
* 	Open the settings page for Woocommerce and click the "Checkout" tab.
* 	Click on the __PayFlexi__ link from the available Checkout Options
*	Configure your __PayFlexi Payment Gateway__ settings. See below for details.

= Manual Installation =
* 	Download the plugin zip file
* 	Login to your WordPress Admin. Click on "Plugins > Add New" from the left hand menu.
*  Click on the "Upload" option, then click "Choose File" to select the zip file from your computer. Once selected, press "OK" and press the "Install Now" button.
*  Activate the plugin.
* 	Open the settings page for Woocommerce and click the "Checkout" tab.
* 	Click on the __PayFlexi__ link from the available Checkout Options
*	Configure your __PayFlexi Payment Gateway__ settings. See below for details.

= Configure the plugin =
To configure the plugin, go to __Woocommerce > Settings__ from the left hand menu, then click __Checkout__ from the top tab. You will see __PayFlexi__ as part of the available Checkout Options. Click on it to configure the payment gateway.

* __Enable/Disable__ - check the box to enable PayFlexi Payment Gateway.
* __Title__ - allows you to determine what your customers will see this payment option as on the checkout page.
* __Description__ - controls the message that appears under the payment fields on the checkout page.
* __Test Mode__ - Check to enable test mode. Test mode enables you to test payments before going live. If you ready to start receving real payment on your site, kindly uncheck this.
* __Test Secret API Key__ - Enter your Test Secret Key here. Get your API keys from your PayFlexi Merchant Account under Developer > API
* __Test Public API Key__ - Enter your Test Public Key here. Get your API keys from your PayFlexi Merchant Account under Developer > API
* __Live Secret API Key__ - Enter your Live Secret Key here. Get your API keys from your PayFlexi Merchant Account under Developer > API
* __Live Public API Key__ - Enter your Live Public Key here. Get your API keys from your PayFlexi Merchant Account under Developer > API
* Click on __Save Changes__ for the changes you made to be effected.

<strong>You have to set the Webhook URL in the [API Keys & Webhooks](https://merchant.payflexi.co/developers?tab=api-keys-integrations) settings page in your PayFlexi Merchant Account</strong>. Go to the plugin settings page for more information.

= Support =

Get timely and friendly support at, [PayFlexi Support](https://support.payflexi.co)

== Screenshots ==

1. PayFlexi Payment Plans Selection

2. PayFlexi Woocommerce Payment Gateway on the checkout page

3. PayFlexi Merchant Dashboard Schedule History

>== Frequently Asked Questions ==

= In which countries can I use PayFlexi? =
PayFlexi is currently available only to merchants based in Nigeria

= How can I get help if I have any issues? =
For issues with your WooCommerce installation you should use the support forum here on wordpress.org. For other issues you should contact PayFlexi.

= Why are orders put pending and not completed? =
When an instalment payment is made on an order, PayFlexi will update the order status to pending since the payment is not yet completed. As the customer continues to make payment, the total amount paid will change. Once the instalment payment is complete, the order status will change to "Processing" or "Complete".

PayFlexi continues to update the order status via the webhook. So ensure you copy the webhook from your WooCommerce settings page to your PayFlexi settings page.

= Does PayFlexi offer a test environment for PayFlexi for WooCommerce? =
Yes, on the developers page on your PayFlexi merchant dashboard, create a test user account. Then enable "test" mode on the WooCommerce PayFlexi settings page. You can learn more about testing here - [How to test payments](https://support.payflexi.co/collections/how-to-test-payments-on-payflexi/)

= What are the requirements? = 
* WooCommerce 3.3.4 or newer is required
* [PayFlexi Merchant Account](https://merchant.payflexi.co/signup)
* PHP 5.6 or higher is required.

== Changelog ==

= 1.0.0 - March 5, 2022 =
*   First release

= 1.1.0 - May 17, 2022 =
*   Fixed error with product title