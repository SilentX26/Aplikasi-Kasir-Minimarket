<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'Auth/index';
$route['404_override'] = 'Err/index';
$route['translate_uri_dashes'] = FALSE;

/* # ERROR ROUTE */
$route['error/(:any)'] = 'Err/index/$1';

/*
    @ User Features
*/
/* # Pengaturan Akun */
$route['pengaturan-akun'] = 'Auth/pengaturan';

/*
    @ Admin Panel
*/
/* # Dashboard */
$route['admin/dashboard'] = 'Admin/Dashboard/index';

/* # Transaksi */
$route['admin/transaksi'] = 'Admin/Transaksi/index';
$route['admin/transaksi/hapus'] = 'Admin/Transaksi/hapus';
$route['admin/transaksi/export'] = 'Admin/Transaksi/export';

/* # Akun */
$route['admin/akun'] = 'Admin/Akun/index';
$route['admin/akun/tambah'] = 'Admin/Akun/tambah';
$route['admin/akun/hapus'] = 'Admin/Akun/hapus';
$route['admin/akun/edit/(:num)'] = 'Admin/Akun/edit/$1';
$route['admin/akun/export'] = 'Admin/Akun/export';
$route['admin/akun/import'] = 'Admin/Akun/import';

/* # Produk */
$route['admin/produk'] = 'Admin/Produk/index';
$route['admin/produk/tambah'] = 'Admin/Produk/tambah';
$route['admin/produk/hapus'] = 'Admin/Produk/hapus';
$route['admin/produk/edit/(:num)'] = 'Admin/Produk/edit/$1';
$route['admin/produk/export'] = 'Admin/Produk/export';
$route['admin/produk/import'] = 'Admin/Produk/import';

/* # Konfigurasi */
$route['admin/konfigurasi'] = 'Admin/Konfigurasi/index';
$route['admin/konfigurasi/logo'] = 'Admin/Konfigurasi/logo';
$route['admin/konfigurasi/lainnya'] = 'Admin/Konfigurasi/lainnya';