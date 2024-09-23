<?php
class ModelCatalogCategory extends Model
{


	public function editCategory($category_id, $data)
	{
		$this->db->query("UPDATE " . DB_PREFIX . "category SET parent_id = '" . (int) $data['parent_id'] . "', `top` = '" . (isset($data['top']) ? (int) $data['top'] : 0) . "', `column` = '" . (int) $data['column'] . "', sort_order = '" . (int) $data['sort_order'] . "', status = '" . (int) $data['status'] . "', date_modified = NOW() WHERE category_id = '" . (int) $category_id . "'");

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "category SET image = '" . $this->db->escape($data['image']) . "' WHERE category_id = '" . (int) $category_id . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "category_description WHERE category_id = '" . (int) $category_id . "'");

		foreach ($data['category_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "category_description SET category_id = '" . (int) $category_id . "', language_id = '" . (int) $language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		// MySQL Hierarchical Data Closure Table Pattern
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE path_id = '" . (int) $category_id . "' ORDER BY level ASC");

		if ($query->rows) {
			foreach ($query->rows as $category_path) {
				// Delete the path below the current one
				$this->db->query("DELETE FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int) $category_path['category_id'] . "' AND level < '" . (int) $category_path['level'] . "'");

				$path = array();

				// Get the nodes new parents
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int) $data['parent_id'] . "' ORDER BY level ASC");

				foreach ($query->rows as $result) {
					$path[] = $result['path_id'];
				}

				// Get whats left of the nodes current path
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int) $category_path['category_id'] . "' ORDER BY level ASC");

				foreach ($query->rows as $result) {
					$path[] = $result['path_id'];
				}

				// Combine the paths with a new level
				$level = 0;

				foreach ($path as $path_id) {
					$this->db->query("REPLACE INTO `" . DB_PREFIX . "category_path` SET category_id = '" . (int) $category_path['category_id'] . "', `path_id` = '" . (int) $path_id . "', level = '" . (int) $level . "'");

					$level++;
				}
			}
		} else {
			// Delete the path below the current one
			$this->db->query("DELETE FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int) $category_id . "'");

			// Fix for records with no paths
			$level = 0;

			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int) $data['parent_id'] . "' ORDER BY level ASC");

			foreach ($query->rows as $result) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET category_id = '" . (int) $category_id . "', `path_id` = '" . (int) $result['path_id'] . "', level = '" . (int) $level . "'");

				$level++;
			}

			$this->db->query("REPLACE INTO `" . DB_PREFIX . "category_path` SET category_id = '" . (int) $category_id . "', `path_id` = '" . (int) $category_id . "', level = '" . (int) $level . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "category_filter WHERE category_id = '" . (int) $category_id . "'");

		if (isset($data['category_filter'])) {
			foreach ($data['category_filter'] as $filter_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "category_filter SET category_id = '" . (int) $category_id . "', filter_id = '" . (int) $filter_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "category_to_store WHERE category_id = '" . (int) $category_id . "'");

		if (isset($data['category_store'])) {
			foreach ($data['category_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "category_to_store SET category_id = '" . (int) $category_id . "', store_id = '" . (int) $store_id . "'");
			}
		}

		// SEO URL
		$this->db->query("DELETE FROM `" . DB_PREFIX . "seo_url` WHERE query = 'category_id=" . (int) $category_id . "'");

		if (isset($data['category_seo_url'])) {
			foreach ($data['category_seo_url'] as $store_id => $language) {
				foreach ($language as $language_id => $keyword) {
					if (!empty($keyword)) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int) $store_id . "', language_id = '" . (int) $language_id . "', query = 'category_id=" . (int) $category_id . "', keyword = '" . $this->db->escape($keyword) . "'");
					}
				}
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "category_to_layout WHERE category_id = '" . (int) $category_id . "'");

		if (isset($data['category_layout'])) {
			foreach ($data['category_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "category_to_layout SET category_id = '" . (int) $category_id . "', store_id = '" . (int) $store_id . "', layout_id = '" . (int) $layout_id . "'");
			}
		}

		//$this->cache->delete('category');
	}
	public function CategoryDescription($category_id)
	{
		$this->db->query("SELECT * FROM " . DB_PREFIX . "category_description WHERE category_id = '" . (int) $category_id . "'");
	}
	public function deleteCategory($category_id)
	{
		//echo "DELETE FROM " . DB_PREFIX . "category_path WHERE category_id = '" . (int)$category_id . "'";die;
		$this->db->query("DELETE FROM " . DB_PREFIX . "category_path WHERE category_id = '" . (int) $category_id . "'");

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_path WHERE path_id = '" . (int) $category_id . "'");

		foreach ($query->rows as $result) {
			$this->deleteCategory($result['category_id']);
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "category WHERE category_id = '" . (int) $category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "category_description WHERE category_id = '" . (int) $category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "category_filter WHERE category_id = '" . (int) $category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "category_to_store WHERE category_id = '" . (int) $category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "category_to_layout WHERE category_id = '" . (int) $category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE category_id = '" . (int) $category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'category_id=" . (int) $category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "coupon_category WHERE category_id = '" . (int) $category_id . "'");


	}



	public function addCategory($data)
	{
		$this->db->query("INSERT INTO " . DB_PREFIX . "category SET parent_id = '" . (int) $data['parent_id'] . "', `top` = '" . (isset($data['top']) ? (int) $data['top'] : 0) . "', `column` = '" . (int) $data['column'] . "', sort_order = '" . (int) $data['sort_order'] . "', status = '" . (int) $data['status'] . "', date_modified = NOW(), date_added = NOW()");

		$category_id = $this->db->getLastId();

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "category SET image = '" . $this->db->escape($data['image']) . "' WHERE category_id = '" . (int) $category_id . "'");
		}

		foreach ($data['category_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "category_description SET category_id = '" . (int) $category_id . "', language_id = '" . (int) $language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}


		$level = 0;

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int) $data['parent_id'] . "' ORDER BY `level` ASC");

		foreach ($query->rows as $result) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET `category_id` = '" . (int) $category_id . "', `path_id` = '" . (int) $result['path_id'] . "', `level` = '" . (int) $level . "'");

			$level++;
		}

		$this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET `category_id` = '" . (int) $category_id . "', `path_id` = '" . (int) $category_id . "', `level` = '" . (int) $level . "'");

		if (isset($data['category_filter'])) {
			foreach ($data['category_filter'] as $filter_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "category_filter SET category_id = '" . (int) $category_id . "', filter_id = '" . (int) $filter_id . "'");
			}
		}

		if (isset($data['category_store'])) {
			foreach ($data['category_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "category_to_store SET category_id = '" . (int) $category_id . "', store_id = '" . (int) $store_id . "'");
			}
		}

		if (isset($data['category_seo_url'])) {
			foreach ($data['category_seo_url'] as $store_id => $language) {
				foreach ($language as $language_id => $keyword) {
					if (!empty($keyword)) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int) $store_id . "', language_id = '" . (int) $language_id . "', query = 'category_id=" . (int) $category_id . "', keyword = '" . $this->db->escape($keyword) . "'");
					}
				}
			}
		}

		// Set which layout to use with this category
		if (isset($data['category_layout'])) {
			foreach ($data['category_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "category_to_layout SET category_id = '" . (int) $category_id . "', store_id = '" . (int) $store_id . "', layout_id = '" . (int) $layout_id . "'");
			}
		}

		$this->cache->delete('category');

		return $category_id;
	}


	public function getCategory($category_id, $lang_id = 1, $store_id = 0)
	{
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.category_id = '" . (int) $category_id . "' AND cd.language_id = '" . (int) $lang_id . "' AND c2s.store_id = '" . (int) $store_id . "' AND c.status = '1'");

		return $query->row;
	}

	public function getCategoryName($category_id)
	{

		$query = $this->db->query("SELECT name FROM " . DB_PREFIX . "category_description WHERE category_id = '" . (int) $category_id . "'");
		return $query->row;
	}

	public function getCategories($limit = 100, $page = 1, $parent_id = 0, $lang_id = 1, $store_id = 0, $status = 1)
	{
		if ($status == 2) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE (c.parent_id = '" . (int) $parent_id . "' OR c.category_id = '" . (int) $parent_id . "') AND cd.language_id = '" . (int) $lang_id . "' AND c2s.store_id = '" . (int) $store_id . "'   ORDER BY c.sort_order, LCASE(cd.name) limit " . (int) $page . " , " . (int) $limit . " ");
		} else {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE (c.parent_id = '" . (int) $parent_id . "' OR c.category_id = '" . (int) $parent_id . "') AND cd.language_id = '" . (int) $lang_id . "' AND c2s.store_id = '" . (int) $store_id . "' AND c.status = '" . (int) $status . "' ORDER BY c.sort_order, LCASE(cd.name) limit " . (int) $page . " , " . (int) $limit . " ");

		}
		return $query->rows;
	}

	public function getCategoryDescriptions($category_id)
	{
		$category_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_description WHERE category_id = '" . (int) $category_id . "'");

		foreach ($query->rows as $result) {
			$category_description_data[$result['language_id']] = array(
				'name' => $result['name'],
				'meta_title' => $result['meta_title'],
				'meta_description' => $result['meta_description'],
				'meta_keyword' => $result['meta_keyword'],
				'description' => $result['description']
			);
		}

		return $category_description_data;
	}

	public function getCategoryFilters($category_id)
	{
		$implode = array();

		$query = $this->db->query("SELECT filter_id FROM " . DB_PREFIX . "category_filter WHERE category_id = '" . (int) $category_id . "'");

		foreach ($query->rows as $result) {
			$implode[] = (int) $result['filter_id'];
		}

		$filter_group_data = array();

		if ($implode) {
			$filter_group_query = $this->db->query("SELECT DISTINCT f.filter_group_id, fgd.name, fg.sort_order FROM " . DB_PREFIX . "filter f LEFT JOIN " . DB_PREFIX . "filter_group fg ON (f.filter_group_id = fg.filter_group_id) LEFT JOIN " . DB_PREFIX . "filter_group_description fgd ON (fg.filter_group_id = fgd.filter_group_id) WHERE f.filter_id IN (" . implode(',', $implode) . ") AND fgd.language_id = '" . (int) $this->config->get('config_language_id') . "' GROUP BY f.filter_group_id ORDER BY fg.sort_order, LCASE(fgd.name)");

			foreach ($filter_group_query->rows as $filter_group) {
				$filter_data = array();

				$filter_query = $this->db->query("SELECT DISTINCT f.filter_id, fd.name FROM " . DB_PREFIX . "filter f LEFT JOIN " . DB_PREFIX . "filter_description fd ON (f.filter_id = fd.filter_id) WHERE f.filter_id IN (" . implode(',', $implode) . ") AND f.filter_group_id = '" . (int) $filter_group['filter_group_id'] . "' AND fd.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY f.sort_order, LCASE(fd.name)");

				foreach ($filter_query->rows as $filter) {
					$filter_data[] = array(
						'filter_id' => $filter['filter_id'],
						'name' => $filter['name']
					);
				}

				if ($filter_data) {
					$filter_group_data[] = array(
						'filter_group_id' => $filter_group['filter_group_id'],
						'name' => $filter_group['name'],
						'filter' => $filter_data
					);
				}
			}
		}

		return $filter_group_data;
	}

	public function getCategoryLayoutId($category_id)
	{
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_to_layout WHERE category_id = '" . (int) $category_id . "' AND store_id = '" . (int) $this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return (int) $query->row['layout_id'];
		} else {
			return 0;
		}
	}
	public function getTotalproductsByCategoryId($store_id, $category_id, $status = 1)
	{

		if ($status == 2) {

			$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id) LEFT JOIN " . DB_PREFIX . "category c ON c.category_id = p2c.category_id WHERE  p2s.store_id = '" . (int) $this->config->get('config_store_id') . "' AND c.category_id = '" . (int) $category_id . "' AND c.status = 1 ");
		} else {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id) LEFT JOIN " . DB_PREFIX . "category c ON c.category_id = p2c.category_id WHERE  p2s.store_id = '" . (int) $this->config->get('config_store_id') . "' AND c.category_id = '" . (int) $category_id . "' AND c.status ='" . (int) $status . "'");
		}
		return $query->row['total'];
	}
	public function getTotalCategoriesByCategoryId($parent_id = 0, $store_id = 0, $status = 1)
	{
		if ($status == 2) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.parent_id = '" . (int) $parent_id . "' AND c2s.store_id = '" . (int) $this->config->get('config_store_id') . "'");
		} else {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.parent_id = '" . (int) $parent_id . "' AND c2s.store_id = '" . (int) $this->config->get('config_store_id') . "' AND c.status ='" . (int) $status . "'");

		}
		return $query->row['total'];
	}
}