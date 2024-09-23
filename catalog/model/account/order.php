<?php
class ModelAccountOrder extends Model
{


	public function getOrderByDeliveryOrderId($delivery_order_id, $store_id)
	{
		$order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE custom_field LIKE '%" . $delivery_order_id . "%'");

		return $order_query->row;

	}

	public function getOrder($order_id)
	{
		$order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int) $order_id . "' AND customer_id = '" . (int) $this->customer->getId() . "' AND order_status_id > '0'");

		if ($order_query->num_rows) {
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int) $order_query->row['payment_country_id'] . "'");

			if ($country_query->num_rows) {
				$payment_iso_code_2 = $country_query->row['iso_code_2'];
				$payment_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$payment_iso_code_2 = '';
				$payment_iso_code_3 = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int) $order_query->row['payment_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$payment_zone_code = $zone_query->row['code'];
			} else {
				$payment_zone_code = '';
			}

			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int) $order_query->row['shipping_country_id'] . "'");

			if ($country_query->num_rows) {
				$shipping_iso_code_2 = $country_query->row['iso_code_2'];
				$shipping_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$shipping_iso_code_2 = '';
				$shipping_iso_code_3 = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int) $order_query->row['shipping_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$shipping_zone_code = $zone_query->row['code'];
			} else {
				$shipping_zone_code = '';
			}

			return array(
				'order_id' => $order_query->row['order_id'],
				'invoice_no' => $order_query->row['invoice_no'],
				'invoice_prefix' => $order_query->row['invoice_prefix'],
				'store_id' => $order_query->row['store_id'],
				'store_name' => $order_query->row['store_name'],
				'store_url' => $order_query->row['store_url'],
				'customer_id' => $order_query->row['customer_id'],
				'firstname' => $order_query->row['firstname'],
				'lastname' => $order_query->row['lastname'],
				'telephone' => $order_query->row['telephone'],
				'email' => $order_query->row['email'],
				'payment_firstname' => $order_query->row['payment_firstname'],
				'payment_lastname' => $order_query->row['payment_lastname'],
				'payment_company' => $order_query->row['payment_company'],
				'payment_address_1' => $order_query->row['payment_address_1'],
				'payment_address_2' => $order_query->row['payment_address_2'],
				'payment_postcode' => $order_query->row['payment_postcode'],
				'payment_city' => $order_query->row['payment_city'],
				'payment_zone_id' => $order_query->row['payment_zone_id'],
				'payment_zone' => $order_query->row['payment_zone'],
				'payment_zone_code' => $payment_zone_code,
				'payment_country_id' => $order_query->row['payment_country_id'],
				'payment_country' => $order_query->row['payment_country'],
				'payment_iso_code_2' => $payment_iso_code_2,
				'payment_iso_code_3' => $payment_iso_code_3,
				'payment_address_format' => $order_query->row['payment_address_format'],
				'payment_method' => $order_query->row['payment_method'],
				'shipping_firstname' => $order_query->row['shipping_firstname'],
				'shipping_lastname' => $order_query->row['shipping_lastname'],
				'shipping_company' => $order_query->row['shipping_company'],
				'shipping_address_1' => $order_query->row['shipping_address_1'],
				'shipping_address_2' => $order_query->row['shipping_address_2'],
				'shipping_postcode' => $order_query->row['shipping_postcode'],
				'shipping_city' => $order_query->row['shipping_city'],
				'shipping_zone_id' => $order_query->row['shipping_zone_id'],
				'shipping_zone' => $order_query->row['shipping_zone'],
				'shipping_zone_code' => $shipping_zone_code,
				'shipping_country_id' => $order_query->row['shipping_country_id'],
				'shipping_country' => $order_query->row['shipping_country'],
				'shipping_iso_code_2' => $shipping_iso_code_2,
				'shipping_iso_code_3' => $shipping_iso_code_3,
				'shipping_address_format' => $order_query->row['shipping_address_format'],
				'shipping_method' => $order_query->row['shipping_method'],
				'comment' => $order_query->row['comment'],
				'total' => $order_query->row['total'],
				'order_status_id' => $order_query->row['order_status_id'],
				'language_id' => $order_query->row['language_id'],
				'currency_id' => $order_query->row['currency_id'],
				'currency_code' => $order_query->row['currency_code'],
				'currency_value' => $order_query->row['currency_value'],
				'date_modified' => $order_query->row['date_modified'],
				'date_added' => $order_query->row['date_added'],
				'ip' => $order_query->row['ip']
			);
		} else {
			return false;
		}
	}

	public function getOrders($start = 0, $limit = 20)
	{
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 1;
		}

		$query = $this->db->query("SELECT o.order_id, o.firstname, o.lastname, os.name as status, o.date_added, o.total, o.currency_code, o.currency_value FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE o.customer_id = '" . (int) $this->customer->getId() . "' AND o.order_status_id > '0' AND o.store_id = '" . (int) $this->config->get('config_store_id') . "' AND os.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY o.order_id DESC LIMIT " . (int) $start . "," . (int) $limit);

		return $query->rows;
	}

	public function getAllOrders($start = 0, $limit = 20)
	{
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 1;
		}

		//	$query = $this->db->query("SELECT oc_order.*,oc_store.name FROM oc_order JOIN oc_store ON oc_order.store_id = oc_store.store_id");
		// echo "SELECT order_id, firstname ,total, date_added, shippingProvider, order_status_id,os.name as name FROM " . DB_PREFIX . "order o LEFT JOIN " . DB_PREFIX . "order_status os ON os.order_status_id = o.order_status_id";
		// die;
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order o LEFT JOIN " . DB_PREFIX . "order_status os ON os.order_status_id = o.order_status_id");

		// $query = $this->db->query("SELECT * from oc_order WHERE MONTH(date_added) = MONTH(CURRENT_DATE()) (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id)");
		return $query->rows;
	}

	public function getOrderProduct($order_id, $order_product_id)
	{
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "' AND order_product_id = '" . (int) $order_product_id . "'");

		return $query->row;
	}

	public function getOrderProducts($order_id)
	{
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'");

		return $query->rows;
	}

	public function getOrderOptions($order_id, $order_product_id)
	{
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int) $order_id . "' AND order_product_id = '" . (int) $order_product_id . "'");

		return $query->rows;
	}
	public function getProductOption($product_id)
	{
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int) $product_id . "'");

		return $query->row;
	}

	public function getOrderVouchers($order_id)
	{
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_voucher` WHERE order_id = '" . (int) $order_id . "'");

		return $query->rows;
	}

	public function getOrderTotals($order_id)
	{
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int) $order_id . "' ORDER BY sort_order");

		return $query->rows;
	}

	public function getOrderHistories($order_id)
	{
		$query = $this->db->query("SELECT date_added, os.name AS status, oh.comment, oh.notify FROM " . DB_PREFIX . "order_history oh LEFT JOIN " . DB_PREFIX . "order_status os ON oh.order_status_id = os.order_status_id WHERE oh.order_id = '" . (int) $order_id . "' AND os.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY oh.date_added");

		return $query->rows;
	}

	public function getTotalOrders()
	{
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` o WHERE customer_id = '" . (int) $this->customer->getId() . "' AND o.order_status_id > '0' AND o.store_id = '" . (int) $this->config->get('config_store_id') . "'");

		return $query->row['total'];
	}

	public function getTotalOrdersRevenue($store_id)
	{

		$query = $this->db->query("SELECT SUM(total) as total FROM oc_order WHERE order_status_id not in (7,1,11) AND  store_id = '" . (int) $store_id . "'");

		return $query->row['total'];
	}

	public function getTotalMonthRevenue($store_id)
	{


		$query = $this->db->query("SELECT sum(total) as total FROM oc_order WHERE MONTH(date_added) = MONTH(CURRENT_DATE()) AND order_status_id not in (7,1,11) AND store_id = '" . (int) $store_id . "'");

		return $query->row['total'];
	}

	public function getCurrentDateRevenue($store_id)
	{
		$query = $this->db->query("SELECT  SUM(total) as total FROM oc_order WHERE DATE(date_added) = CURDATE() AND order_status_id not in (7,1,11) AND store_id = '" . (int) $store_id . "'");
		return $query->row['total'];
	}



	public function getTotalOrderProductsByOrderId($order_id)
	{
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'");

		return $query->row['total'];
	}

	public function getOrdertotalByCustomerid($customer_id)
	{

		$query = $this->db->query("SELECT COUNT(1) AS total FROM " . DB_PREFIX . "order WHERE customer_id = '" . (int) $customer_id . "' AND order_status_id > 2");

		return $query->row['total'];
	}

	public function getOrderRevenueByCustomerid($customer_id)
	{

		$query = $this->db->query("SELECT SUM(total) AS total  FROM " . DB_PREFIX . "order WHERE customer_id = '" . (int) $customer_id . "' AND order_status_id > 2");

		return $query->row['total'];
	}

	public function getTotalOrderVouchersByOrderId($order_id)
	{
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order_voucher` WHERE order_id = '" . (int) $order_id . "'");

		return $query->row['total'];
	}

	public function getTotalOrdersSearch($data = array())
	{
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order`";


		if (!empty($data['filter_order_status'])) {
			$implode = array();

			$order_statuses = explode(',', $data['filter_order_status']);

			foreach ($order_statuses as $order_status_id) {
				$implode[] = "order_status_id = '" . (int) $order_status_id . "'";
			}

			if ($implode) {
				$sql .= " WHERE (" . implode(" OR ", $implode) . ")";
			}
		} elseif (isset($data['filter_order_status_id']) && $data['filter_order_status_id'] !== '') {
			$sql .= " WHERE order_status_id = '" . (int) $data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE order_status_id > '0'";
		}

		if (!empty($data['filter_order_id'])) {
			$sql .= " AND order_id = '" . (int) $data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_date_modified'])) {
			$sql .= " AND DATE(date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
		}

		if (!empty($data['filter_total'])) {
			$sql .= " AND total = '" . (float) $data['filter_total'] . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
	public function getOrderssearch($data = array())
	{
		$sql = "SELECT o.order_id, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS order_status, o.shipping_code, o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified FROM `" . DB_PREFIX . "order` o";

		if (!empty($data['filter_order_status'])) {
			$implode = array();

			$order_statuses = explode(',', $data['filter_order_status']);

			foreach ($order_statuses as $order_status_id) {
				$implode[] = "o.order_status_id = '" . (int) $order_status_id . "'";
			}

			if ($implode) {
				$sql .= " WHERE (" . implode(" OR ", $implode) . ")";
			}
		} elseif (isset($data['filter_order_status_id']) && $data['filter_order_status_id'] !== '') {
			$sql .= " WHERE o.order_status_id = '" . (int) $data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}

		if (!empty($data['filter_order_id'])) {
			$sql .= " AND o.order_id = '" . (int) $data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_date_modified'])) {
			$sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
		}

		if (!empty($data['filter_total'])) {
			$sql .= " AND o.total = '" . (float) $data['filter_total'] . "'";
		}

		$sort_data = array(
			'o.order_id',
			'customer',
			'order_status',
			'o.date_added',
			'o.date_modified',
			'o.total'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY o.order_id";
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
}
