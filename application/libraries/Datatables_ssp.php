<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Datatables_ssp
{
	public $CI;
	private $_table, $_field, $_customQuery = [];

	function __construct()
	{
		$this->CI =& get_instance();
	}
	
	function exec($table, $field, $customQuery = [])
	{
	    $this->_table = $table;
	    $this->_field = $field;
	    $this->_customQuery = $customQuery;

		$this->_getData();
		if($_POST['length'] != -1)
            $this->CI->db->limit($_POST['length'], $_POST['start']);
		$data = $this->CI->db->get($this->_table);

	    return json_encode([
	        'draw' => isset($_POST['draw']) ? intval($_POST['draw']) : 1,
	        'recordsTotal' => $this->_recordsTotal(), 
	        'recordsFiltered' => $this->_recordsFiltered(),
	        'data' => $this->_fetchRow( $data->result_array() )
	    ]);
	}

	private function _clear_select($x)
	{
		return (preg_match('/^(?!json)(?!cast)[\s\S]+,\s+([\s\S]+)/mi', $x))
			? preg_replace('/,\s+([\s\S]+)/mi', '', $x)
			: preg_replace('/\s+as(\s+(?!unsigned))(\w+(?!unsigned))(?!\s+unsigned)/mi', '', $x);
	}
	
	private function _getData()
	{
        if(isset($this->_customQuery['join'])) {
            if (isset($this->_customQuery['join'][0])) {
	    		foreach ($this->_customQuery['join'] as $key => $value) {
		    		$this->CI->db->join($value['table'], $value['on'], $value['param']);
			    }
            } else {
                $this->CI->db->join($this->_customQuery['join']['table'], $this->_customQuery['join']['on'], $this->_customQuery['join']['param']);
            }
		}
		if (isset($this->_customQuery['where'])) {
		    if(is_array($this->_customQuery['where']) && isset($this->_customQuery['where'][0])) {
    			foreach ($this->_customQuery['where'] as $key => $value) {
	    			$this->CI->db->where($value);
		    	}
		    } else {
		        $this->CI->db->where($this->_customQuery['where']);
		    }
		}
		if (isset($this->_customQuery['group_by']))
		    $this->CI->db->group_by($data['group_by']);
		
	    if(isset($_POST['search']['value'])) {
	        foreach($this->_field as $key => $value) {
				if(isset($value['select'])) {
					$column = $this->_clear_select($value['select']);
					$select = $value['select'];
				} else {
					$select = $column = $value['column'];
				}
				
				$this->CI->db->select($select);

	            $search = filter($_POST['search']['value']);
	            $search_column = filter($_POST['columns'][$key]['search']['value']);
	            
	            if(isset($this->_field[$key]['search']) && ($search !== '' || $search_column !== '')) {
	                if(!isset($start_group)) {
	                    $this->CI->db->group_start();
	                    $start_group = TRUE;
	                }
					
	                if($search_column !== '') {
	                    $this->CI->db->where("{$column} = '{$search_column}'");
	                } else {
	                    if(!isset($start_like)) {
	                        $this->CI->db->where("{$column} LIKE '%{$search}%'");
	                        $start_like = TRUE;
	                    } else {
	                        $this->CI->db->or_where("{$column} LIKE '%{$search}%'");
	                    }
	                }
	            }
	            
	            if(($key + 1) == count($this->_field) && isset($start_group))
	                $this->CI->db->group_end();
	        }
	    }
	    
	    if(isset($_POST['order'])) {
			$order_column = $_POST['order']['0']['column'];
			$order_type = $_POST['order']['0']['dir'];
	        $this->CI->db->order_by($this->_field[$order_column]['column'], $order_type);
		}
	}
	
	private function _fetchRow($data)
	{
	    if(count($data) == 0)
	        return [];
	    
		foreach($data as $datas) {
		    $i = 0;
			foreach($this->_field as $key => $value) {
			    $field = $value['column'];
			    if(key_exists($field, $datas)) {
			        $row[$i] = (isset($value['formatted']))
			            ? $value['formatted']( $datas[$field], $datas )
			            : $datas[$field];
			    } else {
			        $row[$i] = '';
			    }
			    
			    $i++;
			}
			$result[] = $row;
		}
		
		return $result;
	}
	
	private function _recordsTotal()
	{
	    $data = $this->CI->db->from($this->_table);
	    if(isset($this->_customQuery['join'])) {
            if (isset($this->_customQuery['join'][0])) {
	    		foreach ($this->_customQuery['join'] as $key => $value) {
		    		$data = $this->CI->db->join($value['table'], $value['on'], $value['param']);
			    }
            } else {
                $data = $this->CI->db->join($this->_customQuery['join']['table'], $this->_customQuery['join']['on'], $this->_customQuery['join']['param']);
            }
		}
		if (isset($this->_customQuery['where'])) {
		    if(is_array($this->_customQuery['where']) && isset($this->_customQuery['where'][0])) {
    			foreach ($this->_customQuery['where'] as $key => $value) {
	    			$data = $this->CI->db->where($value);
		    	}
		    } else {
		        $data = $this->CI->db->where($this->_customQuery['where']);
		    }
		}
           
        return $data->get()->num_rows();
	}

	function _recordsFiltered()
	{
		$this->_getData();
		$data = $this->CI->db->get($this->_table);
		
		return $data->num_rows();
	}
}