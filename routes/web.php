<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ShippingAreaController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\Frontend\LanguageController;
use App\Http\Controllers\Frontend\WishlistController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

// cache clear route /

Route::get('/clear-cache', function () {
    $run = Artisan::call('config:clear');
    $run = Artisan::call('cache:clear');
    $run = Artisan::call('view:clear');
    $run = Artisan::call('config:cache');
    //return \Artisan::call('db:seed');

    return 'CACHE CLEARED SUCCESSFULLY';
});

// ################################## Frontend  ##################################
Route::get('/', [FrontendController::class, 'index'])->name('frontend');
Route::get('single-prduct/details/{id}/{slug}', [FrontendController::class, 'SingleProductDetails'])->name('single-product-details');


//  ################################## Multiple Language Part Start #################################
Route::get('language/Bangla', [LanguageController::class, 'Bangla'])->name('bangla-language');
Route::get('language/English', [LanguageController::class, 'English'])->name('english-language');

// ########## Sub Category Wise Products show  ##########
Route::get('subCatg-wise/products/{subCatgId}/{subCatgSlug}', [FrontendController::class, 'subCategoryWiseProductsView'])->name('subCagt-wise-product');
// ########## Sub SubCategory Wise Products show  ##########
Route::get('subSubCatg-wise/products/{subSubCatId}/{subSubSlug}', [FrontendController::class, 'subSubCategoryWiseProductsView'])->name('subsubCatg-wise-product');

// ########## Products Tag Wise Product show  ##########
Route::get('tagwise-product/show/{tag}', [FrontendController::class, 'productTagWiseProductShow'])->name('products-tagwise-product');

// #################### Ajax Request for select data (Category, Subcategory, Sub Subcategory, Division, District) ####################
Route::get('category-wise/subcategory/{id}', [CategoryController::class, 'categoryWiseSubcategory'])->name('category-wise-subcategory');
Route::get('subcategory-wise/brands/{id}', [CategoryController::class, 'subcategoryWiseBrandData'])->name('subcategory-wise-brand');
Route::get('subcategory-wise/subsubcategory/{id}', [CategoryController::class, 'subcategoryWiseSubsubcategoryData'])->name('subcategory-wise-subsubcategory');
Route::get('division-wise/districts/{id}', [CategoryController::class, 'divisionWiseDistrictData'])->name('division-wise-districts');
Route::get('district-wise/states/{id}', [CategoryController::class, 'districtWiseStatesData'])->name('district-wise-states');

/* ###########################################################################################
            ############################### Cart Part Start  ###############################
        ########################################################################################### */

// #################### Ajax Request for Cart Data Store  ####################
Route::get('cart/data/store/{productId}', [CartController::class, 'cartDataStore']);
// #################### Ajax Request for Product details show  ####################
Route::get('product/view/withModal/{productId}', [FrontendController::class, 'productInfoViewWithModal'])->name('product-view-ajax');
// #################### Ajax Request for Product details show (On Mini Cart) ####################
Route::get('product/mini-cart/info', [CartController::class, 'productBuyInfoOnMiniCart']);
// #################### Ajax Request for Product Remove (From Mini Cart) ####################
Route::get('/miniCart/product-remove/{rowId}', [CartController::class, 'productRemoveFromMiniCart']);


// #################### Go for Cart Page  ####################
Route::get('cart', [CartController::class, 'cartItemView'])->name('cart-item-view');
// #################### Products show at Cart page  ####################
Route::get('/cart-products/view', [CartController::class, 'cartProducts']);
// #################### Ajax Request for Product Remove (From Cart Page) ####################
Route::get('/cart/product-remove/{rowId}', [CartController::class, 'cartProductRemoveFromCartPage']);
// #################### Ajax Request for Product Increment (From Cart Page) ####################
Route::get('/cart/product-increment/{rowId}', [CartController::class, 'cartProductIncrementFromCartPage']);
// #################### Ajax Request for Product Decrement (From Cart Page) ####################
Route::get('/cart/product-decrement/{rowId}', [CartController::class, 'cartProductDecrementFromCartPage']);
// #################### Ajax Request for Product Price with Coupon (From Cart Page) ####################
Route::post('/coupon-apply', [CartController::class, 'couponApplyForCartPage']);
// #################### Ajax Request for Product Price Calculataion with Coupon (From Cart Page) ####################
Route::get('/cart-page/coupon-calculated-data', [CartController::class, 'couponCalculatedDataForCouponPage']);
// #################### Ajax Request for Coupon Remove with Ajax (From Cart Page) ####################
Route::get('/applied-coupon-remove', [CartController::class, 'appliedCouponDataRemoveFromCartPage']);

// #################### Checkout Page (From Cart Page) ####################
Route::get('/checkout-page', [CartController::class, 'checkoutPageForSelectedCartProducts'])->name('checkouts');

// #################### Product Add To Wishlist  ####################
Route::get('product/add/wishlist/{productId}', [CartController::class, 'productAddToWishlist'])->name('product-addTo-wishlist');


// Auth::routes();
Auth::routes();


/* ###########################################################################################
                    ############################### Admin Part  Start  ###############################
                ########################################################################################### */

Route::group(['prefix' => 'admin', 'middleware' => ['admin', 'auth'], 'namespace' => 'Admin'], function () {
    Route::get('dashboard', [AdminController::class, 'index'])->name('admin-dashboard');

    // #################### Admin Profile ####################
    Route::get('profile', [AdminController::class, 'adminProfile'])->name('admin-profile');
    Route::post('profile/update', [AdminController::class, 'adminProfileUpdate'])->name('admin-profile-update');
    Route::get('image', [AdminController::class, 'adminProfileImage'])->name('admin-profile-image');
    Route::post('image/update', [AdminController::class, 'adminProfileImageUpdate'])->name('admin-profile-image-update');
    Route::get('password', [AdminController::class, 'adminPassword'])->name('admin-password');
    Route::post('password/update', [AdminController::class, 'adminPasswordUpdate'])->name('admin-password-update');

    // #################### Category Part ####################
    Route::get('categories', [CategoryController::class, 'index'])->name('categories');
    Route::post('category/add', [CategoryController::class, 'categoryDataAdd'])->name('category-add');
    Route::get('category-edit/{id}', [CategoryController::class, 'categoryDataEdit'])->name('category-data-edit');
    Route::post('category-data/update', [CategoryController::class, 'categoryDataUpdate'])->name('category-data-update');
    Route::get('category-delete/{id}', [CategoryController::class, 'categoryDataDelete'])->name('category-data-delete');

    // #################### Sub Category Part ####################
    Route::get('subcategories', [CategoryController::class, 'subCategoryIndex'])->name('subcategories');
    Route::post('subcategory/add', [CategoryController::class, 'subCategoryAdd'])->name('subcategory-add');
    Route::get('subcategory-edit/{id}', [CategoryController::class, 'subcategoryDataEdit'])->name('subcategory-data-edit');
    Route::post('subcategory-data/update', [CategoryController::class, 'subcategoryDataUpdate'])->name('subcategory-data-update');
    Route::get('subcategory-delete/{id}', [CategoryController::class, 'subcategoryDataDelete'])->name('subcategory-data-delete');

    // #################### Sub subCategory Part ####################
    Route::get('sub-subcategories', [CategoryController::class, 'subSubCategoryIndex'])->name('sub-sub-categories');
    Route::post('sub-subcategory/add', [CategoryController::class, 'subSubCategoryAdd'])->name('sub-sub-category-add');
    Route::get('subsubcategory-edit/{id}', [CategoryController::class, 'subSubCategoryDataEdit'])->name('sub-sub-category-edit');
    Route::post('subsubcategory-data/update', [CategoryController::class, 'subSubCategoryDataUpdate'])->name('sub-sub-category-data-update');
    Route::get('subsubcategory-delete/{id}', [CategoryController::class, 'subSubCategoryDataDelete'])->name('sub-sub-category-data-delete');

    // #################### Brand Part ####################
    Route::get('all-brand', [BrandController::class, 'index'])->name('brands');
    Route::post('brand/add', [BrandController::class, 'brandDataAdd'])->name('brand-add');
    Route::get('brand-edit/{id}', [BrandController::class, 'brandDataEdit'])->name('brand-data-edit');
    Route::post('brand-data/update', [BrandController::class, 'brandDataUpdate'])->name('brand-data-update');
    Route::get('brand-delete/{id}', [BrandController::class, 'brandDataDelete'])->name('brand-data-delete');

    /* ###########################################################################################
          ############################### Product Part Start  ###############################
       ########################################################################################### */
    Route::get('all-product', [ProductController::class, 'index'])->name('products');
    Route::post('product/add', [ProductController::class, 'productDataAdd'])->name('product-add');
    Route::get('manage-products', [ProductController::class, 'productDataManage'])->name('products-manage');
    Route::get('product-edit/{id}', [ProductController::class, 'productDataEdit'])->name('product-data-edit');
    Route::post('product-data/update', [ProductController::class, 'productDataUpdate'])->name('product-data-update');
    // ########## Single Product Information View  ##########
    Route::get('product-info/{id}', [ProductController::class, 'singleProductInfo'])->name('single-product-info');
    // ########## Product Active & Inactive Part  ##########
    Route::get('product-inactive/{id}', [ProductController::class, 'productDataInactive'])->name('product-data-inactive');
    Route::get('product-active/{id}', [ProductController::class, 'productDataActive'])->name('product-data-active');
    // ########## Product Multi Image Part  ##########
    Route::post('product-multiImg/update', [ProductController::class, 'productMultiImgUpdate'])->name('product-multiImg-update');
    Route::get('product-multiImg/delete/{id}', [ProductController::class, 'productMultiImgDelete'])->name('product-multiImg-delete');
    // ########## Product Main Thumbnail Image Update  ##########
    Route::post('product-mainThumbnail/update', [ProductController::class, 'productMainThumbnailUpdate'])->name('product-mainThumb-update');




    //  ################################## Banner Part Start #################################
    Route::get('banner', [BannerController::class, 'index'])->name('banners');
    Route::post('banner/add', [BannerController::class, 'bannerDataAdd'])->name('banner-add');
    Route::get('banner-edit/{id}', [BannerController::class, 'bannerDataEdit'])->name('banner-data-edit');
    Route::post('banner-data/update', [BannerController::class, 'bannerDataUpdate'])->name('banner-data-update');
    Route::get('banner-delete/{id}', [BannerController::class, 'bannerDataDelete'])->name('banner-data-delete');
    //  ################################## Banner Active && Inactive #################################
    Route::get('banner-inactive/{id}', [BannerController::class, 'BannerDataInactive'])->name('banner-data-inactive');
    Route::get('banner-active/{id}', [BannerController::class, 'BannerDataActive'])->name('banner-data-active');

    //  ################################## Coupon Part Start #################################
    Route::get('coupon', [CouponController::class, 'index'])->name('coupons');
    Route::post('coupon/add', [CouponController::class, 'couponDataAdd'])->name('coupon-add');
    Route::get('coupon-edit/{id}', [CouponController::class, 'couponDataEdit'])->name('coupon-data-edit');
    Route::post('coupon-data/update', [CouponController::class, 'couponDataUpdate'])->name('coupon--data-update');
    Route::get('coupon-delete/{id}', [CouponController::class, 'couponDataDelete'])->name('coupon-data-delete');

    //  ################################## Shipping Area (Division) Part Start #################################
    Route::get('division', [ShippingAreaController::class, 'index'])->name('divisions');
    Route::post('division/add', [ShippingAreaController::class, 'divisionDataAdd'])->name('division-add');
    Route::get('division-edit/{id}', [ShippingAreaController::class, 'divisionDataEdit'])->name('division-data-edit');
    Route::post('division-data/update', [ShippingAreaController::class, 'divisionDataUpdate'])->name('division-data-update');
    Route::get('division-delete/{id}', [ShippingAreaController::class, 'divisionDataDelete'])->name('division-data-delete');

    //  ################################## Shipping Area (District) Part Start #################################
    Route::get('district', [ShippingAreaController::class, 'districtIndex'])->name('districts');
    Route::post('district/add', [ShippingAreaController::class, 'districtDataAdd'])->name('district-add');
    Route::get('district-edit/{id}', [ShippingAreaController::class, 'districtDataEdit'])->name('district-data-edit');
    Route::post('district-data/update', [ShippingAreaController::class, 'districtDataUpdate'])->name('district-data-update');
    Route::get('district-delete/{id}', [ShippingAreaController::class, 'districtDataDelete'])->name('district-data-delete');

    //  ################################## Shipping Area (States/Upazilla) Part Start #################################
    Route::get('state', [ShippingAreaController::class, 'stateIndex'])->name('states');
    Route::post('states/add', [ShippingAreaController::class, 'statesDataAdd'])->name('states-add');
    Route::get('states-edit/{id}', [ShippingAreaController::class, 'statesDataEdit'])->name('states-data-edit');
    Route::post('state-data/update', [ShippingAreaController::class, 'stateDataUpdate'])->name('state-data-update');
    Route::get('states-delete/{id}', [ShippingAreaController::class, 'statesDataDelete'])->name('states-data-delete');
});
/* ###########################################################################################
                    ############################### Admin Part  End  ###############################
                ########################################################################################### */






/* ###########################################################################################
                    ############################### User Part  Start  ###############################
                ########################################################################################### */
Route::group(['prefix' => 'user', 'middleware' => ['user', 'auth'], 'namespace' => 'User'], function () {
    Route::get('dashboard', [UserController::class, 'index'])->name('user-dashboard');
    Route::post('update/data', [UserController::class, 'updateData'])->name('update-profile');
    Route::get('image', [UserController::class, 'profileImage'])->name('profile-image');
    Route::post('image/update', [UserController::class, 'profileImageUpdate'])->name('profile-image-update');
    Route::get('password', [UserController::class, 'userPassword'])->name('user-password');
    Route::post('password/update', [UserController::class, 'userPasswordUpdate'])->name('user-password-update');

    // #################### Products view Page at Wishlist  ####################
    Route::get('wishlist', [WishlistController::class, 'wishlistItemView'])->name('wishlist-item-view');
    // #################### Products show at Wishlist page  ####################
    Route::get('/wishlist-products/view', [WishlistController::class, 'wishsistProducts']);
    // #################### Products remove from Wishlist page  ####################
    Route::get('/wishlist/product-remove/{product_id}', [WishlistController::class, 'wishlistProductRemove']);

    // #################### Product Shippin Information from Checkout page (stripe.blade.php) ####################
    Route::post('/shipping-form-data', [CheckoutController::class, 'shippingFormDataFromCheckoutPage'])->name('shipping-form-data');
    Route::post('/payment-request', [CheckoutController::class, 'afterpaymentFromCheckoutPage'])->name('checkout.credit-card');
});
