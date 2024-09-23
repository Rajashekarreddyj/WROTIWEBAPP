<?php
class ModelCustomerCustomerGroup extends Model
{
	public function addCustomerGroup($data, $store_id)
	{

		$this->db->query("INSERT INTO " . DB_PREFIX . "customer_group SET approval = '" . (int) $data['approval'] . "', sort_order = '" . (int) $data['sort_order'] . "' , store_id = '" . (int) $store_id . "'");

		$customer_group_id = $this->db->getLastId();

		foreach ($data['customer_group_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "customer_group_description SET customer_group_id = '" . (int) $customer_group_id . "', language_id = '" . (int) $language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}

		return $customer_group_id;
	}
	public function AddCustomerToGroup($customer_id, $customer_group_id, $store_id)
	{


		try {
			$query = $this->db->query("INSERT INTO " . "oc_customer_to_customer_group SET customer_group_id = '" . (int) $customer_group_id . "' , customer_id = '" . (int) $customer_id . "' , store_id = '" . (int) $store_id . "'");
		} catch (Exception $query) {
			//exception handling code goes here
		}




		return $customer_group_id;
	}



	public function deleteCustomerFromGroup($customer_id, $customer_group_id, $store_id)
	{
		$query = $this->db->query("DELETE FROM " . "oc_customer_to_customer_group WHERE customer_group_id = '" . (int) $customer_group_id . "' AND customer_id = '" . (int) $customer_id . "'");

		return $customer_group_id;
	}

	public function editCustomerGroup($customer_group_id, $data)
	{
		$this->db->query("UPDATE " . DB_PREFIX . "customer_group SET approval = '" . (int) $data['approval'] . "', sort_order = '" . (int) $data['sort_order'] . "' WHERE customer_group_id = '" . (int) $customer_group_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_group_description WHERE customer_group_id = '" . (int) $customer_group_id . "'");

		foreach ($data['customer_group_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "customer_group_description SET customer_group_id = '" . (int) $customer_group_id . "', language_id = '" . (int) $language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}
	}

	public function deleteCustomersGroup($customer_group_id)
	{
		//echo "DELETE FROM " . "oc_customer_to_customer_group WHERE customer_group_id = '" . (int)$customer_group_id . "'";die;
		$this->db->query("DELETE FROM " . "oc_customer_to_customer_group WHERE customer_group_id = '" . (int) $customer_group_id . "'");
	}
	public function deleteCustomerGroup($customer_group_id)
	{
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_group WHERE customer_group_id = '" . (int) $customer_group_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_group_description WHERE customer_group_id = '" . (int) $customer_group_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_discount WHERE customer_group_id = '" . (int) $customer_group_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE customer_group_id = '" . (int) $customer_group_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_reward WHERE customer_group_id = '" . (int) $customer_group_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "tax_rate_to_customer_group WHERE customer_group_id = '" . (int) $customer_group_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_to_customer_group WHERE customer_group_id = '" . (int) $customer_group_id . "'");
		$this->db->query("UPDATE  " . DB_PREFIX . "customer set customer_group_id=1 WHERE customer_group_id = '" . (int) $customer_group_id . "'");


	}

	public function getCustomerGroup($customer_group_id)
	{
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer_group cg LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (cg.customer_group_id = cgd.customer_group_id) WHERE cg.customer_group_id = '" . (int) $customer_group_id . "' AND cgd.language_id = '" . (int) $this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getCustomerGroups($data)
	{

		$sql = "SELECT * FROM " . DB_PREFIX . "customer_group cg LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (cg.customer_group_id = cgd.customer_group_id) WHERE cgd.language_id = '" . (int) $this->config->get('config_language_id') . "'";

		$sort_data = array(
			'cgd.name',
			'cg.sort_order'
		);
		if (!empty($data['filter_name'])) {
			$sql .= " AND cgd.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}
		if (!empty($data['filter_store_id'])) {
			$sql .= " AND cg.store_id = '" . (int) $this->db->escape($data['filter_store_id']) . "'";
		}

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY cgd.name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
		}



		$query = $this->db->query($sql);


		return $query->rows;
	}

	public function getCustomerGroupDescriptions($customer_group_id)
	{
		$customer_group_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_group_description WHERE customer_group_id = '" . (int) $customer_group_id . "'");

		foreach ($query->rows as $result) {
			$customer_group_data[$result['language_id']] = array(
				'name' => $result['name'],
				'description' => $result['description']
			);
		}

		return $customer_group_data;
	}

	public function getTotalCustomerGroups($store_id)
	{
		$query = $this->db->query("SELECT COUNT(1) AS total FROM " . DB_PREFIX . "customer_group WHERE store_id = '" . (int) $store_id . "'");

		return $query->row['total'];
	}

	public function getCustomerInGroups($customer_id, $language_id)
	{
		$sql = "SELECT oc_customer_to_customer_group.customer_group_id, oc_customer_group_description.name FROM oc_customer_to_customer_group INNER JOIN oc_customer_group_description ON oc_customer_to_customer_group.customer_group_id=oc_customer_group_description.customer_group_id WHERE oc_customer_to_customer_group.customer_id = '" . (int) $customer_id . "' AND oc_customer_group_description.language_id = '" . (int) $language_id . "'";
		//echo $sql;die;
		$query = $this->db->query($sql);
		//$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer_to_customer_group cg LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (cg.customer_group_id = cgd.customer_group_id) WHERE cg.customer_group_id = '" . (int)$customer_group_id . "' AND cgd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		//$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer_group WHERE customer_id  IN (SELECT customer_id FROM oc_customer_to_customer_group WHERE customer_id = '" . (int)$customer_id . "')");
		return $query->rows;
	}
	public function updateCustomerFromGroup($customer_id, $customer_group_id, $store_id)
	{
		$query = $this->db->query("INSERT INTO " . "oc_customer_to_customer_group SET customer_group_id = '" . (int) $customer_group_id . "' , store_id = '" . (int) $store_id . "' , customer_id = '" . (int) $customer_id . "'");


		return $customer_group_id;
	}

}