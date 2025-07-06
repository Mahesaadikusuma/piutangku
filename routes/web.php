<?php

use App\Http\Controllers\MidtransController;
use App\Http\Middleware\EnsureUserIsCustomer;
use App\Livewire\Company\Categories\Categories;
use App\Livewire\Company\CustomerAgePiutangDetail;
use App\Livewire\Company\Customers\CustomerCreate;
use App\Livewire\Company\Customers\CustomerEdit;
use App\Livewire\Company\Customers\Customers;
use App\Livewire\Company\Dashboard;
use App\Livewire\Company\Permissions\Permissions;
use App\Livewire\Company\PiutangProducts\PiutangProductCreate;
use App\Livewire\Company\PiutangProducts\PiutangProductDetail;
use App\Livewire\Company\PiutangProducts\PiutangProductEdit;
use App\Livewire\Company\PiutangProducts\PiutangProducts;
use App\Livewire\Company\Piutangs\PiutangCreate;
use App\Livewire\Company\Piutangs\PiutangDetail;
use App\Livewire\Company\Piutangs\PiutangEdit;
use App\Livewire\Company\Piutangs\PiutangMou;
use App\Livewire\Company\Piutangs\Piutangs;
use App\Livewire\Company\Products\Products;
use App\Livewire\Company\Roles\Roles;
use App\Livewire\Company\Transactions\PaymentCreate;
use App\Livewire\Company\Transactions\PaymentDetail;
use App\Livewire\Company\Transactions\PaymentEdit;
use App\Livewire\Company\Transactions\TransactionDetail;
use App\Livewire\Company\Transactions\Transactions;
use App\Livewire\Company\Users\Users;
use App\Livewire\Customers\DashboardCustomer;
use App\Livewire\Customers\Piutangs\PaymentPiutang;
use App\Livewire\Customers\Piutangs\PiutangCustomerDetails;
use App\Livewire\Customers\Piutangs\PiutangCustomers;
use App\Livewire\Customers\PiutangsPiutangCustomers;
use App\Livewire\Customers\Transactions\TransactionDetailCustomer;
use App\Livewire\Customers\Transactions\TransactionList;
use App\Livewire\Customers\Transactions\TransactionPayment;
use App\Livewire\HomePage;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// })->name('home');

Route::get("/", HomePage::class)->name("home");

Route::prefix('company')->middleware('auth', 'role:admin|company')->group(function () {
    Route::get("/dashboard", Dashboard::class)->name("dashboard");
    Route::get("/dashboard/customer-age-piutang/{customer:id}", CustomerAgePiutangDetail::class)->name("company.customer-piutang");
    Route::group(['prefix' => 'transaction', 'as' => 'transaction.'], function () {
        Route::get("/", Transactions::class)->name("index");
        Route::get("/create", PaymentCreate::class)->name("create");
        Route::get("/{transaction:uuid}/edit", PaymentEdit::class)->name("edit");
        Route::get("/{transaction:uuid}/detail", TransactionDetail::class)->name("detail");
        Route::get("/{transaction:uuid}/payment/detail", PaymentDetail::class)->name("show");
    });

    Route::group(['prefix' => 'master-data', 'as' => 'master-data.'], function () {
        Route::group(['prefix' => 'category', 'as' => 'category.'], function () {
            Route::get("/categories", Categories::class)->name("index");
        });
        Route::group(['prefix' => 'product', 'as' => 'product.'], function () {
            Route::get("/products", Products::class)->name("index");
        });
        Route::group(['prefix' => 'customer', 'as' => 'customer.'], function () {
            Route::get("/customers", Customers::class)->name("index");
            Route::get("/create", CustomerCreate::class)->name("create");
            Route::get("/{customer:uuid}/edit", CustomerEdit::class)->name("edit");
        });
        Route::group(['prefix' => 'piutang', 'as' => 'piutang.'], function () {
            Route::get("/piutangs", Piutangs::class)->name("index");
            Route::get("/create", PiutangCreate::class)->name("create");
            Route::get("/{piutang:uuid}/edit", PiutangEdit::class)->name("edit");
            Route::get("/{piutang:uuid}/mou", PiutangMou::class)->name("mou");
            Route::get("/{piutang:uuid}/detail", PiutangDetail::class)->name("detail");
        });

        Route::group(['prefix' => 'piutang-product', 'as' => 'piutang-product.'], function () {
            Route::get("/piutangs", PiutangProducts::class)->name("index");
            Route::get("/create", PiutangProductCreate::class)->name("create");
            Route::get("/{piutang:uuid}/edit", PiutangProductEdit::class)->name("edit");
            Route::get("/{piutang:uuid}/mou", PiutangMou::class)->name("mou");
            Route::get("/{piutang:uuid}/detail", PiutangProductDetail::class)->name("detail");
        });
    });

    Route::group(['prefix' => 'users', 'as' => 'users.'], function () {
        Route::get("/", Users::class)->name("index");
        Route::group(['prefix' => 'roles', 'as' => 'roles.'], function () {
            Route::get("/", Roles::class)->name("index");
        });
        Route::group(['prefix' => 'permissions', 'as' => 'permissions.'], function () {
            Route::get("/", Permissions::class)->name("index");
        });
    });
});



Route::prefix('dashboard')->middleware('auth', EnsureUserIsCustomer::class)->group(function () {
    Route::get("/", DashboardCustomer::class)->name("dashboard-customer");

    Route::group(['prefix' => 'transactions', 'as' => 'transactions.'], function () {
        Route::get("/piutangs", PiutangCustomers::class)->name("piutang.customer.index");
        Route::get('/piutang/{piutang:uuid}/detail', PiutangCustomerDetails::class)->name("piutang.customer.detail");
        Route::get('/piutang/{piutang:uuid}/payment', PaymentPiutang::class)->name("piutang.payment");

        Route::get("/history/transactions", TransactionList::class)->name("customer.trnsaction");
        Route::get("/transaction/history/{transaction:uuid}/detail", TransactionDetailCustomer::class)->name("customer.trnsaction.detail");
        Route::get("/transaction/{transaction:uuid}/payment", TransactionPayment::class)->name("customer.trnsaction.payment");
    });
});


Route::get('/midtrans/success', [MidtransController::class, 'finishRedirect'])->name('success');
Route::get('/midtrans/unfinish', [MidtransController::class, 'unfinishRedirect'])->name('unfinish');
Route::get('/midtrans/error', [MidtransController::class, 'errorRedirect'])->name('error');


Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

require __DIR__ . '/auth.php';
