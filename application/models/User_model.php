<?php
class User_model extends CI_Model
{
  public function showAll()
  {
    $query = $this->db->get('users');
    if ($query->num_rows() > 0) {
      return $query->result();
    } else {
      return false;
    }
  }

  public function add_user($data)
  {
    return $this->db->insert('users', $data);
  }

  public function update_user($id, $field)
  {
    $this->db->where('id', $id);
    $this->db->update('users', $field);
    if ($this->db->affected_rows() > 0) {
      return true;
    } else {
      return false;
    }
  }
  public function delete_user($id)
  {
    $this->db->where('id', $id);
    $this->db->delete('users');
    if ($this->db->affected_rows() == 1) {
      return true;
    } else {
      return false;
    }
  }

  public function get_user_by_name($input)
  {
    $query = $this->db->get_where('users', array('name' => $input));

    return $query->row();
  }

  public function get_user_by_id($input)
  {
    $query = $this->db->get_where('users', array('id' => $input));

    return $query->row();
  }
}
