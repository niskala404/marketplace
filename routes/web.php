<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StorefrontController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\PaymentProofController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\Payments\MidtransController as MidtransPayment;
use App\Http\Controllers\AffiliateController;
use App\Http\Controllers\ReportController;

use App\Http\Controllers\ShopController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\DisputeController as BuyerDispute;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\Seller\MessageController as SellerMessage;

use App\Http\Controllers\Seller\DashboardController as SellerDashboard;
use App\Http\Controllers\Seller\ProductController as SellerProduct;
use App\Http\Controllers\Seller\OrderController as SellerOrder;
use App\Http\Controllers\Seller\DisputeController as SellerDispute;
use App\Http\Controllers\Seller\PayoutController as SellerPayout;
use App\Http\Controllers\Seller\ShopController as SellerShop;
use App\Http\Controllers\Seller\ProductVariantController as SellerProductVariant;
use App\Http\Controllers\Seller\ProductImageController as SellerProductImage;
use App\Http\Controllers\Seller\BoostController as SellerBoost;
use App\Http\Controllers\Seller\KycController as SellerKyc;
use App\Http\Controllers\Admin\FlashSaleController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\CategoryController as AdminCategory;
use App\Http\Controllers\Admin\UserController as AdminUser;
use App\Http\Controllers\Admin\VoucherController as AdminVoucher;
use App\Http\Controllers\Admin\PaymentController as AdminPayment;
use App\Http\Controllers\Admin\ShippingRateController as AdminShippingRate;
use App\Http\Controllers\Admin\ProductModerationController as AdminProductModeration;
use App\Http\Controllers\Admin\DisputeController as AdminDispute;
use App\Http\Controllers\Admin\PayoutController as AdminPayout;
use App\Http\Controllers\Admin\BannerController as AdminBanner;
use App\Http\Controllers\Admin\FlashSaleController as AdminFlashSale;
use App\Http\Controllers\Admin\FinanceController as AdminFinance;
use App\Http\Controllers\SearchSuggestController;
use App\Http\Controllers\Admin\ReportController as AdminReport;
use App\Http\Controllers\Admin\KycController as AdminKyc;

// Midtrans webhook (public)
Route::post('/payments/midtrans/notify', [MidtransPayment::class, 'notify'])->name('payments.midtrans.notify');

Route::get('/', [StorefrontController::class, 'index'])->name('home');
Route::get('/search/suggest', SearchSuggestController::class)->name('search.suggest');
Route::get('/p/{slug}', [ProductController::class, 'show'])->name('product.show');
Route::get('/shop/{slug}', [ShopController::class, 'show'])->name('shop.show');

// Report (public can submit, user_id nullable)
Route::post('/report', [ReportController::class, 'store'])
    ->middleware('throttle:public-report')
    ->name('report.store');

Route::middleware(['auth'])->group(function () {

    // affiliate
    Route::get('/affiliate', [AffiliateController::class, 'index'])->name('affiliate.index');
    Route::post('/affiliate/links', [AffiliateController::class, 'store'])->name('affiliate.links.store');
    // notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.read_all');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'read'])->name('notifications.read');

    // follow shops
    Route::get('/following', [FollowController::class, 'index'])->name('followings.index');
    Route::post('/shops/{shop}/follow', [FollowController::class, 'toggle'])
        ->middleware('throttle:marketplace-write')
        ->name('shops.follow.toggle');

    // messages (buyer)
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{conversation}', [MessageController::class, 'show'])->name('messages.show');
    Route::get('/messages/{conversation}/poll', [MessageController::class, 'poll'])->name('messages.poll');
    Route::post('/messages/{conversation}', [MessageController::class, 'send'])
        ->middleware('throttle:marketplace-write')
        ->name('messages.send');
    Route::post('/shops/{shop}/message', [MessageController::class, 'start'])
        ->middleware('throttle:marketplace-write')
        ->name('messages.start');

    // wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/move-all', [WishlistController::class, 'moveAllToCart'])
        ->middleware('throttle:marketplace-write')
        ->name('wishlist.move_all');
    Route::post('/wishlist/{product}/move-to-cart', [WishlistController::class, 'moveToCart'])
        ->middleware('throttle:marketplace-write')
        ->name('wishlist.move_to_cart');
    Route::post('/wishlist/toggle/{product}', [WishlistController::class, 'toggle'])
        ->middleware('throttle:marketplace-write')
        ->name('wishlist.toggle');

    // cart
    Route::get('/cart', [CartController::class,'index'])->name('cart.index');
    Route::post('/cart/add/{productId}', [CartController::class,'add'])
        ->middleware('throttle:marketplace-write')
        ->name('cart.add');
    Route::post('/cart/update/{itemId}', [CartController::class,'update'])
        ->middleware('throttle:marketplace-write')
        ->name('cart.update');
    Route::post('/cart/remove/{itemId}', [CartController::class,'remove'])
        ->middleware('throttle:marketplace-write')
        ->name('cart.remove');

    // checkout + orders
    Route::get('/checkout', [CheckoutController::class,'show'])->name('checkout.show');
    Route::post('/checkout', [CheckoutController::class,'place'])
        ->middleware('throttle:marketplace-write')
        ->name('checkout.place');

    // wallet
    Route::get('/my-wallet', [WalletController::class, 'index'])->name('wallet.index');

    Route::get('/my-orders', [CheckoutController::class,'myOrders'])->name('orders.mine');
    Route::get('/my-orders/{order}', [CheckoutController::class,'showOrder'])->name('orders.show');
    Route::get('/my-orders/{order}/pay', [MidtransPayment::class,'pay'])->name('payments.midtrans.pay');
    Route::post('/my-orders/{order}/cancel', [CheckoutController::class,'cancel'])
        ->middleware('throttle:marketplace-write')
        ->name('orders.cancel');
    Route::post('/my-orders/{order}/confirm-received', [CheckoutController::class,'confirmReceived'])
        ->middleware('throttle:marketplace-write')
        ->name('orders.confirm_received');
    Route::post('/my-orders/{order}/payment-proof', [PaymentProofController::class,'upload'])
        ->middleware('throttle:marketplace-write')
        ->name('orders.payment_proof.upload');
    Route::post('/my-orders/{order}/items/{orderItem}/review', [ReviewController::class,'store'])
        ->middleware('throttle:marketplace-write')
        ->name('orders.items.review');

    // disputes (buyer)
    Route::get('/disputes', [BuyerDispute::class, 'index'])->name('disputes.index');
    Route::get('/orders/{order}/dispute', [BuyerDispute::class, 'create'])->name('disputes.create');
    Route::post('/orders/{order}/dispute', [BuyerDispute::class, 'store'])
        ->middleware('throttle:marketplace-write')
        ->name('disputes.store');
    Route::get('/disputes/{dispute}', [BuyerDispute::class, 'show'])->name('disputes.show');
    Route::post('/disputes/{dispute}/ship-back', [BuyerDispute::class, 'shipBack'])->name('disputes.ship_back');

    // account - addresses
    Route::prefix('account')->name('account.')->group(function () {
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
        Route::get('/addresses', [AddressController::class, 'index'])->name('addresses.index');
        Route::get('/addresses/create', [AddressController::class, 'create'])->name('addresses.create');
        Route::post('/addresses', [AddressController::class, 'store'])->name('addresses.store');
        Route::get('/addresses/{address}/edit', [AddressController::class, 'edit'])->name('addresses.edit');
        Route::put('/addresses/{address}', [AddressController::class, 'update'])->name('addresses.update');
        Route::delete('/addresses/{address}', [AddressController::class, 'destroy'])->name('addresses.destroy');
    });

    // seller
    Route::middleware(['role:seller','seller.shop'])->prefix('seller')->name('seller.')->group(function () {
        // shop onboarding
        Route::get('/shop/create', [SellerShop::class,'create'])->name('shop.create');
        Route::post('/shop', [SellerShop::class,'store'])->name('shop.store');
        Route::get('/shop/edit', [SellerShop::class,'edit'])->name('shop.edit');
        Route::put('/shop', [SellerShop::class,'update'])->name('shop.update');

        // messages
        Route::get('/messages', [SellerMessage::class,'index'])->name('messages.index');
        Route::get('/messages/{conversation}', [SellerMessage::class,'show'])->name('messages.show');
        Route::get('/messages/{conversation}/poll', [SellerMessage::class,'poll'])->name('messages.poll');
        Route::post('/messages/{conversation}', [SellerMessage::class,'send'])->name('messages.send');

        Route::get('/dashboard', [SellerDashboard::class,'index'])->name('dashboard');

        // bulk tools
        Route::get('/products/bulk', [SellerProduct::class, 'bulk'])->name('products.bulk');
        Route::post('/products/bulk', [SellerProduct::class, 'bulkUpdate'])->name('products.bulk.update');

        Route::resource('/products', SellerProduct::class);

        // product images (delete)
        Route::delete('/products/{product}/images/{image}', [SellerProductImage::class, 'destroy'])->name('products.images.destroy');

        // product variants
        Route::get('/products/{product}/variants', [SellerProductVariant::class,'index'])->name('products.variants.index');
        Route::post('/products/{product}/variants', [SellerProductVariant::class,'store'])->name('products.variants.store');
        Route::post('/products/{product}/variants/{variant}', [SellerProductVariant::class,'update'])->name('products.variants.update');
        Route::delete('/products/{product}/variants/{variant}', [SellerProductVariant::class,'destroy'])->name('products.variants.destroy');

        Route::get('/orders', [SellerOrder::class,'index'])->name('orders.index');
        Route::get('/orders/{order}', [SellerOrder::class,'show'])->name('orders.show');
        Route::post('/orders/{order}/status', [SellerOrder::class,'updateStatus'])->name('orders.status');
        Route::post('/orders/{order}/delivered', [SellerOrder::class,'markDelivered'])->name('orders.delivered');

        // disputes (seller)
        Route::get('/disputes', [SellerDispute::class, 'index'])->name('disputes.index');
        Route::get('/disputes/{dispute}', [SellerDispute::class, 'show'])->name('disputes.show');
        Route::post('/disputes/{dispute}/respond', [SellerDispute::class, 'respond'])->name('disputes.respond');
        Route::post('/disputes/{dispute}/received', [SellerDispute::class, 'markReceived'])->name('disputes.received');

        // payouts (seller)
        Route::get('/payouts', [SellerPayout::class, 'index'])->name('payouts.index');
        Route::get('/payouts/request', [SellerPayout::class, 'create'])->name('payouts.create');
        Route::post('/payouts/request', [SellerPayout::class, 'store'])->name('payouts.store');

        // boosts (ads)
        Route::get('/boosts', [SellerBoost::class, 'index'])->name('boosts.index');
        Route::get('/boosts/create', [SellerBoost::class, 'create'])->name('boosts.create');
        Route::post('/boosts', [SellerBoost::class, 'store'])->name('boosts.store');
        Route::delete('/boosts/{boost}', [SellerBoost::class, 'destroy'])->name('boosts.destroy');

        // KYC
        Route::get('/kyc', [SellerKyc::class, 'edit'])->name('kyc.edit');
        Route::post('/kyc', [SellerKyc::class, 'update'])->name('kyc.update');
    });

    // admin
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboard::class,'index'])->name('dashboard');
Route::post('/flash-sale-items/{item}/toggle', [AdminFlashSale::class, 'toggleItem'])
    ->name('flash-sales.items.toggle');

Route::delete('/flash-sale-items/{item}', [AdminFlashSale::class, 'deleteItem'])
    ->name('flash-sales.items.delete');
        // finance (escrow & wallets)
        Route::get('/finance', [AdminFinance::class,'index'])->name('finance.index');
Route::post('/flash-sales/{flash_sale}/items', [FlashSaleController::class, 'addItem'])
    ->name('flash-sales.items.add');

Route::delete('/flash-sales/{flash_sale}/items/{item}', [FlashSaleController::class, 'removeItem'])
    ->name('flash-sales.items.remove');
        // banners & flash sales
        Route::resource('/banners', AdminBanner::class);
        Route::resource('/flash-sales', AdminFlashSale::class)->parameters(['flash-sales' => 'flash_sale']);

        Route::resource('/categories', AdminCategory::class);

        Route::resource('/vouchers', AdminVoucher::class);
        Route::resource('/shipping-rates', AdminShippingRate::class)->parameters(['shipping-rates'=>'shipping_rate']);

        Route::get('/payments', [AdminPayment::class,'index'])->name('payments.index');
        Route::post('/payments/{order}/verify', [AdminPayment::class,'verify'])->name('payments.verify');
        Route::post('/payments/{order}/reject', [AdminPayment::class,'reject'])->name('payments.reject');

        Route::get('/users', [AdminUser::class,'index'])->name('users.index');
        Route::post('/users/{user}/toggle', [AdminUser::class,'toggleActive'])->name('users.toggle');
        Route::post('/users/{user}/role', [AdminUser::class,'setRole'])->name('users.role');

        // product moderation
        Route::get('/products/moderation', [AdminProductModeration::class,'index'])->name('products.moderation.index');
        Route::get('/products/moderation/{product}', [AdminProductModeration::class,'show'])->name('products.moderation.show');
        Route::post('/products/moderation/{product}/approve', [AdminProductModeration::class,'approve'])->name('products.moderation.approve');
        Route::post('/products/moderation/{product}/reject', [AdminProductModeration::class,'reject'])->name('products.moderation.reject');

        // disputes (admin)
        Route::get('/disputes', [AdminDispute::class, 'index'])->name('disputes.index');
        Route::get('/disputes/{dispute}', [AdminDispute::class, 'show'])->name('disputes.show');
        Route::post('/disputes/{dispute}/decide', [AdminDispute::class, 'decide'])->name('disputes.decide');
        Route::post('/disputes/{dispute}/refunded', [AdminDispute::class, 'markRefunded'])->name('disputes.refunded');

        // payouts (admin)
        Route::get('/payouts', [AdminPayout::class, 'index'])->name('payouts.index');
        Route::get('/payouts/{payout}', [AdminPayout::class, 'show'])->name('payouts.show');
        Route::post('/payouts/{payout}/decide', [AdminPayout::class, 'decide'])->name('payouts.decide');
        Route::post('/payouts/{payout}/paid', [AdminPayout::class, 'markPaid'])->name('payouts.paid');

        // reports
        Route::get('/reports', [AdminReport::class, 'index'])->name('reports.index');
        Route::get('/reports/{report}', [AdminReport::class, 'show'])->name('reports.show');
        Route::post('/reports/{report}/status', [AdminReport::class, 'updateStatus'])->name('reports.status');

        // KYC
        Route::get('/kyc', [AdminKyc::class, 'index'])->name('kyc.index');
        Route::get('/kyc/{kyc}', [AdminKyc::class, 'show'])->name('kyc.show');
        Route::post('/kyc/{kyc}/decide', [AdminKyc::class, 'decide'])->name('kyc.decide');
    });
});
Route::get('/notifications/check', function () {
    if (!auth()->check()) {
        return response()->json(['count' => 0]);
    }

    return response()->json([
        'count' => auth()->user()->unreadNotifications()->count(),
    ]);
})->name('notifications.check');

Route::middleware(['auth'])->get('/dashboard', function () {
    $user = auth()->user();

    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    if ($user->role === 'seller') {
        return redirect()->route('seller.dashboard');
    }

    return redirect()->route('home');
})->name('dashboard');


require __DIR__.'/auth.php';
