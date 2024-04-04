<?php

namespace App\Listeners;

use App\Events\CompanyMenuEvent;

class CompanyMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanyMenuEvent $event): void
    {
        $module = 'Base';
        $menu = $event->menu;

        $menu->add([
            'title' => __('Dashboard'),
            'icon' => 'home',
            'name' => 'admin_dashboard',
            'parent' => null,
            'order' => 1,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => ''
        ]);

        $menu->add([
            'title' => __('Dashboard'),
            'icon' => 'home',
            'name' => 'dashboard',
            'parent' => 'admin_dashboard',
            'order' => 2,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'dashboard',
            'module' => $module,
            'permission' => ''
        ]);

        $menu->add([
            'title' => __('Store Analytics'),
            'icon' => '',
            'name' => 'store-analytics',
            'parent' => 'admin_dashboard',
            'order' => 3,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'theme_analytic',
            'module' => $module,
            'permission' => ''
        ]);



        $menu->add([
            'title' => __('Theme Preview'),
            'icon' => 'rotate',
            'name' => 'themepreview',
            'parent' => null,
            'order' => 20,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'theme-preview.index',
            'module' => $module,
            'permission' => ''
        ]);

        $menu->add([
            'title' => __('Store Setting'),
            'icon' => 'settings-automation',
            'name' => 'storesetting',
            'parent' => null,
            'order' => 40,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'app-setting.index',
            'module' => $module,
            'permission' => ''
        ]);

        $menu->add([
            'title' => __('Mobile App Settings'),
            'icon' => 'settings-automation',
            'name' => 'mobilescreensetting',
            'parent' => null,
            'order' => 60,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'mobilescreen.content',
            'module' => $module,
            'permission' => ''
        ]);

        $menu->add([
            'title' => __('Staff'),
            'icon' => 'users',
            'name' => 'staff',
            'parent' => null,
            'order' => 80,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => ''
        ]);
        $menu->add([
            'title' => __('Roles'),
            'icon' => '',
            'name' => 'roles',
            'parent' => 'staff',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'roles.index',
            'module' => $module,
            'permission' => ''
        ]);
        $menu->add([
            'title' => __('User'),
            'icon' => '',
            'name' => 'user',
            'parent' => 'staff',
            'order' => 11,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'users.index',
            'module' => $module,
            'permission' => ''
        ]);
        $menu->add([
            'title' => __('Delivery Boy'),
            'icon' => 'truck',
            'name' => 'deliveryboy',
            'parent' => null,
            'order' => 100,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'deliveryboy.index',
            'module' => $module,
            'permission' => ''
        ]);


        /** Products Start */
        $menu->add([
            'title' => __('Products'),
            'icon' => 'shopping-cart',
            'name' => 'products',
            'parent' => null,
            'order' => 120,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => ''
        ]);

        $menu->add([
            'title' => __('Brand'),
            'icon' => 'home',
            'name' => 'productBrand',
            'parent' => 'products',
            'order' => 1,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'product-brand.index',
            'module' => $module,
            'permission' => ''
        ]);

        $menu->add([
            'title' => __('Label'),
            'icon' => 'home',
            'name' => 'productLabel',
            'parent' => 'products',
            'order' => 2,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'product-label.index',
            'module' => $module,
            'permission' => ''
        ]);

        $menu->add([
            'title' => __('Product'),
            'icon' => 'home',
            'name' => 'product',
            'parent' => 'products',
            'order' => 26,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'product.index',
            'module' => $module,
            'permission' => ''
        ]);

        $menu->add([
            'title' => __('Main Category'),
            'icon' => 'home',
            'name' => 'maincategory',
            'parent' => 'products',
            'order' => 27,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'main-category.index',
            'module' => $module,
            'permission' => ''
        ]);
        $menu->add([
            'title' => __('Sub Category'),
            'icon' => 'home',
            'name' => 'subcategory',
            'parent' => 'products',
            'order' => 28,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'sub-category.index',
            'module' => $module,
            'permission' => ''
        ]);
        $menu->add([
            'title' => __('Attributes'),
            'icon' => 'home',
            'name' => 'attributes',
            'parent' => 'products',
            'order' => 29,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'product-attributes.index',
            'module' => $module,
            'permission' => ''
        ]);

        $menu->add([
            'title' => __('Tax'),
            'icon' => 'home',
            'name' => 'tax',
            'parent' => 'products',
            'order' => 30,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'taxes.index',
            'module' => $module,
            'permission' => ''
        ]);

        $menu->add([
            'title' => __('Testimonial'),
            'icon' => 'home',
            'name' => 'Testimonial',
            'parent' => 'products',
            'order' => 31,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'testimonial.index',
            'module' => $module,
            'permission' => ''
        ]);
        $menu->add([
            'title' => __('Question Answer'),
            'icon' => 'home',
            'name' => 'question_answer',
            'parent' => 'products',
            'order' => 32,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'product-question.index',
            'module' => $module,
            'permission' => ''
        ]);
        /** Products End */

        /**shipping start */
        $menu->add([
            'title' => __('Shipping'),
            'icon' => 'truck-delivery',
            'name' => 'shipping',
            'parent' => null,
            'order' => 140,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => ''
        ]);
        $menu->add([
            'title' => __('Shipping Class'),
            'icon' => '',
            'name' => 'shipping class',
            'parent' => 'shipping',
            'order' => 34,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'shipping.index',
            'module' => $module,
            'permission' => ''
        ]);
        $menu->add([
            'title' => __('Shipping Zone'),
            'icon' => '',
            'name' => 'shipping zone',
            'parent' => 'shipping',
            'order' => 35,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'shipping-zone.index',
            'module' => $module,
            'permission' => ''
        ]);
        /** shipping end */

        /** order start */
        $menu->add([
            'title' => __('Orders'),
            'icon' => 'briefcase',
            'name' => 'orders',
            'parent' => null,
            'order' => 160,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => ''
        ]);
        $menu->add([
            'title' => __('Orders'),
            'icon' => 'user',
            'name' => 'order',
            'parent' => 'orders',
            'order' => 37,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'order.index',
            'module' => $module,
            'permission' => ''
        ]);

        $menu->add([
            'title' => __('Order Refund Request'),
            'icon' => 'user',
            'name' => 'order-refund-request',
            'parent' => 'orders',
            'order' => 38,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'refund-request.index',
            'module' => $module,
            'permission' => ''
        ]);
        /** order end */

        /** customer start */
        $menu->add([
            'title' => __('Customers'),
            'icon' => 'user',
            'name' => 'customers',
            'parent' => null,
            'order' => 180,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'customer.index',
            'module' => $module,
            'permission' => ''
        ]);
        /** customer end */

        /**Reports Start */
        $menu->add([
            'title' => __('Reports'),
            'icon' => 'chart-bar',
            'name' => 'reports',
            'parent' => null,
            'order' => 200,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => ''
        ]);
        $menu->add([
            'title' => __('Customer Reports'),
            'icon' => 'home',
            'name' => '',
            'parent' => 'reports',
            'order' => 41,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'reports.index',
            'module' => $module,
            'permission' => ''
        ]);
        $menu->add([
            'title' => __('Order Reports'),
            'icon' => 'home',
            'name' => 'order_reports',
            'parent' => 'reports',
            'order' => 42,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => ''
        ]);
        $menu->add([
            'title' => __('Sales Report'),
            'icon' => 'home',
            'name' => '',
            'parent' => 'order_reports',
            'order' => 43,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'reports.order_report',
            'module' => $module,
            'permission' => ''
        ]);
        $menu->add([
            'title' => __('Sales Product Report'),
            'icon' => 'home',
            'name' => '',
            'parent' => 'order_reports',
            'order' => 44,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'reports.order_product_report',
            'module' => $module,
            'permission' => ''
        ]);
        $menu->add([
            'title' => __('Sales Category Report'),
            'icon' => 'home',
            'name' => '',
            'parent' => 'order_reports',
            'order' => 45,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'reports.order_category_report',
            'module' => $module,
            'permission' => ''
        ]);
        $menu->add([
            'title' => __('Sales Downloadable Product'),
            'icon' => 'home',
            'name' => '',
            'parent' => 'order_reports',
            'order' => 46,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'reports.order_downloadable_report',
            'module' => $module,
            'permission' => ''
        ]);
        $menu->add([
            'title' => __('Stock Reports'),
            'icon' => 'home',
            'name' => '',
            'parent' => 'reports',
            'order' => 47,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'reports.stock_report',
            'module' => $module,
            'permission' => ''
        ]);
        /**Reports End */

        /**Marketing Start */
        $menu->add([
            'title' => __('Marketing'),
            'icon' => 'confetti',
            'name' => 'marketing',
            'parent' => null,
            'order' => 220,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => ''
        ]);
        $menu->add([
            'title' => __('Coupon'),
            'icon' => 'home',
            'name' => 'coupon',
            'parent' => 'marketing',
            'order' => 51,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'coupon.index',
            'module' => $module,
            'permission' => ''
        ]);
        $menu->add([
            'title' => __('Newsletter'),
            'icon' => 'home',
            'name' => 'newsletter',
            'parent' => 'marketing',
            'order' => 52,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'newsletter.index',
            'module' => $module,
            'permission' => ''
        ]);
        $menu->add([
            'title' => __('Flash Sale'),
            'icon' => 'home',
            'name' => 'flashsale',
            'parent' => 'marketing',
            'order' => 53,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'flash-sale.index',
            'module' => $module,
            'permission' => ''
        ]);
        $menu->add([
            'title' => __('Wishlist'),
            'icon' => 'home',
            'name' => 'wishlist',
            'parent' => 'marketing',
            'order' => 54,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'wishlist.index',
            'module' => $module,
            'permission' => ''
        ]);
        $menu->add([
            'title' => __('Abandon Cart'),
            'icon' => 'home',
            'name' => 'abandon_cart',
            'parent' => 'marketing',
            'order' => 55,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'abandon.carts.handled',
            'module' => $module,
            'permission' => ''
        ]);
        /**Marketing End */

        /**WooCommerce Start */
        $menu->add([
            'title' => __('WooCommerce'),
            'icon' => 'letter-w',
            'name' => 'WooCommerce',
            'parent' => null,
            'order' => 240,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => ''
        ]);

        $menu->add([
            'title' => __('Main Category'),
            'icon' => 'home',
            'name' => '',
            'parent' => 'WooCommerce',
            'order' => 57,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'woocom_category.index',
            'module' => $module,
            'permission' => ''
        ]);

        $menu->add([
            'title' => __('Sub Category'),
            'icon' => 'home',
            'name' => '',
            'parent' => 'WooCommerce',
            'order' => 58,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'woocom_sub_category.index',
            'module' => $module,
            'permission' => ''
        ]);

        $menu->add([
            'title' => __('Product'),
            'icon' => 'home',
            'name' => '',
            'parent' => 'WooCommerce',
            'order' => 59,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'woocom_product.index',
            'module' => $module,
            'permission' => ''
        ]);

        $menu->add([
            'title' => __('Customer'),
            'icon' => 'home',
            'name' => '',
            'parent' => 'WooCommerce',
            'order' => 60,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'woocom_customer.index',
            'module' => $module,
            'permission' => ''
        ]);

        $menu->add([
            'title' => __('Coupon'),
            'icon' => 'home',
            'name' => '',
            'parent' => 'WooCommerce',
            'order' => 61,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'woocom_coupon.index',
            'module' => $module,
            'permission' => ''
        ]);

        /** WooCommerce End */
        /**Shopify Start */
        $menu->add([
            'title' => __('Shopify'),
            'icon' => 'letter-s',
            'name' => 'shopify',
            'parent' => null,
            'order' => 260,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => ''
        ]);

        $menu->add([
            'title' => __('Main Category'),
            'icon' => 'home',
            'name' => '',
            'parent' => 'shopify',
            'order' => 63,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'shopify_category.index',
            'module' => $module,
            'permission' => ''
        ]);

        $menu->add([
            'title' => __('Sub Category'),
            'icon' => 'home',
            'name' => '',
            'parent' => 'shopify',
            'order' => 64,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'shopify_sub_category.index',
            'module' => $module,
            'permission' => ''
        ]);

        $menu->add([
            'title' => __('Product'),
            'icon' => 'home',
            'name' => '',
            'parent' => 'shopify',
            'order' => 65,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'shopify_product.index',
            'module' => $module,
            'permission' => ''
        ]);

        $menu->add([
            'title' => __('Customer'),
            'icon' => 'home',
            'name' => '',
            'parent' => 'shopify',
            'order' => 66,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'shopify_customer.index',
            'module' => $module,
            'permission' => ''
        ]);

        $menu->add([
            'title' => __('Coupon'),
            'icon' => 'home',
            'name' => '',
            'parent' => 'shopify',
            'order' => 67,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'shopify_coupon.index',
            'module' => $module,
            'permission' => ''
        ]);
        /**Shopify End */

        /**Support ticket start */
        $menu->add([
            'title' => __('Support Ticket'),
            'icon' => 'ticket',
            'name' => 'support_ticket.index',
            'parent' => null,
            'order' => 280,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'support_ticket.index',
            'module' => $module,
            'permission' => ''
        ]);
        /**Support ticket End */
        /**POS start */
        $menu->add([
            'title' => __('POS'),
            'icon' => 'layers-difference',
            'name' => 'pos',
            'parent' => null,
            'order' => 300,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'pos.index',
            'module' => $module,
            'permission' => ''
        ]);
        /**POS End */
        /**CMS start */
        $menu->add([
            'title' => __('CMS'),
            'icon' => 'layout-cards',
            'name' => 'cms',
            'parent' => null,
            'order' => 320,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => ''
        ]);

        $menu->add([
            'title' => __('Menu'),
            'icon' => 'home',
            'name' => 'menu',
            'parent' => 'cms',
            'order' => 72,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'menus.index',
            'module' => $module,
            'permission' => ''
        ]);
        $menu->add([
            'title' => __('Pages'),
            'icon' => 'home',
            'name' => 'pages',
            'parent' => 'cms',
            'order' => 73,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'pages.index',
            'module' => $module,
            'permission' => ''
        ]);

        $menu->add([
            'title' => __('Blog'),
            'icon' => '',
            'name' => 'blog',
            'parent' => 'blog_section',
            'order' => 74,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'blog.index',
            'module' => $module,
            'permission' => ''
        ]);
        $menu->add([
            'title' => __('Blog Category'),
            'icon' => '',
            'name' => 'blog-category',
            'parent' => 'blog_section',
            'order' => 75,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'blog-category.index',
            'module' => $module,
            'permission' => ''
        ]);

        $menu->add([
            'title' => __('Faqs'),
            'icon' => 'home',
            'name' => 'faq',
            'parent' => 'cms',
            'order' => 76,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'faqs.index',
            'module' => $module,
            'permission' => ''
        ]);

        $menu->add([
            'title' => __('Blog Section'),
            'icon' => 'home',
            'name' => 'blog_section',
            'parent' => 'cms',
            'order' => 77,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => ''
        ]);

        $menu->add([
            'title' => __('Tag'),
            'icon' => 'home',
            'name' => 'tag',
            'parent' => 'cms',
            'order' => 78,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'tag.index',
            'module' => $module,
            'permission' => ''
        ]);

        $menu->add([
            'title' => __('Contact Us'),
            'icon' => 'home',
            'name' => 'contact-us',
            'parent' => 'cms',
            'order' =>  79,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'contacts.index',
            'module' => $module,
            'permission' => ''
        ]);
        /**CMS End */

        $menu->add([
            'title' => __('Plan'),
            'icon' => 'trophy',
            'name' => 'plan',
            'parent' => null,
            'order' => 340,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'plan.index',
            'module' => $module,
            'permission' => ''
        ]);

        $menu->add([
            'title' => __('Settings'),
            'icon' => 'settings',
            'name' => 'settings',
            'parent' => null,
            'order' => 360,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'setting.index',
            'module' => $module,
            'permission' => ''
        ]);

    }
}
