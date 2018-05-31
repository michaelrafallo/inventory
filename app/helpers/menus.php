<?php

function sidebar_menu($type = '') {


  $menu[] = array(
        'title'    => 'Dashboard',
        'icon'     => 'home',
        'class'    => '',
        'url'      => URL::route('app.general.dashboard'),
        'sub_menu' => array()
    );


    if( App\Permission::has_access('users') ) {

        $menu[] = array(
            'title'    => 'Users',
            'icon'     => 'users',
            'class'    => '',
            'url'      => '',
            'sub_menu' => array(
                array(
                    'title'    => 'All Users',
                    'icon'     => '',
                    'class'    => '',
                    'url'      => URL::route('app.users.index'),
                    'sub_menu' => array()
                )
            )
        );

        if( App\Permission::has_access('users', ['add_edit']) ) {
            $menu[1]['sub_menu'][] = array(
                'title'    => 'Add User',
                'icon'     => '',
                'class'    => '',
                'url'      => URL::route('app.users.add'),
                'sub_menu' => array()
            );
        }
    }


    if( App\Permission::has_access('groups') ) {
        $menu[2] = array(
            'title'    => 'Groups',
            'icon'     => 'badge',
            'class'    => '',
            'url'      => '',
            'sub_menu' => array(
                array(
                    'title'    => 'All Groups',
                    'icon'     => '',
                    'class'    => '',
                    'url'      => URL::route('app.groups.index'),
                    'sub_menu' => array()
                )
            )
        );

        if( App\Permission::has_access('groups', ['add_edit']) ) {
            $menu[2]['sub_menu'][] = array(
                'title'    => 'Add Group',
                'icon'     => '',
                'class'    => '',
                'url'      => URL::route('app.groups.add'),
                'sub_menu' => array()
            );
        }
    }

    if( App\Permission::has_access('companies') ) {
        $menu[3] = array(
            'title'    => 'Companies',
            'icon'     => 'briefcase',
            'class'    => '',
            'url'      => '',
            'sub_menu' => array(
                array(
                    'title'    => 'All Companies',
                    'icon'     => '',
                    'class'    => '',
                    'url'      => URL::route('app.companies.index'),
                    'sub_menu' => array()
                )
            )
        );

        if( App\Permission::has_access('companies', ['add_edit']) ) {
            $menu[3]['sub_menu'][] = array(
                'title'    => 'Add Company',
                'icon'     => '',
                'class'    => '',
                'url'      => URL::route('app.companies.add'),
                'sub_menu' => array()
            );
        }
    }

    if( App\Permission::has_access('suppliers') ) {
        $menu[4] = array(
            'title'    => 'Suppliers',
            'icon'     => 'users',
            'class'    => '',
            'url'      => '',
            'sub_menu' => array(
                array(
                    'title'    => 'All Suppliers',
                    'icon'     => '',
                    'class'    => '',
                    'url'      => URL::route('app.suppliers.index'),
                    'sub_menu' => array()
                )
            )
        );

        if( App\Permission::has_access('suppliers', ['add_edit']) ) {
            $menu[4]['sub_menu'][] = array(
                'title'    => 'Add Supplier',
                'icon'     => '',
                'class'    => '',
                'url'      => URL::route('app.suppliers.add'),
                'sub_menu' => array()
            );
        }

    }

    if( App\Permission::has_access('purchase-orders') ) {
        $menu[5] = array(
            'title'    => 'Purchase Orders',
            'icon'     => 'basket-loaded',
            'class'    => '',
            'url'      => '',
            'sub_menu' => array(
                array(
                    'title'    => 'All Purchase Orders',
                    'icon'     => '',
                    'class'    => '',
                    'url'      => URL::route('app.purchase-orders.index'),
                    'sub_menu' => array()
                )
            )
        );

        if( App\Permission::has_access('purchase-orders', ['add_edit']) ) {
            $menu[5]['sub_menu'][] = array(
                'title'    => 'Add Purchase Order',
                'icon'     => '',
                'class'    => '',
                'url'      => URL::route('app.purchase-orders.add'),
                'sub_menu' => array()
            );
        }

    }
    
    if( App\Permission::has_access('products') ) {
        $menu[6] = array(
            'title'    => 'Products',
            'icon'     => 'note',
            'class'    => '',
            'url'      => '',
            'sub_menu' => array()
        );


        $menu[6]['sub_menu'][] = array(
            'title'    => 'All Products',
            'icon'     => '',
            'class'    => '',
            'url'      => URL::route('app.inventory.index'),
            'sub_menu' => array()
        );    
            
        if( App\Permission::has_access('products', ['add_edit']) ) {
            $menu[6]['sub_menu'][] = array(
                'title'    => 'Add Product',
                'icon'     => '',
                'class'    => '',
                'url'      => URL::route('app.inventory.add'),
                'sub_menu' => array()
            );
        }


        if( App\Permission::has_access('products', ['product_category']) ) {
            $menu[6]['sub_menu'][] = array(
                'title'    => 'Product Category',
                'icon'     => '',
                'class'    => '',
                'url'      => URL::route('app.inventory.category'),
                'sub_menu' => array()
            );
        }
        
            if( App\Permission::has_access('products', ['terms']) ) {
            $menu[6]['sub_menu'][] = array(
                'title'    => 'Terms',
                'icon'     => '',
                'class'    => '',
                'url'      => URL::route('app.inventory.terms'),
                'sub_menu' => array()
            );
        }

        if( App\Permission::has_access('products', ['inventory']) ) {
            $menu[6]['sub_menu'][] = array(
                'title'    => 'Inventory',
                'icon'     => '',
                'class'    => '',
                'url'      => URL::route('app.inventory.stocks'),
                'sub_menu' => array()
            );
        }
    }
    
    if( App\Permission::has_access('customers') ) {
        $menu[7] = array(
            'title'    => 'Customers',
            'icon'     => 'users',
            'class'    => '',
            'url'      => '',
            'sub_menu' => array(
                array(
                    'title'    => 'All Customers',
                    'icon'     => '',
                    'class'    => '',
                    'url'      => URL::route('app.customers.index'),
                    'sub_menu' => array()
                )                
            )
        );

        if( App\Permission::has_access('customers', ['add_edit']) ) {
            $menu[7]['sub_menu'][] = array(
                'title'    => 'Add Customer',
                'icon'     => '',
                'class'    => '',
                'url'      => URL::route('app.customers.add'),
                'sub_menu' => array()
            );
        }
    }
    
    if( App\Permission::has_access('sales-orders') ) {
        $menu[8] = array(
            'title'    => 'Sales',
            'icon'     => 'bar-chart',
            'class'    => '',
            'url'      => '',
            'sub_menu' => array(
                array(
                    'title'    => 'All Sales Orders',
                    'icon'     => '',
                    'class'    => '',
                    'url'      => URL::route('app.sales-orders.index'),
                    'sub_menu' => array()
                )
            )
        );

        if( App\Permission::has_access('sales-orders', ['add_edit']) ) {
            $menu[8]['sub_menu'][] = array(
                'title'    => 'Add Sales Order',
                'icon'     => '',
                'class'    => '',
                'url'      => URL::route('app.sales-orders.add'),
                'sub_menu' => array()
            );
        }
    }
    
    if( App\Permission::has_access('expenses') ) {
        $menu[9] = array(
            'title'    => 'Expenses',
            'icon'     => 'pie-chart',
            'class'    => '',
            'url'      => '',
            'sub_menu' => array(
                array(
                    'title'    => 'All Expenses',
                    'icon'     => '',
                    'class'    => '',
                    'url'      => URL::route('app.expenses.index'),
                    'sub_menu' => array()
                )
            )
        );

        if( App\Permission::has_access('expenses', ['add_edit']) ) {
            $menu[9]['sub_menu'][] = array(
                'title'    => 'Add Expenses',
                'icon'     => '',
                'class'    => '',
                'url'      => URL::route('app.expenses.add'),
                'sub_menu' => array()
            );
        }
    }

    
    if( App\Permission::has_access('settings') ) {
        $menu[] = array(
        'title'    => 'Settings',
        'icon'     => 'settings',
        'class'    => '',
        'url'      => URL::route('app.general.settings'),
        'sub_menu' => array()
        );
    }

    

  return $menu;
}

//----------------------------------------------------------------

function top_nav_menu() {
    
  $menu = array(
    array(
      'title' => 'My Profile',
      'icon' => 'user',
      'class' => '',
      'url'   => URL::route('app.users.profile'),
      'sub_menu' => array()
    )
  );

  return $menu;
}

//----------------------------------------------------------------