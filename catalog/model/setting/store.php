<?php
class ModelSettingStore extends Model
{
	public function getStores($data = array())
	{
		// print_r($data);
		// die;
		$store_data = $this->cache->get('store');
		$sql = "SELECT name,store_id FROM " . DB_PREFIX . "store";


		if (!empty($data['filter_name'])) {
			$sql .= " WHERE name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}
		// echo $sql;
		// die;
		$query = $this->db->query($sql);

		$store_data = $query->rows;

		$this->cache->set('store', $store_data);


		return $store_data;
	}
	public function getStore($store_id)
	{
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "store WHERE store_id = '" . (int) $store_id . "'");

		return $query->row;
	}

	public function addStore($data)
	{
		// print_r($data);
		// die;

		// echo "INSERT INTO " . DB_PREFIX . "store SET name = '" . $data['config_name'] . "', `url` = '" . $data['config_url'] . "', `ssl` = '" . $data['config_ssl'] . "'";
		// die;
		$query = $this->db->query("INSERT INTO " . DB_PREFIX . "store SET name = '" . $data['config_name'] . "', `url` = '" . $data['config_url'] . "', `ssl` = '" . $data['config_ssl'] . "'");
		// print_r($data);
		// die;

		$store_id = $this->db->getLastId();

		// Layout Route
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "layout_route WHERE store_id = '" . (int) $store_id . "'");

		foreach ($query->rows as $layout_route) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "layout_route SET layout_id = '" . (int) $layout_route['layout_id'] . "', route = '" . $this->db->escape($layout_route['route']) . "', store_id = '" . (int) $store_id . "'");
		}

		$this->cache->delete('store');

		return $store_id;
	}
	public function editSetting($code, $data, $store_id = 0)
	{
		$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE store_id = '" . (int) $store_id . "' AND `code` = '" . $this->db->escape($code) . "'");

		foreach ($data as $key => $value) {
			if (substr($key, 0, strlen($code)) == $code) {
				if (!is_array($value)) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '" . (int) $store_id . "', `code` = '" . $this->db->escape($code) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape($value) . "'");
				} else {
					$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '" . (int) $store_id . "', `code` = '" . $this->db->escape($code) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape(json_encode($value, true)) . "', serialized = '1'");
				}
			}
		}
	}

	public function editStore($store_id, $data)
	{
		$this->db->query("UPDATE " . DB_PREFIX . "store SET name = '" . $this->db->escape($data['config_name']) . "', `url` = '" . $this->db->escape($data['config_url']) . "', `ssl` = '" . $this->db->escape($data['config_ssl']) . "' WHERE store_id = '" . (int) $store_id . "'");

		$this->cache->delete('store');
	}

	public function deleteStore($store_id)
	{
		$this->db->query("DELETE FROM " . DB_PREFIX . "store WHERE store_id = '" . (int) $store_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "layout_route WHERE store_id = '" . (int) $store_id . "'");

		$this->cache->delete('store');
	}
}