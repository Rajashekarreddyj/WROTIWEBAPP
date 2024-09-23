<?php
class ModelExtensionModulePromotions extends Model
{
    private $module = array();

    public function __construct($registry)
    {
        parent::__construct($registry);

        $this->config->load('isenselabs/promotions');
        $this->module = $this->config->get('promotions');

        if (isset($this->request->post['store_id'])) {
            $this->module['store_id'] = (int) $this->request->post['store_id'];
        } elseif (isset($this->request->get['store_id'])) {
            $this->module['store_id'] = (int) $this->request->get['store_id'];
        }
    }

    public function getItem($promo_id)
    {
        $item = $this->db->query("SELECT * FROM `" . DB_PREFIX . "promotions_item` WHERE promotion_id = " . (int) $promo_id);

        return $this->prepareItem($item->row, false);
    }

    public function getItems($param)
    {
        $items = array();

        $results = $this->db->query(
            "SELECT pi.*, IF (pi.priority > 0, pi.priority, 99999) as priority_order, (SELECT COUNT(*) FROM `" . DB_PREFIX . "promotions_log` pl WHERE pl.promotion_id = pi.promotion_id) AS times_used
            FROM `" . DB_PREFIX . "promotions_item` pi
            WHERE stores LIKE '%\"" . $this->module['store_id'] . "\"%'
            ORDER BY priority_order ASC
            LIMIT " . (int) $param['start'] . "," . (int) $param['limit']
        );

        foreach ($results->rows as $key => $value) {
            $items[$key] = $this->prepareItem($value);
        }

        return $items;
    }

    protected function prepareItem($itemValues, $specificLang = true)
    {
        $item = $itemValues;
        $lang_id = $this->config->get('config_language_id');
        $titles = array('title', 'design_page_message', 'message_congrats', 'message_eligible', 'message_upsell');
        $decodes = array('condition_min_quantities', 'condition_min_amounts', 'condition_product_ids', 'condition_category_ids', 'condition_manufacturer_ids', 'discount_product_ids',
            'discount_category_ids', 'discount_manufacturer_ids', 'discount_values', 'zone_ids', 'excluded_category_ids', 'customer_group_ids', 'customer_ids', 'stores', 'meta');

        foreach ($itemValues as $key => $value) {
            $temp_val = json_decode($value, true);

            if (!is_array($temp_val)) {
                continue;
            }

            if (in_array($key, $titles) || in_array($key, $decodes)) {
                $item[$key] = $temp_val;

                if (!empty($temp_val) && $specificLang && in_array($key, $titles)) {
                    $item[$key] = isset($temp_val[$lang_id]) ? $temp_val[$lang_id] : $temp_val[array_rand($temp_val)];
                }
            }
        }

        if ($item) {
            $item['date_start'] = $item['date_start'] != '0000-00-00 00:00:00' ? $item['date_start'] : '';
            $item['date_end'] = $item['date_end'] != '0000-00-00 00:00:00' ? $item['date_end'] : '';

            $item['duration_start'] = $item['date_start'] ? date('M d, Y', strtotime($item['date_start'])) : '';
            $item['duration_end'] = $item['date_end'] ? date('M d, Y', strtotime($item['date_end'])) : '';
        }

        return $item;
    }

    public function getTotalItems()
    {
        $results = $this->db->query("SELECT COUNT(DISTINCT promotion_id) AS total FROM `" . DB_PREFIX . "promotions_item`");

        return $results->row['total'];
    }

    public function insert($param)
    {
        $this->db->query("INSERT INTO `" . DB_PREFIX . "promotions_item` SET " . $this->queryForm($param) . ", `date_added` = NOW()");

        return $this->db->getLastId();
    }

    public function update($promo_id, $param)
    {
        $this->db->query("UPDATE `" . DB_PREFIX . "promotions_item` SET " . $this->queryForm($param) . " WHERE promotion_id = '" . (int) $promo_id . "'");

        return $promo_id;
    }

    /**
     * Provide standarize insert and update query
     */
    private function queryForm($param)
    {
        // Promo options: the nature of checkbox is void of value
        $this->module['setting']['item']['limit_usage'] = 0;
        $this->module['setting']['item']['apply_special'] = 0;
        $this->module['setting']['item']['apply_coupon'] = 0;
        $this->module['setting']['item']['stop'] = 0;

        // Default setting: fill in missing column
        $param = array_merge($this->module['setting']['item'], $param);

        return "
            `title`                         = '" . $this->db->escape(json_encode($param['title'])) . "',
            `rule_group`                    = '" . $this->db->escape($param['rule_group']) . "',
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
            `apply_coupon`                  = '" . (int) $param['apply_coupon'] . "',
            `coupon_code`                   = '" . $this->db->escape($param['coupon_code']) . "',
            `limit_customer_groups`         = '" . (int) $param['limit_customer_groups'] . "',
            `customer_group_ids`            = '" . $this->db->escape(json_encode($param['customer_group_ids'])) . "',
            `limit_customer_profile`        = '" . (int) $param['limit_customer_profile'] . "',
            `customer_ids`                  = '" . $this->db->escape(json_encode($param['customer_ids'])) . "',
            `stores`                        = '" . $this->db->escape(json_encode($param['stores'])) . "',
            `stop`                          = '" . (int) $param['stop'] . "',
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

    public function getProductsInId($ids)
    {

        $results = $this->db->query("SELECT product_id, name FROM `" . DB_PREFIX . "product_description` WHERE product_id IN (" . implode(',', $ids) . ") AND language_id = " . (int) $this->config->get('config_language_id'));

        return $results->rows;
    }

    public function getCategoriesInId($ids)
    {
        $results = $this->db->query("SELECT cd.category_id, cd.name,
            (SELECT GROUP_CONCAT(cd1.name ORDER BY level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (cp.path_id = cd1.category_id AND cp.category_id != cp.path_id) WHERE cp.category_id = cd.category_id AND cd1.language_id = '" . (int) $this->config->get('config_language_id') . "' GROUP BY cp.category_id) AS path
            FROM `" . DB_PREFIX . "category_description` cd WHERE cd.category_id IN (" . implode(',', $ids) . ") AND cd.language_id = " . (int) $this->config->get('config_language_id'));

        return $results->rows;
    }

    public function getManufacturerInId($ids)
    {
        $results = $this->db->query("SELECT manufacturer_id, name FROM `" . DB_PREFIX . "manufacturer` WHERE manufacturer_id IN (" . implode(',', $ids) . ")");

        return $results->rows;
    }

    public function getCustomersInId($ids)
    {
        // $ids = 123;
        // echo "SELECT customer_id, concat(firstname, ' ', lastname) as name FROM `" . DB_PREFIX . "customer` WHERE customer_id IN ($ids)";
        // die;
        $results = $this->db->query("SELECT customer_id, concat(firstname, ' ', lastname) as name FROM `" . DB_PREFIX . "customer` WHERE customer_id IN ($ids)");

        return $results->rows;
    }

    // ================================================================

    public function install($drop = false)
    {
        if ($drop) {
            $this->uninstall();
        }

        if (!$this->checkTable('promotions_item')) {
            $this->db->query(
                "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "promotions_item` (
                    `promotion_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                    `title` TEXT NOT NULL,
                    `rule_group` VARCHAR(32) NOT NULL DEFAULT '0',
                    `rule_type` VARCHAR(255) NOT NULL DEFAULT '0',
                    `condition_min_quantity` INT(11) NOT NULL DEFAULT '0',
                    `condition_min_quantities` TEXT NOT NULL COMMENT 'json qty, pair with discount_values',
                    `condition_min_amount` DECIMAL(15,4) NOT NULL DEFAULT '0.0000',
                    `condition_min_amounts` TEXT NOT NULL COMMENT 'pair with discount_values',
                    `condition_min_orders` INT(11) NOT NULL,
                    `condition_product_ids` TEXT NOT NULL COMMENT 'json string id',
                    `condition_category_ids` TEXT NOT NULL COMMENT 'json string id',
                    `condition_manufacturer_ids` TEXT NOT NULL COMMENT 'json string id',
                    `discount_quantity` INT(11) NOT NULL DEFAULT '0',
                    `discount_product_ids` TEXT NOT NULL COMMENT 'json string id',
                    `discount_category_ids` TEXT NOT NULL COMMENT 'json string id',
                    `discount_manufacturer_ids` TEXT NOT NULL COMMENT 'json string id',
                    `discount_value` DECIMAL(15,4) NOT NULL DEFAULT '0.0000',
                    `discount_values` TEXT NOT NULL COMMENT 'json value',
                    `discount_type` VARCHAR(32) NOT NULL DEFAULT '0' COMMENT 'fixed, percentage',
                    `discount_qualifier` VARCHAR(32) NOT NULL DEFAULT '0' COMMENT 'least, most',
                    `zone_ids` TEXT NOT NULL COMMENT 'json string id',
                    `limit_usage` TINYINT(1) NOT NULL,
                    `limit_max_usage` INT(11) NOT NULL DEFAULT '0',
                    `exclude_categories` TINYINT(1) NOT NULL,
                    `excluded_category_ids` TEXT NOT NULL COMMENT 'json of string id',
                    `apply_once` TINYINT(1) NOT NULL DEFAULT '0',
                    `apply_special` TINYINT(1) NOT NULL DEFAULT '0',
                    `apply_coupon` TINYINT(1) NOT NULL DEFAULT '0',
                    `coupon_code` VARCHAR(36) NOT NULL,
                    `limit_customer_groups` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'apply to specific group',
                    `customer_group_ids` TEXT NOT NULL COMMENT 'json string id',
                    `limit_customer_profile` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'apply to specific customer',
                    `customer_ids` TEXT NOT NULL COMMENT 'json string id',
                    `stores` TEXT NOT NULL COMMENT 'json of string id',
                    `stop` TINYINT(1) NOT NULL DEFAULT '0',
                    `meta` TEXT NOT NULL COMMENT 'for all other things',
                    `priority` INT(11) NOT NULL DEFAULT '0',
                    `status` TINYINT(1) NOT NULL DEFAULT '0',
                    `design_status` TINYINT(1) NOT NULL DEFAULT '0',
                    `design_module_banner` TEXT NOT NULL,
                    `design_page_banner` TEXT NOT NULL,
                    `design_page_message` TEXT NOT NULL,
                    `message_congrats` TEXT NOT NULL,
                    `message_eligible` TEXT NOT NULL,
                    `message_upsell` TEXT NOT NULL,
                    `date_start` DATETIME NOT NULL,
                    `date_end` DATETIME NOT NULL,
                    `date_update` DATETIME NOT NULL,
                    `date_added` DATETIME NOT NULL,
                    PRIMARY KEY (`promotion_id`)
                )
                ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1"
            );

            $this->db->query(
                "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "promotions_log` (
                    `log_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                    `promotion_id` INT(11) UNSIGNED NOT NULL,
                    `customer_id` INT(11) UNSIGNED NOT NULL,
                    `order_id` INT(11) UNSIGNED NOT NULL,
                    `store_id` INT(11) UNSIGNED NOT NULL,
                    `meta` TEXT NOT NULL COMMENT 'json for all other things',
                    `date_added` DATETIME NOT NULL,
                    PRIMARY KEY (`log_id`)
                )
                ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1"
            );
        }
    }

    public function uninstall()
    {
        $this->db->query(
            "DROP TABLE IF EXISTS
                `" . DB_PREFIX . "promotions_item`,
                `" . DB_PREFIX . "promotions_log`"
        );
    }

    public function setup()
    {
        // Promotions 5.1
        if (!$this->checkTableColumn('promotions_item', 'condition_manufacturer_ids')) {
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "promotions_item` ADD COLUMN `condition_manufacturer_ids` TEXT NOT NULL COMMENT 'json string id' AFTER `condition_category_ids`;");
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "promotions_item` ADD COLUMN `discount_category_ids` TEXT NOT NULL COMMENT 'json string id' AFTER `discount_product_ids`;");
        }
        if (!$this->checkTableColumn('promotions_item', 'apply_coupon')) {
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "promotions_item` ADD COLUMN `apply_coupon` TINYINT(1) NOT NULL DEFAULT '0' AFTER `apply_special`;");
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "promotions_item` ADD COLUMN `coupon_code` VARCHAR(36) NOT NULL AFTER `apply_coupon`;");
        }
        if (!$this->checkTableColumn('promotions_item', 'limit_customer_profile')) {
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "promotions_item` ADD COLUMN `limit_customer_profile` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'apply to specific customer' AFTER `customer_group_ids`;");
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "promotions_item` ADD COLUMN `customer_ids` TEXT NOT NULL COMMENT 'json string id' AFTER `limit_customer_profile`;");
        }

        // Promotions 5.2
        if ($this->checkTableColumnType('promotions_item', 'discount_value') == 'int') {
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "promotions_item` CHANGE COLUMN `discount_value` `discount_value` DECIMAL(15,4) NOT NULL DEFAULT '0.000' AFTER `discount_manufacturer_ids`;");
        }

        // Promotions 5.2.1
        if ($this->checkTableColumnType('promotions_item', 'condition_min_amount') == 'int') {
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "promotions_item` CHANGE COLUMN `condition_min_amount` `condition_min_amount` DECIMAL(15,4) NOT NULL DEFAULT '0.000' AFTER `condition_min_quantities`;");
        }
    }

    public function checkTable($table = 'promotions_item')
    {
        $tables = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . $table . "';");

        if ($tables->num_rows) {
            return true;
        }

        return false;
    }

    public function checkTableColumn($table, $column)
    {
        if ($this->checkTable($table)) {
            $results = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . $table . "` LIKE '" . $column . "';");

            if ($results->num_rows) {
                return true;
            }
        }

        return false;
    }

    public function checkTableColumnType($table, $column)
    {
        if ($this->checkTable($table)) {
            $results = $this->db->query("SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . $table . "' AND COLUMN_NAME = '" . $column . "';");

            if (isset($results->row['DATA_TYPE'])) {
                return $results->row['DATA_TYPE'];
            }
        }

        return false;
    }
}
