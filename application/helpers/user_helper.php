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