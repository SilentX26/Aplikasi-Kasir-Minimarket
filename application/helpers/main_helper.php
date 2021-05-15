<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function setAlertResult($status, $message, $redirect = FALSE)
{
    $redirect = ($redirect !== FALSE) ? $redirect : $_SERVER['PATH_INFO'];
    get_instance()->session->set_flashdata('alertResult', ['status' => $status, 'message' => $message]);
    redirect($redirect);
}

function filter($a)
{
	$CI =& get_instance();
	$a = strip_tags(htmlspecialchars($CI->db->escape_str($a)));
	$a = $CI->security->xss_clean($a);
	return $a;
}

function isMenuActive($type, $uri, $length)
{
    $CI =& get_instance();

    $array_uris = $CI->uri->segment_array();
    $current_uri = implode('/', array_slice($array_uris, 0, $length));

    $success = ($type == 'submenu') ? 'show' : 'active';
    $return = (is_array($uri)) ? in_array($current_uri, $uri) : $current_uri == $uri;
    return ($return !== FALSE) ? $success : '';
}

function formatted($format, $val)
{
	$mounth = [
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

	switch($format) {
		case 'currency':
			return number_format($val ,0,',','.');
		    break;
		case 'month':
		    $cut = explode('-', $val);
			return "{$mounth[ $cut[1] ]} {$cut[0]}";
		    break;
		case 'date':
			$cut = explode('-', $val);
			return $cut[2]. ' ' .$mounth[ $cut[1] ]. ' ' .$cut[0];
	    	break;
		case 'datetime':
			$cut = explode(' ', $val);
			$date = explode('-', $cut[0]);
			return $date[2]. ' ' .$mounth[ $date[1] ]. ' ' .$date[0]. ', ' .explode(':', $cut[1])[0]. '.' .explode(':', $cut[1])[1]. ' WIB';
    		break;
    	case 'diff_date':
            $interval = strtotime($val[1]) - strtotime($val[0]);
            $data_rumus = [
                'y' => 31536000,
                'm' => 2592000,
                'd' => 86400,
                'h' => 3600,
                'i' => 60,
                's' => 1
            ];
        
            array_walk($data_rumus, function(&$value, $key, $interval) {
                $value = abs(round($interval / $value));
            }, $interval);
            return (object) $data_rumus;
            break;
        case 'diff_date_string':
    	    $origin = new DateTime($val[0]);
            $target = new DateTime($val[1]);
            $diff = $origin->diff($target);
 
            $diff->w = floor($diff->d / 7);
            $diff->d -= $diff->w * 7;
 
            $string = array(
                'y' => 'Tahun',
                'm' => 'Bulan',
                'w' => 'Minggu',
                'd' => 'Hari',
                'h' => 'Jam',
                'i' => 'Menit',
                's' => 'Detik',
            );
            foreach ($string as $k => &$v) {
                if ($diff->$k != 0) {
                    $v = "{$diff->$k} {$v}";
                } else {
                    unset($string[$k]);
                }
            }
            
            if (!isset($val[2])) $string = array_slice($string, 0, 1);
            return $string ? implode(', ', $string) .' Yang Lalu' : 'Baru Saja';
    	    break;
		 
	    default:
	        return NULL;
	}
}

function get_time($act)
{
    switch($act) {
        case 'time':
            return date('H:i:s');
            break;
        case 'date':
            return date('Y-m-d');
            break;
        case 'datetime':
            return date('Y-m-d H:i:s');
            break;
            
        default:
            return NULL;
    }
}

function callJsFiles($val, $debug = FALSE)
{
    $result = '';
    $version = ($debug === TRUE) ? time() : '';

    foreach($val as $path => $src) {
        foreach($src as $key => $value) {
            $result .= '<script src="' .base_url("assets/js/{$path}/{$value}.js?v={$version}"). '"></script>' . "\n";
        }
    }
    
    return $result;
}

function callCssFiles($val, $debug = FALSE)
{
    $result = '';
    $version = ($debug) ? time() : '';

    foreach($val as $path => $src) {
        foreach($src as $key => $value) {
            $result .= '<link rel="stylesheet" href="' .base_url("assets/css/{$path}/{$value}.css?v={$version}"). '">' . "\n";
        }
    }
    
    return $result;
}

function invalid_validation($name)
{
    return !empty(form_error($name)) ? 'is-invalid' : '';
}

function select_opt($value, $valid_value)
{
    return ($value == $valid_value) ? 'selected' : '';
}