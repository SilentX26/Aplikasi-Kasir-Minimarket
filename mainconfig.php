<?php
/*
    ###################################################################################################

    @ Script Point of Sales
    @ Pengelolaan penjualan Minimarket/Toko
    @ Dibuat oleh Muhammad Randika Rosyid
    @ Dilarang memperjual belikan script ini tanpa sepengetahuan pembuat!!
    @ Undang-Undang Nomor 28 tahun 2014 tentang Hak Cipta 

    ###################################################################################################
*/

date_default_timezone_set('Asia/Jakarta');

$_SERVER['CI_ENV'] = 'development'; // ci env: development, testing, production
$config['silentX']['encryption_key'] = 'silentX'; // encryption key