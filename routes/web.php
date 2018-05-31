<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/* Localhost Only */
if( in_array($_SERVER["REMOTE_ADDR"], ['127.0.0.1', '::1']) ){
	Route::get('/', function () {
		$url = '//'.getHostByName(getHostName()).$_SERVER['REQUEST_URI'].'login';
	    return Redirect::to($url);
	});
}

// Get database name
$database = explode ('/', $_SERVER['PHP_SELF'])[1].'_'.request()->segment(1);

// Override configuration
Config::set('database.connections.mysql.database', $database);
// Config::set('app.key', 'base64:'.base64_encode(md5($database)));




Route::group(['prefix' => request()->segment(1)], function() {

	Route::any('/', [
	    'as'   => 'auth.login', 
	    'uses' => 'AuthController@login'
	]);

	Route::any('forgot-password/{token?}', [
	    'as'   => 'auth.forgot-password', 
	    'uses' => 'AuthController@forgotPassword'
	]);


	Route::group(['middleware' => ['auth']], function() {

		Route::any('dashboard', [
		    'as'   => 'app.general.dashboard', 
		    'uses' => 'GeneralController@dashboard'
		]);


		Route::group(['prefix' => 'ajax/posts'], function() {
			Route::any('view/{id?}', [
			    'as'   => 'app.ajax.posts.view', 
			    'uses' => 'AjaxPostController@view'
			]);
		});

		/* START USERS */
		Route::group(['prefix' => 'users'], function() {
			Route::any('/', [
			    'as'   => 'app.users.index', 
			    'uses' => 'UserController@index'
			]);
			Route::any('add', [
			    'as'   => 'app.users.add', 
			    'uses' => 'UserController@add'
			]);
			Route::any('edit/{id?}', [
			    'as'   => 'app.users.edit', 
			    'uses' => 'UserController@edit'
			]);
			Route::any('delete/{id?}', [
			    'as'   => 'app.users.delete', 
			    'uses' => 'UserController@delete'
			]);
			Route::any('restore/{id?}', [
			    'as'   => 'app.users.restore', 
			    'uses' => 'UserController@restore'
			]);
			Route::any('destroy/{id?}', [
			    'as'   => 'app.users.destroy', 
			    'uses' => 'UserController@destroy'
			]);
			Route::any('profile', [
			    'as'   => 'app.users.profile', 
			    'uses' => 'UserController@profile'
			]);
			Route::any('login/{id?}', [
			    'as'   => 'app.users.login', 
			    'uses' => 'UserController@login'
			]);
		});
		/* END USERS */


		/* START GROUPS */
		Route::group(['prefix' => 'groups'], function() {
			Route::any('/', [
			    'as'   => 'app.groups.index', 
			    'uses' => 'GroupController@index'
			]);
			Route::any('add', [
			    'as'   => 'app.groups.add', 
			    'uses' => 'GroupController@add'
			]);
			Route::any('edit/{id?}', [
			    'as'   => 'app.groups.edit', 
			    'uses' => 'GroupController@edit'
			]);
			Route::any('delete/{id?}', [
			    'as'   => 'app.groups.delete', 
			    'uses' => 'GroupController@delete'
			]);
			Route::any('restore/{id?}', [
			    'as'   => 'app.groups.restore', 
			    'uses' => 'GroupController@restore'
			]);
			Route::any('destroy/{id?}', [
			    'as'   => 'app.groups.destroy', 
			    'uses' => 'GroupController@destroy'
			]);

			Route::any('permissions/{id?}', [
			    'as'   => 'app.groups.permissions', 
			    'uses' => 'GroupController@permissions'
			]);
		});
		/* END GROUPS */


		/* START COMPANIES */
		Route::group(['prefix' => 'companies'], function() {
			Route::any('/', [
			    'as'   => 'app.companies.index', 
			    'uses' => 'CompanyController@index'
			]);
			Route::any('add', [
			    'as'   => 'app.companies.add', 
			    'uses' => 'CompanyController@add'
			]);
			Route::any('edit/{id?}', [
			    'as'   => 'app.companies.edit', 
			    'uses' => 'CompanyController@edit'
			]);
			Route::any('delete/{id?}', [
			    'as'   => 'app.companies.delete', 
			    'uses' => 'CompanyController@delete'
			]);
			Route::any('restore/{id?}', [
			    'as'   => 'app.companies.restore', 
			    'uses' => 'CompanyController@restore'
			]);
			Route::any('destroy/{id?}', [
			    'as'   => 'app.companies.destroy', 
			    'uses' => 'CompanyController@destroy'
			]);
		});
		/* END COMPANIES */


		/* START SUPPLIERS */
		Route::group(['prefix' => 'suppliers'], function() {
			Route::any('/', [
			    'as'   => 'app.suppliers.index', 
			    'uses' => 'SupplierController@index'
			]);
			Route::any('add', [
			    'as'   => 'app.suppliers.add', 
			    'uses' => 'SupplierController@add'
			]);
			Route::any('edit/{id?}', [
			    'as'   => 'app.suppliers.edit', 
			    'uses' => 'SupplierController@edit'
			]);
			Route::any('delete/{id?}', [
			    'as'   => 'app.suppliers.delete', 
			    'uses' => 'SupplierController@delete'
			]);
			Route::any('restore/{id?}', [
			    'as'   => 'app.suppliers.restore', 
			    'uses' => 'SupplierController@restore'
			]);
			Route::any('destroy/{id?}', [
			    'as'   => 'app.suppliers.destroy', 
			    'uses' => 'SupplierController@destroy'
			]);
			Route::any('product', [
			    'as'   => 'app.suppliers.product', 
			    'uses' => 'SupplierController@product'
			]);

		});
		/* END SUPPLIERS */


		/* START CUSTOMERS */
		Route::group(['prefix' => 'customers'], function() {
			Route::any('/', [
			    'as'   => 'app.customers.index', 
			    'uses' => 'CustomerController@index'
			]);
			Route::any('add', [
			    'as'   => 'app.customers.add', 
			    'uses' => 'CustomerController@add'
			]);
			Route::any('edit/{id?}', [
			    'as'   => 'app.customers.edit', 
			    'uses' => 'CustomerController@edit'
			]);
			Route::any('delete/{id?}', [
			    'as'   => 'app.customers.delete', 
			    'uses' => 'CustomerController@delete'
			]);
			Route::any('restore/{id?}', [
			    'as'   => 'app.customers.restore', 
			    'uses' => 'CustomerController@restore'
			]);
			Route::any('destroy/{id?}', [
			    'as'   => 'app.customers.destroy', 
			    'uses' => 'CustomerController@destroy'
			]);
			Route::any('soa/{id?}', [
			    'as'   => 'app.customers.soa', 
			    'uses' => 'CustomerController@soa'
			]);
			Route::any('product', [
			    'as'   => 'app.customers.product', 
			    'uses' => 'CustomerController@product'
			]);
			Route::any('pricelist', [
			    'as'   => 'app.customers.pricelist', 
			    'uses' => 'CustomerController@pricelist'
			]);
		});
		/* END CUSTOMERS */


		/* START PURCHASE ORDERS */
		Route::group(['prefix' => 'purchase-orders'], function() {
			Route::any('/', [
			    'as'   => 'app.purchase-orders.index', 
			    'uses' => 'PurchaseOrderController@index'
			]);
			Route::any('add', [
			    'as'   => 'app.purchase-orders.add', 
			    'uses' => 'PurchaseOrderController@add'
			]);
			Route::any('edit/{id?}', [
			    'as'   => 'app.purchase-orders.edit', 
			    'uses' => 'PurchaseOrderController@edit'
			]);
			Route::any('delete/{id?}', [
			    'as'   => 'app.purchase-orders.delete', 
			    'uses' => 'PurchaseOrderController@delete'
			]);
			Route::any('restore/{id?}', [
			    'as'   => 'app.purchase-orders.restore', 
			    'uses' => 'PurchaseOrderController@restore'
			]);
			Route::any('destroy/{id?}', [
			    'as'   => 'app.purchase-orders.destroy', 
			    'uses' => 'PurchaseOrderController@destroy'
			]);
			Route::any('payables', [
			    'as'   => 'app.purchase-orders.payables', 
			    'uses' => 'PurchaseOrderController@payables'
			]);
		});
		/* END PURCHASE ORDERS */

		/* START DELIVERY ORDERS */
		Route::group(['prefix' => 'sales-orders'], function() {
			Route::any('/', [
			    'as'   => 'app.sales-orders.index', 
			    'uses' => 'SalesController@index'
			]);
			Route::any('add', [
			    'as'   => 'app.sales-orders.add', 
			    'uses' => 'SalesController@add'
			]);
			Route::any('edit/{id?}', [
			    'as'   => 'app.sales-orders.edit', 
			    'uses' => 'SalesController@edit'
			]);
			Route::any('delete/{id?}', [
			    'as'   => 'app.sales-orders.delete', 
			    'uses' => 'SalesController@delete'
			]);
			Route::any('restore/{id?}', [
			    'as'   => 'app.sales-orders.restore', 
			    'uses' => 'SalesController@restore'
			]);
			Route::any('destroy/{id?}', [
			    'as'   => 'app.sales-orders.destroy', 
			    'uses' => 'SalesController@destroy'
			]);
			Route::any('sales', [
			    'as'   => 'app.sales-orders.sales', 
			    'uses' => 'SalesController@sales'
			]);
		});
		/* END DELIVERY ORDERS */





		/* START INVENTORY */
		Route::group(['prefix' => 'inventory'], function() {
			Route::any('/', [
			    'as'   => 'app.inventory.index', 
			    'uses' => 'InventoryController@index'
			]);
			Route::any('add', [
			    'as'   => 'app.inventory.add', 
			    'uses' => 'InventoryController@add'
			]);
			Route::any('edit/{id?}', [
			    'as'   => 'app.inventory.edit', 
			    'uses' => 'InventoryController@edit'
			]);
			Route::any('delete/{id?}', [
			    'as'   => 'app.inventory.delete', 
			    'uses' => 'InventoryController@delete'
			]);
			Route::any('restore/{id?}', [
			    'as'   => 'app.inventory.restore', 
			    'uses' => 'InventoryController@restore'
			]);
			Route::any('destroy/{id?}', [
			    'as'   => 'app.inventory.destroy', 
			    'uses' => 'InventoryController@destroy'
			]);
			Route::any('category', [
			    'as'   => 'app.inventory.category', 
			    'uses' => 'InventoryController@product_category'
			]);
			Route::any('terms', [
			    'as'   => 'app.inventory.terms', 
			    'uses' => 'InventoryController@terms'
			]);
			Route::any('stocks', [
			    'as'   => 'app.inventory.stocks', 
			    'uses' => 'InventoryController@stocks'
			]);
		});
		/* END INVENTORY */


		/* START EXPENSES */
		Route::group(['prefix' => 'expenses'], function() {

			Route::any('/', [
			    'as'   => 'app.expenses.index', 
			    'uses' => 'ExpenseController@index'
			]);
			Route::any('add', [
			    'as'   => 'app.expenses.add', 
			    'uses' => 'ExpenseController@add'
			]);
			Route::any('edit/{id?}', [
			    'as'   => 'app.expenses.edit', 
			    'uses' => 'ExpenseController@edit'
			]);
			Route::any('delete/{id?}', [
			    'as'   => 'app.expenses.delete', 
			    'uses' => 'ExpenseController@delete'
			]);
			Route::any('restore/{id?}', [
			    'as'   => 'app.expenses.restore', 
			    'uses' => 'ExpenseController@restore'
			]);
			Route::any('destroy/{id?}', [
			    'as'   => 'app.expenses.destroy', 
			    'uses' => 'ExpenseController@destroy'
			]);
		});
		/* END EXPENSES */


		Route::group(['prefix' => 'settings'], function() {
			Route::any('/', [
			    'as'   => 'app.general.settings', 
			    'uses' => 'GeneralController@settings'
			]);
			Route::any('ajax/update', [
		        'as'   => 'app.general.settings.ajax.update', 
		        'uses' => 'GeneralController@ajax_update_settings'
			]);
		});


		Route::any('lock', [
		    'as'   => 'auth.lock', 
		    'uses' => 'AuthController@lock'
		]);


		Route::any('logout', [
		    'as'   => 'auth.logout', 
		    'uses' => 'AuthController@logout'
		]);

	});


	Route::any('unlock', [
	    'as'   => 'auth.unlock', 
	    'uses' => 'AuthController@unlock'
	]);

});