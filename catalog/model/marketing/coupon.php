<?php
class ModelMarketingCoupon extends Model
{
	public function addCoupon($data, $store_id)
	{
		$this->db->query("INSERT INTO " . DB_PREFIX . "coupon SET name = '" . $this->db->escape($data['name']) . "', code = '" . $this->db->escape($data['code']) . "',  store_id = '" . (int) $store_id . "', discount = '" . (float) $data['discount'] . "', description = '" . $this->db->escape($data['description']) . "', type = '" . $this->db->escape($data['type']) . "', 	total = '" . (float) $data['total'] . "', logged = '" . (int) $data['logged'] . "', shipping = '" . (int) $data['shipping'] . "', date_start = '" . $this->db->escape($data['date_start']) . "', date_end = '" . $this->db->escape($data['date_end']) . "', uses_total = '" . (int) $data['uses_total'] . "', uses_customer = '" . (int) $data['uses_customer'] . "', status = '" . (int) $data['status'] . "', date_added = NOW() , coupon_cap = '" . (float) $data['coupon_cap'] . "'");

		$coupon_id = $this->db->getLastId();

		if (isset($data['coupon_product'])) {
			foreach ($data['coupon_product'] as $product_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "coupon_product SET coupon_id = '" . (int) $coupon_id . "', product_id = '" . (int) $product_id . "'");
			}
		}

		if (isset($data['coupon_category'])) {
			foreach ($data['coupon_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "coupon_category SET coupon_id = '" . (int) $coupon_id . "', category_id = '" . (int) $category_id . "'");
			}
		}
		if (!empty($data['filter_store_id'])) {
			$implode[] = "c.store_id = '" . (int) $this->db->escape($data['filter_store_id']) . "'";
		}


		return $coupon_id;
	}
	public function getCouponByCode($code, $store_id)
	{

		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "coupon WHERE code = '" . $this->db->escape($code) . "' AND store_id = '" . (int) $store_id . "'");

		return $query->row;
	}

	public function editCoupon($coupon_id, $data, $store_id)
	{
		$this->db->query("UPDATE " . DB_PREFIX . "coupon SET name = '" . $this->db->escape($data['name']) . "', code = '" . $this->db->escape($data['code']) . "', discount = '" . (float) $data['discount'] . "', description = '" . $this->db->escape($data['description']) . "', type = '" . $this->db->escape($data['type']) . "', total = '" . (float) $data['total'] . "', logged = '" . (int) $data['logged'] . "', shipping = '" . (int) $data['shipping'] . "', date_start = '" . $this->db->escape($data['date_start']) . "', date_end = '" . $this->db->escape($data['date_end']) . "', uses_total = '" . (int) $data['uses_total'] . "', uses_customer = '" . (int) $data['uses_customer'] . "', status = '" . (int) $data['status'] . "', coupon_cap = " . (float) $data['coupon_cap'] . " WHERE coupon_id = '" . (int) $coupon_id . "' AND store_id = '" . (int) $store_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "coupon_product WHERE coupon_id = '" . (int) $coupon_id . "'");

		if (isset($data['coupon_product'])) {
			foreach ($data['coupon_product'] as $product_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "coupon_product SET coupon_id = '" . (int) $coupon_id . "', product_id = '" . (int) $product_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "coupon_category WHERE coupon_id = '" . (int) $coupon_id . "'");

		if (isset($data['coupon_category'])) {
			foreach ($data['coupon_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "coupon_category SET coupon_id = '" . (int) $coupon_id . "', category_id = '" . (int) $category_id . "'");
			}
		}
	}

	public function deleteCoupon($coupon_id, $store_id)
	{
		$this->db->query("DELETE FROM " . DB_PREFIX . "coupon WHERE coupon_id = '" . (int) $coupon_id . "' AND store_id = '" . (int) $store_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "coupon_product WHERE coupon_id = '" . (int) $coupon_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "coupon_category WHERE coupon_id = '" . (int) $coupon_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "coupon_history WHERE coupon_id = '" . (int) $coupon_id . "'");
	}

	public function getCoupon($coupon_id)
	{
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "coupon WHERE coupon_id = '" . (int) $coupon_id . "'");

		return $query->row;
	}

	public function getCouponById($coupon_id, $store_id)
	{
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "coupon WHERE coupon_id = '" . $this->db->escape($coupon_id) . "' AND store_id = '" . (int) $store_id . "'");

		return $query->row;
	}

	public function getCoupons($data = array())
	{


		$sql = "SELECT coupon_id, store_id, name,type, code, discount,description, date_start, date_end, status FROM " . DB_PREFIX . "coupon WHERE store_id = '" . (int) $this->db->escape($data['filter_store_id']) . "'";

		$sort_data = array(
			'name',
			'code',
			'discount',
			'date_start',
			'date_end',
			'status'
		);

		if (!empty($data['filter_name'])) {

			$sql .= " AND code LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY name";
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

	public function getActivCoupons($data = array())
	{

		// echo "SELECT coupon_id, store_id, name,type, code, discount, date_start, date_end, status FROM " . DB_PREFIX . "coupon WHERE store_id = '" . (int) $this->db->escape($data['filter_store_id']) . "' AND status = '1' ";
		// die;
		$sql = "SELECT coupon_id, store_id, name,type, code, discount,description, date_start, date_end, status FROM " . DB_PREFIX . "coupon WHERE store_id = '" . (int) $this->db->escape($data['filter_store_id']) . "' AND status = '1' ";
		$sort_data = array(
			'name',
			'code',
			'discount',
			'date_start',
			'date_end',
			'status'
		);

		if (!empty($data['filter_name'])) {

			$sql .= " AND code LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY name";
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

	public function getCouponProducts($coupon_id)
	{
		$coupon_product_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "coupon_product WHERE coupon_id = '" . (int) $coupon_id . "'");

		foreach ($query->rows as $result) {
			$coupon_product_data[] = $result['product_id'];
		}

		return $coupon_product_data;
	}

	public function getCouponCategories($coupon_id)
	{
		$coupon_category_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "coupon_category WHERE coupon_id = '" . (int) $coupon_id . "'");

		foreach ($query->rows as $result) {
			$coupon_category_data[] = $result['category_id'];
		}

		return $coupon_category_data;
	}

	public function getTotalCoupons($data, $store_id)
	{


		$sql = "SELECT COUNT(1) AS total FROM " . DB_PREFIX . "coupon WHERE store_id = '" . (int) $store_id . "'";


		if (!empty($data['filter_name'])) {

			$sql .= " AND code LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}
		$query = $this->db->query($sql);


		return $query->row['total'];

	}

	public function getCouponHistories($coupon_id, $start = 0, $limit = 10)
	{
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$query = $this->db->query("SELECT ch.order_id, CONCAT(c.firstname, ' ', c.lastname) AS customer, ch.amount, ch.date_added FROM " . DB_PREFIX . "coupon_history ch LEFT JOIN " . DB_PREFIX . "customer c ON (ch.customer_id = c.customer_id) WHERE ch.coupon_id = '" . (int) $coupon_id . "' ORDER BY ch.date_added ASC LIMIT " . (int) $start . "," . (int) $limit);

		return $query->rows;
	}

	public function getTotalCouponHistories($coupon_id)
	{
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "coupon_history WHERE coupon_id = '" . (int) $coupon_id . "'");

		return $query->row['total'];
	}


	public function addrating($data, $store_id)
	{

		$this->db->query("INSERT INTO " . DB_PREFIX . "order_rating SET order_id = '" . (int) $data['order_id'] . "', customer_id = '" . (int) $data['customer_id'] . "',  store_id = '" . (int) $store_id . "', rating = '" . (float) $data['rating'] . "', delivery_rating = '" . (float) $data['delivery_rating'] . "', comments = '" . $this->db->escape($data['comments']) . "' , rating_type = '" . $this->db->escape($data['rating_type']) . "' ,  created_at = NOW() ");

		$coupon_id = $this->db->getLastId();




		return $coupon_id;
	}

	public function editRating($rating_id, $data)
	{

		$this->db->query("UPDATE " . DB_PREFIX . "order_rating SET order_id = '" . (int) $data['order_id'] . "', customer_id = '" . (int) $data['customer_id'] . "', rating = '" . (float) $data['rating'] . "',  delivery_rating = '" . (float) $data['delivery_rating'] . "', comments = '" . $this->db->escape($data['comments']) . "' , merchant_comment = '" . $this->db->escape($data['merchant_comment']) . "' , rating_type = '" . $this->db->escape($data['rating_type']) . "' , created_at = NOW(), date_modified = NOW() WHERE rating_id = '" . (int) $rating_id . "' AND store_id = '" . (int) $data['store_id'] . "'");

	}

	public function getRatings($data = array(), $rating)
	{


		if ($rating != "") {
			$sql = "SELECT oc_order.order_id, rating_id, rating, comments, merchant_comment, delivery_rating, oc_order_rating.date_modified, created_at ,firstname,telephone FROM " . DB_PREFIX . "order_rating  JOIN oc_order  ON oc_order_rating.order_id = oc_order.order_id AND rating = '" . (float) $rating['rating'] . "'";
		} else {

			$sql = "SELECT oc_order.order_id, rating_id, rating, comments, merchant_comment, delivery_rating, oc_order_rating.date_modified, created_at ,firstname,telephone FROM " . DB_PREFIX . "order_rating  JOIN oc_order  ON oc_order_rating.order_id = oc_order.order_id ";
		}
		if (!empty($data['filter_name'])) {
			$sql .= " WHERE (firstname LIKE '%" . $this->db->escape($data['filter_name']) . "%'  OR  telephone LIKE '%" . $this->db->escape($data['filter_name']) . "%') ";
		}


		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(oc_order.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(oc_order.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
		$sql .= " ORDER BY oc_order_rating.created_at   DESC";

		// if (isset($data['start']) || isset($data['limit'])) {
		// 	if ($data['start'] < 0) {
		// 		$data['start'] = 0;
		// 	}

		// 	if ($data['limit'] < 1) {
		// 		$data['limit'] = 20;
		// 	}

		// 	$sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
		// }

		$query = $this->db->query($sql);
		// echo $sql;
		// die;
		return $query->rows;
	}
	public function getRatings1($data = array(), $test, $rating)
	{
		// print_r($data);
		// die;

		$sql = "SELECT oc_order.order_id, rating, rating_id, comments, merchant_comment, delivery_rating, oc_order_rating.date_modified, created_at ,firstname,telephone FROM " . DB_PREFIX . "order_rating  JOIN oc_order  ON oc_order_rating.order_id = oc_order.order_id ";
		if (!empty($test['filter_name'])) {
			$sql .= " WHERE (firstname LIKE '%" . $this->db->escape($test['filter_name']) . "%'  OR  telephone LIKE '%" . $this->db->escape($test['filter_name']) . "%') ";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(oc_order.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";

		}


		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(oc_order.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";

		}
		// ORDER BY `order_product_id` DESC
		$sql .= " ORDER BY oc_order_rating.created_at  DESC";

		// if (isset($data['start']) || isset($data['limit'])) {
		// 	if ($data['start'] < 0) {
		// 		$data['start'] = 0;
		// 	}

		// 	if ($data['limit'] < 1) {
		// 		$data['limit'] = 20;
		// 	}

		// 	$sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
		// }

		// echo $sql;
		// die;
		$query = $this->db->query($sql);
		// echo $sql;
		// die;


		return $query->rows;
	}
	public function getOrderRatingCount($data = array(), $store_id, $test, $rating)
	{
		// print_r($data);
		// die;
		if ($rating != "") {
			$sql = "SELECT count(rating_id) AS total FROM " . DB_PREFIX . "order_rating JOIN oc_order  ON oc_order_rating.order_id = oc_order.order_id AND rating = '" . $rating . "'";
			if (!empty($test['filter_name'])) {
				$sql .= " AND (firstname LIKE '%" . $this->db->escape($test['filter_name']) . "%'  OR  telephone LIKE '%" . $this->db->escape($test['filter_name']) . "%') ";
			}
		} else {
			$sql = "SELECT count(rating_id) AS total FROM " . DB_PREFIX . "order_rating JOIN oc_order  ON oc_order_rating.order_id = oc_order.order_id  WHERE oc_order.store_id = '" . (int) $store_id . "'";
			if (!empty($test['filter_name'])) {
				$sql .= " AND (firstname LIKE '%" . $this->db->escape($test['filter_name']) . "%'  OR  telephone LIKE '%" . $this->db->escape($test['filter_name']) . "%') ";
			}
		}
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(oc_order.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}
		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(oc_order.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}


		$query = $this->db->query($sql);
		// echo $sql;
		// die;
		return $query->rows;
	}
	public function getOrderRatingCount1($data = array(), $store_id, $rating, $test)
	{

		if ($rating != "") {
			$sql = "SELECT count(rating_id) AS reviews FROM " . DB_PREFIX . "order_rating JOIN oc_order  ON oc_order_rating.order_id = oc_order.order_id AND rating = '" . $rating . "'";
		} else {
			$sql = "SELECT count(rating_id) AS reviews FROM " . DB_PREFIX . "order_rating  JOIN oc_order  ON oc_order_rating.order_id = oc_order.order_id  WHERE oc_order.store_id = '" . (int) $store_id . "'";
			if (!empty($test['filter_name'])) {
				$sql .= " AND (firstname LIKE '%" . $this->db->escape($test['filter_name']) . "%'  OR  telephone LIKE '%" . $this->db->escape($test['filter_name']) . "%') ";
			}
		}

		$query = $this->db->query($sql);

		// echo $sql;
		// die;
		return $query->rows;
	}
	public function getDeliveryRatingCount($data = array(), $store_id, $rating, $test)
	{


		if ($rating != "") {

			$sql = "SELECT count(delivery_rating) AS total FROM " . DB_PREFIX . "order_rating JOIN oc_order  ON oc_order_rating.order_id = oc_order.order_id AND rating = '" . $data['rating'] . "'";
			if (!empty($test['filter_name'])) {
				$sql .= " AND (firstname LIKE '%" . $this->db->escape($test['filter_name']) . "%'  OR  telephone LIKE '%" . $this->db->escape($test['filter_name']) . "%') ";
			}
		} else {

			$sql = "SELECT count(delivery_rating) AS total FROM " . DB_PREFIX . "order_rating JOIN oc_order  ON oc_order_rating.order_id = oc_order.order_id  WHERE oc_order.store_id = '" . (int) $store_id . "'";
			if (!empty($test['filter_name'])) {
				$sql .= " AND (firstname LIKE '%" . $this->db->escape($test['filter_name']) . "%'  OR  telephone LIKE '%" . $this->db->escape($test['filter_name']) . "%') ";
			}
		}
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(oc_order.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}
		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(oc_order.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}


		$query = $this->db->query($sql);

		return $query->rows;

	}

	public function getDeliveryRatingCount1($data = array(), $store_id, $rating, $test)
	{
		// print_r($data);
		// die;
		if ($rating != "") {
			$sql = "SELECT count(delivery_rating) AS reviews FROM " . DB_PREFIX . "order_rating JOIN oc_order  ON oc_order_rating.order_id = oc_order.order_id AND rating = '" . $rating . "'";
		} else {
			$sql = "SELECT count(delivery_rating) AS reviews FROM " . DB_PREFIX . "order_rating  JOIN oc_order  ON oc_order_rating.order_id = oc_order.order_id  WHERE oc_order.store_id = '" . (int) $store_id . "'";
			if (!empty($test['filter_name'])) {
				$sql .= " AND (firstname LIKE '%" . $this->db->escape($test['filter_name']) . "%'  OR  telephone LIKE '%" . $this->db->escape($test['filter_name']) . "%') ";
			}
		}

		$query = $this->db->query($sql);

		// echo $sql;
		// die;
		return $query->rows;
	}

	public function getOrderAvgRating($data = array(), $store_id, $test, $rating)
	{

		if ($rating != "") {
			$sql = "SELECT AVG(rating) AS rating FROM " . DB_PREFIX . "order_rating  JOIN oc_order  ON oc_order_rating.order_id = oc_order.order_id  AND rating = '" . $rating . "'";
			if (!empty($test['filter_name'])) {
				$sql .= " AND (firstname LIKE '%" . $this->db->escape($test['filter_name']) . "%'  OR  telephone LIKE '%" . $this->db->escape($test['filter_name']) . "%') ";
			}
		} else {
			$sql = "SELECT AVG(rating) AS rating FROM " . DB_PREFIX . "order_rating  JOIN oc_order  ON oc_order_rating.order_id = oc_order.order_id  WHERE oc_order.store_id = '" . (int) $store_id . "'";
			if (!empty($test['filter_name'])) {
				$sql .= " AND (firstname LIKE '%" . $this->db->escape($test['filter_name']) . "%'  OR  telephone LIKE '%" . $this->db->escape($test['filter_name']) . "%') ";
			}
		}
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(oc_order.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}
		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(oc_order.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
		$query = $this->db->query($sql);

		// echo $sql;
		// die;
		return $query->rows;
	}
	public function getOrderAvgRating1($data = array(), $store_id, $rating, $test)
	{

		if ($rating != "") {
			$sql = "SELECT AVG(rating) AS rating FROM " . DB_PREFIX . "order_rating JOIN oc_order  ON oc_order_rating.order_id = oc_order.order_id AND rating = '" . $data['rating'] . "'";
		} else {
			$sql = "SELECT AVG(rating) AS rating FROM " . DB_PREFIX . "order_rating JOIN oc_order  ON oc_order_rating.order_id = oc_order.order_id  WHERE oc_order.store_id = '" . (int) $store_id . "'";
			if (!empty($test['filter_name'])) {
				$sql .= " AND (firstname LIKE '%" . $this->db->escape($test['filter_name']) . "%'  OR  telephone LIKE '%" . $this->db->escape($test['filter_name']) . "%') ";
			}
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(oc_order.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}
		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(oc_order.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}


		$query = $this->db->query($sql);
		// echo $sql;
		// die;

		return $query->rows;
	}
	public function getDeliveryAvgRating($data = array(), $store_id, $rating, $test)
	{

		if ($rating != "") {
			$sql = "SELECT AVG(delivery_rating) AS rating FROM " . DB_PREFIX . "order_rating  JOIN oc_order  ON oc_order_rating.order_id = oc_order.order_id  AND rating = '" . $rating . "'";
			if (!empty($test['filter_name'])) {
				$sql .= " AND (firstname LIKE '%" . $this->db->escape($test['filter_name']) . "%'  OR  telephone LIKE '%" . $this->db->escape($test['filter_name']) . "%') ";
			}
		} else {
			$sql = "SELECT AVG(delivery_rating) AS rating FROM " . DB_PREFIX . "order_rating  JOIN oc_order  ON oc_order_rating.order_id = oc_order.order_id  WHERE oc_order.store_id = '" . (int) $store_id . "'";
			if (!empty($test['filter_name'])) {
				$sql .= " AND (firstname LIKE '%" . $this->db->escape($test['filter_name']) . "%'  OR  telephone LIKE '%" . $this->db->escape($test['filter_name']) . "%') ";
			}
		}
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(oc_order.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}
		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(oc_order.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
		$query = $this->db->query($sql);

		// echo $sql;
		// die;
		return $query->rows;
	}
	public function getDeliveryAvgRating1($data = array(), $store_id, $rating, $test)
	{

		if ($rating != "") {
			$sql = "SELECT AVG(delivery_rating) AS rating FROM " . DB_PREFIX . "order_rating JOIN oc_order  ON oc_order_rating.order_id = oc_order.order_id AND rating = '" . $data['rating'] . "'";
		} else {
			$sql = "SELECT AVG(delivery_rating) AS rating FROM " . DB_PREFIX . "order_rating JOIN oc_order  ON oc_order_rating.order_id = oc_order.order_id  WHERE oc_order.store_id = '" . (int) $store_id . "'";
			if (!empty($test['filter_name'])) {
				$sql .= " AND (firstname LIKE '%" . $this->db->escape($test['filter_name']) . "%'  OR  telephone LIKE '%" . $this->db->escape($test['filter_name']) . "%') ";
			}
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(oc_order.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}
		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(oc_order.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}


		$query = $this->db->query($sql);
		// echo $sql;
		// die;

		return $query->rows;
	}
	public function updateOrderRatingComments($rating_id, $comments)
	{
		$query = $this->db->query("UPDATE  `oc_order_rating` SET `comments` = '" . $this->db->escape($comments) . "' WHERE `rating_id` = '" . (int) $rating_id . "'");

		return $query;
	}
	public function updateMerchantRatingComments($rating_id, $merchant_comment)
	{


		$query = $this->db->query("UPDATE  `oc_order_rating` SET `merchant_comment` = '" . $this->db->escape($merchant_comment) . "' WHERE `rating_id` = '" . (int) $rating_id . "'");

		return $query;
	}
	public function updateDeliveryRating($rating_id, $delivery_rating)
	{


		$query = $this->db->query("UPDATE  `oc_order_rating` SET `delivery_rating` = '" . $this->db->escape($delivery_rating) . "' WHERE `rating_id` = '" . (int) $rating_id . "'");

		return $query;
	}


	public function getRatingsTotal5($store_id, $data)
	{
		// print_r($data);
		// die;
		$sql = "SELECT count(rating_id) AS total FROM " . DB_PREFIX . "order_rating WHERE store_id = '" . (int) $store_id . "' AND rating = 5 ";

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(oc_order_rating.created_at) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}
		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(oc_order_rating.created_at) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
		// echo $sql;
		// die;
		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getRatingsTotal4($store_id, $data)
	{
		$sql = "SELECT count(rating_id) AS total FROM " . DB_PREFIX . "order_rating WHERE store_id = '" . (int) $store_id . "' AND rating = 4 ";

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(oc_order_rating.created_at) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}
		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(oc_order_rating.created_at) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
		$query = $this->db->query($sql);

		return $query->rows;
	}
	public function getRatingsTotal3($store_id, $data)
	{
		$sql = "SELECT count(rating_id) AS total FROM " . DB_PREFIX . "order_rating WHERE store_id = '" . (int) $store_id . "' AND rating = 3 ";


		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(oc_order_rating.created_at) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}
		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(oc_order_rating.created_at) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
		$query = $this->db->query($sql);


		return $query->rows;
	}
	public function getRatingsTotal2($store_id, $data)
	{
		$sql = "SELECT count(rating_id) AS total FROM " . DB_PREFIX . "order_rating WHERE store_id = '" . (int) $store_id . "' AND rating = 2 ";


		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(oc_order_rating.created_at) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}
		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(oc_order_rating.created_at) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
		$query = $this->db->query($sql);


		return $query->rows;
	}
	public function getRatingsTotal1($store_id, $data)
	{
		// print_r($data);
		// die;
		$sql = "SELECT count(rating_id) AS total FROM " . DB_PREFIX . "order_rating WHERE store_id = '" . (int) $store_id . "' AND rating = 1 ";


		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(oc_order_rating.created_at) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}
		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(oc_order_rating.created_at) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
		// echo $sql;
		// die;
		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getOrderRatingById($store_id, $rating_id)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "order_rating WHERE rating_id = '" . (int) $rating_id . "' AND `store_id` = '" . (int) $store_id . "'";

		$query = $this->db->query($sql);

		return $query->rows;
	}
}