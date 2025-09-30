<?php

use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Illuminate\Support\Facades\Auth;
use App\Http\Livewire\Dashboard\Dashboard;
use App\Http\Livewire\Dashboard\Users\Index as UsersIndex;
use App\Http\Livewire\Dashboard\Roles\Index as RolesIndex;
use App\Http\Livewire\Dashboard\Permissions\Index as PermissionsIndex;
use App\Http\Livewire\Dashboard\Countries\Index as CountriesIndex;
use App\Http\Livewire\Dashboard\Cities\Index as CitiesIndex;
use App\Http\Livewire\Dashboard\Villages\Index as VillagesIndex;
use App\Http\Livewire\Dashboard\Stores\Index as StoresIndex;
use App\Http\Livewire\Dashboard\StoreCategories\Index as StoreCategoriesIndex;
use App\Http\Livewire\Profile\Show as ProfileShow;
use App\Http\Livewire\Auth\Login;
// use App\Http\Livewire\Auth\Register;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
    ],
    function () {
             Livewire::setUpdateRoute(function ($handle) {
            return Route::post('/livewire/update', $handle);
        });

        // Authentication Routes
        Route::middleware('guest')->group(function () {
            Route::get('login', Login::class)->name('login');
            // Route::get('register', Register::class)->name('register');
        });

        Route::post('/logout', function () {
            Auth::logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
            return redirect('/');
        })->name('logout')->middleware('auth');


        // Authenticated Routes (merchants with inactive stores will be redirected to setup)
        Route::middleware(['auth', 'merchant.store.setup'])->group(function () {
            Route::get('/', Dashboard::class)->name('admin.dashboard');
            Route::get('/users', UsersIndex::class)->name('admin.users');
            Route::get('/roles', RolesIndex::class)->name('admin.roles');
            Route::get('/permissions', PermissionsIndex::class)->name('admin.permissions');

            // Locations Management
            Route::get('/countries', CountriesIndex::class)->name('admin.countries');
            Route::get('/cities', CitiesIndex::class)->name('admin.cities');
            Route::get('/villages', VillagesIndex::class)->name('admin.villages');
            Route::get('/stores', StoresIndex::class)->name('admin.stores');
            Route::get('/store-categories', StoreCategoriesIndex::class)->name('admin.store-categories');

            // Merchant store setup (forces merchants with inactive store to complete setup)
            Route::get('/merchant/store/setup', \App\Http\Livewire\Merchant\Store\Setup::class)
                ->name('merchant.store.setup');

            // Profile page
            Route::get('/profile/{user:username?}', ProfileShow::class)
                ->name('profile.show');
                
            // Friends Routes - Using Livewire
            Route::get('/friends', \App\Http\Livewire\Friends\FriendsIndex::class)
                ->name('friends.index');
                
            // Test Friends System Route
            Route::get('/test-friends', function () {
                return view('test-friends');
            })->name('test.friends');
        });
   
    }
);


    
