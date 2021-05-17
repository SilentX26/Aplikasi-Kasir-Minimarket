<?php
function logged_in()
{
    if(!isset($_COOKIE['login_token']))
        return FALSE;

    $CI =& get_instance();
    $login_token = hash('gost', $_COOKIE['login_token']);

    $data_user = $CI->Main_model->get_row('akun', '*', ['login_token' => $login_token]);
    if(!$data_user)
        return FALSE;

    $data_user->data = json_decode($data_user->data);
    return $data_user;
}

function core_waktu_terkini()
{
    $hari = [
        'Sun' => 'Minggu',
        'Mon' => 'Senin',
        'Tue' => 'Selasa',
        'Wed' => 'Rabu',
        'Thu' => 'kamis',
        'Fri' => 'Jumat',
        'Sat' => 'Sabtu'
    ];
    $bulan = [
        '01' => 'Januari',
		'02' => 'Februari',
		'03' => 'Maret',
		'04' => 'April',
		'05' => 'Mei',
		'06' => 'Juni',
		'07' => 'Juli',
		'08' => 'Agustus',
		'09' => 'September',
		'10' => 'Oktober',
		'11' => 'November',
		'12' => 'Desember'
	];

    return $hari[ date('D') ]. ", " .date('d'). " " .$bulan[ date('m') ]. " " .date('Y');
}