<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main_model extends CI_Model
{
	function get_byId($table, $id)
	{
		$data = $this->db->get_where($table, ['id' => $id]);
		return ($data->num_rows() > 0) ? $data->row() : false;
	} 

	function get_rows($table, $data = [])
	{
		$this->db->select(isset($data['select']) ? $data['select'] : '*');
		if (isset($data['join'])) {
            if(isset($data['join'][0])) {
    			foreach($data['join'] as $key => $value) {
	    			$this->db->join($value['table'], $value['on'], $value['param']);
                }
            } else {
                $this->db->join($data['join']['table'], $data['join']['on'], $data['join']['param']);
			}
		}
		if (isset($data['where'])) {
		    if(is_array($data['where']) && isset($data['where'][0])) {
    			foreach ($data['where'] as $key => $value)
	    			$this->db->where($value);
                    
		    } else {
		        $this->db->where($data['where']);
		    }
		}

		if (isset($data['group_by']))
		    $this->db->group_by($data['group_by']);
            
		if (isset($data['order_by']))
		    $this->db->order_by(
                $data['order_by'][0],
                (isset($data['order_by'][1]) ? $data['order_by'][1] : '')
            );

		if (isset($data['limit']))
    		$this->db->limit(
                $data['limit'][0],
                (isset($data['limit'][1]) ? $data['limit'][1] : '')
            );

		if (isset($data['offset']))
		    $this->db->offset($data['offset']);
	
		return $this->db->get(isset($data['from']) ? $data['from'] : $table)->result_array();
	}

	function get_row($table, $select = '*', $where = false)
	{
	    $this->db->select($select);
	    if(!is_bool($where))
	        $this->db->where($where);
	    
		$data = $this->db->get($table);
		return ($data->num_rows() > 0) ? $data->row() : false;
	}

	function count_rows($table, $data = [])
	{
		$this->db->select((isset($data['select'])) ? $data['select'] : '*');
		if (isset($data['join'][0])) {
			foreach ($data['join'] as $key => $value) {
				$this->db->join($value['table'], $value['on'], $value['param']);
			}
		}
		if (isset($data['where'])) {
		    if(isset($data['where'][0])) {
    			foreach ($data['where'] as $key => $value) {
	    			$this->db->where($value);
		    	}
		    } else {
		        $this->db->where($data['where']);
		    }
		}
		if (isset($data['group_by'])) $this->db->group_by($data['group_by']);
		return $this->db->get((isset($data['from'])) ? $data['from'] : $table)->num_rows();
	}

	function insert($table, $data)
	{
		$this->db->set($data);
		$exec = $this->db->insert($table);
		return ($exec !== FALSE) ? $this->db->insert_id() : FALSE;
	}

	function insert_batch($table, $data)
	{
		return $this->db->insert_batch($table, $data);
	}

	function update($table, $data, $where = [])
	{
		$this->db->set($data);
		$this->db->where($where);
		$exec = $this->db->update($table);
		return ($exec !== FALSE) ? $this->db->affected_rows() : FALSE;
	}

	function update_batch($table, $data, $where = NULL)
	{	
		return $this->db->update_batch($table, $data, $where);
	}

    function get_error()
    {
        return (object) $this->db->error();
    }
	
	function json($table, $action, $data, $where = [])
    {
        switch($action) {
            case 'set':
                foreach($data as $column => $values) {
                    $array_set = [];
                    foreach($values as $key => $value) {
                        $value = filter($value);
                        array_push($array_set, "'$.{$key}', '{$value}'");
                    }

                    $this->db->set($column, "JSON_SET({$column}, " .implode(', ', $array_set). ")", FALSE);
                }

                return $this->db->where($where)->update($table);
                break;
            case 'remove':
                foreach($data as $column => $values) {
                    if(is_array($values)) {
                        foreach($values as $key => $value)
                            $this->db->set($column, "JSON_REMOVE({$column}, '$.{$value}')", FALSE);

                    } else {
                        $this->db->set($column, "JSON_REMOVE({$column}, '$.{$values}')", FALSE);
                    } 
                }

                return $this->db->where($where)->update($table);
                break;

            default:
                return FALSE;
        }
    }
	
	function delete($table, $where)
	{
		$this->db->where($where);
		$exec = $this->db->delete($table);
		return ($exec !== FALSE) ? $this->db->affected_rows() : FALSE;
	}

    function delete_batch($table, $id)
    {
        $this->db->where_in('id', $id);
        $exec = $this->db->delete($table);
		return ($exec !== FALSE) ? $this->db->affected_rows() : FALSE;
    }

	function truncate($table)
	{
		return $this->db->truncate($table);
	}
	
	function next_ai($table)
	{
	    return ($this->db->select('id')->from($table)
	        ->count_all_results() + 1);
	}
}