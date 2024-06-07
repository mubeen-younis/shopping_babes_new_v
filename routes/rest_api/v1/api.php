<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\PaymentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::group(['namespace' => 'RestAPI\v1', 'prefix' => 'v1', 'middleware' => ['api_lang']], function () {

    Route::group(['prefix' => 'auth', 'namespace' => 'auth'], function () {
        Route::post('register', 'PassportAuthController@register');
        Route::post('login', 'PassportAuthController@login');
        Route::get('logout', 'PassportAuthController@logout')->middleware('auth:api');

        Route::post('check-phone', 'PhoneVerificationController@check_phone');
        Route::post('resend-otp-check-phone', 'PhoneVerificationController@resend_otp_check_phone');
        Route::post('verify-phone', 'PhoneVerificationController@verify_phone');

        Route::post('check-email', 'EmailVerificationController@check_email');
        Route::post('resend-otp-check-email', 'EmailVerificationController@resend_otp_check_email');
        Route::post('verify-email', 'EmailVerificationController@verify_email');

        Route::post('forgot-password', 'ForgotPassword@reset_password_request');
        Route::post('verify-otp', 'ForgotPassword@otp_verification_submit');
        Route::put('reset-password', 'ForgotPassword@reset_password_submit');

        Route::post('social-login', 'SocialAuthController@social_login');
        Route::post('update-phone', 'SocialAuthController@update_phone');
    });

    Route::group(['prefix' => 'config'], function () {
        Route::get('/', 'ConfigController@configuration');
    });

    Route::group(['prefix' => 'shipping-method','middleware'=>'apiGuestCheck'], function () {
        Route::get('detail/{id}', 'ShippingMethodController@get_shipping_method_info');
        Route::get('by-seller/{id}/{seller_is}', 'ShippingMethodController@shipping_methods_by_seller');
        Route::post('choose-for-order', 'ShippingMethodController@choose_for_order');
        Route::get('chosen', 'ShippingMethodController@chosen_shipping_methods');

        Route::get('check-shipping-type','ShippingMethodController@check_shipping_type');
    });

    Route::group(['prefix' => 'cart','middleware'=>'apiGuestCheck'], function () {
        Route::get('/', 'CartController@cart');
        Route::post('add', 'CartController@add_to_cart');
        Route::put('update', 'CartController@update_cart');
        Route::delete('remove', 'CartController@remove_from_cart');
        Route::delete('remove-all','CartController@remove_all_from_cart');

    });

    Route::group(['prefix' => 'customer/order', 'middleware'=>'apiGuestCheck'], function () {
        Route::get('get-order-by-id', 'CustomerController@get_order_by_id');
    });

    Route::get('faq', 'GeneralController@faq');

    Route::group(['prefix' => 'notifications'], function () {
        Route::get('/', 'NotificationController@list');
        Route::get('/seen', 'NotificationController@notification_seen')->middleware('auth:api');
    });

    Route::group(['prefix' => 'attributes'], function () {
        Route::get('/', 'AttributeController@get_attributes');
    });

    Route::group(['prefix' => 'flash-deals'], function () {
        Route::get('/', 'FlashDealController@get_flash_deal');
        Route::get('products/{deal_id}', 'FlashDealController@get_products');
    });

    Route::group(['prefix' => 'deals'], function () {
        Route::get('featured', 'DealController@get_featured_deal');
    });

    Route::group(['prefix' => 'dealsoftheday'], function () {
        Route::get('deal-of-the-day', 'DealOfTheDayController@get_deal_of_the_day_product');
    });

    Route::group(['prefix' => 'products'], function () {
        Route::get('reviews/{product_id}', 'ProductController@get_product_reviews');
        Route::get('rating/{product_id}', 'ProductController@get_product_rating');
        Route::get('counter/{product_id}', 'ProductController@counter');
        Route::get('shipping-methods', 'ProductController@get_shipping_methods');
        Route::get('social-share-link/{product_id}', 'ProductController@social_share_link');
        Route::post('reviews/submit', 'ProductController@submit_product_review')->middleware('auth:api');
        Route::put('review/update', 'ProductController@updateProductReview')->middleware('auth:api');
        Route::get('review/{product_id}/{order_id}', 'ProductController@getProductReviewByOrder')->middleware('auth:api');
        Route::delete('review/delete-image', 'ProductController@deleteReviewImage')->middleware('auth:api');
    });

    Route::group(['middleware' => 'apiGuestCheck'], function () {
        Route::group(['prefix' => 'products'], function () {
            Route::get('latest', 'ProductController@get_latest_products');
            Route::get('featured', 'ProductController@get_featured_products');
            Route::get('top-rated', 'ProductController@get_top_rated_products');
            Route::any('search', 'ProductController@get_searched_products');
            Route::post('filter', 'ProductController@product_filter');
            Route::any('suggestion-product', 'ProductController@get_suggestion_product');
            Route::get('details/{slug}', 'ProductController@get_product');
            Route::get('related-products/{product_id}', 'ProductController@get_related_products');
            Route::get('best-sellings', 'ProductController@get_best_sellings');
            Route::get('home-categories', 'ProductController@get_home_categories');
            Route::get('discounted-product', 'ProductController@get_discounted_product');
            Route::get('most-demanded-product', 'ProductController@get_most_demanded_product');
            Route::get('shop-again-product', 'ProductController@get_shop_again_product')->middleware('auth:api');
            Route::get('just-for-you', 'ProductController@just_for_you');
            Route::get('most-searching', 'ProductController@get_most_searching_products');
        });

        Route::group(['prefix' => 'seller'], function () {
            Route::get('{seller_id}/products', 'SellerController@get_seller_products');
            Route::get('{seller_id}/seller-best-selling-products', 'SellerController@get_seller_best_selling_products');
            Route::get('{seller_id}/seller-featured-product', 'SellerController@get_sellers_featured_product');
            Route::get('{seller_id}/seller-recommended-products', 'SellerController@get_sellers_recommended_products');
        });

        Route::group(['prefix' => 'categories'], function () {
            Route::get('/', 'CategoryController@get_categories');
            Route::get('products/{category_id}', 'CategoryController@get_products');
            Route::get('/find-what-you-need', 'CategoryController@find_what_you_need');
        });

        Route::group(['prefix' => 'brands'], function () {
            Route::get('/', 'BrandController@get_brands');
            Route::get('products/{brand_id}', 'BrandController@get_products');
        });

        Route::group(['prefix' => 'customer'], function () {
            Route::put('cm-firebase-token', 'CustomerController@update_cm_firebase_token');

            Route::get('get-restricted-country-list', 'CustomerController@get_restricted_country_list');
            Route::get('get-restricted-zip-list', 'CustomerController@get_restricted_zip_list');

            Route::group(['prefix' => 'address'], function () {
                Route::post('add', 'CustomerController@add_new_address');
                Route::get('list', 'CustomerController@address_list');
                Route::delete('/', 'CustomerController@delete_address');
            });

            Route::group(['prefix' => 'order'], function () {
                Route::get('place', 'OrderController@place_order');
                Route::get('offline-payment-method-list', 'OrderController@offline_payment_method_list');
                Route::post('place-by-offline-payment', 'OrderController@place_order_by_offline_payment');
                Route::get('details', 'CustomerController@get_order_details');
            });
        });
    });

    Route::group(['prefix' => 'customer', 'middleware' => 'auth:api'], function () {
        Route::get('info', 'CustomerController@info');
        Route::put('update-profile', 'CustomerController@update_profile');
        Route::get('account-delete/{id}','CustomerController@account_delete');

        Route::group(['prefix' => 'address'], function () {
            Route::get('get/{id}', 'CustomerController@get_address');
            Route::post('update', 'CustomerController@update_address');
        });

        Route::group(['prefix' => 'support-ticket'], function () {
            Route::post('create', 'CustomerController@create_support_ticket');
            Route::get('get', 'CustomerController@get_support_tickets');
            Route::get('conv/{ticket_id}', 'CustomerController@get_support_ticket_conv');
            Route::post('reply/{ticket_id}', 'CustomerController@reply_support_ticket');
            Route::get('close/{id}', 'CustomerController@support_ticket_close');
        });


