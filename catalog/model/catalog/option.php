<?php
class ModelCatalogOption extends Model
{

	private $module = array();
	public function addOption($data)
	{
		$this->db->query("INSERT INTO `" . DB_PREFIX . "option` SET type = '" . $this->db->escape($data['type']) . "', sort_order = '" . (int) $data['sort_order'] . "', store_id = '" . (int) $data['store_id'] . "'");

		$option_id = $this->db->getLastId();

		foreach ($data['option_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "option_description SET option_id = '" . (int) $option_id . "', language_id = '" . (int) $language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}

		if (isset($data['option_value'])) {
			foreach ($data['option_value'] as $option_value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "option_value SET option_id = '" . (int) $option_id . "', image = '" . $this->db->escape(html_entity_decode($option_value['image'], ENT_QUOTES, 'UTF-8')) . "', ptype = '" . (int) $data['ptype'] . "', sort_order = '" . (int) $option_value['sort_order'] . "'");

				$option_value_id = $this->db->getLastId();

				foreach ($option_value['option_value_description'] as $language_id => $option_value_description) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "option_value_description SET option_value_id = '" . (int) $option_value_id . "', language_id = '" . (int) $language_id . "', option_id = '" . (int) $option_id . "', name = '" . $this->db->escape($option_value_description['name']) . "'");
				}
			}
		}

		return $option_id;
	}

	public function editOption($option_id, $data)
	{

		$this->db->query("UPDATE `" . DB_PREFIX . "option` SET type = '" . $this->db->escape($data['type']) . "', sort_order = '" . (int) $data['sort_order'] . "' WHERE option_id = '" . (int) $option_id . "' AND store_id = '" . (int) $data['store_id'] . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "option_description WHERE option_id = '" . (int) $option_id . "'");

		foreach ($data['option_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "option_description SET option_id = '" . (int) $option_id . "', language_id = '" . (int) $language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "option_value WHERE option_id = '" . (int) $option_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "option_value_description WHERE option_id = '" . (int) $option_id . "'");

		if (isset($data['option_value'])) {
			foreach ($data['option_value'] as $option_value) {
				if ($option_value['option_value_id']) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "option_value SET option_value_id = '" . (int) $option_value['option_value_id'] . "', option_id = '" . (int) $option_id . "', image = '" . $this->db->escape(html_entity_decode($option_value['image'], ENT_QUOTES, 'UTF-8')) . "', ptype = '" . (int) $data['ptype'] . "', sort_order = '" . (int) $option_value['sort_order'] . "'");
				} else {
					$this->db->query("INSERT INTO " . DB_PREFIX . "option_value SET option_id = '" . (int) $option_id . "', image = '" . $this->db->escape(html_entity_decode($option_value['image'], ENT_QUOTES, 'UTF-8')) . "', ptype = '" . (int) $data['ptype'] . "', sort_order = '" . (int) $option_value['sort_order'] . "'");
				}

				$option_value_id = $this->db->getLastId();

				foreach ($option_value['option_value_description'] as $language_id => $option_value_description) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "option_value_description SET option_value_id = '" . (int) $option_value_id . "', language_id = '" . (int) $language_id . "', option_id = '" . (int) $option_id . "', name = '" . $this->db->escape($option_value_description['name']) . "'");
				}
			}

		}
		return $option_id;
	}

	public function deleteOption($option_id, $store_id)
	{
		$this->db->query("DELETE FROM `" . DB_PREFIX . "option` WHERE option_id = '" . (int) $option_id . "' AND store_id = '" . (int) $store_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "option_description WHERE option_id = '" . (int) $option_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "option_value WHERE option_id = '" . (int) $option_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "option_value_description WHERE option_id = '" . (int) $option_id . "'");
	}

	public function getOption($option_id)
	{
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "option` o LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE o.option_id = '" . (int) $option_id . "' AND od.language_id = '" . (int) $this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getOptions($data = array())
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "option` o LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE od.language_id = '" . (int) $this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND od.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sort_data = array(
			'od.name',
			'o.type',
			'o.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY od.name";
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

	public function getOptionDescriptions($option_id)
	{
		$option_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "option_description WHERE option_id = '" . (int) $option_id . "'");

		foreach ($query->rows as $result) {
			$option_data[$result['language_id']] = array('name' => $result['name']);
		}

		return $option_data;
	}

	public function getOptionValue($option_value_id)
	{
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "option_value ov LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE ov.option_value_id = '" . (int) $option_value_id . "' AND ovd.language_id = '" . (int) $this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getOptionValues($option_id)
	{
		$option_value_data = array();

		$option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "option_value ov LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE ov.option_id = '" . (int) $option_id . "' AND ovd.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY ov.sort_order, ovd.name");

		foreach ($option_value_query->rows as $option_value) {
			$option_value_data[] = array(
				'option_value_id' => $option_value['option_value_id'],
				'name' => $option_value['name'],
				'image' => $option_value['image'],
				'sort_order' => $option_value['sort_order'],
				'ptype' => $option_value['ptype']
			);
		}

		return $option_value_data;
	}

	public function getOptionValueDescriptions($option_id)
	{
		$option_value_data = array();

		$option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "option_value WHERE option_id = '" . (int) $option_id . "' ORDER BY sort_order");

		foreach ($option_value_query->rows as $option_value) {
			$option_value_description_data = array();

			$option_value_description_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "option_value_description WHERE option_value_id = '" . (int) $option_value['option_value_id'] . "'");

			foreach ($option_value_description_query->rows as $option_value_description) {
				$option_value_description_data[$option_value_description['language_id']] = array('name' => $option_value_description['name']);
			}

			$option_value_data[] = array(
				'option_value_id' => $option_value['option_value_id'],
				'option_value_description' => $option_value_description_data,
				'image' => $option_value['image'],
				'sort_order' => $option_value['sort_order']
			);
		}

		return $option_value_data;
	}

	public function getTotalOptions()
	{
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "option`");

		return $query->row['total'];
	}

	public function addPromotions($param)
	{
		// print_r($param);
		// die;
		// echo "INSERT INTO `" . DB_PREFIX . "promotions_item` SET " . $this->queryForm($param) . ", `date_added` = NOW()";
		// die;
		$this->db->query("INSERT INTO `" . DB_PREFIX . "promotions_item` SET " . $this->queryForm($param) . ", `date_added` = NOW()");

		return $this->db->getLastId();
	}

	public function editPromotions($promo_id, $param)
	{

		$this->db->query("UPDATE `" . DB_PREFIX . "promotions_item` SET " . $this->queryForm($param) . " WHERE promotion_id = '" . (int) $promo_id . "'");

		return $promo_id;
	}
	private function queryForm($param)
	{

		// Promo options: the nature of checkbox is void of value
		// $this->module['setting']['item']['limit_usage'] = 0;
		// $this->module['setting']['item']['apply_special'] = 0;
		// $this->module['setting']['item']['apply_coupon'] = 0;
		// $this->module['setting']['item']['stop'] = 0;
		// print_r($param);
		// die;
		// Default setting: fill in missing column
		// $ex = $this->module['setting']['item'];
		// print_r($param);
		// die;
		$param = array_merge($param);
		// $param = $just;
		// $param = json_decode($just);
		// print_r($param['condition_min_quantity	']);
		// die;
		return "
            `title`                        = '" . $this->db->escape(json_encode($param['title'])) . "',            `rule_group`                    = '" . $this->db->escape($param['rule_group']) . "',
            `rule_type`                     = '" . $this->db->escape($param['rule_type']) . "',
            `condition_min_quantity`        = '" . (int) $param['condition_min_quantity'] . "',
            `condition_min_quantities`      = '" . $this->db->escape(json_encode($param['condition_min_quantities'])) . "',
            `condition_min_amount`          = '" . (float) $param['condition_min_amount'] . "',
            `condition_min_amounts`         = '" . $this->db->escape(json_encode($param['condition_min_amounts'])) . "',
            `condition_min_orders`          = '" . (int) $param['condition_min_orders'] . "',
            `condition_product_ids`         = '" . $this->db->escape(json_encode($param['condition_product_ids'])) . "',
            `condition_category_ids`        = '" . $this->db->escape(json_encode($param['condition_category_ids'])) . "',
            `condition_manufacturer_ids`    = '" . $this->db->escape(json_encode($param['condition_manufacturer_ids'])) . "',
            `discount_quantity`             = '" . (int) $param['discount_quantity'] . "',
            `discount_product_ids`          = '" . $this->db->escape(json_encode($param['discount_product_ids'])) . "',
            `discount_category_ids`         = '" . $this->db->escape(json_encode($param['discount_category_ids'])) . "',
            `discount_manufacturer_ids`     = '" . $this->db->escape(json_encode($param['discount_manufacturer_ids'])) . "',
            `discount_value`                = '" . (float) $param['discount_value'] . "',
            `discount_values`               = '" . $this->db->escape(json_encode($param['discount_values'])) . "',
            `discount_type`                 = '" . $this->db->escape($param['discount_type']) . "',
            `discount_qualifier`            = '" . $this->db->escape($param['discount_qualifier']) . "',
            `zone_ids`                      = '" . $this->db->escape(json_encode($param['zone_ids'])) . "',
            `limit_usage`                   = '" . (int) $param['limit_usage'] . "',
            `limit_max_usage`               = '" . (int) $param['limit_max_usage'] . "',
            `exclude_categories`            = '" . (int) $param['exclude_categories'] . "',
            `excluded_category_ids`         = '" . $this->db->escape(json_encode($param['excluded_category_ids'])) . "',
            `apply_once`                    = '" . (int) $param['apply_once'] . "',
            `apply_special`                 = '" . (int) $param['apply_special'] . "',
            `coupon_code`                   = '" . $this->db->escape($param['coupon_code']) . "',
            `limit_customer_groups`         = '" . (int) $param['limit_customer_groups'] . "',
            `customer_group_ids`            = '" . $this->db->escape(json_encode($param['customer_group_ids'])) . "',
            `limit_customer_profile`        = '" . (int) $param['limit_customer_profile'] . "',
            `customer_ids`                  = '" . $this->db->escape(json_encode($param['customer_ids'])) . "',
            `stores`                        = '" . $this->db->escape(json_encode($param['stores'])) . "',
            `meta`                          = '" . $this->db->escape(json_encode($param['meta'])) . "',
            `priority`                      = '" . (int) $param['priority'] . "',
            `status`                        = '" . (int) $param['status'] . "',
            `design_status`                 = '" . (int) $param['design_status'] . "',
            `design_module_banner`          = '" . $this->db->escape($param['design_module_banner']) . "',
            `design_page_banner`            = '" . $this->db->escape($param['design_page_banner']) . "',
            `design_page_message`           = '" . $this->db->escape(json_encode($param['design_page_message'])) . "',
            `message_congrats`              = '" . $this->db->escape(json_encode($param['message_congrats'])) . "',
            `message_eligible`              = '" . $this->db->escape(json_encode($param['message_eligible'])) . "',
            `message_upsell`                = '" . $this->db->escape(json_encode($param['message_upsell'])) . "',
            `date_start`                    = '" . $this->db->escape($param['date_start']) . "',
            `date_end`                      = '" . $this->db->escape($param['date_end']) . "',
            `date_update`                   = NOW()
        ";
	}
}