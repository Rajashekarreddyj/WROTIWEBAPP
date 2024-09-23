<?php


class Store
{

	var $store_id, $name, $distance, $rating, $address, $mobilenumber, $image, $sort_order, $offers;

	public function __construct($data)
	{
		$this->store_id = $data['store_id'];
		$this->name = $data['name'];
		$this->distance = $data['distance'];
		$this->rating = $data['rating'];
		$this->address = $data['address'];
		$this->mobilenumber = $data['mobilenumber'];
		$this->image = $data['image'];
		$this->sort_order = $data['sort_order'];
		$this->offers = $data['offers'];
	}

}
class ControllerextensionFeedRestApi extends Controller
{

	private $debugIt = false;
	//clear cart 


	public function getOrderByDeliveryOrderId()
	{

		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}

		$delivery_order_id = $this->request->get['delivery_order_id'];
		$this->load->model('account/order');
		$order_data = $this->model_account_order->getOrderByDeliveryOrderId($delivery_order_id, $store_id);
		$json = array();
		$json['success'] = true;
		$json['order'] = $order_data;
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}


	}


	public function unsubscribe()
	{
		$this->checkPlugin();
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}



		$catjson = file_get_contents('php://input');

		$data = json_decode($catjson, true);
		$data['store_id'] = $store_id;

		$unsubscribe = $this->unsubscribes($data);

		$json = array();

		$json['success'] = true;
		$json['message'] = "successfully unsubscribed";
		$json['customer_id'] = $data['customer_id'];





		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}

	}
	public function unsubscribes($data)
	{

		$this->db->query("INSERT INTO " . DB_PREFIX . "subscriptions SET store_id = '" . (int) $data['store_id'] . "',  `customer_id` = '" . (int) $data['customer_id'] . "',create_date = NOW(), modified_date = NOW()");
		return $data;
	}

	public function getProductOptions()
	{


		$this->checkPlugin();
		$this->load->model('catalog/product');
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}
		$product_id = 0;


		if (isset($this->request->post['product_id']) && $this->request->post['product_id'] != "") {
			$product_id = $this->request->post['product_id'];
		}

		$results = $this->model_catalog_product->getProductOptions($product_id);


		if (count($results)) {
			$json['success'] = true;
			$json['productoptions'] = $results;

		} else {
			$json['success'] = false;
		}
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}
	public function getBanners()
	{


		$this->checkPlugin();

		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}

		$results = $this->getBannerapi();

		if (count($results)) {
			$json['success'] = true;
			$json['banners'] = $results;

		} else {
			$json['success'] = false;
		}
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}
	public function getOptions()
	{


		$this->checkPlugin();
		$this->load->model('catalog/product');
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}


		$results = $this->model_catalog_product->getOptions($store_id);

		if (count($results)) {
			$json['success'] = true;
			$json['productoptions'] = $results;

		} else {
			$json['success'] = false;
		}
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}


	public function addStore()
	{

		$this->checkPlugin();



		$this->load->model('setting/store');
		$json = file_get_contents('php://input');
		$data = json_decode($json, true);


		$store_id = $this->model_setting_store->addStore($data);

		$this->model_setting_store->editSetting('config', $data, $store_id);



		$json = array();
		if ($store_id > 0) {
			$json['success'] = true;
			$json['store_id'] = $store_id;
		} else {
			$json['success'] = false;
		}


		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';
		} else {
			$this->response->setOutput(json_encode($json));
		}

	}


	public function getAllStores()
	{
		$this->checkPlugin();

		$IsAvailable = false;
		$this->load->model('setting/store');


		$this->load->model('setting/setting');

		// $filter_data = array();
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}
		$sort_distance = "";
		if (isset($this->request->post['sort_distance']) && $this->request->post['sort_distance'] != "") {
			$sort_distance = $this->request->post['sort_distance'];
		}
		$rating = "";
		if (isset($this->request->post['rating']) && $this->request->post['rating'] != "") {
			$rating = $this->request->post['rating'];
		}
		$sort_order = "";
		if (isset($this->request->post['sort_order']) && $this->request->post['sort_order'] != "") {
			$sort_order = $this->request->post['sort_order'];
		}
		if (isset($this->request->post['latitude']) && $this->request->post['latitude'] != "") {
			$lat = $this->request->post['latitude'];
		}
		if (isset($this->request->post['longitude']) && $this->request->post['longitude'] != "") {
			$long = $this->request->post['longitude'];
		}
		$test = array();
		if (isset($this->request->post['seakeyword']) && $this->request->post['seakeyword'] != "") {
			$seakeyword = $this->request->post['seakeyword'];
			$test['filter_name'] = $seakeyword;
		}

		$stores = $this->model_setting_store->getStores($test);


		foreach ($stores as $store) {

			$result = $this->model_setting_setting->getAllSetting($store['store_id']);


			// $storerating = $result['config_rating'];
			// $storeaddress = $result['config_address'];
			// $storemobile = $result['config_telephone'];
			// $storeimage = $result['config_image'];
			// $store_brands = $result['config_brands'];
			// $store_offers = $result['config_promotions'];

			if (isset($result['config_rating'])) {
				$storerating = $result['config_rating'];
			} else {
				$storerating = 0;
			}
			if (isset($result['config_address'])) {
				$storeaddress = $result['config_address'];
			} else {
				$storeaddress = "";
			}
			if (isset($result['config_telephone'])) {
				$storemobile = $result['config_telephone'];
			} else {
				$storemobile = "";
			}
			if (isset($result['config_image'])) {
				$storeimage = 'https://ocprod.wroti.app/image/' . $result['config_image'];
			} else {
				$storeimage = "";
			}
			if (isset($result['config_brands'])) {
				$store_brands = $result['config_brands'];
			} else {
				$store_brands = "";
			}
			if (isset($result['config_promotions'])) {
				$store_offers = $result['config_promotions'];
			} else {
				$store_offers = "";
			}
			$storegeocode = $result['config_geocode'];

			$storegeocode = explode(",", $storegeocode);


			$storelat = floatval($storegeocode[0]);
			$storelong = floatval($storegeocode[1]);
			if (isset($result['config_geocode'])) {
				$distance = ceil($this->distance($storelat, $storelong, $lat, $long, "K"));
			} else {
				$distance = 0;
			}



			// $storegeocode = $this->model_setting_setting->getSettingValue('config_geocode', $store['store_id']);

			// $storerating = $this->model_setting_setting->getSettingValue('config_rating', $store['store_id']);
			// $storeaddress = $this->model_setting_setting->getSettingValue('config_address', $store['store_id']);
			// $storemobile = $this->model_setting_setting->getSettingValue('config_telephone', $store['store_id']);
			// $storeimage = $this->model_setting_setting->getSettingValue('config_image', $store['store_id']);
			// $store_brands = $this->model_setting_setting->getSettingValue('config_brands', $store['store_id']);

			// $store_offers = $this->model_setting_setting->getSettingValue('config_promotions', $store['store_id']);
			// if ($store_offers != "") {
			// 	$store_offers = $this->model_setting_setting->getSettingValue('config_promotions', $store['store_id']);
			// } else {
			// 	$store_offers = "";
			// }
			// $store_url = $this->model_setting_setting->getSettingValue('config_url', $store['store_id']);

			// if ($storerating != 0) {
			// 	$storerating = $this->model_setting_setting->getSettingValue('config_rating', $store['store_id']);
			// } else {
			// 	$storerating = 0;
			// }

			// if ($store_brands != "") {
			// 	$store_brands = $this->model_setting_setting->getSettingValue('config_brands', $store['store_id']);
			// } else {
			// 	$store_brands = "";
			// }


			// $storegeocode = array_pad(explode(",", $storegeocode), 2, null);


			// $storelat = floatval($storegeocode[0]);
			// $storelong = floatval($storegeocode[1]);




			// if ($storegeocode != "") {

			// 	$distance = ceil($this->distance($storelat, $storelong, $lat, $long, "K"));

			// } else {
			// 	$distance = 0;
			// }

			// if ($storeimage != "" && $storeimage != null) {
			// 	$storeimage = 'https://ocuat.wroti.app/image/' . $storeimage;
			// } else {
			// 	$storeimage = "";
			// }


			// if ($distance <= 5) {
			$IsAvailable = true;
			$data['stores'][] = array(
				'store_id' => $store['store_id'],
				'name' => $store['name'],
				"distance" => $distance,
				"rating" => $storerating,
				'address' => $storeaddress,
				'mobilenumber' => $storemobile,
				'image' => $storeimage,
				'sort_order' => $store_brands,
				'offers' => $store_offers,



			);

			// }
		}
		function data2Object($data)
		{
			$class_object = new Store($data);
			return $class_object;
		}
		function comparatordistance1($object1, $object2)
		{
			return $object1->distance > $object2->distance;
		}
		function comparatordistance2($object1, $object2)
		{
			return $object1->distance < $object2->distance;
		}
		function comparatorrating1($object1, $object2)
		{
			return $object1->rating > $object2->rating;
		}
		function comparatorrating2($object1, $object2)
		{
			return $object1->rating < $object2->rating;
		}
		function comparatorbrands1($object1, $object2)
		{
			return $object1->sort_order > $object2->sort_order;
		}


		$sorted_stores = [];
		if ($IsAvailable)
			$sorted_stores = array_map('data2Object', $data['stores']);

		// usort($sorted_stores, 'comparator2');

		if ($sorted_stores != null) {
			if ($sort_distance == "ASC") {
				usort($sorted_stores, 'comparatordistance1');
			} else if ($sort_distance == "DESC") {
				usort($sorted_stores, 'comparatordistance2');
			}

			if ($rating == "ASC") {
				usort($sorted_stores, 'comparatorrating1');
			} else if ($rating == "DESC") {
				usort($sorted_stores, 'comparatorrating2');
			}
			if ($sort_order == "ASC") {
				usort($sorted_stores, 'comparatorbrands1');
			}
		}


		$json = array();
		// if ($data) {
		// 	$data['success'] = true;
		// 	// $store['name'] = $seakeyword;


		// } else {
		// 	$json['success'] = false;

		// }

		//$json['products_prodadditional'] = $add_prod_data;


		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';
		} else {

			if (sizeof($sorted_stores) > 0) {
				$this->response->setOutput(json_encode($sorted_stores));
			} else {
				$json['success'] = false;
				$this->response->setOutput(json_encode($json));
			}




		}
	}

	public function addOption()
	{

		$this->checkPlugin();
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}
		$this->load->model('catalog/option');
		$json = file_get_contents('php://input');
		$data = json_decode($json, true);
		$data['store_id'] = $store_id;
		// if (!isset($data['ptype'])) {
		// 	$data['ptype'] = '';
		// }
		$option_id = $this->model_catalog_option->addOption($data);

		$json = array();
		if ($option_id > 0) {
			$json['success'] = true;
			$json['option_id'] = $option_id;
		} else {
			$json['success'] = false;
		}


		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';
		} else {
			$this->response->setOutput(json_encode($json));
		}

	}
	public function ruleForm()
	{
		$data = $this->data;
		$output = '';

		if (isset($this->request->get['promo_rule'])) {
			$this->load->model('localisation/geo_zone');
			$this->load->model('catalog/manufacturer');

			$data['module_setting'] = $this->module['code'] . '_item';
			$data['currency'] = $this->config->get('config_currency');
			$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
			$data['manufacturers'] = $this->model_catalog_manufacturer->getManufacturers();
			$data['link_module'] = $this->module['url_module'];
			$data['link_module_form'] = $this->module['url_module_form'];

			$data['discount_product_ids_data'] = array();
			$data['condition_product_ids_data'] = array();
			$data['discount_category_ids_data'] = array();
			$data['condition_category_ids_data'] = array();
			$data['condition_manufacturer_ids_data'] = array();
			$data['condition_min_quantities_data'] = array();
			$data['condition_min_amounts_data'] = array();

			$data['setting'] = $this->module['setting'];
			if ($this->module['promo_id'] || isset($this->session->data['promotions_item'])) {
				$item = $this->module['model']->getItem($this->module['promo_id']);

				if (!$item) {
					$item = $this->session->data['promotions_item'];
				}

				if ($this->request->get['promo_rule'] == $item['rule_group'] . '_' . $item['rule_type']) {
					$data['setting']['item'] = $item;

					if ($item['discount_product_ids']) {
						$data['discount_product_ids_data'] = $this->module['model']->getProductsInId($item['discount_product_ids']);
					}
					if ($item['condition_product_ids']) {
						$data['condition_product_ids_data'] = $this->module['model']->getProductsInId($item['condition_product_ids']);
					}
					if ($item['discount_category_ids']) {
						$data['discount_category_ids_data'] = $this->module['model']->getCategoriesInId($item['discount_category_ids']);
					}
					if ($item['condition_category_ids']) {
						$data['condition_category_ids_data'] = $this->module['model']->getCategoriesInId($item['condition_category_ids']);
					}
					if ($item['condition_manufacturer_ids']) {
						$data['condition_manufacturer_ids_data'] = $this->module['model']->getManufacturerInId($item['condition_manufacturer_ids']);
					}
					if ($item['condition_min_quantities'] && $item['discount_values']) {
						foreach ($item['condition_min_quantities'] as $key => $value) {
							$data['condition_min_quantities_data'][$key] = array(
								'quantity' => $value,
								'discount' => isset($item['discount_values'][$key]) ? $item['discount_values'][$key] : '',
							);
						}
					}
					if ($item['condition_min_amounts'] && $item['discount_values']) {
						foreach ($item['condition_min_amounts'] as $key => $value) {
							$data['condition_min_amounts_data'][$key] = array(
								'amount' => $value,
								'discount' => isset($item['discount_values'][$key]) ? $item['discount_values'][$key] : '',
							);
						}
					}
				}
			}
			// print_r($data);
			// die;
			$output = $this->load->view($this->module['path'] . '/rule/' . $this->request->get['promo_rule'], $data);
		}

		$this->response->setOutput($output);
	}

	public function addPromotion()
	{

		$this->checkPlugin();
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}

		$this->load->model('catalog/option');

		$json = file_get_contents('php://input');
		$data = json_decode($json, true);

		$promotion_id = $this->model_catalog_option->addPromotions($data);

		$json = array();
		if ($promotion_id > 0) {
			$json['success'] = true;
			$json['promotion_id'] = $promotion_id;
		} else {
			$json['success'] = false;
		}


		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';
		} else {
			$this->response->setOutput(json_encode($json));
		}

	}

	public function editPromotion()
	{

		$this->checkPlugin();
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}
		$this->load->model('catalog/option');
		$json = file_get_contents('php://input');

		$data = json_decode($json, true);

		$data['store_id'] = $store_id;

		$promo_id = $this->model_catalog_option->editPromotions($data['promotion_id'], $data);


		$json = array();
		if ($promo_id > 0) {
			$json['success'] = true;
			$json['promotion_id'] = $promo_id;
		} else {
			$json['success'] = false;
		}


		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';
		} else {
			$this->response->setOutput(json_encode($json));
		}

	}

	public function editOption()
	{

		$this->checkPlugin();
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}
		$this->load->model('catalog/option');
		$json = file_get_contents('php://input');

		$data = json_decode($json, true);

		$data['store_id'] = $store_id;
		$option_id = $this->model_catalog_option->editOption($data['option_id'], $data);


		$json = array();
		if ($option_id > 0) {
			$json['success'] = true;
			$json['option_id'] = $option_id;
		} else {
			$json['success'] = false;
		}


		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';
		} else {
			$this->response->setOutput(json_encode($json));
		}

	}

	public function deleteOption()
	{
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}

		$this->load->model('catalog/option');


		$catjson = file_get_contents('php://input');

		$data = json_decode($catjson, true);

		$option_id = $data['option_id'];
		if ((int) $option_id > 0) {
			$category = $this->model_catalog_option->deleteOption($data['option_id'], $store_id);
		}

		$json = array();
		if ($option_id > 0) {
			$json['success'] = true;
			$json['option_id'] = $option_id;
			$json['message'] = "Option deleted";
		} else {
			$json['success'] = false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}

	}

	public function getOptionValuebyid()
	{

		$this->checkPlugin();
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}

		$this->load->model('catalog/option');

		$option_id = 0;
		if (isset($this->request->post['option_id']) && $this->request->post['option_id'] != "") {
			$option_id = $this->request->post['option_id'];
		}

		$optiondata = array();
		if ($option_id != 0) {
			$option_info = array();
			$option_info = $this->model_catalog_option->getOption($option_id);

			$options = $this->model_catalog_option->getOptionValues($option_id);
			$option_values = $this->model_catalog_option->getOptionValueDescriptions($option_id);
			$this->load->model('tool/image');
			foreach ($option_values as $option_value) {
				if (isset($option_value['image']) && is_file(DIR_IMAGE . $option_value['image'])) {
					$option_value['image_path'] = $option_value['image'];
					$option_value['image'] = $this->model_tool_image->resize($option_value['image'], 200, 200);
				} else {
					$option_value['image'] = "";
					$option_value['image_path'] = "";
				}


				$optiondata[] = array(
					'option_value_id' => $option_value['option_value_id'],


					'option_value_description' => $option_value['option_value_description'],
					'image' => $option_value['image'],
					'image_path' => $option_value['image_path'],
					'sort_order' => $option_value['sort_order'],
					'name' => $option_value['option_value_description']['1']['name']
				);
			}
		}
		$option_info['optiondata'] = $optiondata;


		if ($option_info) {

			$json = $option_info;

			$json['success'] = true;
		} else {
			$json['success'] = false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}

	public function deleteProduct()
	{
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}

		$this->load->model('catalog/product');


		$catjson = file_get_contents('php://input');

		$data = json_decode($catjson, true);

		$product_id = $data['product_id'];
		if ((int) $product_id > 0) {
			$product = $this->model_catalog_product->deleteProduct($data['product_id'], $store_id);
		}

		$json = array();
		if ($product_id > 0) {
			$json['success'] = true;
			$json['product_id'] = $product_id;
			$json['message'] = "Product deleted";
		} else {
			$json['success'] = false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}

	}
	public function applyCouponLocal()
	{
		$this->checkPlugin();
		$json = array();
		$json['success'] = false;
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}

		$code = "";
		if (isset($this->request->post['code']) && $this->request->post['code'] != "") {
			$code = $this->request->post['code'];
		}

		if (isset($this->request->post['customer_id']) && $this->request->post['customer_id'] != "") {
			$customer_id = $this->request->post['customer_id'];
		}


		$this->load->language('extension/total/coupon', 'coupon');

		$coupon_info = $this->getCoupon($code, $customer_id, $store_id);

		$total = [];
		if ($coupon_info && isset($coupon_info['coupon_id'])) {

			$discount_total = 0;
			$products = $this->getCartProducts($customer_id, $store_id);


			$sub_total = 0;
			if (!$coupon_info['product']) {
				foreach ($products as $product) {
					$sub_total += $product['total'];
				}

			} else {
				$sub_total = 0;
				foreach ($products as $product) {
					if (in_array($product['product_id'], $coupon_info['product'])) {
						$sub_total += $product['total'];
					}
				}
			}


			if ($coupon_info['type'] == 'F') {
				$coupon_info['discount'] = min($coupon_info['discount'], $sub_total);
			}

			foreach ($products as $product) {
				$discount = 0;

				if (!$coupon_info['product']) {
					$status = true;
				} else {
					$status = in_array($product['product_id'], $coupon_info['product']);
				}

				if ($status) {
					if ($coupon_info['type'] == 'F') {
						$discount = $coupon_info['discount'] * ($product['total'] / $sub_total);
					} elseif ($coupon_info['type'] == 'P') {
						$discount = $product['total'] / 100 * $coupon_info['discount'];

					}


					if ($product['tax_class_id']) {
						$tax_rates = $this->tax->getRates($product['total'] - ($product['total'] - $discount), $product['tax_class_id']);

						foreach ($tax_rates as $tax_rate) {
							if ($tax_rate['type'] == 'P') {
								$total['taxes'][$tax_rate['tax_rate_id']] -= $tax_rate['amount'];
							}
						}
					}
				}

				$discount_total += $discount;

			}

			if ($coupon_info['shipping'] && isset($this->session->data['shipping_method'])) {
				if (!empty($this->session->data['shipping_method']['tax_class_id'])) {
					$tax_rates = $this->tax->getRates($this->session->data['shipping_method']['cost'], $this->session->data['shipping_method']['tax_class_id']);

					foreach ($tax_rates as $tax_rate) {
						if ($tax_rate['type'] == 'P') {
							$total['taxes'][$tax_rate['tax_rate_id']] -= $tax_rate['amount'];
						}
					}
				}

				$discount_total += $this->session->data['shipping_method']['cost'];

			}


			// If discount greater than total
			if ($discount_total > $sub_total) {

				$discount_total = $sub_total;
			}

			if ($coupon_info['coupon_cap'] <= $discount_total && $coupon_info['type'] == 'P' && $coupon_info['coupon_cap'] > 0) {
				$discount_total = $coupon_info['coupon_cap'];
			}

			$coupon_total = $this->getTotalCouponHistoriesByCoupon($code);


			$customer_total = $this->getTotalCouponHistoriesByCustomerId($code, $this->customer->getId());

			// if ($coupon_info['uses_customer'] > 0 && ($customer_total >= $coupon_info['uses_customer'])) {
			// 	$json['success'] = false;
			// 	$json['uses_customer'] = true;
			// 	$json['message'] = "The maximum number of times the coupon can be used by a single customer.";
			// }
			// else if ($coupon_info['uses_total'] > 0 && ($coupon_total >= $coupon_info['uses_total'])) {
			// 	$json['success'] = false;
			// 	$json['uses_total'] = true;
			// 	$json['message'] = "The maximum number of times the coupon can be used by any customer.";
			// }
			if ($coupon_info['total'] > $sub_total) {

				$json['success'] = false;
				$json['total'] = true;
				$json['message'] = "Your cart Total Should be Greater Than " . (float) $coupon_info['total'];

			} else if ($discount_total > 0) {
				// $total['totals'][] = array(
				// 	'code'       => 'coupon',
				// 	'title'      => 'Coupon',//sprintf($this->language->get('coupon')->get('text_coupon'), $this->session->data['coupon']),
				// 	'value'      => -$discount_total,
				// 	'sort_order' => $this->config->get('total_coupon_sort_order')
				// );

				$total['discount_total'] = number_format($discount_total, 2);
				$json['success'] = true;
				$json['message'] = "Coupon applied successfully ";

			}
		} else {
			$json['total'] = false;
			$json['message'] = $coupon_info["message"];
		}




		$json['data'] = $total;


		return $json;
	}
	public function applyCoupon()
	{
		$this->checkPlugin();
		$json = array();
		$json['success'] = false;
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}

		$code = "";
		if (isset($this->request->post['code']) && $this->request->post['code'] != "") {
			$code = $this->request->post['code'];
		}
		$customer_id = "";
		if (isset($this->request->post['customer_id']) && $this->request->post['customer_id'] != "") {
			$customer_id = $this->request->post['customer_id'];
		}

		$this->load->language('extension/total/coupon', 'coupon');
		$coupon_info = $this->getCoupon($code, $customer_id, $store_id);

		$total = [];
		if ($coupon_info && isset($coupon_info['coupon_id'])) {

			$discount_total = 0;
			$products = $this->getCartProducts($customer_id, $store_id);


			$sub_total = 0;
			if (!$coupon_info['product']) {
				foreach ($products as $product) {
					$sub_total += $product['total'];
				}

			} else {
				$sub_total = 0;
				foreach ($products as $product) {
					if (in_array($product['product_id'], $coupon_info['product'])) {
						$sub_total += $product['total'];
					}
				}
			}


			if ($coupon_info['type'] == 'F') {
				$coupon_info['discount'] = min($coupon_info['discount'], $sub_total);
			}

			foreach ($products as $product) {
				$discount = 0;

				if (!$coupon_info['product']) {
					$status = true;
				} else {
					$status = in_array($product['product_id'], $coupon_info['product']);
				}

				if ($status) {
					if ($coupon_info['type'] == 'F') {
						$discount = $coupon_info['discount'] * ($product['total'] / $sub_total);
					} elseif ($coupon_info['type'] == 'P') {
						$discount = $product['total'] / 100 * $coupon_info['discount'];


					}


					if ($product['tax_class_id']) {
						$tax_rates = $this->tax->getRates($product['total'] - ($product['total'] - $discount), $product['tax_class_id']);

						foreach ($tax_rates as $tax_rate) {
							if ($tax_rate['type'] == 'P') {
								$total['taxes'][$tax_rate['tax_rate_id']] -= $tax_rate['amount'];
							}
						}
					}
				}

				$discount_total += $discount;

			}

			if ($coupon_info['shipping'] && isset($this->session->data['shipping_method'])) {
				if (!empty($this->session->data['shipping_method']['tax_class_id'])) {
					$tax_rates = $this->tax->getRates($this->session->data['shipping_method']['cost'], $this->session->data['shipping_method']['tax_class_id']);

					foreach ($tax_rates as $tax_rate) {
						if ($tax_rate['type'] == 'P') {
							$total['taxes'][$tax_rate['tax_rate_id']] -= $tax_rate['amount'];
						}
					}
				}

				$discount_total += $this->session->data['shipping_method']['cost'];
			}

			// If discount greater than total
			if ($discount_total > $sub_total) {

				$discount_total = $sub_total;

			}
			if ($coupon_info['coupon_cap'] <= $discount_total && $coupon_info['type'] == 'P' && $coupon_info['coupon_cap'] > 0) {
				$discount_total = $coupon_info['coupon_cap'];
			}

			$coupon_total = $this->getTotalCouponHistoriesByCoupon($code);


			$customer_total = $this->getTotalCouponHistoriesByCustomerId($code, $this->customer->getId());


			if ($coupon_info['total'] > $sub_total) {

				$json['success'] = false;
				$json['total'] = true;
				$json['message'] = "Your cart Total Should be Greater Than " . (float) $coupon_info['total'];

			} else if ($discount_total > 0) {


				$total['discount_total'] = $discount_total;
				$json['success'] = true;
				$json['message'] = "Coupon applied successfully ";

			}
		} else {
			$json['total'] = false;
			$json['message'] = $coupon_info["message"];
		}




		$json['data'] = $total;


		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}

	}
	public function getTotalCouponHistoriesByCoupon($coupon)
	{
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "coupon_history` ch LEFT JOIN `" . DB_PREFIX . "coupon` c ON (ch.coupon_id = c.coupon_id) WHERE c.code = '" . $this->db->escape($coupon) . "'");

		return $query->row['total'];
	}
	public function getCoupon($code, $customer_id, $store_id)
	{
		$status = true;

		$coupon_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "coupon` WHERE code = '" . $this->db->escape($code) . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) AND status = '1' And store_id = '" . $this->db->escape($store_id) . "'");

		if ($coupon_query->num_rows) {

			// if ($coupon_query->row['total'] > $this->cart->getSubTotal()) {
			// 	$status = false;
			// }
			// echo "hello";

			$coupon_total = $this->getTotalCouponHistoriesByCoupon($code);


			if ($coupon_query->row['uses_total'] > 0 && ($coupon_total >= $coupon_query->row['uses_total'])) {
				$status = false;
				$message = "Warning: Coupon is either invalid, expired or reached it's usage limit!";
			}
			if ($customer_id) {
				$customer_total = $this->getTotalCouponHistoriesByCustomerId($code, $customer_id);

				if ($coupon_query->row['uses_customer'] > 0 && ($customer_total >= $coupon_query->row['uses_customer'])) {
					$status = false;
					$message = "Warning: Coupon is either invalid, expired or reached it's usage limit!";
				}
			}

			// Products
			$coupon_product_data = array();

			$coupon_product_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "coupon_product` WHERE coupon_id = '" . (int) $coupon_query->row['coupon_id'] . "'");

			foreach ($coupon_product_query->rows as $product) {
				$coupon_product_data[] = $product['product_id'];
			}

			// Categories
			$coupon_category_data = array();

			$coupon_category_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "coupon_category` cc LEFT JOIN `" . DB_PREFIX . "category_path` cp ON (cc.category_id = cp.path_id) WHERE cc.coupon_id = '" . (int) $coupon_query->row['coupon_id'] . "'");

			foreach ($coupon_category_query->rows as $category) {
				$coupon_category_data[] = $category['category_id'];
			}

			$product_data = array();

			if ($coupon_product_data || $coupon_category_data) {


				foreach ($this->getCartProducts($customer_id, $store_id) as $product) {
					if (in_array($product['product_id'], $coupon_product_data)) {
						$product_data[] = $product['product_id'];

						continue;
					}

					foreach ($coupon_category_data as $category_id) {

						$coupon_category_query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "product_to_category` WHERE `product_id` = '" . (int) $product['product_id'] . "' AND category_id = '" . (int) $category_id . "'");

						if ($coupon_category_query->row['total']) {
							$product_data[] = $product['product_id'];

							continue;
						}
					}
				}


				if (!$product_data) {
					$status = false;
				}

			}
		} else {
			$status = false;
			$message = "Invalid coupon";
		}


		if ($status) {
			return array(
				'coupon_id' => $coupon_query->row['coupon_id'],
				'code' => $coupon_query->row['code'],
				'name' => $coupon_query->row['name'],
				'type' => $coupon_query->row['type'],
				'discount' => $coupon_query->row['discount'],
				'shipping' => $coupon_query->row['shipping'],
				'total' => $coupon_query->row['total'],
				'product' => $product_data,
				'date_start' => $coupon_query->row['date_start'],
				'date_end' => $coupon_query->row['date_end'],
				'uses_total' => $coupon_query->row['uses_total'],
				'uses_customer' => $coupon_query->row['uses_customer'],
				'status' => $coupon_query->row['status'],
				'date_added' => $coupon_query->row['date_added'],
				'coupon_cap' => $coupon_query->row['coupon_cap']
			);
		} else {
			return array(
				"status" => $status,
				"message" => $message
			);
		}

	}

	public function getTotalCouponHistoriesByCustomerId($coupon, $customer_id)
	{
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "coupon_history` ch LEFT JOIN `" . DB_PREFIX . "coupon` c ON (ch.coupon_id = c.coupon_id) WHERE c.code = '" . $this->db->escape($coupon) . "' AND ch.customer_id = '" . (int) $customer_id . "'");

		return $query->row['total'];
	}

	public function getProductTotalSales()
	{

		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}
		$product_id = 0;


		if (isset($this->request->post['product_id']) && $this->request->post['product_id'] != "") {
			$product_id = $this->request->post['product_id'];
		}


		$this->load->model('catalog/product');
		$product_sales = $this->model_catalog_product->getProductTotalSales($product_id);

		$json = array();
		$json['success'] = true;
		$json['productsales'] = $product_sales;
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}


	}


	public function updatedCustomerGroup()
	{
		$this->checkPlugin();

		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}

		$this->load->model('customer/customer_group');
		$json = file_get_contents('php://input');
		$data = json_decode($json, true);




		$results = $this->model_customer_customer_group->editCustomerGroup($data['customer_group_id'], $data);
		$json = array();
		$json['success'] = true;
		$json['message'] = "Customer group updated successfully.";
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}

	}

	public function deleteCustomerGroup()
	{

		$this->checkPlugin();

		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}

		$this->load->model('customer/customer_group');
		$json = file_get_contents('php://input');
		$data = json_decode($json, true);

		if ($data['customer_group_id'] == 1) {
			$json = array();
			$json['success'] = false;
			$json['message'] = "Default Customer group cannot be deleted.";
		} else {



			$results = $this->model_customer_customer_group->deleteCustomerGroup($data['customer_group_id']);

			$json = array();
			$json['success'] = true;
			$json['message'] = "Customer group delete successfully.";
		}
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}



	}

	public function deletecategory()
	{
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}

		$this->load->model('catalog/category');


		$catjson = file_get_contents('php://input');

		$data = json_decode($catjson, true);

		$category_id = $data['category_id'];
		if ((int) $category_id > 0) {
			$category = $this->model_catalog_category->deleteCategory($data['category_id']);
		}

		$json = array();
		if ($category_id > 0) {
			$json['success'] = true;
			$json['category_id'] = $category_id;
			$json['message'] = "Category deleted";
		} else {
			$json['success'] = false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}

	}

	public function addcategory()
	{

		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}
		$this->load->model('catalog/category');


		$catjson = file_get_contents('php://input');

		$data = json_decode($catjson, true);

		$data['category_store'] = array($store_id);

		$category = $this->model_catalog_category->addCategory($data);

		$categories = $this->model_catalog_category->getCategory($category, 1, $store_id);

		$json = array();
		if ($category) {
			$data['category_id'] = $category;

			$json['success'] = true;
			$json['category_id'] = $category;
			$json['category_info'] = $categories;
			$json['category_info']['category_description'] = $data['category_description'];
		} else {
			$json['success'] = false;
			$json['category_id'] = 0;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}

	}


	public function updatecategory()
	{

		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}

		$this->load->model('catalog/category');


		$catjson = file_get_contents('php://input');

		$data = json_decode($catjson, true);

		$data['category_store'] = array($store_id);

		$this->model_catalog_category->editCategory($data['category_id'], $data);

		$json = array();
		if ($data['category_id'] > 0) {
			$json['success'] = true;
			$json['message'] = "Category updated successfully!";
		} else {
			$json['success'] = false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}

	}

	public function updateCustomer()
	{

		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}
		$customer_id = 0;


		if (isset($this->request->post['customer_id']) && $this->request->post['customer_id'] != "") {
			$customer_id = $this->request->post['customer_id'];
		}
		$this->load->model('customer/customer');


		$customer = file_get_contents('php://input');

		$data = json_decode($customer, true);
		$data['customer'] = array($store_id);

		$customer_id = $this->model_customer_customer->updateCustomer($data['customer_id'], $data);



		$json = array();
		if ($customer_id > 0) {
			$json['success'] = true;
			$json['customer_id'] = $customer_id;
			$json['message'] = "Customer updated successfully!";
		} else {
			$json['success'] = false;
		}



		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}

	}

	public function getBrands()
	{

		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}
		$this->load->model('catalog/manufacturer');

		$data = array(

			'store_id' => $store_id,

		);

		$brands = $this->model_catalog_manufacturer->getManufacturers($data);
		$json = array();
		if (count($brands)) {
			$json['success'] = true;
			$json['brands'] = $brands;
		} else {
			$json['success'] = false;
			$json['brands'] = array();
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}


	}

	public function test()
	{
		//$this->load->model('setting/store');
		$this->load->language('checkout/cart');
		$this->checkPlugin();
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}



		if (isset($this->request->post['customer_id']) && $this->request->post['customer_id'] != "") {
			$customer_id = $this->request->post['customer_id'];
		}

		$res_data = $this->getCartProducts($customer_id, $store_id);

		$this->load->model('setting/setting');
		$this->load->model('setting/extension');

		$storecurrency = $this->model_setting_setting->getSettingValue('config_currency', $store_id);


		$tax_data = array();

		foreach ($res_data as $product) {

			if ($product['tax_class_id']) {

				$tax_rates = $this->tax->getRates($product['total'], $product['tax_class_id'], $tax_data);


				foreach ($tax_rates as $tax_rate) {

					if (!isset($tax_data[$tax_rate['tax_rate_id']])) {

						$tax_data[$tax_rate['tax_rate_id']] = ($tax_rate['amount'] * $product['quantity']);
					} else {

						$tax_data[$tax_rate['tax_rate_id']] += ($tax_rate['amount'] * $product['quantity']);
					}
				}


			}
		}
		/*$long=0;
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																													$lat=0;
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																													if (isset($this->request->post['latitude']) && $this->request->post['latitude'] != "") {
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																													}
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																													if (isset($this->request->post['longitude']) && $this->request->post['longitude'] != "") {
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																													$long = $this->request->post['longitude'];
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																													}
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																													//$obj = json_decode($shipping_custom_field);
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																													$long = explode(',', $long);
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																													$lat = explode(',', $lat);
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																													
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																													$store = $this->model_setting_store->getStore($store_id);*/


		$this->load->model('setting/setting');
		$results = $this->model_setting_setting->getSettingValue('config_currency', $store_id);
		$formats = array("INR" => "en_In", "AED" => "en_ED");
		echo $formats[$results];
		if ($results) {
			$json['success'] = true;
			$json['Currency'] = $results;
		} else {
			$json['success'] = false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}

	}

	public function reports()
	{

		$this->checkPlugin();
		$this->load->model('extension/report/product');
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = 15;
		}
		if (isset($this->request->post['code']) && $this->request->post['code'] != "") {
			$code = $this->request->post['code'];
		}
		if (isset($this->request->post['filter_date_start'])) {
			$filter_date_start = $this->request->post['filter_date_start'];
		} else {
			$filter_date_start = '';
		}

		if (isset($this->request->post['filter_date_end'])) {
			$filter_date_end = $this->request->post['filter_date_end'];
		} else {
			$filter_date_end = '';
		}

		if (isset($this->request->post['filter_order_status_id'])) {
			$filter_order_status_id = $this->request->post['filter_order_status_id'];
		} else {
			$filter_order_status_id = 0;
		}
		if (isset($this->request->post['filter_group'])) {
			$filter_group = $this->request->post['filter_group'];
		} else {
			$filter_group = 'week';
		}
		if (isset($this->request->post['filter_customer'])) {
			$filter_customer = $this->request->post['filter_customer'];
		} else {
			$filter_customer = '';
		}


		$data = array();

		if ($code == 'product_purchased') {
			$this->load->model('extension/report/product');


			$filter_data = array(
				'filter_date_start' => $filter_date_start,
				'filter_date_end' => $filter_date_end,
				'filter_order_status_id' => $filter_order_status_id,
				'start' => ($page - 1) * $limit,
				'limit' => $limit,
				'store_id' => $store_id
			);

			//Array ( [filter_date_start] => 2022-10-20 [filter_date_end] => 2022-10-25 [filter_order_status_id] => 2 [start] => 0 [limit] => 20 )
			$product_total = $this->model_extension_report_product->getTotalPurchased($filter_data);

			$results = $this->model_extension_report_product->getPurchased($filter_data);

			foreach ($results as $result) {
				$data[] = array(
					'name' => $result['name'],
					'model' => $result['model'],
					'quantity' => $result['quantity'],
					'total' => $this->currency->format($result['total'], $this->config->get('config_currency'))
				);
			}
			$json['products'] = $data;
			$json['limit'] = $limit;
			$json['total'] = $product_total;
		}
		if ($code == 'sale_order') {
			$this->load->model('extension/report/sale');

			$data = array();

			$filter_data = array(
				'filter_date_start' => $filter_date_start,
				'filter_date_end' => $filter_date_end,
				'filter_group' => $filter_group,
				'filter_order_status_id' => $filter_order_status_id,
				'start' => ($page - 1) * $limit,
				'limit' => $limit,
				'store_id' => $store_id
			);

			$order_total = $this->model_extension_report_sale->getTotalOrders($filter_data);

			$results = $this->model_extension_report_sale->getOrders($filter_data);

			foreach ($results as $result) {
				$data[] = array(
					'date_start' => date($this->language->get('date_format_short'), strtotime($result['date_start'])),
					'date_end' => date($this->language->get('date_format_short'), strtotime($result['date_end'])),
					'orders' => $result['orders'],
					'products' => $result['products'],
					'tax' => $this->currency->format($result['tax'], $this->config->get('config_currency')),
					'total' => $this->currency->format($result['total'], $this->config->get('config_currency'))
				);
			}
			$json['orders'] = $data;
			$json['limit'] = $limit;
			$json['total'] = $order_total;
		}

		if ($code == 'customer_order') {
			$this->load->model('extension/report/customer');

			$data = array();

			$filter_data = array(
				'filter_date_start' => $filter_date_start,
				'filter_date_end' => $filter_date_end,
				'filter_customer' => $filter_customer,
				'filter_order_status_id' => $filter_order_status_id,
				'start' => ($page - 1) * $limit,
				'limit' => $limit,
				'store_id' => $store_id
			);

			$customer_total = $this->model_extension_report_customer->getTotalOrders($filter_data);

			$results = $this->model_extension_report_customer->getOrders($filter_data);
			foreach ($results as $result) {
				$data[] = array(
					'customer' => $result['customer'],
					'email' => $result['email'],
					'customer_group' => $result['customer_group'],
					'status' => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
					'orders' => $result['orders'],
					'products' => $result['products'],
					'total' => $this->currency->format($result['total'], $this->config->get('config_currency'))

				);
			}
			$json['customer_order'] = $data;
			$json['limit'] = $limit;
			$json['total'] = $customer_total;
		}




		$json['success'] = true;


		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
		//$name = $store['name'];
	}


	public function getSupportedPayments()
	{
		$this->checkPlugin();
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}
		$this->load->model('setting/setting');
		$this->load->model('setting/extension');
		$payment_codes = json_decode($this->model_setting_setting->getSettingValue('config_payments', $store_id));

		$results = array();

		$this->load->model('setting/extension');

		$results = $this->model_setting_extension->getExtensions('payment');

		$recurring = $this->cart->hasRecurringProducts();

		foreach ($results as $result) {
			if ($this->config->get('payment_' . $result['code'] . '_status') && in_array($result['code'], $payment_codes)) {
				$this->load->model('extension/payment/' . $result['code']);

				$method = $this->{'model_extension_payment_' . $result['code']}->getMethodByCode();

				if ($method) {
					if ($recurring) {
						if (property_exists($this->{'model_extension_payment_' . $result['code']}, 'recurringPayments') && $this->{'model_extension_payment_' . $result['code']}->recurringPayments()) {
							$method_data[$result['code']] = $method;
						}
					} else {
						$method_data[$result['code']] = $method;
					}
				}
			}
		}

		if (count($results)) {
			$json['success'] = true;
			$json['payments'] = $results;
		} else {
			$json['success'] = false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}


	public function uploadoptionimage()
	{


		$this->load->language('tool/upload');

		$json = array();

		if (!empty($this->request->files['file']['name']) && is_file($this->request->files['file']['tmp_name'])) {
			// Sanitize the filename
			$filename = basename(preg_replace('/[^a-zA-Z0-9\.\-\s+]/', '', html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8')));

			// Validate the filename length
			if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 64)) {
				$json['error'] = $this->language->get('error_filename');
			}

			// Allowed file extension types
			$allowed = array();

			$extension_allowed = preg_replace('~\r?\n~', "\n", $this->config->get('config_file_ext_allowed'));

			$filetypes = explode("\n", $extension_allowed);

			foreach ($filetypes as $filetype) {
				$allowed[] = trim($filetype);
			}

			if (!in_array(strtolower(substr(strrchr($filename, '.'), 1)), $allowed)) {
				$json['error'] = $this->language->get('error_filetype');
			}

			// Allowed file mime types
			$allowed = array();

			$mime_allowed = preg_replace('~\r?\n~', "\n", $this->config->get('config_file_mime_allowed'));

			$filetypes = explode("\n", $mime_allowed);

			foreach ($filetypes as $filetype) {
				$allowed[] = trim($filetype);
			}

			if (!in_array($this->request->files['file']['type'], $allowed)) {
				$json['error'] = $this->language->get('error_filetype');
			}

			// Check to see if any PHP files are trying to be uploaded
			$content = file_get_contents($this->request->files['file']['tmp_name']);

			if (preg_match('/\<\?php/i', $content)) {
				$json['error'] = $this->language->get('error_filetype');
			}

			// Return any upload error
			if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
				$json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
			}
		} else {
			$json['error'] = $this->language->get('error_upload');
		}

		if (!$json) {
			$file = $filename . '.' . token(32);

			move_uploaded_file($this->request->files['file']['tmp_name'], DIR_UPLOAD . $file);

			// Hide the uploaded file name so people can not link to it directly.
			$this->load->model('tool/upload');

			$json['code'] = $this->model_tool_upload->addUpload($filename, $file);

			$json['success'] = $this->language->get('text_upload');
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';
		} else {
			$this->response->setOutput(json_encode($json));
		}


	}

	public function getOrderStats()
	{
		$this->checkPlugin();
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE language_id = 1");

		$orderStatusData = $query->rows;
		if (isset($this->request->get['offset']) && $this->request->get['offset'] != "" && ctype_digit($this->request->get['offset'])) {
			$offset = $this->request->get['offset'];
		} else {
			$offset = 0;
		}

		/*check limit parameter*/
		if (isset($this->request->get['limit']) && $this->request->get['limit'] != "" && ctype_digit($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = 10000;
		}
		if (isset($this->request->post['shippingtype']) && $this->request->post['shippingtype'] != "") {
			$shippingtype = $this->request->post['shippingtype'];
		} else {
			$shippingtype = 0;
		}
		$orderCounts = array();
		foreach ($orderStatusData as $value) {

			$order_status_id = $value['order_status_id'];
			$results = $this->getOrdersapi(0, $offset, $limit, $order_status_id, $store_id, $shippingtype);
			$orderCounts[$order_status_id] = count($results);
		}

		$json = array();
		if ($orderCounts) {
			$json['success'] = true;
			$json['order_counts'] = $orderCounts;
		} else {
			$json['success'] = false;
		}

		//$json['products_prodadditional'] = $add_prod_data;


		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';
		} else {
			$this->response->setOutput(json_encode($json));


		}

	}
	public function getStoreTimings()
	{
		$this->checkPlugin();
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}
		//config_open
		$this->load->model('setting/setting');
		$data = $this->model_setting_setting->getSettingValue('config_open', $store_id);
		$json = array();
		if ($data) {
			$json['success'] = true;
			$json['store_timings'] = html_entity_decode($data);
		} else {
			$json['success'] = false;
		}

		//$json['products_prodadditional'] = $add_prod_data;


		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';
		} else {
			$this->response->setOutput(json_encode($json));


		}
	}
	public function updatedStoreTimings()
	{
		$this->checkPlugin();

		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}

		$this->load->model('setting/setting');
		$json = file_get_contents('php://input');
		$data = json_decode($json, true);


		$storeTime = $this->model_setting_setting->updateStoreTimings("config_open", $data['value'], $store_id);

		$json = array();

		$json['success'] = true;
		$json['message'] = "Store Timings Updated Successfully.";


		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}

	}
	public function updateProducts()
	{

		$this->checkPlugin();
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}
		$this->load->model('catalog/product');

		$json = file_get_contents('php://input');

		$data = json_decode($json, true);



		$product_id = 1;
		$this->model_catalog_product->updateProducts($data, $store_id);

		$json = array();
		if ($product_id > 0) {
			$json['success'] = true;
			$json['product_id'] = $product_id;
		} else {
			$json['success'] = false;
		}

		//$json['products_prodadditional'] = $add_prod_data;


		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';
		} else {
			$this->response->setOutput(json_encode($json));
		}


	}

	public function clearCart()
	{
		$customer_id = 0;


		if (isset($this->request->post['customer_id']) && $this->request->post['customer_id'] != "") {
			$customer_id = $this->request->post['customer_id'];
		}

		$cart = array();
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET cart = '" . serialize($cart) . "' WHERE customer_id = '" . (int) $customer_id . "'");


		$json['success'] = true;
		$json['cartproduct'] = $cart;


		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}



	}
	public function getAllproducts()
	{

		$this->checkPlugin();
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}
		if (isset($this->request->get['offset']) && $this->request->get['offset'] != "" && ctype_digit($this->request->get['offset'])) {
			$offset = $this->request->get['offset'];
		} else {
			$offset = 0;
		}

		/*check limit parameter*/
		if (isset($this->request->get['limit']) && $this->request->get['limit'] != "" && ctype_digit($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = 10000;
		}

		$this->load->model('catalog/product');
		$this->load->model('catalog/category');

		//$json = array('success' => true, 'products' => array());
		$status = 1;
		if (isset($this->request->get['status'])) {
			$status = $this->request->get['status'];
		}

		/*check category id parameter*/
		if (isset($this->request->get['category'])) {
			$category_id = $this->request->get['category'];
		} else {
			$category_id = 0;
		}
		if (isset($this->request->get['language_id'])) {
			$language_id = $this->request->get['language_id'];
		} else {
			$language_id = 1;

		}
		$productsjson = array();
		$categories = $this->model_catalog_category->getCategory($category_id, $language_id, $store_id);


		$products = $this->model_catalog_product->getProducts(
			array(
				'filter_category_id' => $category_id,
				'status' => $status,
				'store_id' => $store_id,

			),
			$this->request->get['language_id']
		);

		//config_currency
		$this->load->model('setting/setting');
		$storecurrency = $this->model_setting_setting->getSettingValue('config_currency', $store_id);

		foreach ($products as $product) {


			$image = $product['image'];
			$this->load->model('tool/image');

			if (isset($product['image']) && is_file(DIR_IMAGE . $product['image'])) {
				$product['thumb'] = $this->model_tool_image->resize($product['image'], 200, 200);
				$product['image'] = $this->model_tool_image->resize($product['image'], 100, 100);

			} else {
				$product['image'] = $image = $this->model_tool_image->resize('no_image.png', 100, 100);
				$product['thumb'] = $image = $this->model_tool_image->resize('no_image.png', 200, 200);
			}



			if ((float) $product['special']) {
				$special = $product['special'];
			} else {
				$special = false;
			}

			$categoryInfo = $this->model_catalog_product->getCategoryByProductId($product['product_id']);
			$catId = 0;
			$catName = '';
			if ($categoryInfo) {
				$catId = $categoryInfo['category_id'];
				$category_name = $this->model_catalog_category->getCategory($categoryInfo['category_id'], $this->request->get['language_id'], $store_id);
				if ($category_name)
					$catName = $category_name['name'];

			}
			$options = $this->model_catalog_product->getProductOptions($product['product_id']);

			$productsjson[] = array(
				'id' => $product['product_id'],
				'product_id' => $product['product_id'],
				'name' => $product['name'],
				'description' => $product['description'],
				'stock_status' => $product['stock_status'],
				'manufacturer' => $product['manufacturer'],
				'quantity' => $product['quantity'],
				'reviews' => $product['reviews'],
				'formatted_price' => $this->currency->format((float) $product['price'], $storecurrency, 1.00000),
				'price' => $product['price'],
				'href' => $this->url->link('product/product', 'product_id=' . $product['product_id']),
				'thumb' => $product['thumb'],
				'image' => $product['image'],
				'special' => $special,
				'rating' => $product['rating'],
				'status' => $product['status'],
				'options' => $options,
				'category_id' => $catId,
				'category_name' => $catName,
				'sku' => $product['sku'],
				'product_description' => $product['product_description']
			);


		}
		if (count($productsjson)) {
			$json['success'] = true;
			$json['products'] = $productsjson;
			$json['category'] = $categories;
		} else {
			$json['success'] = false;
			$json['products'] = array();
			$json['category'] = $categories;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';
		} else {
			$this->response->setOutput(json_encode($json));
		}
	}
	public function products()
	{

		$this->checkPlugin();
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}

		$this->load->model('catalog/product');
		$this->load->model('catalog/category');
		if (isset($this->request->get['offset'])) {
			$offset = $this->request->get['offset'];
		} else {
			$offset = 1;
		}
		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = 15;
		}

		//$json = array('success' => true, 'products' => array());
		$status = 1;
		if (isset($this->request->get['status'])) {
			$status = $this->request->get['status'];
		}

		/*check category id parameter*/
		if (isset($this->request->get['category'])) {
			$category_id = $this->request->get['category'];
		} else {
			$category_id = 0;
		}
		if (isset($this->request->get['language_id'])) {
			$language_id = $this->request->get['language_id'];
		} else {
			$language_id = 1;

		}
		$productsjson = array();
		$categories = $this->model_catalog_category->getCategory($category_id, $language_id, $store_id);
		// $product_total = $this->model_catalog_category->getTotalproductsByCategoryId($store_id, $category_info['category_id']);

		$products_count = $this->model_catalog_product->getTotalProductsCount(
			array(
				'filter_category_id' => $category_id,
				'status' => $status,
				'store_id' => $store_id,
			),
			$this->request->get['language_id']
		);

		$products = $this->model_catalog_product->getProducts(
			array(
				'filter_category_id' => $category_id,
				'status' => $status,
				'store_id' => $store_id,
				'start' => ($offset - 1) * $limit,
				'limit' => $limit,


			),
			$this->request->get['language_id']
		);

		$this->load->model('setting/setting');
		$storecurrency = $this->model_setting_setting->getSettingValue('config_currency', $store_id);

		foreach ($products as $product) {


			$image = $product['image'];
			$this->load->model('tool/image');

			if (isset($product['image']) && is_file(DIR_IMAGE . $product['image'])) {
				$product['image_path'] = $product['image'];
				$product['thumb'] = $this->model_tool_image->resize($product['image'], 200, 200);
				$product['image'] = $this->model_tool_image->resize($product['image'], 76, 76);

			} else {
				$product['image'] = $image = $this->model_tool_image->resize('no_image.png', 76, 76);
				$product['thumb'] = $image = $this->model_tool_image->resize('no_image.png', 200, 200);
				$product['image_path'] = "";
			}






			if ((float) $product['special']) {
				$special = floatval($product['special']);
			} else {
				$special = false;
			}

			$categoryInfo = $this->model_catalog_product->getCategoryByProductId($product['product_id']);
			$catId = 0;
			$catName = '';
			if ($categoryInfo) {
				$catId = $categoryInfo['category_id'];
				$category_name = $this->model_catalog_category->getCategory($categoryInfo['category_id'], $this->request->get['language_id'], $store_id);
				if ($category_name)
					$catName = $category_name['name'];

			}
			$options = $this->model_catalog_product->getProductOptions($product['product_id']);
			// echo "jjjjjjjjjjjjj";
// 			print_r($product['attribute']);
// 			die;

			// $attributes = $product['attribute'];

			$productAttributes = $this->model_catalog_product->getProductAttributes($product['product_id']);



			if ($productAttributes != array()) {
				$attribute = $productAttributes[0]['attribute'][0]['text'];
			} else {
				$attribute = '';
			}


			$test1 = html_entity_decode($attribute);
			// print_r($test1);
			// die;
			// $dartrr = trim($attribute, '"');



			// if ($product['sprice'] != 0 && $product['zprice'] = 0) {
			// 	$avg = ($product['sprice'] + $product['zprice']) / 2;
			// } else {
			// 	$avg = 0;
			// }

			if ($special != false && $product['sprice'] > $product['special'] && $product['zprice'] > $product['special']) {


				$avg = ($product['sprice'] + $product['zprice']) / 2;



			} elseif ($special != false && $product['sprice'] > $product['special'] && $product['zprice'] <= $product['special']) {

				$avg = $product['sprice'];



			} elseif ($special != false && $product['zprice'] > $product['special'] && $product['sprice'] <= $product['special']) {
				$avg = $product['zprice'];


			} elseif ($special == false && $product['sprice'] > $product['price'] && $product['zprice'] > $product['price']) {
				$avg = ($product['sprice'] + $product['zprice']) / 2;

			} elseif ($special == false && $product['zprice'] > $product['price'] && $product['sprice'] <= $product['price']) {


				$avg = $product['zprice'];
			} elseif ($special == false && $product['sprice'] > $product['price'] && $product['zprice'] <= $product['price']) {


				$avg = $product['sprice'];
			} else {


				$avg = 0;
			}


			if ($special != false && $avg > $product['special']) {

				$discount = $avg - $product['special'];
			} else {

				if ($special == false && $avg > $product['price'])

					$discount = $avg - $product['price'];
				else
					$discount = 0;
			}


			// if (is_numeric($special) && $avg < $product['special']) {
			// 	$discount = floatval($avg - $product['special']);
			// } else {
			// 	$discount = "";
			// }

			// echo $product['product_id'] . "-" . $avg . "-" . $discount . "-" . $product['special'] . "-" . $product['price'];
			// echo "<br/>";
			$productsjson[] = array(
				'id' => $product['product_id'],
				'product_id' => $product['product_id'],
				'name' => $product['name'],
				'description' => $product['description'],
				'stock_status' => $product['stock_status'],
				'manufacturer' => $product['manufacturer'],
				'quantity' => $product['quantity'],
				'reviews' => $product['reviews'],
				'formatted_price' => $this->currency->format((float) $product['price'], $storecurrency, 1.00000),
				'price' => floatval($product['price']),
				'thumb' => $product['thumb'],
				'href' => $this->url->link('product/product', 'product_id=' . $product['product_id']),
				'sprice' => $product['sprice'],
				'zprice' => $product['zprice'],
				'average' => $avg,
				'discount' => $discount,
				'image' => $product['image'],
				'image_path' => $product['image_path'],
				'special' => $special,
				'pos_product_id' => $product['pos_product_id'],
				'rating' => $product['rating'],
				'status' => $product['status'],
				// 'type' => $product['type'],
				'options' => $options,
				'attribute' => json_decode($test1),
				'category_id' => $catId,
				'category_name' => $catName,
				'sku' => $product['sku'],
				'product_description' => $product['product_description']
			);





		}

		if (count($productsjson)) {
			$json['success'] = true;
			$json['products'] = $productsjson;
			$json['category'] = $categories;
			$json['total'] = $products_count;
			$json['limit'] = $limit;
		} else {
			$json['success'] = false;
			$json['products'] = array();
			$json['category'] = $categories;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';
		} else {
			$this->response->setOutput(json_encode($json));
		}
	}
	public function home()
	{

		$this->checkPlugin();
		$this->load->language('extension/dashboard/customer');
		$this->load->model('catalog/product');
		$this->load->model('catalog/category');
		//$this->load->model('design/banner');
		//$this->load->model('extension/module');
		$this->load->model('design/layout');
		$this->load->model('customer/customer');




		$customer_total = $this->model_customer_customer->getTotalCustomers();

		if ($customer_total > 1000000000000) {
			$data['total'] = round($customer_total / 1000000000000, 1) . 'T';
		} elseif ($customer_total > 1000000000) {
			$data['total'] = round($customer_total / 1000000000, 1) . 'B';
		} elseif ($customer_total > 1000000) {
			$data['total'] = round($customer_total / 1000000, 1) . 'M';
		} elseif ($customer_total > 1000) {
			$data['total'] = round($customer_total / 1000, 1) . 'K';
		} else {
			$data['total'] = $customer_total;
		}

		return $this->load->view('extension/dashboard/customer_info', $data);



		//$json = array('success' => true, 'products' => array());

		//start Category
		$categories = array();
		$category_info = array();

		$categories = $this->model_catalog_category->getCategories(0);
		foreach ($categories as $category) {
			$children_data = array();
			$filter_data = array(
				'filter_category_id' => $category['category_id'],
				'filter_sub_category' => true
			);

			$category_info['menu'][] = array(
				'category_id' => $category['category_id'],
				'name' => $category['name'],
				'image' => $category['image'],
				'description' => $category['description'],
				'parent_id' => $category['parent_id'],
				'status' => $category['status']
				//'children'    => $children_data,
				//'href'        => $this->url->link('product/category', 'path=' . $category['category_id'])
			);
		}

		//End Category
		$data['modules'] = array();
		$data['modules'] = $category_info;
		//Banner start
		//$banner = array();
		//$banner = $this->model_design_banner->getBanner(7);
		//End Banner
		$data['modules']['special']['special'] = array();
		//$data['modules']['menu']['menu'] = array();
		$data['modules']['latest']['latest'] = array();
		$data['modules']['featured']['Featured'] = array();



		$layout_id = $this->model_design_layout->getLayout('common/home');


		$modules = $this->model_design_layout->getLayoutModules($layout_id, 'content_top');
		$modules1 = $this->model_design_layout->getLayoutModules($layout_id, 'content_bottom');
		$modules = array_merge($modules, $modules1);
		//echo "<pre>";print_r($modules);
		foreach ($modules as $module) {
			$part = explode('.', $module['code']);
			//print_r($part);

			if (isset($part[0]) && $this->config->get($part[0] . '_status')) {
				$module_data = $this->load->controller('extension/restapi/' . $part[0]);

				if ($module_data) {
					$data['modules'][] = $module_data;
				}
			}


			if (isset($part[1])) {
				$setting_info = $this->model_extension_module->getModule($part[1]);

				if ($setting_info && $setting_info['status']) {
					$output = $this->load->controller('extension/restapi/' . $part[0], $setting_info);

					if ($output) {
						$data['modules'][$part[0]] = $output;
					}
				}
			}
		}
		//echo "<pre>";print_r($data);

		//echo json_encode($data);

		if (count($data)) {
			$json['success'] = true;
			$json['home'] = $data;
		} else {
			$json['success'] = false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';
		} else {
			$this->response->setOutput(json_encode($json));
		}
	}

	public function updatedPreferredLanguge()
	{
		$this->checkPlugin();
		$customer_id = 0;
		$language_id = 0;

		if (isset($this->request->post['customer_id']) && $this->request->post['customer_id'] != "") {
			$customer_id = $this->request->post['customer_id'];
		}


		if (isset($this->request->post['language_id']) && $this->request->post['language_id'] != "") {
			$language_id = $this->request->post['language_id'];
		}

		$this->db->query("UPDATE `" . DB_PREFIX . "customer` SET language_id = '" . (int) $language_id . "'  WHERE customer_id = '" . (int) $customer_id . "'");

		// update query
		if ($customer_id) {
			$json['success'] = true;
			$json['customer_id'] = $customer_id;
		} else {
			$json['success'] = false;
		}
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';
		} else {
			$this->response->setOutput(json_encode($json));
		}
	}

	//update product status

	public function updatedProductStatus()
	{
		$this->checkPlugin();
		$product_id = 0;
		$status = 0;

		if (isset($this->request->post['product_id']) && $this->request->post['product_id'] != "") {
			$product_id = $this->request->post['product_id'];
		}


		if (isset($this->request->post['status']) && $this->request->post['status'] != "") {
			$status = $this->request->post['status'];
		}

		$this->db->query("UPDATE `" . DB_PREFIX . "product` SET status = '" . (int) $status . "'  WHERE product_id = '" . (int) $product_id . "'");

		// update query
		if ($product_id) {
			$json['success'] = true;
			$json['product_id'] = $product_id;
		} else {
			$json['success'] = false;
		}
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';
		} else {
			$this->response->setOutput(json_encode($json));
		}
	}





	//return






	public function getProduct()
	{
		$this->checkPlugin();
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}

		$this->load->model('catalog/product');
		$this->load->model('tool/image');
		$this->load->model('catalog/review');

		//$json = array('success' => true, 'products' => array());

		/*check category id parameter*/
		if (isset($this->request->get['p_id'])) {
			$p_id = $this->request->get['p_id'];
		} else {
			$p_id = 0;
		}
		$pro_data = array();
		if ($p_id != 0) {
			$product = $this->model_catalog_product->getProduct($p_id, 1, $store_id);
			//$product_info = $this->model_catalog_product->getProduct($product_id);
			if (isset($product['image'])) {
				$image = $product['image'];
			} else {
				$image = 'placeholder.png';
			}

			if (isset($product['special'])) {
				$special = $product['special'];
			} else {
				$special = false;
			}
			$product_de = array();
			if (!empty($product)) {
				$product_de['product'] = array(
					'id' => $product['product_id'],
					'name' => $product['name'],
					'description' => $product['description'],
					'meta_title' => $product['meta_title'],
					'meta_description' => $product['meta_description'],
					'meta_keyword' => $product['meta_keyword'],
					'tag' => $product['tag'],
					'model' => $product['model'],
					'sku' => $product['sku'],
					'upc' => $product['upc'],
					'ean' => $product['ean'],
					'jan' => $product['jan'],
					'isbn' => $product['isbn'],
					'mpn' => $product['mpn'],
					'location' => $product['location'],
					'quantity' => $product['quantity'],
					'stock_status' => $product['stock_status'],
					'manufacturer_id' => $product['manufacturer_id'],
					'manufacturer' => $product['manufacturer'],
					'reward' => $product['reward'],
					'points' => $product['points'],
					'date_available' => $product['date_available'],
					'tax_class_id' => $product['tax_class_id'],
					'weight_class_id' => $product['weight_class_id'],
					'length' => $product['length'],
					'width' => $product['width'],
					'height' => $product['height'],
					'length_class_id' => $product['length_class_id'],
					'subtract' => $product['subtract'],
					'reviews' => $product['reviews'],
					'minimum' => $product['minimum'],
					'sort_order' => $product['sort_order'],
					'status' => $product['status'],
					'type' => $product['type'],
					'date_added' => $product['date_added'],
					'date_modified' => $product['date_modified'],
					'viewed' => $product['viewed'],


					'price' => $product['price'],
					'sprice' => $product['sprice'],
					'zprice' => $product['zprice'],
					'href' => $this->url->link('product/product', 'product_id=' . $product['product_id']),
					'thumb' => $image,
					'special' => $special,
					'rating' => $product['rating']
				);
			}

			$tax_class = $this->getTaxClassapi($product['tax_class_id']);
			//print_r($tax_class);
			$tax_rates1 = $this->getTaxRulesapi($product['tax_class_id']);
			$tax_rates = array();
			foreach ($tax_rates1 as $tax_rate) {
				$tax_rates['value'][] = $this->getTaxRateapi($tax_rate['tax_rate_id']);
			}
			$product_de['taxs']['tax_class'] = array('tax_class_id' => 0);
			$product_de['taxs']['tax_rate'] = array('tax_rate_id' => 0);
			if (!empty($tax_class)) {
				$product_de['taxs']['tax_class'] = $tax_class; //array('tax_class'=>$tax_class,'tax_rate'=>$tax_rates);
			}
			if (!empty($tax_rates)) {
				$product_de['taxs']['tax_rate'] = $tax_rates;
			}



			$results_img = $this->model_catalog_product->getProductImages($p_id);
			$product_de['images'] = array();
			foreach ($results_img as $result) {
				$product_de['images'][] = array(
					'popup' => $this->model_tool_image->resize($result['image'], $this->config->get($this->config->get('config_theme') . '_image_popup_width'), $this->config->get($this->config->get('config_theme') . '_image_popup_height')),
					'thumb' => $this->model_tool_image->resize($result['image'], $this->config->get($this->config->get('config_theme') . '_image_additional_width'), $this->config->get($this->config->get('config_theme') . '_image_additional_height'))
				);
			}
			$product_de['discounts'] = array();
			$discounts = $this->model_catalog_product->getProductDiscounts($p_id);
			foreach ($discounts as $discount) {
				$product_de['discounts'][] = array(
					'quantity' => $discount['quantity'],
					'price' => $discount['price']
				);
			}
			$product_de['options'] = array();
			foreach ($this->model_catalog_product->getProductOptions($p_id) as $option) {
				$product_option_value_data = array();

				foreach ($option['product_option_value'] as $option_value) {
					if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
						if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float) $option_value['price']) {
							$price = $option_value['price'];
						} else {
							$price = false;
						}

						$product_option_value_data[] = array(
							'product_option_value_id' => $option_value['product_option_value_id'],
							'option_value_id' => $option_value['option_value_id'],
							'name' => $option_value['name'],
							'image' => $this->model_tool_image->resize($option_value['image'], 50, 50),
							'price' => $price,
							'price_prefix' => $option_value['price_prefix']
						);
					}
				}

				$product_de['options'][] = array(
					'product_option_id' => $option['product_option_id'],
					'product_option_value' => $product_option_value_data,
					'option_id' => $option['option_id'],
					'name' => $option['name'],
					'type' => $option['type'],
					'value' => $option['value'],
					'required' => $option['required']
				);
			}
			$reresults = $this->model_catalog_product->getProductRelated($p_id);
			$product_de['related'] = array();
			foreach ($reresults as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get($this->config->get('config_theme') . '_image_related_width'), $this->config->get($this->config->get('config_theme') . '_image_related_height'));
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get($this->config->get('config_theme') . '_image_related_width'), $this->config->get($this->config->get('config_theme') . '_image_related_height'));
				}

				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
					$price = $result['price'];
				} else {
					$price = false;
				}

				if ((float) $result['special']) {
					$special = $result['special'];
				} else {
					$special = false;
				}

				if ($this->config->get('config_tax')) {
					$tax = $result['special'];
				} else {
					$tax = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = (int) $result['rating'];
				} else {
					$rating = false;
				}

				$product_de['related'][] = array(
					'product_id' => $result['product_id'],
					'thumb' => $image,
					'name' => $result['name'],
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')) . '..',
					'price' => $price,
					'special' => $special,
					'tax' => $tax,
					'minimum' => $result['minimum'] > 0 ? $result['minimum'] : 1,
					'rating' => $rating,
					'href' => $this->url->link('product/product', 'product_id=' . $result['product_id'])
				);
			}

			$review_total = $this->model_catalog_review->getTotalReviewsByProductId($p_id);

			$results = $this->model_catalog_review->getReviewsByProductId($p_id, 5);
			$product_de['reviews'] = array();
			foreach ($results as $result) {
				$product_de['reviews'][] = array(
					'author' => $result['author'],
					'text' => nl2br($result['text']),
					'rating' => (int) $result['rating'],
					'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
				);
			}


		}
		if (count($product_de)) {
			$json['success'] = true;
			$json['product'] = $product_de;
		} else {
			$json['success'] = false;
		}
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';
		} else {
			$this->response->setOutput(json_encode($json));
		}
	} //seo
	public function getrelaProduct()
	{
		$this->checkPlugin();

		$this->load->model('catalog/product');

		//$json = array('success' => true, 'products' => array());

		/*check category id parameter*/
		if (isset($this->request->get['p_id'])) {
			$p_id = $this->request->get['p_id'];
		} else {
			$p_id = 0;
		}
		$pro_data = array();

		if ($p_id != 0) {
			$results = $this->model_catalog_product->getProductRelated($p_id);
			$this->load->model('tool/image');
			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));
				}

				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $result['price'];
				} else {
					$price = false;
				}

				if ((float) $result['special']) {
					$special = $result['special'];
				} else {
					$special = false;
				}

				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float) $result['special'] ? $result['special'] : $result['price']);
				} else {
					$tax = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = (int) $result['rating'];
				} else {
					$rating = false;
				}

				$pro_data[] = array(
					'product_id' => $result['product_id'],
					'thumb' => $image,
					'name' => $result['name'],
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
					'price' => $price,
					'special' => $special,
					'tax' => $tax,
					'minimum' => $result['minimum'] > 0 ? $result['minimum'] : 1,
					'rating' => $rating,
					'href' => $this->url->link('product/product', 'product_id=' . $result['product_id'])
				);
			}
		}
		if (count($pro_data)) {
			$json['success'] = true;
			$json['relatedproduct'] = $pro_data;
		} else {
			$json['success'] = false;
		}
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';
		} else {
			$this->response->setOutput(json_encode($json));
		}
	}


	public function getOptionbyid()
	{
		$this->checkPlugin();

		$this->load->model('catalog/product');

		/*check category id parameter*/
		if (isset($this->request->get['p_id'])) {
			$p_id = $this->request->get['p_id'];
		} else {
			$p_id = 0;
		}
		$sstatus = false;
		$optionsdata = array();
		if ($p_id != 0) {
			$product_info = $this->model_catalog_product->getProduct($p_id);
			foreach ($this->model_catalog_product->getProductOptions($this->request->get['p_id']) as $option) {
				$product_option_value_data = array();

				foreach ($option['product_option_value'] as $option_value) {
					if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
						if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float) $option_value['price']) {
							$price = $option_value['price'];
						} else {
							$price = false;
						}
						$product_option_value_data[] = array(
							'product_option_value_id' => $option_value['product_option_value_id'],
							'option_value_id' => $option_value['option_value_id'],
							'name' => $option_value['name'],
							'image' => $option_value['image'],
							'price' => $price,
							'price_prefix' => $option_value['price_prefix']
						);
					}
				}

				$optionsdata[] = array(
					'product_option_id' => $option['product_option_id'],
					'product_option_value' => $product_option_value_data,
					'option_id' => $option['option_id'],
					'name' => $option['name'],
					'type' => $option['type'],
					'value' => $option['value'],
					'required' => $option['required']
				);
				$sstatus = true;
			}
		} else {
			$optionsdata = 'Product id cant available';
		}
		$json['success'] = $sstatus;
		$json['products_option'] = $optionsdata;


		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';
		} else {
			$this->response->setOutput(json_encode($json));
		}
	} //seo

	public function uploadImage()
	{
		$data = json_decode(file_get_contents("php://input"), true); // collect input parameters and convert into readable format

		$fileName = $_FILES['sendimage']['name'];
		$tempPath = $_FILES['sendimage']['tmp_name'];
		$fileSize = $_FILES['sendimage']['size'];

		if (empty($fileName)) {
			$errorMSG = json_encode(array("message" => "please select image", "status" => false));
			echo $errorMSG;
		} else {
			$upload_path = DIR_IMAGE . 'catalog/demo/'; // set upload folder path 


			$fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION)); // get image extension
			$filePath = 'catalog/demo/' . $fileName;
			$url = HTTPS_SERVER . "image/" . $filePath;
			// valid image extensions
			$valid_extensions = array('jpeg', 'jpg', 'png', 'gif');

			// allow valid image file formats
			if (in_array($fileExt, $valid_extensions)) {
				//check file not exist our upload folder path
				if (!file_exists($upload_path . $fileName)) {
					// check file size '5MB'
					if ($fileSize < 20000000) {
						move_uploaded_file($tempPath, $upload_path . $fileName); // move file from system temporary path to our upload folder path 
					} else {
						$errorMSG = json_encode(array("message" => "Sorry, your file is too large, please upload 5 MB size", "status" => false));
						echo $errorMSG;
					}
				} else {
					$errorMSG = json_encode(array("message" => "Sorry, file already exists check upload folder", "status" => false));
					echo $errorMSG;
				}
			} else {
				$errorMSG = json_encode(array("message" => "Sorry, only JPG, JPEG, PNG & GIF files are allowed", "status" => false));
				echo $errorMSG;
			}
		}

		// if no error caused, continue ....
		if (!isset($errorMSG)) {


			echo json_encode(array("message" => "Image Uploaded Successfully", "status" => true, "path" => $filePath, "url" => $url));
		}


	}
	public function addProduct()
	{

		$this->checkPlugin();
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}
		$this->load->model('catalog/product');
		$json = file_get_contents('php://input');
		$data = json_decode($json, true);
		$data['product_store'] = array($store_id);
		if (!isset($data['type'])) {
			$data['type'] = '';
		}
		$product_id = $this->model_catalog_product->addProduct($data, $store_id);

		$json = array();
		if ($product_id > 0) {
			$json['success'] = true;
			$json['product_id'] = $product_id;
		} else {
			$json['success'] = false;
		}

		//$json['products_prodadditional'] = $add_prod_data;


		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';
		} else {
			$this->response->setOutput(json_encode($json));
		}

	}

	//edit-product

	public function editProduct()
	{

		$this->checkPlugin();
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}
		$this->load->model('catalog/product');
		$json = file_get_contents('php://input');

		$data = json_decode($json, true);
		// print_r($data);
		// die;
		$data['product_store'] = array($store_id);
		$product_id = $this->model_catalog_product->editProduct($data['product_id'], $data);

		if (!isset($data['type'])) {
			$data['type'] = '';
		}
		$json = array();
		if ($product_id > 0) {
			$json['success'] = true;
			$json['product_id'] = $product_id;
			$json['product_id'] = $product_id;
		} else {
			$json['success'] = false;
		}

		//$json['products_prodadditional'] = $add_prod_data;


		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';
		} else {
			$this->response->setOutput(json_encode($json));
		}

	}


	public function getStoreLatLong()
	{
		$this->checkPlugin();
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}
		$long = 0;
		$lat = 0;

		if (isset($this->request->post['latitude']) && $this->request->post['latitude'] != "") {
			$lat = $this->request->post['latitude'];
		}
		if (isset($this->request->post['longitude']) && $this->request->post['longitude'] != "") {
			$long = $this->request->post['longitude'];
		}
		$this->load->model('setting/setting');
		$storelatlong = $this->model_setting_setting->getSettingValue('config_geocode', $store_id);
		$latlong = $this->model_setting_setting->getSettingValue('config_geocode', $store_id);
		$latitude = $this->model_setting_setting->getSettingValue('config_geocode', $store_id);
		$longitude = $this->model_setting_setting->getSettingValue('config_geocode', $store_id);
		$name = $this->model_setting_setting->getSettingValue('config_owner', $store_id);
		$fulladdress = $this->model_setting_setting->getSettingValue('config_address', $store_id);
		//$storelatlong = preg_split("/[s,]+/", "storelatitude, storelongitude"); print_r($storelatlong); 
		list($latitude, $longitude) = explode(",", $storelatlong);
		if ($storelatlong) {
			$json['success'] = true;
			//$json['storelatlong'] 	= $storelatlong;
			$json['latitude'] = $latitude;
			$json['longitude'] = $longitude;
			$json['name'] = $name;
			$json['fullAddress'] = $fulladdress;


		} else {
			$json['success'] = false;
		}
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';
		} else {
			$this->response->setOutput(json_encode($json));
		}
	}


	public function salesReport()
	{
		$this->checkPlugin();
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}

		$this->load->model('account/order');
		$results = array();

		/*$formatter = new NumberFormatter('en_IN',  NumberFormatter::CURRENCY);
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						echo 'INDIA: ', $formatter->formatCurrency($results, 'INR'), PHP_EOL;*/


		$total_order_revenue = $this->model_account_order->getTotalOrdersRevenue($store_id);

		if ($total_order_revenue == null) {
			$total_order_revenue = 0;
		}
		$this->load->model('setting/setting');
		$currency_code = $this->model_setting_setting->getSettingValue('config_currency', $store_id);
		$formats = array("INR" => "en_IN", "AED" => "ar_AE");
		$formatter = new NumberFormatter($formats[$currency_code], NumberFormatter::CURRENCY);


		$monthtotal = $this->model_account_order->getTotalMonthRevenue($store_id);

		$currentdate = $this->model_account_order->getCurrentDateRevenue($store_id);

		if ($currentdate == null) {
			$currentdate = 0;
		}

		$json = array();

		$json['success'] = true;
		$json['total'] = $formatter->formatCurrency($total_order_revenue, $currency_code);
		$json['month'] = $formatter->formatCurrency($monthtotal, $currency_code);
		$json['today'] = $formatter->formatCurrency($currentdate, $currency_code);

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';
		} else {
			$this->response->setOutput(json_encode($json));


		}
	}




	//get validate location

	public function ValidateLocation()
	{
		$this->checkPlugin();
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}

		$long = 0;
		$lat = 0;

		if (isset($this->request->post['latitude']) && $this->request->post['latitude'] != "") {
			$lat = $this->request->post['latitude'];
		}
		if (isset($this->request->post['longitude']) && $this->request->post['longitude'] != "") {
			$long = $this->request->post['longitude'];
		}

		$this->load->model('setting/setting');
		$getApiValue = $this->model_setting_setting->getSettingValue('config_api', $store_id);
		$storelatlong = $this->model_setting_setting->getSettingValue('config_geocode', $store_id);


		if ($getApiValue == "api") {
			$distance = ($this->getDistanceByLatLang($storelatlong, $lat . ',' . $long));
			$distance = ceil(($distance['rows'][0]['elements'][0]['distance']['value']) / 1000);
		} else {
			$storelatlong = explode(",", $storelatlong);
			$storelat = $storelatlong[0];

			$storelong = $storelatlong[1];

			$distance = ceil($this->distance($storelat, $storelong, $lat, $long, "K"));



		}

		$storedistance = $this->model_setting_setting->getSettingValue('config_location_distance', $store_id);

		$serviceable = false;

		if ($storedistance >= $distance) {
			$serviceable = true;
		}

		$storeDeliveryCharges = $this->model_setting_setting->getSettingValue('config_delivery_charges', $store_id);

		//Get Shipping Charge basis on the config against store
		$shippingCharge = 0;
		$previous_shippingCharge = 0;
		$upperdistance = 0;
		if ($storeDeliveryCharges) {
			$deliveryCharges = explode(",", $storeDeliveryCharges);

			if (count($deliveryCharges)) {
				foreach ($deliveryCharges as $dc) {

					$distRangesArr = explode("=", $dc);

					$distRange = explode("-", $distRangesArr[0]);
					if (count($distRange)) {

						$perkmcharges = explode("/", $distRangesArr[1]);

						if (count($perkmcharges) > 1) {

							$remainingdistance = $distance - $upperdistance;
							$shippingCharge = $previous_shippingCharge + round($perkmcharges[0] * $remainingdistance);

						} else {
							$previous_shippingCharge = $distRangesArr[1];
							$upperdistance = $distRange[1];


							if ($distRange[0] <= $distance && $distance <= $distRange[1]) {
								$shippingCharge = $distRangesArr[1];
								break;
							}
						}
					}

				}
			}


		}


		if ($distance) {
			$json['success'] = true;
			$json['distance'] = $distance;
			$json['storedistance'] = $storedistance;
			$json['serviceable'] = $serviceable;
			$json['shipping_charge'] = $shippingCharge;

		} else {
			$json['success'] = false;
		}
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';
		} else {
			$this->response->setOutput(json_encode($json));
		}
	}


	public function ValidateLocationserviceable()
	{
		$this->checkPlugin();
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}

		$long = 0;
		$lat = 0;

		if (isset($this->request->post['latitude']) && $this->request->post['latitude'] != "") {
			$lat = $this->request->post['latitude'];
		}
		if (isset($this->request->post['longitude']) && $this->request->post['longitude'] != "") {
			$long = $this->request->post['longitude'];
		}

		$this->load->model('setting/setting');
		$getApiValue = $this->model_setting_setting->getSettingValue('config_api', $store_id);
		$storelatlong = $this->model_setting_setting->getSettingValue('config_geocode', $store_id);


		if ($getApiValue == "api") {
			$distance = ($this->getDistanceByLatLang($storelatlong, $lat . ',' . $long));
			$distance = ceil(($distance['rows'][0]['elements'][0]['distance']['value']) / 1000);
		} else {
			$storelatlong = explode(",", $storelatlong);
			$storelat = $storelatlong[0];

			$storelong = $storelatlong[1];

			$distance = ceil($this->distance($storelat, $storelong, $lat, $long, "K"));



		}

		$storedistance = $this->model_setting_setting->getSettingValue('config_location_distance', $store_id);

		$serviceable = false;

		if ($storedistance >= $distance) {
			$serviceable = true;
		}



		if ($distance) {
			$json['success'] = true;
			$json['distance'] = $distance;
			$json['storedistance'] = $storedistance;
			$json['serviceable'] = $serviceable;


		} else {
			$json['success'] = false;
		}
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';
		} else {
			$this->response->setOutput(json_encode($json));
		}
	}

	public function distance($lat1, $lon1, $lat2, $lon2, $unit)
	{
		if (($lat1 == $lat2) && ($lon1 == $lon2)) {
			return 0;
		} else {

			$theta = $lon1 - $lon2;
			$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
			$dist = acos($dist);
			$dist = rad2deg($dist);
			$miles = $dist * 60 * 1.1515;
			$unit = strtoupper($unit);

			if ($unit == "K") {
				return ($miles * 1.609344);
			} else if ($unit == "N") {
				return ($miles * 0.8684);
			} else {
				return $miles;
			}
		}
	}




	function getDistanceByLatLang($origins, $destinations)
	{


		$data = array(
			"origins" => $origins,
			"destinations" => $destinations,
			"mode" => "4w",
			"key" => "2d51d0eb8c0649cc9f09886772f20b39"
		);


		$url = "https://api.nextbillion.io/distancematrix/json?" . http_build_query($data);


		$crl = curl_init();
		curl_setopt($crl, CURLOPT_URL, $url);
		curl_setopt($crl, CURLOPT_FRESH_CONNECT, true);
		curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);

		$response = curl_exec($crl);

		if (!$response) {
			die('Error: "' . curl_error($crl) . '" - Code: ' . curl_errno($crl));
		}

		curl_close($crl);
		return json_decode($response, true);

	}


	public function getProdadditional()
	{
		$this->checkPlugin();

		$this->load->model('catalog/product');

		//$json = array('success' => true, 'products_prodadditional' => array());

		/*check category id parameter*/
		if (isset($this->request->get['p_id'])) {
			$p_id = $this->request->get['p_id'];
		} else {
			$p_id = 0;
		}
		$add_prod_data = array();

		if ($p_id != 0) {
			$product_info = $this->model_catalog_product->getProduct($p_id);
			/*start discount*/
			$discounts = $this->model_catalog_product->getProductDiscounts($p_id);
			$discountsdata = array();
			foreach ($discounts as $discount) {
				$discountsdata[] = array(
					'quantity' => $discount['quantity'],
					'price' => $discount['price']
				);
			}
			/*end discount*/

			/*start review*/
			$this->load->model('catalog/review');
			$results = $this->model_catalog_review->getReviewsByProductId($p_id);
			$reviewsdata = array();
			foreach ($results as $result) {
				$reviewsdata[] = array(
					'author' => $result['author'],
					'text' => nl2br($result['text']),
					'rating' => (int) $result['rating'],
					'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
				);
			}
			/*end review*/

			/*start additional image*/
			$add_imgs = $this->model_catalog_product->getProductImages($p_id);
			/*end additional image*/
			$add_prod_data = array('add_imgs' => $add_imgs, 'reviewsdata' => $reviewsdata, 'discountsdata' => $discountsdata);
		}
		if (count($add_prod_data)) {
			$json['success'] = true;
			$json['products_prodadditional'] = $add_prod_data;
		} else {
			$json['success'] = false;
		}

		//$json['products_prodadditional'] = $add_prod_data;


		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';
		} else {
			$this->response->setOutput(json_encode($json));
		}
	} //seo



	public function updateOrderComments()
	{
		$this->checkPlugin();
		$status = false;
		if (isset($this->request->post['order_id']) && isset($this->request->post['order_id'])) {
			$this->load->model('checkout/order');
			$product_total = $this->model_checkout_order->addOrderComments($this->request->post['order_id'], $this->request->post['comments']);
			$status = true;
		}

		$json['success'] = $status;

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}

	}

	public function updateShippingProvider()
	{
		$this->checkPlugin();
		$status = false;
		if (isset($this->request->post['order_id']) && isset($this->request->post['order_id'])) {
			$this->load->model('checkout/order');
			$shipping_provider = $this->model_checkout_order->updateShippingProvider($this->request->post['order_id'], $this->request->post['shippingProvider'], $this->request->post['deliveryType']);
			$status = true;
		}

		$json['success'] = $status;
		$json['message'] = "Updated Successfully";


		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}

	}


	public function updateOrderStatus()
	{


		$this->checkPlugin();
		$status = false;
		if (isset($this->request->post['order_id']) && isset($this->request->post['order_id'])) {
			$this->load->model('checkout/order');
			$product_total = $this->model_checkout_order->addOrderHistory($this->request->post['order_id'], $this->request->post['order_status_id'], $this->request->post['comments']);
			$status = true;
		}


		if (isset($this->request->post['shipping_order_id'])) {
			$this->load->model('checkout/order');

			$shipping_order_id = array('shipping_order_id' => $this->request->post['shipping_order_id']);

			$shippingjson = json_encode($shipping_order_id);
			$addOrderShipping = $this->model_checkout_order->addOrderShipping($this->request->post['order_id'], $shippingjson);
			//$json = array("shipping_order_id" => $shipping_order_id );
		}
		/*$results = array();
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						if(isset($json) && $json != ''){
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						$dataorder1 = $json;*/

		$json['success'] = $status;



		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}

	}





	public function getorderbycus()
	{

		$this->checkPlugin();
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}
		$order_status_id = '';

		if (isset($this->request->post['order_status_id'])) {

			$order_status_id = $this->request->post['order_status_id'];
			$order_status_id = $this->request->post['order_status_id'];
			if ((int) $order_status_id == 1) {
				$order_status_id = $order_status_id . ",19";
			}

		}
		// $shippingsubtype = '';
		// if (isset($this->request->post['shippingsubtype'])) {

		// 	$shippingsubtype = $this->request->post['shippingsubtype'];


		// }


		$orderData['orders'] = array();

		$this->load->model('account/order');

		/*check offset parameter*/
		if (isset($this->request->get['offset']) && $this->request->get['offset'] != "" && ctype_digit($this->request->get['offset'])) {
			$offset = $this->request->get['offset'];
		} else {
			$offset = 0;
		}

		/*check limit parameter*/
		if (isset($this->request->get['limit']) && $this->request->get['limit'] != "" && ctype_digit($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = 10000;
		}
		if (isset($this->request->post['customer_id']) && $this->request->post['customer_id'] != "") {
			$customer_id = $this->request->post['customer_id'];
		} else {
			$customer_id = 0;
		}
		if (isset($this->request->post['shippingtype']) && $this->request->post['shippingtype'] != "") {
			$shippingtype = $this->request->post['shippingtype'];
		} else {
			$shippingtype = 0;
		}


		/*get all orders of user*/
		$results = array();
		//if($customer_id != 0){format(
		$results = $this->getOrdersapi($customer_id, $offset, $limit, $order_status_id, $store_id, $shippingtype);


		$orders_count = $this->getOrdersTotalapi($customer_id, $order_status_id, $store_id);
		$orders = array();
		//print_r($results);exit;
		if (count($results)) {
			foreach ($results as $result) {
				$products = $this->getOrderProductsData($result, $store_id);
				$product_total = $this->model_account_order->getTotalOrderProductsByOrderId($result['order_id']);
				$voucher_total = $this->model_account_order->getTotalOrderVouchersByOrderId($result['order_id']);
				$order_history = $this->model_account_order->getOrderHistories($result['order_id']);
				$orders[] = array(
					'order_id' => $result['order_id'],
					'order_status_id' => $result['order_status_id'],
					'name' => $result['firstname'] . ' ' . $result['lastname'],
					'firstname' => $result['firstname'],
					'lastname' => $result['lastname'],
					'telephone' => $result['telephone'],
					'store_id' => $store_id,
					'customer_id' => $result['customer_id'],
					'comment' => $result['comment'],
					'status' => $result['status'],
					'date_added' => $result['date_added'],
					'products' => ($product_total + $voucher_total),
					'total' => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
					'final_amount' => $result['total'],


					'currency_code' => $result['currency_code'],
					'currency_value' => $result['currency_value'],
					'products' => $products,
					'payment_method' => $result['payment_method'],
					'order_history' => $order_history,
					'shipping_address' => $result['shipping_address'],
					'deliveryType' => $result['deliveryType'],
					'deliveryMethod' => $result['deliveryMethod'],
					'bid_id' => $result['bid_id'],
					'source' => $result['source'],
					'source_value' => $result['source_value'],
					'cooking_instructions' => $result['cooking_instructions'],
					'receiver_name' => $result['receiver_name'],
					'receiver_phone' => $result['receiver_phone'],
					'type' => $result['type'],
					'tableNumber' => $result['table_number'],
					'serviceChargeType' => $result['service_charge_type'],
					'comments' => $result['order_comments'],
					'shippingtype' => $result['shippingtype'],
					'shippingsubtype' => $result['shippingsubtype'],
					'shippingProvider' => $result['shippingProvider'],
					'deliveryMethod' => $result['deliveryMethod'],
					'custom_field' => $result['custom_field']


				);
			}

			$json['success'] = true;
			$json['orders'] = $orders;
			$json['total'] = $orders_count;
			$json['offset'] = $offset;
			$json['limit'] = $limit;
		} else {
			$json['success'] = false;
			$json['orders'] = array();
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}

	// Previous Order Count


	//getorders based on store wise

	public function getOrdersByMerchant()
	{

		$this->checkPlugin();
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}

		$this->load->language('checkout/cart');
		$inputjson = file_get_contents('php://input');
		$data = json_decode($inputjson, true);

		if (isset($data['customer_id'])) {
			$customer_id = (int) $data['customer_id'];
		} else {
			$customer_id = 0;
		}
		if (isset($data['offset'])) {
			$offset = (int) $data['offset'];
		} else {
			$offset = 0;
		}
		if (isset($data['limit'])) {
			$limit = (int) $data['limit'];
		} else {
			$limit = 0;
		}

		$order_status_ids = $data['order_status_ids'];

		// print_r($data);
		// die;

		// if (isset($this->request->post['order_status_id'])) {

		// 	$order_status_id = $this->request->post['order_status_id'];
		// 	$order_status_id = $this->request->post['order_status_id'];
		// 	if ((int) $order_status_id == 1) {
		// 		$order_status_id = $order_status_id . ",19";
		// 	}

		// }
		// $customer_ids = $data['customer_ids'];


		$stores = $data['store_ids'];

		// print_r($stores);
		// die;

		$orderData['orders'] = array();

		$this->load->model('account/order');


		// print_r($store_id);
		// die;

		/*get all orders of user*/
		$results = array();
		//if($customer_id != 0){format(
		$results = $this->getMerchantOrdersapi($customer_id, $offset, $limit, $order_status_ids, $store_id, $stores);
		// print_r($results);
		// die;

		$orders_count = $this->getOrdersTotalapiMerchant($customer_id, $order_status_ids, $stores);
		$orders = array();
		//print_r($results);exit;
		if (count($results)) {
			foreach ($results as $result) {
				$products = $this->getOrderProductsData($result, $store_id);
				$product_total = $this->model_account_order->getTotalOrderProductsByOrderId($result['order_id']);
				$voucher_total = $this->model_account_order->getTotalOrderVouchersByOrderId($result['order_id']);
				$order_history = $this->model_account_order->getOrderHistories($result['order_id']);
				$orders[] = array(
					'order_id' => $result['order_id'],
					'order_status_id' => $result['order_status_id'],
					'name' => $result['firstname'] . ' ' . $result['lastname'],
					'firstname' => $result['firstname'],
					'lastname' => $result['lastname'],
					'store_id' => $result['store_id'],
					'store_name' => $result['store_name'],
					'telephone' => $result['telephone'],
					'comment' => $result['comment'],
					'status' => $result['status'],
					'date_added' => $result['date_added'],
					'products' => ($product_total + $voucher_total),
					'total' => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
					'final_amount' => $result['total'],


					'currency_code' => $result['currency_code'],
					'currency_value' => $result['currency_value'],
					'products' => $products,
					'payment_method' => $result['payment_method'],
					'order_history' => $order_history,
					'shipping_address' => $result['shipping_address'],
					'deliveryType' => $result['deliveryType'],
					'deliveryMethod' => $result['deliveryMethod'],
					'bid_id' => $result['bid_id'],
					'source' => $result['source'],
					'source_value' => $result['source_value'],
					'cooking_instructions' => $result['cooking_instructions'],
					'receiver_name' => $result['receiver_name'],
					'receiver_phone' => $result['receiver_phone'],
					'type' => $result['type'],
					'tableNumber' => $result['table_number'],
					'serviceChargeType' => $result['service_charge_type'],
					'comments' => $result['order_comments'],
					'shippingtype' => $result['shippingtype'],
					'shippingsubtype' => $result['shippingsubtype'],
					'shippingProvider' => $result['shippingProvider'],
					'deliveryMethod' => $result['deliveryMethod'],
					'custom_field' => $result['custom_field']


				);
			}

			$json['success'] = true;
			$json['orders'] = $orders;
			$json['total'] = $orders_count;
			$json['offset'] = $offset;
			$json['limit'] = $limit;
		} else {
			$json['success'] = false;
			$json['orders'] = array();
		}


		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}

	public function getOrdersCountByCustomer()
	{

		$this->checkPlugin();

		$orderData['orders'] = array();

		$this->load->model('account/order');


		if (isset($this->request->post['customer_id']) && $this->request->post['customer_id'] != "") {
			$customer_id = $this->request->post['customer_id'];
		} else {

			$customer_id = -1;

		}

		if (isset($this->request->post['order_status_id']) && $this->request->post['customer_id'] != "") {
			$order_status_id = $this->request->post['order_status_id'];
		} else {
			$order_status_id = '';
		}

		/*get all orders of user*/
		$results = array();
		$count = "";
		if ($customer_id && $customer_id != -1) {
			$results = $this->getOrdersCountApi($customer_id, $order_status_id);
		} else {
			$count = "Please provide valid customer_id.";
		}



		if (count($results)) {

			$json['success'] = true;
			$json['count'] = $results[0]['count'];
		} else {
			$json['success'] = false;
			$json['count'] = $count;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}






	//seo
	public function getorderbydeliveryboy()
	{

		$this->checkPlugin();
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}
		$orderData['orders'] = array();

		$this->load->model('account/order');

		/*check offset parameter*/
		if (isset($this->request->get['offset']) && $this->request->get['offset'] != "" && ctype_digit($this->request->get['offset'])) {
			$offset = $this->request->get['offset'];
		} else {
			$offset = 0;
		}

		/*check limit parameter*/
		if (isset($this->request->get['limit']) && $this->request->get['limit'] != "" && ctype_digit($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = 10000;
		}
		if (isset($this->request->post['customer_id']) && $this->request->post['customer_id'] != "") {
			$customer_id = $this->request->post['customer_id'];
		} else {
			$customer_id = 0;
		}

		/*get all orders of user*/
		$results = array();
		if ($customer_id != 0) {
			$results = $this->getOrdersapi($customer_id, $offset, $limit, $store_id);
		}

		$orders = array();

		if (count($results)) {
			foreach ($results as $result) {

				$product_total = $this->model_account_order->getTotalOrderProductsByOrderId($result['order_id']);
				$voucher_total = $this->model_account_order->getTotalOrderVouchersByOrderId($result['order_id']);

				$orders[] = array(
					'order_id' => $result['order_id'],
					'name' => $result['firstname'] . ' ' . $result['lastname'],
					'status' => $result['status'],
					'date_added' => $result['date_added'],
					'products' => ($product_total + $voucher_total),
					'total' => $result['total'],
					'currency_code' => $result['currency_code'],
					'currency_value' => $result['currency_value'],
				);
			}

			$json['success'] = true;
			$json['orders'] = $orders;
		} else {
			$json['success'] = false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	} //seo


	public function getInvoice()
	{
		$this->checkPlugin();
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}

		$orderbyid = array();

		$this->load->model('sale/order');

		$this->load->model('setting/setting');

		if (isset($this->request->post['order_id']) && $this->request->post['order_id'] != "") {
			$order_id = $this->request->post['order_id'];
		} else {
			$order_id = 0;
		}


		$json['orders'] = array();
		if ($order_id != 0) {

			$order_info = $this->model_sale_order->getOrder($order_id);
			if ($order_info) {
				$store_info = $this->model_setting_setting->getSetting('config', $order_info['store_id']);

				if ($store_info) {
					$store_address = $store_info['config_address'];
					$store_email = $store_info['config_email'];
					$store_telephone = $store_info['config_telephone'];
					$store_fax = $store_info['config_fax'];
				} else {
					$store_address = $this->config->get('config_address');
					$store_email = $this->config->get('config_email');
					$store_telephone = $this->config->get('config_telephone');
					$store_fax = $this->config->get('config_fax');
				}

				if ($order_info['invoice_no']) {
					$invoice_no = $order_info['invoice_prefix'] . $order_info['invoice_no'];
				} else {
					$invoice_no = '';
				}

				if ($order_info['payment_address_format']) {
					$format = $order_info['payment_address_format'];
				} else {
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}

				$find = array(
					'{firstname}',
					'{lastname}',
					'{company}',
					'{address_1}',
					'{address_2}',
					'{city}',
					'{postcode}',
					'{zone}',
					'{zone_code}',
					'{country}'
				);

				$replace = array(
					'firstname' => $order_info['payment_firstname'],
					'lastname' => $order_info['payment_lastname'],
					'company' => $order_info['payment_company'],
					'address_1' => $order_info['payment_address_1'],
					'address_2' => $order_info['payment_address_2'],
					'city' => $order_info['payment_city'],
					'postcode' => $order_info['payment_postcode'],
					'zone' => $order_info['payment_zone'],
					'zone_code' => $order_info['payment_zone_code'],
					'country' => $order_info['payment_country']
				);

				$payment_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

				if ($order_info['shipping_address_format']) {
					$format = $order_info['shipping_address_format'];
				} else {
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}

				$find = array(
					'{firstname}',
					'{lastname}',
					'{company}',
					'{address_1}',
					'{address_2}',
					'{city}',
					'{postcode}',
					'{zone}',
					'{zone_code}',
					'{country}'
				);

				$replace = array(
					'firstname' => $order_info['shipping_firstname'],
					'lastname' => $order_info['shipping_lastname'],
					'company' => $order_info['shipping_company'],
					'address_1' => $order_info['shipping_address_1'],
					'address_2' => $order_info['shipping_address_2'],
					'city' => $order_info['shipping_city'],
					'postcode' => $order_info['shipping_postcode'],
					'zone' => $order_info['shipping_zone'],
					'zone_code' => $order_info['shipping_zone_code'],
					'country' => $order_info['shipping_country']
				);

				$shipping_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

				$this->load->model('tool/upload');

				$product_data = array();

				$products = $this->model_sale_order->getOrderProducts($order_id);

				foreach ($products as $product) {
					$option_data = array();

					$options = $this->model_sale_order->getOrderOptions($order_id, $product['order_product_id']);

					foreach ($options as $option) {
						if ($option['type'] != 'file') {
							$value = $option['value'];
						} else {
							$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

							if ($upload_info) {
								$value = $upload_info['name'];
							} else {
								$value = '';
							}
						}

						$option_data[] = array(
							'name' => $option['name'],
							'value' => $value
						);
					}

					$product_data[] = array(
						'name' => $product['name'],
						'model' => $product['model'],
						'option' => $option_data,
						'quantity' => $product['quantity'],
						'price' => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
						'total' => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value'])
					);
				}

				$voucher_data = array();

				$vouchers = $this->model_sale_order->getOrderVouchers($order_id);

				foreach ($vouchers as $voucher) {
					$voucher_data[] = array(
						'description' => $voucher['description'],
						'amount' => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value'])
					);
				}

				$total_data = array();

				$totals = $this->model_sale_order->getOrderTotals($order_id);

				foreach ($totals as $total) {
					$total_data[] = array(
						'title' => $total['title'],
						'text' => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value'])
					);
				}

				$json['orders'][] = array(
					'order_id' => $order_id,
					'invoice_no' => $invoice_no,
					'date_added' => date($this->language->get('date_format_short'), strtotime($order_info['date_added'])),
					'store_name' => $order_info['store_name'],
					'store_url' => rtrim($order_info['store_url'], '/'),
					'store_address' => nl2br($store_address),
					'store_email' => $store_email,
					'store_telephone' => $store_telephone,
					'store_fax' => $store_fax,
					'email' => $order_info['email'],
					'telephone' => $order_info['telephone'],
					'shipping_address' => $shipping_address,
					'shipping_method' => $order_info['shipping_method'],
					'payment_address' => $payment_address,
					'payment_method' => $order_info['payment_method'],
					'product' => $product_data,
					'voucher' => $voucher_data,
					'total' => $total_data,
					'comment' => nl2br($order_info['comment'])
				);
			}


		}
		if (count($json['orders']) > 0) {
			$json['success'] = true;

		} else {
			$json['success'] = false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}

	}
	public function getorderbyid()
	{

		$this->checkPlugin();
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}

		$orderbyid = array();

		$this->load->model('account/order');
		$this->load->model('tool/upload');

		if (isset($this->request->post['order_id']) && $this->request->post['order_id'] != "") {
			$order_id = $this->request->post['order_id'];
		} else {
			$order_id = 0;
		}

		$orderfull_data = array();
		if ($order_id != 0) {
			$order_info = array();

			$order_info = $this->getOrderidapi($order_id, $store_id);

			$products = array();
			if ($order_info['order_id'] === $order_id || $order_info['store_id'] === $store_id) {
				$products = $this->model_account_order->getOrderProducts($order_id);
			} else {
				return false;
			}


			if ($products) {

			} else {
				$productsdata = array();
			}
			foreach ($products as $product) {
				$option_data = array();

				$this->load->model('catalog/product');

				$product_info = $this->model_catalog_product->getProduct($product['product_id'], 1, $store_id);

				if ($product_info) {
					$reorder = $this->url->link('account/order/reorder', 'order_id=' . $order_id . '&order_product_id=' . $product['order_product_id'], 'SSL');
				} else {
					$reorder = '';
				}

				$test1 = html_entity_decode($product['attribute']);

				$dartrr = trim($test1, '"');

				$options = $this->model_account_order->getOrderOptions($order_id, $product['order_product_id']);
				// print_r($product['tax']);
				// die;

				foreach ($options as $option) {
					if ($option['type'] != 'file') {
						$value = $option['value'];
					} else {
						$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

						if ($upload_info) {
							$file = DIR_UPLOAD . $upload_info['filename'];
							$mask = basename($upload_info['name']);
							if (is_file($file)) {
								copy($file, DIR_IMAGE . $mask);



								$value = HTTPS_SERVER . 'image/' . $mask;



							} else {
								$value = $upload_info['name'];
							}
						} else {
							$value = '';
						}
					}

					$option_data[] = array(
						'type' => $option['type'],
						'name' => $option['name'],
						'price' => $option['price'],
						'value' => $value,
						'price' => $option['price'],
						'order_option_id' => $option['order_option_id'],
						'order_product_id' => $option['order_product_id'],
						'product_option_id' => $option['product_option_id'],
						'product_option_value_id' => $option['product_option_value_id'],
						'pos_option_value_id' => $option['pos_option_value_id'],
						'pos_option_id' => $option['pos_option_id'],
					);
				}



				// print_r($product['tax']);
				// die;
				$productsdata[] = array(
					'product_id' => $product['product_id'],
					'order_product_id' => $product['order_product_id'],
					'order_id' => $order_id,
					'tax' => $product['tax'],
					'name' => $product['name'],
					'pos_product_id' => $product['pos_product_id'],
					'attribute' => json_decode($dartrr),
					'model' => $product['model'],
					'option' => $option_data,
					'quantity' => $product['quantity'],
					'price' => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
					'total' => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value'])
				);
			}
			// print_r($productsdata);die;
			$vouchersdata = array();
			$vouchers = $this->model_account_order->getOrderVouchers($order_id);
			foreach ($vouchers as $voucher) {
				$vouchersdata[] = array(
					'description' => $voucher['description'],
					'amount' => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value'])
				);
			}
			$totalsdata = array();
			$totals = $this->model_account_order->getOrderTotals($order_id);
			foreach ($totals as $total) {
				$totalsdata[] = array(
					'title' => $total['title'],
					'text' => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
				);
			}
			$historiesdata = array();
			$his_data = $this->model_account_order->getOrderHistories($this->request->post['order_id']);
			foreach ($his_data as $result) {
				$historiesdata[] = array(
					'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'comment' => $result['comment'],
					'status' => $result['status'],
					'notify' => $result['notify']
					//'comment'    => $result['notify'] ? nl2br($result['comment']) : ''
				);

			}

			$orderfull_data = array('order_info' => $order_info, 'products' => $productsdata, 'vouchersdata' => $vouchersdata, 'totals' => $totals, 'historiesdata' => $historiesdata, 'totalsdata' => $totalsdata);
		}
		if (count($orderfull_data)) {
			$json['success'] = true;
			$json['orders'] = $orderfull_data;
		} else {
			$json['success'] = false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	} //seo

	//Re Order - all Products
	public function reorder()
	{
		$this->checkPlugin();
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}

		//Inputs
		if (isset($this->request->post['order_id']) && $this->request->post['order_id'] != "") {
			$order_id = $this->request->post['order_id'];
		} else {
			$order_id = 0;
		}

		if (isset($this->request->post['customer_id']) && $this->request->post['customer_id'] != "") {
			$customer_id = $this->request->post['customer_id'];
		} else {
			$customer_id = 0;
		}

		$products_not_exists = array();

		if ($customer_id != 0 && $order_id != 0) {

			$this->load->model('account/order');
			$this->load->model('catalog/product');

			$cart = array();


			$this->db->query("UPDATE " . DB_PREFIX . "customer SET cart = '" . serialize($cart) . "' WHERE customer_id = '" . (int) $customer_id . "'");

			//Get products under order_id
			$products = $this->model_account_order->getOrderProducts($order_id, $store_id);

			foreach ($products as $product) {
				$product_info = $this->model_catalog_product->getProduct($product['product_id'], 1, $store_id);
				if ($product_info) {
					$option_data = array();
					//Get options of product
					$options = $this->model_account_order->getOrderOptions($order_id, $product['product_id']);
					foreach ($options as $order_option) {
						if ($order_option['type'] == 'select' || $order_option['type'] == 'radio' || $order_option['type'] == 'image') {
							$option_data[$order_option['product_option_id']] = $order_option['product_option_value_id'];
						} elseif ($order_option['type'] == 'checkbox') {
							$option_data[$order_option['product_option_id']][] = $order_option['product_option_value_id'];
						} elseif ($order_option['type'] == 'text' || $order_option['type'] == 'textarea' || $order_option['type'] == 'date' || $order_option['type'] == 'datetime' || $order_option['type'] == 'time') {
							$option_data[$order_option['product_option_id']] = $order_option['value'];
						} elseif ($order_option['type'] == 'file') {
							$option_data[$order_option['product_option_id']] = $this->encryption->encrypt($this->config->get('config_encryption'), $order_option['value']);
						}
					}

					//Add to cart
					$results = $this->cartnewadd($product['product_id'], $customer_id, $product['quantity'], $option_data, 0);

					$status = true;

				} else {
					//Product not exist
					array_push($products_not_exists, $product['product_id']);
				}

			}
		} else {
			//Please provide order_id and customer_id
			$status = false;
		}

		//Response
		if (count($results)) {
			$json['success'] = $status;
			$json['cartproduct'] = $results;
		} else {
			$json['success'] = false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}

	}


	public function getOrderProductsData($order_info, $store_id)
	{

		$order_id = $order_info['order_id'];

		$this->load->model('tool/upload');
		$products = $this->model_account_order->getOrderProducts($order_id);
		if ($products) {

		} else {
			return array();
		}
		foreach ($products as $product) {
			$option_data = array();

			$options = $this->model_account_order->getOrderOptions($order_id, $product['order_product_id']);

			foreach ($options as $option) {
				if ($option['type'] != 'file') {
					$value = $option['value'];
				} else {
					$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);



					if ($upload_info) {
						$file = DIR_UPLOAD . $upload_info['filename'];
						$mask = basename($upload_info['name']);
						if (is_file($file)) {
							copy($file, DIR_IMAGE . $upload_info['filename']);
							$this->load->model('tool/image');


							$value = $this->model_tool_image->resize($upload_info['filename'], 400, 400);




						} else {
							$value = $upload_info['name'];
						}


					} else {
						$value = '';
					}
				}

				$option_data[] = array(
					'name' => $option['name'],
					'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
				);
			}
			$this->load->model('catalog/product');

			$product_info = $this->model_catalog_product->getProduct($product['product_id'], 1, $store_id);

			if ($product_info) {
				$reorder = $this->url->link('account/order/reorder', 'order_id=' . $order_id . '&order_product_id=' . $product['order_product_id'], 'SSL');
			} else {
				$reorder = '';
			}

			$productsdata[] = array(
				'name' => $product['name'],
				'model' => $product['model'],
				'option' => $option_data,
				'quantity' => $product['quantity'],
				'price' => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
				'total' => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value'])
			);
		}

		return $productsdata;

	}
	public function getorderproduct()
	{

		$this->checkPlugin();
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}

		$orderbyid = array();

		$this->load->model('account/order');
		$this->load->model('tool/upload');

		if (isset($this->request->post['order_id']) && $this->request->post['order_id'] != "") {
			$order_id = $this->request->post['order_id'];
		} else {
			$order_id = 0;
		}

		$orderfull_data = array();
		if ($order_id != 0) {
			$order_info = array();
			$order_info = $this->getOrderidapi($order_id);

			$products = array();
			$products = $this->model_account_order->getOrderProducts($order_id);

			if ($products) {

			} else {
				$productsdata = array();
			}
			foreach ($products as $product) {
				$option_data = array();

				$options = $this->model_account_order->getOrderOptions($order_id, $product['order_product_id']);

				foreach ($options as $option) {
					if ($option['type'] != 'file') {
						$value = $option['value'];
					} else {
						$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

						if ($upload_info) {
							$file = DIR_UPLOAD . $upload_info['filename'];
							$mask = basename($upload_info['name']);
							if (is_file($file)) {
								copy($file, DIR_IMAGE . $mask);



								$value = HTTPS_SERVER . 'image/' . $mask;



							} else {
								$value = $upload_info['name'];
							}
						} else {
							$value = '';
						}
					}

					$option_data[] = array(
						'type' => $option['type'],
						'name' => $option['name'],
						'value' => $value,
						'order_option_id' => $option['order_option_id'],
						'order_product_id' => $option['order_product_id'],
						'product_option_id' => $option['product_option_id'],
						'product_option_value_id' => $option['product_option_value_id'],
					);
				}
				$this->load->model('catalog/product');

				$product_info = $this->model_catalog_product->getProduct($product['product_id'], 1, $store_id);

				if ($product_info) {
					$reorder = $this->url->link('account/order/reorder', 'order_id=' . $order_id . '&order_product_id=' . $product['order_product_id'], 'SSL');
				} else {
					$reorder = '';
				}


				$productsdata[] = array(
					'product_id' => $product['product_id'],
					'order_product_id' => $product['order_product_id'],
					'order_id' => $order_id,
					'tax' => $product['tax'],
					'name' => $product['name'],
					'model' => $product['model'],
					'option' => $option_data,
					'quantity' => $product['quantity'],
					'price' => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
					'total' => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value'])
				);
			}
			$vouchersdata = array();
			$vouchers = $this->model_account_order->getOrderVouchers($order_id);
			foreach ($vouchers as $voucher) {
				$vouchersdata[] = array(
					'description' => $voucher['description'],
					'amount' => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value'])
				);
			}
			$totalsdata = array();
			$totals = $this->model_account_order->getOrderTotals($order_id);
			foreach ($totals as $total) {
				$totalsdata[] = array(
					'title' => $total['title'],
					'text' => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
				);
			}
			$historiesdata = array();
			$his_data = $this->model_account_order->getOrderHistories($this->request->post['order_id']);
			foreach ($his_data as $result) {
				$historiesdata[] = array(
					'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'comment' => $result['comment'],
					'status' => $result['status'],
					'notify' => $result['notify']
					//'comment'    => $result['notify'] ? nl2br($result['comment']) : ''
				);

			}

			$orderfull_data = array('order_info' => $order_info, 'products' => $productsdata, 'vouchersdata' => $vouchersdata, 'totals' => $totals, 'historiesdata' => $historiesdata, 'totalsdata' => $totalsdata);
		}
		if (count($orderfull_data)) {
			$json['success'] = true;
			$json['orders'] = $orderfull_data;
		} else {
			$json['success'] = false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	} //seo
	public function success()
	{

		$this->checkPlugin();
		$json['success'] = true;

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	} //seo
	public function failure()
	{

		$this->checkPlugin();
		$order_id = $this->request->post['order_id'];
		$order_status_id = $this->request->post['order_status_id'];
		$json['success'] = false;
		if (isset($order_id) && $order_id != '' && isset($order_status_id) && $order_status_id != '') {
			$this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int) $order_status_id . "', date_modified = NOW() WHERE order_id = '" . (int) $order_id . "'");
			$json['success'] = true;
		}


		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	} //seo

	public function gettotalbyid()
	{

		$this->checkPlugin();

		$orderbyid = array();

		$this->load->model('account/order');
		$this->load->model('tool/upload');

		if (isset($this->request->post['order_id']) && $this->request->post['order_id'] != "") {
			$order_id = $this->request->post['order_id'];
		} else {
			$order_id = 0;
		}
		$totalsdata = array();
		if ($order_id != 0) {
			$order_info = array();
			$order_info = $this->model_account_order->getOrder($order_id);
			$totalsdata = array();
			$totals = $this->model_account_order->getOrderTotals($order_id);
			foreach ($totals as $total) {
				$totalsdata[] = array(
					'title' => $total['title'],
					'text' => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
				);
			}
		}

		if (count($totalsdata)) {
			$json['success'] = true;
			$json['totalsdata'] = $totalsdata;
		} else {
			$json['success'] = false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	} //seo
	public function getCategories()
	{

		$this->checkPlugin();
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}
		$status = 1;
		if (isset($this->request->get['status']) && $this->request->get['status'] != "") {
			$status = $this->request->get['status'];
		}
		if (isset($this->request->get['cate_id']) && $this->request->get['cate_id'] != "") {
			$cate_id = $this->request->get['cate_id'];
		}
		if (isset($this->request->get['language_id'])) {
			$language_id = $this->request->get['language_id'];
		} else {
			$language_id = 1;

		}
		$this->load->model('catalog/category');
		$this->load->model('localisation/language');
		$this->load->model('setting/setting');
		$category_info = array();
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'] - 1;
		} else {
			$page = 0;
		}
		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = 15;
		}
		$category_total = $this->model_catalog_category->getTotalCategoriesByCategoryId($parent_id = 0, $store_id, $status);

		// $product_total = $this->model_catalog_category->getTotalproductsByCategoryId($store_id, $category_info['category_id']);
		if (isset($cate_id) && $cate_id != '') {
			$category_info = $this->model_catalog_category->getCategory($cate_id, $store_id, $language_id);
		} else {
			$data['categories'] = array();
			$categories = $this->model_catalog_category->getCategories($limit, $page, 0, $this->request->get['language_id'], $store_id, $status);
			foreach ($categories as $category) {

				$children_data = array();
				$filter_data = array(
					'filter_category_id' => $category['category_id'],
					'filter_sub_category' => true
				);


				$this->load->model('tool/image');

				if (isset($category['image']) && is_file(DIR_IMAGE . $category['image'])) {
					$category['image_path'] = $category['image'];
					$category['image'] = $this->model_tool_image->resize($category['image'], 200, 200);

				} else {
					$category['image'] = "";
					$category['image_path'] = "";
				}


				$category_description = $this->model_catalog_category->getCategoryDescriptions($category['category_id']);


				$category_info[] = array(
					'category_id' => $category['category_id'],
					'name' => $category['name'],
					'image' => $category['image'],
					'image_path' => $category['image_path'],
					'description' => $category['description'],
					'parent_id' => $category['parent_id'],
					'status' => $category['status'],
					'category_description' => $category_description
					//'children'    => $children_data,
					//'href'        => $this->url->link('product/category', 'path=' . $category['category_id'])
				);
			}
		}


		if (count($category_info)) {
			$json['success'] = true;
			$json['category_info'] = $category_info;
			$json['total'] = $category_total;
			$json['limit'] = $limit;
		} else {
			$json['success'] = false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	} //seo


	//seo
	public function getchildCategories()
	{

		$this->checkPlugin();
		if (isset($this->request->get['cate_id']) && $this->request->get['cate_id'] != "") {
			$cate_id = $this->request->get['cate_id'];
		}
		$this->load->model('catalog/category');
		$children_data = array();
		$category_info = array();
		if ($cate_id) {
			$children = $this->model_catalog_category->getCategories($cate_id);
			foreach ($children as $child) {
				$filter_data = array('filter_category_id' => $child['category_id'], 'filter_sub_category' => true);

				$category_info[] = array(
					'category_id' => $child['category_id'],
					'name' => $child['name'],
					'image' => $child['image'],
					'description' => $child['description'],
					'parent_id' => $child['parent_id'],
					'status' => $child['status'],
				);
			}
		}
		if (count($category_info)) {
			$json['success'] = true;
			$json['category_info'] = $category_info;
		} else {
			$json['success'] = false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	} //seo
	public function getbannerbyname()
	{
		$this->checkPlugin();

		$results = 'not exist banner';
		$sstatus = false;
		if (isset($this->request->get['bannername']) && $this->request->get['bannername'] != "") {
			$bannername = $this->request->get['bannername'];
			$results = $this->getBannerapi($bannername);
			$sstatus = true;
		}
		if (count($results)) {
			$json['success'] = $sstatus;
			$json['banners'] = $results;
		} else {
			$json['success'] = false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	} //seo

	public function getmanufacture()
	{

		$this->checkPlugin();
		$this->load->model('catalog/manufacturer');
		$results = $this->model_catalog_manufacturer->getManufacturers();
		if (count($results)) {
			$json['success'] = true;
			$json['manufacturer'] = $results;
		} else {
			$json['success'] = false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	} //seo
	public function returnlist()
	{
		$this->checkPlugin();
		$results = array();

		if (isset($this->request->post['customer_id']) && $this->request->post['customer_id'] != '') {
			$customer_id = $this->request->post['customer_id'];
			if ($customer_id) {
				$results1 = $this->getReturnsapi($customer_id);
				if (!empty($results1)) {
					foreach ($results1 as $result) {
						$data['results'][] = array(
							'return_id' => $result['return_id'],
							'order_id' => $result['order_id'],
							'name' => $result['firstname'] . ' ' . $result['lastname'],
							'status' => $result['status'],
							'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
							//'href'       => $this->url->link('account/return/info', 'return_id=' . $result['return_id'] . $url, 'SSL')
						);
					}
				} else {
					$results = 'null';
				}
			} else {
				$results = 'null';

			}


			if (count($results1)) {
				$json['success'] = true;
				$json['returns'] = $data;
			} else {
				$json['success'] = false;
			}

			if ($this->debugIt) {
				echo '<pre>';
				print_r($json);
				echo '</pre>';

			} else {
				$this->response->setOutput(json_encode($json));
			}
		}
	}
	public function returnbyid()
	{
		$this->checkPlugin();
		$results = array();

		if (isset($this->request->post['customer_id']) && $this->request->post['customer_id'] != '') {
			$customer_id = $this->request->post['customer_id'];
			$return_id = $this->request->post['return_id'];
			if ($customer_id && $return_id) {
				$results = $this->getReturnapi($return_id, $customer_id);
			} else {
				$results = 'null';

			}


			if (count($results)) {
				$json['success'] = true;
				$json['return'] = $results;
			} else {
				$json['success'] = false;
			}

			if ($this->debugIt) {
				echo '<pre>';
				print_r($json);
				echo '</pre>';

			} else {
				$this->response->setOutput(json_encode($json));
			}
		}
	}

	public function getOrderStatuses()
	{
		$this->checkPlugin();
		// Order Status
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE language_id = 1");
		$sstatus = 'false';
		$orderStatusData = $query->rows;
		if (count($orderStatusData)) {
			$json['success'] = true;
			$json['statues'] = $orderStatusData;
		} else {
			$json['success'] = false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}

	}

	function decryptToken($token)
	{

		$decoded = JWT::decode($token, new Key(encryption_saltkey, 'HS256'));

		return $decoded;
	}



	public function autologin()
	{
		$this->checkPlugin();
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}

		$results = array();
		$sstatus = 'false';
		if (isset($this->request->post['token']) && $this->request->post['token'] != '') {
			$cutomer_data = $this->decryptToken($this->request->get['token']);
			// print_r($cutomer_data);
			$telephone = $cutomer_data->mobile_number;
			$redirect_uri = $cutomer_data->redirect_uri;

			$this->load->model('account/customer');
			$status = $this->customer->login_new($telephone, $store_id);
			if ($status == true) {
				$results = $this->model_account_customer->getCustomer($this->customer->getId());
				$sstatus = 'true';
			} else {
				$results = 'Token expired';
			}
		} else {
			$results = 'Please Post token';
		}


		if (count($results)) {
			$json['success'] = $sstatus;
			$json['userdata'] = $results;
		} else {
			$json['success'] = false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	} //seo


	public function getCustomerByMobile()
	{
		$this->checkPlugin();
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}
		$results = array();
		$sstatus = 'false';
		if (isset($this->request->post['mobile']) && $this->request->post['mobile'] != '') {
			$telephone = $this->request->post['mobile'];

			$this->load->model('account/customer');
			$status = $this->customer->login_new($telephone, $store_id);

			if ($status == true) {
				$results = $this->model_account_customer->getCustomer($this->customer->getId());
				$language = $this->model_localisation_language->getLanguage($results['language_id']);
				$results['language'] = $language;
				$sstatus = 'true';
			} else {
				$results = 'userName & password Failed';
			}
		} else {
			$results = 'Please Post email and password';
		}
		if (is_countable($results) && count($results)) {
			$json['success'] = $sstatus;
			$json['userdata'] = $results;
		} else {
			$json['success'] = false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}

	public function getCustomerDetailsById()
	{

		$this->checkPlugin();

		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}
		$this->load->model('account/customer');
		$this->load->model('account/order');
		$this->load->model('customer/customer_group');
		//$this->load->model('account/customer_group');
		$results = array();

		if (isset($this->request->post['customer_id']) && $this->request->post['customer_id'] != '') {
			$customer_id = $this->request->post['customer_id'];

			$results = $this->model_account_customer->getCustomerById($customer_id);
		}
		$customer_group_id = 0;
		if (isset($this->request->post['customer_group_id']) && isset($this->request->post['customer_group_id'])) {
			$customer_group_id = $this->request->post['customer_group_id'];

		}
		$totalorder = array();
		$totalorder = $this->model_account_order->getOrdertotalByCustomerid($customer_id);
		$results['totalorder'] = $totalorder;

		$totalrevenue = array();
		$totalrevenue = $this->model_account_order->getOrderRevenueByCustomerid($customer_id);
		$results['totalrevenue'] = $totalrevenue;
		$customeringroups = array();
		$customeringroups = $this->model_customer_customer_group->getCustomerInGroups($customer_id, 1);
		$results['customeringroups'] = $customeringroups;

		$customeraddress = array();
		$customeraddresses = $this->getAddressesapi($customer_id);


		if (count($customeraddresses)) {

			foreach ($customeraddresses as $customeraddress) {




				$tempaddress = $customeraddress['address_1'] . ' ' . $customeraddress['address_2'] . ' ' . $customeraddress['postcode'] . ' ' . $customeraddress['city'] . ' ' . $customeraddress['zone'] . ' ' . $customeraddress['zone_code'] . ' ' . $customeraddress['country_id'] . ' ' . $customeraddress['country'];

				$customeraddress['fulladdress'] = $tempaddress;


			}

		}
		$results['customeraddress'] = $customeraddress;


		if (count($results)) {
			$json['success'] = true;
			$json['customerdetails'] = $results;
			//$json['customeraddress'] 	= $customeraddress;


		} else {
			$json['success'] = false;
		}


		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}

	public function getCustomerInGroups()
	{

		$this->checkPlugin();

		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}

		$this->load->model('account/customer');
		$this->load->model('customer/customer_group');

		$results = array();

		if (isset($this->request->post['customer_id']) && $this->request->post['customer_id'] != '') {
			$customer_id = $this->request->post['customer_id'];

			$results = $this->model_account_customer->getCustomerById($customer_id);
		}


		$customeringroups = array();
		$results = $this->model_customer_customer_group->getCustomerInGroups($customer_id, 1);

		if (count($results)) {
			$json['success'] = true;
			$json['customeringroups'] = $results;

		} else {
			$json['success'] = false;
		}


		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}
	public function updateCustomerToCustomerGroup()
	{

		$this->checkPlugin();
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}
		$customer_id = 0;
		if (isset($this->request->post['customer_id']) && $this->request->post['customer_id'] != "") {
			$customer_id = $this->request->post['customer_id'];
		}
		$customer_group_id = 0;
		if (isset($this->request->post['customer_group_id']) && isset($this->request->post['customer_group_id'])) {
			$customer_group_id = $this->request->post['customer_group_id'];

		}
		$this->load->model('customer/customer');
		$this->load->model('customer/customer_group');

		$json = file_get_contents('php://input');

		$data = json_decode($json, true);
		$customer_id = $this->model_customer_customer->updateCustomer($data['customer_id'], $data);
		$customer_id = $data['customer_id'];
		$customer_group_ids = $data['customer_group_ids'];

		$sql = "SELECT customer_group_id FROM oc_customer_to_customer_group WHERE  customer_id = '" . (int) $customer_id . "'";
		$query = $this->db->query($sql);

		if (in_array($query->row['customer_group_id'], $customer_group_ids)) {

		} else {
			$arrLength = count($customer_group_ids);
			if ($arrLength > 0) {
				$this->db->query("UPDATE " . DB_PREFIX . "customer SET customer_group_id = '" . (int) $customer_group_ids[0] . "' WHERE customer_id = '" . (int) $customer_id . "'");
			}
		}


		$query = $this->db->query("DELETE  FROM  " . "oc_customer_to_customer_group WHERE customer_id = '" . (int) $customer_id . "'");
		foreach ($customer_group_ids as $customer_group_id)

			$results = $this->model_customer_customer_group->updateCustomerFromGroup($customer_id, $customer_group_id, $store_id);

		$json = array();
		if ($customer_id > 0) {
			$json['success'] = true;
			$json['customer_id'] = $customer_id;
		} else {
			$json['success'] = false;
		}
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}


	public function login()
	{
		$this->checkPlugin();
		$results = array();
		$sstatus = 'false';
		if (isset($this->request->post['email']) && isset($this->request->post['password']) && $this->request->post['email'] != '' && $this->request->post['password'] != '') {
			$email = $this->request->post['email'];
			$password = $this->request->post['password'];
			$this->load->model('account/customer');
			$status = $this->customer->login($email, $password);
			if ($status == true) {
				$results = $this->model_account_customer->getCustomer($this->customer->getId());
				$sstatus = 'true';
			} else {
				$results = 'userName & password Failed';
			}
		} else {
			$results = 'Please Post email and password';
		}


		if (count($results)) {
			$json['success'] = $sstatus;
			$json['userdata'] = $results;
		} else {
			$json['success'] = false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	} //seo
	public function logindeliveryboy()
	{
		$this->checkPlugin();
		$results = array();

		if (isset($this->request->post['email']) && isset($this->request->post['password']) && $this->request->post['email'] != '' && $this->request->post['password'] != '') {
			$email = $this->request->post['email'];
			$password = $this->request->post['password'];
			$data = $this->logindeliveryboyapi($email, $password);
			if (!empty($data)) {
				$results = $data;
				$state = 'true';
			} else {
				$results = array('userName & password Failed');
				$state = 'False';
			}
		}


		if (count($results)) {
			$json['success'] = $state;
			$json['deliveryboydata'] = $results;
		} else {
			$json['success'] = false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	} //seo
	public function deliveryboyorderlist()
	{
		$this->checkPlugin();
		$results = array();

		if (isset($this->request->post['delivery_boy_id'])) {
			$delivery_boy_id = $this->request->post['delivery_boy_id'];
			$status = (isset($this->request->post['status']) ? $this->request->post['status'] : '');
			$data = $this->deliveryboyorderlistapi($delivery_boy_id, $status);
			if (!empty($data)) {
				$results = $data;
				$state = 'true';
			} else {
				$results = array('Not Found');
				$state = true;
			}
		}


		if (count($results)) {
			$json['success'] = $state;
			$json['deliveryorder'] = $results;
		} else {
			$json['success'] = false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	} //seo

	public function register()
	{
		$this->checkPlugin();
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}
		$this->load->model('account/customer');
		$this->load->model('customer/customer_group');
		$results = array();
		$sstatus = false;
		$cdata = $this->request->post;
		$data = $this->request->post;
		$cdata['store_id'] = $store_id;
		$error_msg = "";
		$this->load->model('customer/customer_group');

		// $json = file_get_contents('php://input');
		// $data = json_decode($json, true);

		if (!empty($cdata) && isset($cdata['email'], $cdata['firstname'], $cdata['lastname'], $cdata['telephone'])) {

			$customer_info = $this->model_account_customer->getCustomerByEmail($cdata['email'], $store_id);




			if (empty($customer_info)) {
				$customer_id = $this->model_account_customer->addCustomer($cdata);

				if ($customer_id) {

					$customer_info['customer_id'] = $customer_id;
					$customer_group_ids = explode(',', $cdata['customer_group_ids']);
					$customer_group_ids = preg_replace('/[^A-Za-z0-9\-]/', '', $customer_group_ids);

					if (in_array($cdata['customer_group_ids'], $customer_group_ids)) {

					} else {
						$arrLength = count($customer_group_ids);
						if ($arrLength > 0) {
							$this->db->query("UPDATE " . DB_PREFIX . "customer SET customer_group_id = '" . (int) $customer_group_ids[0] . "' WHERE customer_id = '" . (int) $customer_id . "'");
						}
					}


					$query = $this->db->query("DELETE  FROM  " . "oc_customer_to_customer_group WHERE customer_id = '" . (int) $customer_id . "'");
					foreach ($customer_group_ids as $customer_group_id) {

						$customerGroup = $this->model_customer_customer_group->updateCustomerFromGroup($customer_id, $customer_group_id, $store_id);
					}


					$results = $this->model_account_customer->getCustomer($customer_id);


					$sstatus = true;
				} else {
					$sstatus = false;
					$error_msg = 'Please try again';
				}
			} else {
				$sstatus = false;
				$error_msg = 'Email Alredy Exist';
			}

		} else {
			$sstatus = false;
			$$error_msg = 'Please data post email,firstname,lastname etc...';
		}



		if ($sstatus == true) {
			$json['success'] = $sstatus;
			$json['userdata'] = $results;
		} else {
			$json['success'] = false;
			$json['message'] = $error_msg;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}


	//seo
	public function editCustomer()
	{
		$this->checkPlugin();
		$this->load->model('account/customer');
		$this->load->language('account/edit'); //customer_id
		$results = array();
		if (isset($this->request->post) && isset($this->request->post['customer_id']) && $this->request->post != '' && $this->request->post['customer_id'] != '') {
			$this->editCustomers($this->request->post);
			$results = $this->language->get('text_success');
		}
		if (count(array($results))) {
			$json['success'] = true;
			$json['userdata'] = $results;
		} else {
			$json['success'] = false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	} //seo
	public function editPassword()
	{
		$this->checkPlugin();
		$results = array();
		$this->load->model('account/customer');
		$this->load->language('account/edit');
		$email = '';
		$password = '';
		$results = array();
		if (isset($this->request->post['email']) && isset($this->request->post['new_password']) && isset($this->request->post['old_password']) && $this->request->post['email'] && $this->request->post['new_password'] && $this->request->post['old_password']) {

			$email = $this->request->post['email'];
			$old_pass = $this->request->post['old_password'];

			$status = $this->customer->login($email, $old_pass);
			if ($status == true) {
				$password = $this->request->post['new_password'];
				$this->model_account_customer->editPassword($email, $password);
				$results = $this->language->get('text_success');
			} else {
				$results = $this->language->get('Old Password Wrong');
			}
		}
		if (count($results)) {
			$json['success'] = true;
			$json['result'] = $results;
		} else {
			$json['success'] = false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	} //seo

	public function getaddress()
	{
		$this->checkPlugin();
		$this->load->model('account/address');
		$results = array();
		if (isset($this->request->post['customer_id']) && $this->request->post['customer_id'] != '') {
			$customer_id = $this->request->post['customer_id'];
			$results = $this->getAddressesapi($customer_id);
		}
		if (count($results)) {
			$json['success'] = true;
			$json['addressesdata'] = $results;
		} else {
			$json['success'] = false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	} //seo
	public function getaddressbyid()
	{
		$this->checkPlugin();
		$this->load->model('account/address');
		$results = array();
		if (isset($this->request->post['address_id']) && $this->request->post['address_id'] != '') {
			$address_id = $this->request->post['address_id'];
			$results = $this->getAddressidapi($address_id);
		}
		if (count($results)) {
			$json['success'] = true;
			$json['addressdata'] = $results;
		} else {
			$json['success'] = false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}
	public function addaddress()
	{
		$this->checkPlugin();
		$this->load->model('account/address');
		$results = array();
		if ($this->request->post && $this->request->post != '') {
			$address_id = $this->addAddressapi($this->request->post);
			$results = $this->getAddressapi($address_id, $this->request->post['customer_id']);
		}
		if (count($results)) {
			$json['success'] = true;
			$json['addressesdata'] = $results;
		} else {
			$json['success'] = false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	} //seo
	public function editaddress()
	{
		$this->checkPlugin();
		$this->load->model('account/address');
		//$customer_id = $this->request->post['customer_id'];
		$results = array();
		if ($this->request->post && $this->request->post != '') {
			if ($this->editAddressapi($this->request->post)) {
				$results = 'Successfully Update Address';
			}
		}
		if (count($results)) {
			$json['success'] = true;
			$json['addressesdata'] = $results;
		} else {
			$json['success'] = false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	} //seo
	public function deleteaddress()
	{
		$this->checkPlugin();
		$this->load->model('account/address');
		$customer_id = 0;
		$address_id = 0;
		if (isset($this->request->post['customer_id']) && isset($this->request->post['address_id'])) {
			$customer_id = $this->request->post['customer_id'];
			$address_id = $this->request->post['address_id'];
		}

		if ($address_id != '' && $customer_id != '' && $this->deleteAddressapi($address_id, $customer_id)) {
			$results = 'successfully Delete';
		} else {
			$results = array();
		}
		if (count($results)) {
			$json['success'] = true;
			$json['delete'] = $results;
		} else {
			$json['success'] = false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	} //seo
	public function getCountries()
	{
		$this->checkPlugin();
		$this->load->model('localisation/country');
		$results = $this->model_localisation_country->getCountries();
		if (count($results)) {
			$json['success'] = true;
			$json['Countries'] = $results;
		} else {
			$json['success'] = false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	} //seo
	public function getCountry()
	{
		$this->checkPlugin();
		$this->load->model('localisation/country');
		if (isset($this->request->get['country_id']) && $this->request->get['country_id'] != "") {
			$country_id = $this->request->get['country_id'];
		} else {
			$country_id = 0;
		}
		$results = $this->model_localisation_country->getCountry($country_id);
		if (count($results)) {
			$json['success'] = true;
			$json['Country'] = $results;
		} else {
			$json['success'] = false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	} //seo
	public function getZonesByCountryId()
	{
		$this->checkPlugin();
		$this->load->model('localisation/zone');
		if (isset($this->request->get['country_id']) && $this->request->get['country_id'] != "") {
			$country_id = $this->request->get['country_id'];
		} else {
			$country_id = 0;
		}
		$results = $this->model_localisation_zone->getZonesByCountryId($country_id);
		if (count($results)) {
			$json['success'] = true;
			$json['Zones'] = $results;
		} else {
			$json['success'] = false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	} //seo
	public function getZone()
	{
		$this->checkPlugin();
		$this->load->model('localisation/zone');
		if (isset($this->request->get['zone_id']) && $this->request->get['zone_id'] != "") {
			$zone_id = $this->request->get['zone_id'];
		} else {
			$zone_id = 0;
		}
		$results = $this->model_localisation_zone->getZone($zone_id);
		if (count($results)) {
			$json['success'] = true;
			$json['zone'] = $results;
		} else {
			$json['success'] = false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}

	public function getCustomersListCsv()
	{
		$this->checkPlugin();
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}
		$this->load->model('customer/customer');

		$filter_data = array('filter_store_id' => $store_id);

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = 15;
		}

		$filter_data['start'] = ($page - 1) * $limit;
		$filter_data['limit'] = $limit;
		if (isset($this->request->post['seakeyword']) && $this->request->post['seakeyword'] != "") {
			$seakeyword = $this->request->post['seakeyword'];
			$filter_data['filter_name'] = $seakeyword;
		}




		$customer_total = $this->model_customer_customer->getTotalCustomers($filter_data);
		$results = $this->model_customer_customer->getCustomers($filter_data);
		$persons = $this->model_customer_customer->getPersons();

		$list = array(
			['firstname', 'lastname', 'telephone']


		);

		foreach ($persons as $person) {
			$list[] = array(
				$person['firstname'],
				$person['lastname'],
				$person['telephone'] //,$person['name']
			);
		}
		// Open a file in write mode ('w')
		$fp = fopen('customer-1.csv', 'w');

		// Loop through file pointer and a line
		foreach ($list as $fields) {
			fputcsv($fp, $fields);
		}



		if (count($results)) {
			$json['success'] = true;
			$json['customers'] = $results;
			$json['total'] = $customer_total;
			$json['limit'] = $limit;
		} else {
			$json['success'] = false;
		}
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}

	}

	public function ordersListCsv()
	{

		$this->checkPlugin();

		$orderData['orders'] = array();

		$this->load->model('account/order');

		/*check offset parameter*/
		if (isset($this->request->get['offset']) && $this->request->get['offset'] != "" && ctype_digit($this->request->get['offset'])) {
			$offset = $this->request->get['offset'];
		} else {
			$offset = 0;
		}

		/*check limit parameter*/
		if (isset($this->request->get['limit']) && $this->request->get['limit'] != "" && ctype_digit($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = 10000;
		}

		/*get all orders of user*/
		$results = $this->model_account_order->getAllOrders($offset, $limit);


		$list = array(
			['order_id', 'firstname', 'price', 'date_added', 'shippingProvider', 'order_status_id']


		);

		foreach ($results as $order) {
			$list[] = array(
				$order['order_id'],
				$order['firstname'],
				$order['total'],
				$order['date_added'],
				$order['shippingProvider'],
				$order['order_status_id']
			);
		}
		// Open a file in write mode ('w')
		$fp = fopen('orders-3.csv', 'w');

		// Loop through file pointer and a line
		foreach ($list as $fields) {
			fputcsv($fp, $fields);
		}
		$orders = array();

		if (count($results)) {
			foreach ($results as $result) {

				$product_total = $this->model_account_order->getTotalOrderProductsByOrderId($result['order_id']);
				$voucher_total = $this->model_account_order->getTotalOrderVouchersByOrderId($result['order_id']);

				$orders[] = array(
					'order_id' => $result['order_id'],
					// 'name'			=> $result['firstname'] . ' ' . $result['lastname'],
					// 'status'		=> $result['status'],
					// 'date_added'	=> $result['date_added'],
					// 'products'		=> ($product_total + $voucher_total),
					'total' => $result['total'],
					// 'currency_code'	=> $result['currency_code'],
					// 'currency_value'=> $result['currency_value'],
				);
			}

			$json['success'] = true;
			$json['orders'] = $orders;
		} else {
			$json['success'] = false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}



	//get customers
	public function getCustomers()
	{


		$this->checkPlugin();
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}
		$this->load->model('customer/customer');

		$filter_data = array('filter_store_id' => $store_id);

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = 15;
		}

		$filter_data['start'] = ($page - 1) * $limit;
		$filter_data['limit'] = $limit;
		if (isset($this->request->post['seakeyword']) && $this->request->post['seakeyword'] != "") {
			$seakeyword = $this->request->post['seakeyword'];
			$filter_data['filter_name'] = $seakeyword;
		}




		$customer_total = $this->model_customer_customer->getTotalCustomers($filter_data);
		$results = $this->model_customer_customer->getCustomers($filter_data);




		if (count($results)) {
			$json['success'] = true;
			$json['customers'] = $results;
			$json['total'] = $customer_total;
			$json['limit'] = $limit;
		} else {
			$json['success'] = false;
		}
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}


	public function getCustomersByPage()
	{

		$this->checkPlugin();
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}
		$this->load->model('customer/customer');

		$filter_data = array('filter_store_id' => $store_id);

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = 15;
		}

		$filter_data['start'] = ($page - 1) * $limit;
		$filter_data['limit'] = $limit;
		//print_r($filter_data);
		$customer_total = $this->model_customer_customer->getTotalCustomers($filter_data);
		$results = $this->model_customer_customer->getCustomers($filter_data);


		if (count($results)) {
			$json['success'] = true;
			$json['customers'] = $results;
			$json['total'] = $customer_total;
			$json['limit'] = $limit;
		} else {
			$json['success'] = false;
		}
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}


	public function getCustomersFromGroups()
	{

		$this->checkPlugin();
		$this->load->model('customer/customer');
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = 15;
		}


		$page = ($page - 1) * $limit;
		$filter_data = array();
		$customer_group_id = array('customer_group_id' => $store_id);
		if (isset($this->request->post['seakeyword']) && $this->request->post['seakeyword'] != "") {
			$seakeyword = $this->request->post['seakeyword'];
			$filter_data['filter_name'] = $seakeyword;
		}
		$customer_group_id = 0;
		if (isset($this->request->post['customer_group_id']) && isset($this->request->post['customer_group_id'])) {
			$customer_group_id = $this->request->post['customer_group_id'];




			$exclude = 0;
			if (isset($this->request->post['exclude']) && isset($this->request->post['exclude'])) {
				$exclude = $this->request->post['exclude'];
			}

			$results = $this->model_customer_customer->getCustomersFromGroup($filter_data, $limit, $page, $customer_group_id, $exclude, $store_id);
			$total_customers = $this->model_customer_customer->getTotalCustomersByCustomerGroupId($filter_data, $customer_group_id, $exclude, $store_id);
		}


		//$total_customers = $this->model_customer_customer->getTotalCustomersByCustomerGroupId($customer_group_id);
		if (count($results)) {
			$json['success'] = true;
			$json['customers'] = $results;
			$json['total'] = $total_customers;
			$json['limit'] = $limit;
		} else {
			$json['success'] = false;
		}
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}
	public function getCustomerGroups()
	{

		$this->checkPlugin();
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}




		$this->load->model('customer/customer_group');
		$filter_data = array();
		$customer_group_id = array('customer_group_id' => $store_id);
		if (isset($this->request->post['seakeyword']) && $this->request->post['seakeyword'] != "") {
			$seakeyword = $this->request->post['seakeyword'];
			$filter_data['filter_name'] = $seakeyword;
		}

		$filter_data['filter_store_id'] = $store_id;
		$results = $this->model_customer_customer_group->getCustomerGroups($filter_data);

		$total_groups = $this->model_customer_customer_group->getTotalCustomerGroups($store_id);
		if (count($results)) {
			$json['success'] = true;
			$json['customergroups'] = $results;
			$json['total'] = $total_groups;
		} else {
			$json['success'] = false;
		}
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}

	public function getCoupons()
	{

		$this->checkPlugin();
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}

		$this->load->model('marketing/coupon');
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = 15;
		}

		$seakeyword = '';
		if (isset($this->request->post['seakeyword']) && $this->request->post['seakeyword'] != "") {
			$seakeyword = $this->request->post['seakeyword'];

		}
		$data = array(
			'start' => ($page - 1) * $limit,
			'limit' => $limit,
			'filter_store_id' => $store_id,
			'filter_name' => $seakeyword
		);

		$results = $this->model_marketing_coupon->getCoupons($data);


		$coupon_total = $this->model_marketing_coupon->getTotalCoupons($data, $store_id);

		if (count($results)) {
			$json['success'] = true;
			$json['coupons'] = $results;
			$json['limit'] = $limit;
			$json['total'] = $coupon_total;
		} else {
			$json['success'] = false;
			$json['coupons'] = array();
		}
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}

	public function getActivCoupons()
	{

		$this->checkPlugin();
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}

		$this->load->model('marketing/coupon');
		// if (isset($this->request->get['page'])) {
		// 	$page = $this->request->get['page'];
		// } else {
		// 	$page = 1;
		// }
		// if (isset($this->request->get['limit'])) {
		// 	$limit = $this->request->get['limit'];
		// } else {
		// 	$limit = 15;
		// }

		$customer_id = 0;
		if (isset($this->request->post['customer_id'])) {
			$customer_id = $this->request->post['customer_id'];
		}
		$code = "";
		if (isset($this->request->post['code']) && $this->request->post['code'] != "") {
			$code = $this->request->post['code'];
		}
		$seakeyword = '';
		if (isset($this->request->post['seakeyword']) && $this->request->post['seakeyword'] != "") {
			$seakeyword = $this->request->post['seakeyword'];

		}
		$data = array(
			// 'start' => ($page - 1) * $limit,
			// 'limit' => $limit,
			'filter_store_id' => $store_id,
			'filter_name' => $seakeyword
		);

		$results = $this->model_marketing_coupon->getActivCoupons($data);

		$items = array();
		$coupon_total = 0;

		foreach ($results as $result) {

			$coupon_info = $this->getCoupon($result['code'], $customer_id, $store_id);







			if ($coupon_info && isset($coupon_info['coupon_id'])) {

				$discount_total = 0;
				$products = $this->getCartProducts($customer_id, $store_id);

				$sub_total = 0;
				if (!$coupon_info['product']) {
					foreach ($products as $product) {
						$sub_total += $product['total'];
					}

				} else {
					$sub_total = 0;
					foreach ($products as $product) {
						if (in_array($product['product_id'], $coupon_info['product'])) {
							$sub_total += $product['total'];
						}
					}
				}


				if ($coupon_info['type'] == 'F') {
					$coupon_info['discount'] = min($coupon_info['discount'], $sub_total);
				}

				foreach ($products as $product) {
					$discount = 0;

					if (!$coupon_info['product']) {
						$status = true;
					} else {
						$status = in_array($product['product_id'], $coupon_info['product']);
					}

					if ($status) {
						if ($coupon_info['type'] == 'F') {
							$discount = $coupon_info['discount'] * ($product['total'] / $sub_total);
						} elseif ($coupon_info['type'] == 'P') {
							$discount = $product['total'] / 100 * $coupon_info['discount'];


						}


						if ($product['tax_class_id']) {
							$tax_rates = $this->tax->getRates($product['total'] - ($product['total'] - $discount), $product['tax_class_id']);

							foreach ($tax_rates as $tax_rate) {
								if ($tax_rate['type'] == 'P') {
									$total['taxes'][$tax_rate['tax_rate_id']] -= $tax_rate['amount'];
								}
							}
						}
					}

					$discount_total += $discount;

				}

				if ($coupon_info['shipping'] && isset($this->session->data['shipping_method'])) {
					if (!empty($this->session->data['shipping_method']['tax_class_id'])) {
						$tax_rates = $this->tax->getRates($this->session->data['shipping_method']['cost'], $this->session->data['shipping_method']['tax_class_id']);

						foreach ($tax_rates as $tax_rate) {
							if ($tax_rate['type'] == 'P') {
								$total['taxes'][$tax_rate['tax_rate_id']] -= $tax_rate['amount'];
							}
						}
					}

					$discount_total += $this->session->data['shipping_method']['cost'];
				}

				// If discount greater than total
				if ($discount_total > $sub_total) {

					$discount_total = $sub_total;

				}
				if ($coupon_info['coupon_cap'] <= $discount_total && $coupon_info['type'] == 'P' && $coupon_info['coupon_cap'] > 0) {
					$discount_total = $coupon_info['coupon_cap'];
				}

				// $coupon_total = $this->getTotalCouponHistoriesByCoupon($code);


				$customer_total = $this->getTotalCouponHistoriesByCustomerId($code, $this->customer->getId());


				if ($coupon_info['total'] > $sub_total) {
					$items[] = $result;
					$coupon_total = $coupon_total + 1;
					// $json['success'] = false;
					// $json['total'] = true;
					// $json['message'] = "Your cart Total Should be Greater Than " . (float) $coupon_info['total'];

				} else if ($discount_total > 0) {

					$items[] = $result;
					$coupon_total = $coupon_total + 1;
					// $total['discount_total'] = $discount_total;
					// $json['success'] = true;
					// $json['data'] = "Coupon applied successfully ";

				}
			} else {
				$json['total'] = false;
				// $json['message'] = $coupon_info["message"];
			}



		}




		// $coupon_total = $this->model_marketing_coupon->getTotalCoupons($data, $store_id);

		if (count($items)) {
			$json['success'] = true;
			$json['coupons'] = $items;
			// $json['limit'] = $limit;
			$json['total'] = $coupon_total;
		} else {
			$json['success'] = false;
			$json['coupons'] = array();
		}
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}

	public function addCoupon()
	{

		$this->checkPlugin();
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}

		$this->load->model('marketing/coupon');
		$json = file_get_contents('php://input');
		$data = json_decode($json, true);


		$coupon_id = 0;
		$json = array();
		$coupon_info = $this->model_marketing_coupon->getCouponByCode($data['code'], $store_id);

		if ($coupon_info) {
			$json['success'] = false;
			$json['message'] = "Coupon code already in use";
		} else {

			if (!isset($data['type'])) {
				$data['type'] = '';
			}
			if (!isset($data['discount'])) {
				$data['discount'] = '';
			}
			if (!isset($data['description'])) {
				$data['description'] = '';
			}

			if (!isset($data['logged'])) {
				$data['logged'] = '';
			}
			if (!isset($data['shipping'])) {
				$data['shipping'] = '';
			}
			if (!isset($data['total'])) {
				$data['total'] = '';
			}
			if (!isset($data['date_start']) || $data['date_start'] == "") {
				$data['date_start'] = date('Y-m-d', time());
			}
			if (!isset($data['date_end']) || $data['date_end'] == "") {
				$data['date_end'] = date('Y-m-d', strtotime('+1 month'));
			}
			if (!isset($data['uses_total'])) {
				$data['uses_total'] = 1;
			}
			if (!isset($data['uses_customer'])) {
				$data['uses_customer'] = 1;
			}
			if (!isset($data['status'])) {
				$data['status'] = true;
			}


			$coupon_id = $this->model_marketing_coupon->addCoupon($data, $store_id);

		}
		if ($coupon_id > 0) {
			$json['success'] = true;
			$json['coupon_id'] = $coupon_id;
		} else {
			$json['success'] = false;
		}



		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';
		} else {
			$this->response->setOutput(json_encode($json));
		}

	}

	public function addRating()
	{

		$this->checkPlugin();
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}

		$this->load->model('marketing/coupon');
		$json = file_get_contents('php://input');
		$data = json_decode($json, true);
		if (isset($data['order_id'])) {
			$order_id = (int) $data['order_id'];
		} else {
			$order_id = 0;
		}

		if (isset($data['customer_id'])) {
			$customer_id = (int) $data['customer_id'];
		} else {
			$customer_id = 0;
		}


		$rating_id = 0;

		$rating_id = $this->model_marketing_coupon->addrating($data, $store_id);

		$json = array();
		if ($rating_id > 0) {
			$json['success'] = true;
			$json['rating_id'] = $rating_id;
		} else {
			$json['success'] = false;
		}


		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';
		} else {
			$this->response->setOutput(json_encode($json));
		}

	}

	public function editRating()
	{

		$this->checkPlugin();
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}

		$this->load->model('marketing/coupon');
		$json = file_get_contents('php://input');

		$data = json_decode($json, true);


		$data['store_id'] = $store_id;
		$rating_id = $this->model_marketing_coupon->editRating($data['rating_id'], $data);

		$rating_id = $data['rating_id'];


		$json = array();
		if ($rating_id > 0) {
			$json['success'] = true;
			$json['rating_id'] = $rating_id;
		} else {
			$json['success'] = false;
		}


		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';
		} else {
			$this->response->setOutput(json_encode($json));
		}

	}

	public function getRatings()
	{

		$this->checkPlugin();
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}

		$this->load->model('marketing/coupon');



		$data = array(

			'filter_store_id' => $store_id,
		);

		$results = $this->model_marketing_coupon->getRatings($data);




		if (count($results)) {
			$json['success'] = true;
			$json['ratings'] = $results;

		} else {
			$json['success'] = false;
			$json['coupons'] = array();
		}
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}

	public function getOrderRatings()
	{

		$this->checkPlugin();
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}



		$this->load->model('marketing/coupon');

		$test = array();
		if (isset($this->request->post['seakeyword']) && $this->request->post['seakeyword'] != "") {
			$seakeyword = $this->request->post['seakeyword'];
			$test['filter_name'] = $seakeyword;
		}
		if (isset($this->request->post['code']) && $this->request->post['code'] != "") {
			$code = $this->request->post['code'];
		} else {
			$code = '';
		}
		if (isset($this->request->post['rating']) && $this->request->post['rating'] != "") {
			$rating = $this->request->post['rating'];
		} else {
			$rating = '';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = 15;
		}
		if (isset($this->request->post['filter_date_start'])) {
			$filter_date_start = $this->request->post['filter_date_start'];
		} else {
			$filter_date_start = '';
		}

		if (isset($this->request->post['filter_date_end'])) {
			$filter_date_end = $this->request->post['filter_date_end'];
		} else {
			$filter_date_end = '';
		}



		$data = array(

			'filter_store_id' => $store_id,
			'filter_date_start' => $filter_date_start,
			'filter_date_end' => $filter_date_end,
			// 'start' => ($page - 1) * $limit,
			'limit' => $limit,
			'rating' => $rating,



		);

		if ($rating != "") {

			$details = $this->model_marketing_coupon->getRatings($test, $data);
		} else {

			$details = $this->model_marketing_coupon->getRatings1($data, $test, $rating);
		}

		$results = $this->model_marketing_coupon->getOrderRatingCount1($data, $store_id, $rating, $test);

		$results1 = $this->model_marketing_coupon->getDeliveryRatingCount1($data, $store_id, $rating, $test);


		$avgrating = $this->model_marketing_coupon->getOrderAvgRating1($data, $store_id, $rating, $test);

		$avgrating1 = $this->model_marketing_coupon->getDeliveryAvgRating1($data, $store_id, $rating, $test);


		$data = array(
			'customer_details' => $details,
			'total_ratings' => $results[0]['reviews'],
			'average_ratings' => $avgrating[0]['rating'],
			'total_delivery_ratings' => $results1[0]['reviews'],
			'total_delivery_average_ratings' => $avgrating1[0]['rating'],

		);

		if ($code == 'order_rating') {
			$this->load->model('marketing/coupon');


			$filter_data = array(
				'filter_date_start' => $filter_date_start,
				'filter_date_end' => $filter_date_end,
				// 'start' => ($page - 1) * $limit,
				'limit' => $limit,
				'store_id' => $store_id,
				'code' => $code,
			);

			//Array ( [filter_date_start] => 2022-10-20 [filter_date_end] => 2022-10-25 [filter_order_status_id] => 2 [start] => 0 [limit] => 20 )


			if ($code != "") {

				$results = $this->model_marketing_coupon->getOrderRatingCount($filter_data, $store_id, $test, $rating);
			} else {

				$results = $this->model_marketing_coupon->getOrderRatingCount1($filter_data, $store_id, $test, $rating);
			}

			if ($code != "") {

				$avgrating = $this->model_marketing_coupon->getOrderAvgRating($filter_data, $store_id, $test, $rating);
			} else {

				$avgrating = $this->model_marketing_coupon->getOrderAvgRating1($filter_data, $store_id, $test, $rating);
			}
			if ($code != "") {
				$results1 = $this->model_marketing_coupon->getDeliveryRatingCount($filter_data, $store_id, $rating, $test);
			} else {
				$results1 = $this->model_marketing_coupon->getDeliveryRatingCount1($filter_data, $store_id, $rating, $test);
			}
			if ($code != "") {
				$avgrating1 = $this->model_marketing_coupon->getDeliveryAvgRating($filter_data, $store_id, $rating, $test);
			} else {
				$avgrating1 = $this->model_marketing_coupon->getDeliveryAvgRating1($filter_data, $store_id, $rating, $test);
			}




			$result = array(
				'customer_details' => $details,
				'average_ratings' => $avgrating[0]['rating'],
				'total_reviews' => $results[0]['total'],
				'total_delivery_reviews' => $results1[0]['total'],
				'total_delivery_average_reviews' => $avgrating1[0]['rating'],

			);



			$json = $result;
			$json['limit'] = $limit;
			$this->response->setOutput(json_encode($json));
			return;
		}

		if (count($details)) {

			$json['success'] = true;

			$json = $data;
			$json['limit'] = $limit;

		} else {
			$json['success'] = false;
			$json['details'] = array();
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}

	public function getRatingsByNumber()
	{


		$this->checkPlugin();
		$this->load->model('marketing/coupon');
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}
		if (isset($this->request->post['filter_date_start'])) {
			$filter_date_start = $this->request->post['filter_date_start'];
		} else {
			$filter_date_start = '';
		}

		if (isset($this->request->post['filter_date_end'])) {
			$filter_date_end = $this->request->post['filter_date_end'];
		} else {
			$filter_date_end = '';
		}


		$filter_data = array(


			'filter_date_start' => $filter_date_start,
			'filter_date_end' => $filter_date_end,

		);


		$results = $this->model_marketing_coupon->getRatingsTotal5($store_id, $filter_data);
		$results4 = $this->model_marketing_coupon->getRatingsTotal4($store_id, $filter_data);
		$results3 = $this->model_marketing_coupon->getRatingsTotal3($store_id, $filter_data);
		$results2 = $this->model_marketing_coupon->getRatingsTotal2($store_id, $filter_data);
		$results1 = $this->model_marketing_coupon->getRatingsTotal1($store_id, $filter_data);


		$data[] = array(
			'5' => $results[0]['total'],
			'4' => $results4[0]['total'],
			'3' => $results3[0]['total'],
			'2' => $results2[0]['total'],
			'1' => $results1[0]['total'],
		);

		$results['data'] = $data;

		if (count($results)) {
			$json['success'] = true;
			$json = $data;


		} else {
			$json['success'] = false;
		}
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}

	public function getOrderRatingById()
	{
		$this->checkPlugin();
		$this->load->model('marketing/coupon');
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}
		if (isset($this->request->post['rating_id']) && $this->request->post['rating_id'] != "") {
			$rating_id = $this->request->post['rating_id'];
		} else {
			$rating_id = 0;
		}
		$results = $this->model_marketing_coupon->getOrderRatingById($store_id, $rating_id);
		//$results = array();
		if (count($results)) {
			$json['success'] = true;
			$json['data'] = $results;
		} else {
			$json['success'] = false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}

	public function updateMerchantRatingComments()
	{
		$this->checkPlugin();
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}
		$status = false;
		if (isset($this->request->post['rating_id']) && isset($this->request->post['rating_id'])) {
			$this->load->model('marketing/coupon');
			$merchant_comments = $this->model_marketing_coupon->updateMerchantRatingComments($this->request->post['rating_id'], $this->request->post['merchant_comment']);
			$status = true;
		}

		$json['success'] = $status;
		$json['message'] = "comment updated successfully";

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}

	}
	public function updateDeliveryRating()
	{
		$this->checkPlugin();
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}
		$status = false;
		if (isset($this->request->post['rating_id']) && isset($this->request->post['rating_id'])) {
			$this->load->model('marketing/coupon');
			$merchant_comments = $this->model_marketing_coupon->updateDeliveryRating($this->request->post['rating_id'], $this->request->post['delivery_rating']);
			$status = true;
		}

		$json['success'] = $status;
		$json['message'] = "rating updated successfully";

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}

	}


	public function updateOrderRatingComments()
	{
		$this->checkPlugin();
		$status = false;
		if (isset($this->request->post['rating_id']) && isset($this->request->post['rating_id'])) {
			$this->load->model('marketing/coupon');
			$rating_comments = $this->model_marketing_coupon->updateOrderRatingComments($this->request->post['rating_id'], $this->request->post['comments']);
			$status = true;
		}

		$json['success'] = $status;

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}

	}

	public function deleteCoupon()
	{

		$this->checkPlugin();

		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}

		$this->load->model('marketing/coupon');
		$json = file_get_contents('php://input');
		$data = json_decode($json, true);
		$coupon_id = $data['coupon_id'];

		$coupon_id = $this->model_marketing_coupon->deleteCoupon($data['coupon_id'], $store_id);

		$json = array();
		$json['success'] = true;
		$json['message'] = "coupon deleted successfully.";

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}



	}
	public function editCoupon()
	{

		$this->checkPlugin();
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}
		$this->load->model('marketing/coupon');
		$json = file_get_contents('php://input');
		$data = json_decode($json, true);
		$json = array();
		$coupon_info = $this->model_marketing_coupon->getCouponByCode($data['code'], $store_id);

		if ($coupon_info && ($data['coupon_id'] != $coupon_info['coupon_id'])) {
			$json['success'] = false;
			$json['message'] = "Coupon code already in use";
		} else {

			$coupon_id = $this->model_marketing_coupon->editCoupon($data['coupon_id'], $data, $store_id);
			$coupon_id = $data['coupon_id'];


			if ($coupon_id > 0) {
				$json['success'] = true;
				$json['coupon_id'] = $coupon_id;

			} else {
				$json['success'] = false;
			}
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';
		} else {
			$this->response->setOutput(json_encode($json));
		}

	}
	public function getCouponById()
	{
		$this->checkPlugin();
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}
		$this->load->model('marketing/coupon');
		$this->load->model('catalog/product');
		$this->load->model('catalog/category');
		$json = file_get_contents('php://input');
		$data = json_decode($json, true);
		$coupon_id = $data['coupon_id'];

		$results = $this->model_marketing_coupon->getCouponById($coupon_id, $store_id);

		$products_ids = $this->model_marketing_coupon->getCouponProducts($coupon_id);

		$products = array();
		if (count($products_ids) > 0) {
			foreach ($products_ids as $product_id) {
				$product = $this->model_catalog_product->getProduct($product_id, 1, $store_id);
				if ($product) {

					$products[] = array(
						'id' => $product['product_id'],
						'name' => $product['name']

					);
				}

			}
		}


		$results['products'] = $products;
		$categories_ids = $this->model_marketing_coupon->getCouponCategories($coupon_id);
		$categories = array();
		if (count($categories_ids) > 0) {
			foreach ($categories_ids as $category_id) {
				$category = $this->model_catalog_category->getCategory($category_id, 1, $store_id);
				if ($category) {
					$categories[] = array(
						'category_id' => $category['category_id'],
						'name' => $category['name']

					);
				}
			}
		}

		$results['categories'] = $categories;
		$json = array();
		if ($coupon_id > 0) {
			$json['success'] = true;
			$json['results'] = $results;


		} else {
			$json['success'] = false;
		}


		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}


	public function addCustomerGroup()
	{

		$this->checkPlugin();
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}
		if (isset($this->request->get['language_id'])) {
			$language_id = $this->request->get['language_id'];
		} else {
			$language_id = 1;

		}
		$this->load->model('customer/customer_group');
		$json = file_get_contents('php://input');
		$data = json_decode($json, true);


		$customer_group_id = $this->model_customer_customer_group->addCustomerGroup($data, $store_id);
		$json = array();
		if ($customer_group_id > 0) {
			$json['success'] = true;
			$json['customergroup'] = $customer_group_id;
		} else {
			$json['success'] = false;
		}



		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';
		} else {
			$this->response->setOutput(json_encode($json));
		}

	}



	public function AddCustomerToCustomerGroup()
	{

		$this->checkPlugin();
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}

		$this->load->model('customer/customer_group');

		$json = file_get_contents('php://input');

		$data = json_decode($json, true);

		$customer_group_id = $data['customer_group_id'];
		$customer_ids = $data['customer_ids'];
		//$results = $this->model_customer_customer_group->deleteCustomersGroup($customer_group_id);

		foreach ($customer_ids as $customer_id)
			$results = $this->model_customer_customer_group->AddCustomerToGroup($customer_id, $customer_group_id, $store_id);





		/*$customer_id = 0;
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						if(isset($this->request->post['customer_id']) && isset($this->request->post['customer_id'])){
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						$customer_id = $this->request->post['customer_id'];
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						}
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						$customer_group_id = 0;
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						if(isset($this->request->post['customer_group_id']) && isset($this->request->post['customer_group_id'])){
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						$customer_group_id = $this->request->post['customer_group_id'];
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						}
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						$this->load->model('customer/customer_group');
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						//$customer_group_id=array('customer_group_id'=>$store_id);
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						//{"coustomer_group_id":"1","customer_ids":["1","2","3"]}
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						$results = $this->model_customer_customer_group->AddCustomerToGroup($customer_id,$customer_group_id);*/
		$json = array();
		if ($customer_group_id > 0) {
			$json['success'] = true;
			$json['addcustomertogroup'] = $customer_group_id;
		} else {
			$json['success'] = false;
		}
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}

	public function deleteCustomerFromGroup()
	{

		$this->checkPlugin();
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}
		if (isset($this->request->post['customer_id']) && $this->request->post['customer_id'] != "") {
			$customer_id = $this->request->post['customer_id'];
		}

		$this->load->model('customer/customer_group');

		$json = file_get_contents('php://input');

		$data = json_decode($json, true);

		$customer_id = $data['customer_id'];
		$customer_group_id = $data['customer_group_id'];
		//$customer_ids = $data['customer_ids'];
		//$results = $this->model_customer_customer_group->deleteCustomersGroup($customer_group_id);

		//foreach($customer_ids as $customer_id)
		$results = $this->model_customer_customer_group->deleteCustomerFromGroup($customer_id, $customer_group_id, $store_id);

		$json = array();
		if ($customer_id > 0) {
			$json['success'] = true;
			$json['customer_group_id'] = $customer_group_id;
			$json['customer_id'] = $customer_id;
		} else {
			$json['success'] = false;
		}
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}







	//seo
	public function getLanguages()
	{
		$this->checkPlugin();
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}
		$this->load->model('localisation/language');
		$this->load->model('setting/setting');
		$language_ids = json_decode($this->model_setting_setting->getSettingValue('config_languages', $store_id));
		$results = array();
		foreach ($language_ids as $language_id) {
			$results[] = $this->model_localisation_language->getLanguage($language_id);
		}

		if (count($results)) {
			$json['success'] = true;
			$json['Languages'] = $results;
		} else {
			$json['success'] = false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	} //seo
	public function getLanguage()
	{
		$this->checkPlugin();
		$this->load->model('localisation/language');
		if (isset($this->request->get['code']) && $this->request->get['code'] != "") {
			$code = $this->request->get['code'];
		} else {
			$code = '';
		}
		$results = $this->getLanguageapi($code);
		if (count($results)) {
			$json['success'] = true;
			$json['Language'] = $results;
		} else {
			$json['success'] = false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}
	public function getLanguageByName()
	{
		$this->checkPlugin();
		$this->load->model('localisation/language');
		if (isset($this->request->post['name']) && $this->request->post['name'] != "") {
			$code = $this->request->post['name'];
		} else {
			$code = '';
		}
		$results = $this->model_localisation_language->getLanguageByName($code);
		//$results = array();
		if (count($results)) {
			$json['success'] = true;
			$json['Language'] = $results;
		} else {
			$json['success'] = false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}


	//seo
	public function getSettings()
	{
		$this->checkPlugin();
		$this->load->model('setting/setting');
		$results = $this->getSettingsapi();
		if (count($results)) {
			$json['success'] = true;
			$json['Settings'] = $results;
		} else {
			$json['success'] = false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	} //seo
	public function getSetting($scode = '')
	{
		if (isset($scode) && $scode != '') {
			$code = $scode;
		}
		$this->checkPlugin();
		$this->load->model('setting/setting');
		if (isset($this->request->get['code']) && $this->request->get['code'] != "") {
			$code = $this->request->get['code'];
		} else {
			$code = 0;
		}
		$results = $this->model_setting_setting->getSetting($code);
		if (count($results)) {
			$json['success'] = true;
			$json['Setting'] = $results;
		} else {
			$json['success'] = false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	} //seo
	public function getCurrencies()
	{
		$this->checkPlugin();
		$this->load->model('localisation/currency');
		$results = $this->model_localisation_currency->getCurrencies();
		if (count($results)) {
			$json['success'] = true;
			$json['Currencies'] = $results;
		} else {
			$json['success'] = false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	} //seo
	public function getCurrencyByCode()
	{
		$this->checkPlugin();
		$this->load->model('localisation/currency');
		if (isset($this->request->get['curr_code']) && $this->request->get['curr_code'] != "") {
			$curr_code = $this->request->get['curr_code'];
		} else {
			$curr_code = 0;
		}
		$results = $this->model_localisation_currency->getCurrencyByCode($curr_code);
		if (count($results)) {
			$json['success'] = true;
			$json['Currencie'] = $results;
		} else {
			$json['success'] = false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	} //seo

	public function createOrder()
	{

		$this->checkPlugin();
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}
		$this->load->model('checkout/order');
		$this->load->model('setting/store');
		$this->load->model('account/address');

		$customer_id = 0;
		if (isset($this->request->post['customer_id'])) {
			$customer_id = $this->request->post['customer_id'];
		}


		$shipping_custom_field = false;
		if (isset($this->request->post['location'])) {
			$shipping_custom_field = array('location' => $this->request->post['location']);
		}
		$custom_field = false;
		if (isset($this->request->post['delivery_instructions'])) {
			$custom_field = array('delivery_instructions' => $this->request->post['delivery_instructions']);
			$custom_field = preg_replace('/^\'?(.*?(?=\'?$))\'?$/', '$1', $custom_field);
		}
		// print_r($custom_field);
		// die;

		// $custom_field = preg_replace('/^\'?(.*?(?=\'?$))\'?$/', '$1', $custom_field);

		$postcode = $this->request->post['postcode'];
		$address_id = 0;
		if (isset($this->request->post['address_id'])) {
			$address_id = $this->request->post['address_id'];
		}
		$comments = $this->request->post['notes'];
		$country = $this->request->post['country'];
		$city = $this->request->post['city'];
		$zone = $this->request->post['zone'];
		$address = $this->request->post['address'];



		// $deliveryType = $this->request->post['deliveryType'];

		$deliveryType = "";
		if (isset($this->request->post['deliveryType'])) {
			$deliveryType = $this->request->post['deliveryType'];
		}
		$shippingProvider = "";
		if (isset($this->request->post['shippingProvider'])) {
			$shippingProvider = $this->request->post['shippingProvider'];
		}
		$shippingtype = "";
		if (isset($this->request->post['shippingtype'])) {
			$shippingtype = $this->request->post['shippingtype'];
		}

		$shippingsubtype = "";
		if (isset($this->request->post['shippingsubtype'])) {
			$shippingsubtype = $this->request->post['shippingsubtype'];
		}
		$deliveryMethod = "";
		if (isset($this->request->post['deliveryMethod'])) {
			$deliveryMethod = $this->request->post['deliveryMethod'];
		}

		$cooking_instructions = "";
		if (isset($this->request->post['cooking_instructions'])) {
			$cooking_instructions = $this->request->post['cooking_instructions'];
		}
		$receiever_name = "";
		if (isset($this->request->post['receiever_name'])) {
			$receiever_name = $this->request->post['receiever_name'];
		}
		$receiever_phone = "";
		if (isset($this->request->post['receiever_phone'])) {
			$receiever_phone = $this->request->post['receiever_phone'];
		}
		// $this->load->model('customer/customer');
		// $tableData = $this->model_customer_customer->getTableData($customer_id);

		// $table_number = "";
		// if (isset($this->request->post['table_number'])) {
		// 	$table_number = $this->request->post['table_number'];
		// }
		// $order_comments = "";
		// if (isset($this->request->post['order_comments'])) {
		// 	$order_comments = $this->request->post['order_comments'];
		// }

		// print_r($service_charge);
		// die;
		$bid_id = "";
		if (isset($this->request->post['bid_id'])) {
			$bid_id = $this->request->post['bid_id'];
		}

		$payment_method = $this->request->post['payment_method'];
		$payment_code = $this->request->post['payment_code'];
		$platformfee = $this->request->post['plat_form_fee'];
		if (isset($this->request->post['plat_form_fee'])) {
			$platformfee = $this->request->post['plat_form_fee'];
		}
		$source = 0;
		if (isset($this->request->post['source'])) {
			$source = $this->request->post['source'];
		}
		$source_value = 0;
		if (isset($this->request->post['source_value'])) {
			$source_value = $this->request->post['source_value'];
		}
		$service_charge = $this->request->post['service_charge'];
		if (isset($this->request->post['service_charge'])) {
			$service_charge = $this->request->post['service_charge'];
		}

		$this->load->model('account/customer');
		$res_data = $this->getCartProducts($customer_id, $store_id);

		$tableData = $this->getCartProducts($customer_id, $store_id);
		$tableData = array_shift($tableData);

		// print_r($res_data);
		// die;
		$this->load->model('setting/setting');
		$this->load->model('setting/extension');

		$storecurrency = $this->model_setting_setting->getSettingValue('config_currency', $store_id);
		$this->load->model('catalog/product');
		if (count($res_data) > 0) {

			$customer = $this->model_account_customer->getCustomer($customer_id);
			$products = array_values($res_data);
			$subtotal = 0;
			$rate = 0;
			foreach ($products as $product) {

				$subtotal = $subtotal + $product['total'];
				$rate = $rate + $product['product_tax'];
			}

			// print_r($res_data);
			// die;

			// print_r($attribute);
			// die;
			// $tax = ($subtotal * 5) / 100;



			$store = $this->model_setting_store->getStore($store_id);
			$name = 'WP-SUPERMARKET';

			$store_url = $this->config->get('config_url');

			if ($store) {
				$store_url = $store['url'];
				$name = $store['name'];


			}
			/*echo $shipping_custom_field;
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																									   $obj = json_decode($this->request->post['location']),true);
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																									   print_r($obj);die;*/
			$latlong = explode(',', $this->request->post['location']);
			if ($this->request->post['location'] != "") {
				$lat = $latlong[0];
				$long = $latlong[1];
			}


			$storeDeliveryCharges = $this->request->post['shipping_charge'];


			$discount_total = 0;
			$code = "";
			if (isset($this->request->post['code']) && $this->request->post['code'] != "") {
				$code = $this->request->post['code'];
			}


			$data = array();
			if ($code != "") {

				$data = $this->applyCouponLocal();

			}

			$totals = array(

				[
					"code" => "sub_total",
					"title" => "Sub-Total",
					"value" => $subtotal,
					"sort_order" => "1"
				],
				[
					"code" => "shipping",
					"title" => "Flat Shipping Rate",
					"value" => $storeDeliveryCharges,
					"sort_order" => "3"
				],
				[
					"code" => "plat_form_fee",
					"title" => "Platform Fee",
					"value" => $platformfee,
					"sort_order" => "4"
				],
				[
					"code" => "service_charge",
					"title" => "Service Charge",
					"value" => $service_charge,
					"sort_order" => "4"
				],
				[
					"code" => "total",
					"title" => "total",
					"value" => $subtotal + $storeDeliveryCharges + $platformfee + $service_charge,
					"sort_order" => "7"
				],
				[
					"code" => "tax",
					"title" => "GST",
					"value" => $rate,
					"sort_order" => "5"
				]


			);

			if ($code != "" && $data['success']) {
				$discount_total = $data['data']['discount_total'];
				$totals = array(

					[
						"code" => "sub_total",
						"title" => "Sub-Total",
						"value" => $subtotal,
						"sort_order" => "1"
					],
					[
						"code" => "shipping",
						"title" => "Flat Shipping Rate",
						"value" => $storeDeliveryCharges,
						"sort_order" => "3"
					],
					[
						"code" => "plat_form_fee",
						"title" => "Platform Fee",
						"value" => $platformfee,
						"sort_order" => "5"
					],
					[
						"code" => "service_charge",
						"title" => "Service Charge",
						"value" => $service_charge,
						"sort_order" => "4"
					],

					[
						"code" => "total",
						"title" => "Total",
						"value" => $subtotal + $storeDeliveryCharges + $platformfee + $service_charge - ($discount_total),
						"sort_order" => "9"
					],

					[
						"code" => "coupon",
						"title" => "Coupon (" . $code . ")",
						"value" => -$discount_total,
						"sort_order" => "4"
					],
					[
						"code" => "tax",
						"title" => "GST",
						"value" => $rate,
						"sort_order" => "7"
					]

				);



			}
			// print_r($totals);
			// die;

			$jayParsedAry = array(
				"totals" => $totals,
				"invoice_prefix" => "INV-2022-00",
				"store_id" => $store_id,

				"store_name" => $name,
				"store_url" => $store_url,
				"customer_id" => $customer_id,
				"customer_group_id" => $customer['customer_group_id'],
				"firstname" => $customer['firstname'],
				"lastname" => $customer['lastname'],
				"email" => $customer['email'],
				"telephone" => $customer['telephone'],
				"deliveryType" => $deliveryType,
				"shippingProvider" => $shippingProvider,
				"deliveryMethod" => $deliveryMethod,
				"cooking_instructions" => $cooking_instructions,
				"receiver_name" => $receiever_name,
				"receiver_phone" => $receiever_phone,
				"type" => $tableData['type'],
				"table_number" => $tableData['tableNumber'],
				'service_charge_type' => $tableData['serviceChargeType'],
				"comments" => $tableData['comments'],
				"service_charge" => $service_charge,
				"bid_id" => $bid_id,
				"source" => $source,
				"source_value" => $source_value,
				"shippingtype" => $shippingtype,
				"shippingsubtype" => $shippingsubtype,
				"fax" => $customer['fax'],
				"custom_field" => $custom_field,
				"payment_firstname" => $customer['firstname'],
				"payment_lastname" => $customer['lastname'],
				"payment_company" => "",
				"payment_address_1" => $address,
				"payment_address_2" => "",
				"payment_city" => $city,
				"payment_postcode" => $postcode,
				"payment_zone" => $zone,
				"payment_zone_id" => "4231",
				"payment_country" => $country,
				"payment_country_id" => "99",
				"payment_address_format" => "",
				"payment_custom_field" => false,
				"payment_method" => $payment_method,
				"payment_code" => $payment_code,
				"shipping_firstname" => $customer['firstname'],
				"shipping_lastname" => $customer['lastname'],
				"shipping_company" => "",
				"shipping_address_1" => $address,
				"shipping_address_2" => "",
				"shipping_city" => $city,

				"shipping_postcode" => $postcode,
				"shipping_zone" => $zone,
				"shipping_zone_id" => "4231",
				"shipping_country" => $country,
				"shipping_country_id" => "99",
				"shipping_address_format" => "",
				"shipping_custom_field" => $shipping_custom_field,
				"shipping_method" => "Flat Shipping Rate",
				"shipping_code" => "flat.flat",
				"products" => $products,
				"vouchers" => [
				],
				"comment" => $comments,
				"product_tax" => $product['product_tax'],
				"total" => $subtotal + $storeDeliveryCharges + $platformfee + $service_charge - ($discount_total),
				"affiliate_id" => 0,
				"commission" => 0,
				"marketing_id" => 0,
				"tracking" => "",
				"language_id" => "1",
				"currency_id" => "2",
				"currency_code" => $storecurrency,
				"currency_value" => "1.00000000",
				"ip" => "127.0.0.1",
				"order_status_id" => "1",
				"forwarded_ip" => "",
				"user_agent" => "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:39.0) Gecko/20100101 Firefox/39.0",
				"accept_language" => "en-US,en;q=0.5",
			);

			// print_r($jayParsedAry);
			// die;
			$results = array();
			if (isset($jayParsedAry) && $jayParsedAry != '') {
				$dataorder1 = $jayParsedAry;
				$results1 = array();
				$dataorder = $this->convertobjtoarray($dataorder1, $results1);

				// print_r($dataorder);
				// die;

				// $dataorder = json_decode(json_encode($dataorder1), true);
				$totalValue = $subtotal + $storeDeliveryCharges - ($discount_total);
				// print_r($dataorder);die;
				if (isset($dataorder) && $dataorder != '') {
					$order_id = $this->model_checkout_order->addOrder($dataorder);
					$coupon_info = $this->getCoupon($code, $customer_id, $store_id);


					// if ($code != "" && $data['success']) {
					// 	$this->db->query("INSERT INTO `" . DB_PREFIX . "coupon_history` SET coupon_id = '" . (int) $coupon_info['coupon_id'] . "', order_id = '" . (int) $order_id . "', customer_id = '" . (int) $customer_id . "', amount = '" . (float) $totalValue . "', date_added = NOW()");
					// }
					if ($order_id && $order_id != '') {
						$sta_id = (int) $dataorder['order_status_id'];
						$dd = $this->model_checkout_order->addOrderHistory($order_id, $sta_id);

						if ((int) $address_id == 0) {
							$data = array(

								"firstname" => $customer['firstname'],
								"lastname" => $customer['lastname'],
								"company" => "",
								"address_1" => $address,
								"address_2" => "",
								"postcode" => $postcode,
								"city" => $city,
								"zone_id" => "4231",
								"country_id" => "99",
								"custom_field" => array("address" => $shipping_custom_field)




							);
							$add = $this->model_account_address->addAddress($customer_id, $data);
						}

						//$this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int)$dataorder['order_status_id'] . "', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'");

					}
					$results = $this->model_checkout_order->getOrder($order_id);

				} else {
					$json['success'] = false;
				}
			}
		}


		if (isset($results) && count($results)) {
			$cart = array();
			// $this->db->query("UPDATE " . DB_PREFIX . "customer SET cart = '" . serialize($cart) . "' WHERE customer_id = '" . (int) $customer_id . "'");

			$this->db->query("UPDATE " . DB_PREFIX . "cart SET type = '" . "" . "' WHERE customer_id = '" . (int) $customer_id . "'");

			$json['success'] = true;
			$json['order'] = $results;
		} else {
			$json['success'] = false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}


	}


	public function setPaymentAddress($country_id, $zone_id)
	{
		$tax_rates = array();
		$tax_query = $this->db->query("SELECT tr1.tax_class_id, tr2.tax_rate_id, tr2.name, tr2.rate, tr2.type, tr1.priority FROM " . DB_PREFIX . "tax_rule tr1 LEFT JOIN " . DB_PREFIX . "tax_rate tr2 ON (tr1.tax_rate_id = tr2.tax_rate_id) INNER JOIN " . DB_PREFIX . "tax_rate_to_customer_group tr2cg ON (tr2.tax_rate_id = tr2cg.tax_rate_id) LEFT JOIN " . DB_PREFIX . "zone_to_geo_zone z2gz ON (tr2.geo_zone_id = z2gz.geo_zone_id) LEFT JOIN " . DB_PREFIX . "geo_zone gz ON (tr2.geo_zone_id = gz.geo_zone_id) WHERE tr1.based = 'payment' AND tr2cg.customer_group_id = '" . (int) $this->config->get('config_customer_group_id') . "' AND z2gz.country_id = '" . (int) $country_id . "' AND (z2gz.zone_id = '0' OR z2gz.zone_id = '" . (int) $zone_id . "') ORDER BY tr1.priority ASC");

		foreach ($tax_query->rows as $result) {
			$tax_rates[$result['tax_class_id']][$result['tax_rate_id']] = array(
				'tax_rate_id' => $result['tax_rate_id'],
				'name' => $result['name'],
				'rate' => $result['rate'],
				'type' => $result['type'],
				'priority' => $result['priority']
			);
		}
		return $tax_rates;
	}

	public function getRates($value, $tax_class_id, $tax_rates)
	{


		$tax_rate_data = array();

		if (isset($tax_rates[$tax_class_id])) {
			foreach ($tax_rates[$tax_class_id] as $tax_rate) {

				if (isset($tax_rate_data[$tax_rate['tax_rate_id']])) {
					$amount = $tax_rate_data[$tax_rate['tax_rate_id']]['amount'];
				} else {
					$amount = 0;
				}

				if ($tax_rate['type'] == 'F') {
					$amount += $tax_rate['rate'];
				} elseif ($tax_rate['type'] == 'P') {
					$amount += ($value / 100 * $tax_rate['rate']);
				}

				$tax_rate_data[$tax_rate['tax_rate_id']] = array(
					'tax_rate_id' => $tax_rate['tax_rate_id'],
					'name' => $tax_rate['name'],
					'rate' => $tax_rate['rate'],
					'type' => $tax_rate['type'],
					'amount' => $amount
				);
			}
		}

		return $tax_rate_data;
	}
	public function setShippingAddress($country_id, $zone_id)
	{
		$tax_query = $this->db->query("SELECT tr1.tax_class_id, tr2.tax_rate_id, tr2.name, tr2.rate, tr2.type, tr1.priority FROM " . DB_PREFIX . "tax_rule tr1 LEFT JOIN " . DB_PREFIX . "tax_rate tr2 ON (tr1.tax_rate_id = tr2.tax_rate_id) INNER JOIN " . DB_PREFIX . "tax_rate_to_customer_group tr2cg ON (tr2.tax_rate_id = tr2cg.tax_rate_id) LEFT JOIN " . DB_PREFIX . "zone_to_geo_zone z2gz ON (tr2.geo_zone_id = z2gz.geo_zone_id) LEFT JOIN " . DB_PREFIX . "geo_zone gz ON (tr2.geo_zone_id = gz.geo_zone_id) WHERE tr1.based = 'shipping' AND tr2cg.customer_group_id = '" . (int) $this->config->get('config_customer_group_id') . "' AND z2gz.country_id = '" . (int) $country_id . "' AND (z2gz.zone_id = '0' OR z2gz.zone_id = '" . (int) $zone_id . "') ORDER BY tr1.priority ASC");

		foreach ($tax_query->rows as $result) {
			$this->tax_rates[$result['tax_class_id']][$result['tax_rate_id']] = array(
				'tax_rate_id' => $result['tax_rate_id'],
				'name' => $result['name'],
				'rate' => $result['rate'],
				'type' => $result['type'],
				'priority' => $result['priority']
			);
		}
	}
	public function addorder()
	{
		$this->checkPlugin();
		$this->load->model('checkout/order');
		if (isset($this->request->post['addorder'])) {
			$addorder = htmlspecialchars_decode($this->request->post['addorder']);
		}

		$results = array();
		if (isset($addorder) && $addorder != '') {
			$dataorder1 = json_decode($addorder);
			$results1 = array();
			$dataorder = $this->convertobjtoarray($dataorder1, $results1);
			//$dataorder = json_decode(json_encode($addorder), true);

			if (isset($dataorder) && $dataorder != '') {
				$order_id = $this->model_checkout_order->addOrder($dataorder);

				if ($order_id && $order_id != '') {
					$sta_id = (int) $dataorder['order_status_id'];
					$dd = $this->model_checkout_order->addOrderHistory($order_id, $sta_id);

					//$this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int)$dataorder['order_status_id'] . "', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'");

				}
				$results = $this->model_checkout_order->getOrder($order_id);
			} else {
				$results = array();
			}
		}
		if (count($results)) {
			$json['success'] = true;
			$json['order'] = $results;
		} else {
			$json['error'] = false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	} //seo
	public function shippingmethod()
	{
		$this->checkPlugin();
		$this->load->model('extension/extension');
		$results = $this->model_extension_extension->getExtensions('shipping');
		/*foreach ($shipp_results as $result) {
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						if ($this->config->get($result['code'] . '_status')) {
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						$this->load->model('shipping/' . $result['code']);
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						$quote = $this->{'model_shipping_' . $result['code']}->getQuote($this->session->data['shipping_address']);
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						if ($quote) {
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						$results[$result['code']] = array(
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						'title'      => $quote['title'],
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						'quote'      => $quote['quote'],
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						'sort_order' => $quote['sort_order'],
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						'error'      => $quote['error']
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						);
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						}
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						}
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						}*/
		if (count($results)) {
			$json['success'] = true;
			$json['ShippingMethod'] = $results;
		} else {
			$json['success'] = false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	} //seo
	public function paymentmethod()
	{
		$this->checkPlugin();
		$this->load->model('setting/extension');
		$language_id = 1;
		if (isset($this->request->get['language_id']) && $this->request->get['language_id'] != "") {
			$language_id = $this->request->get['language_id'];
		}
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}

		$this->load->model('localisation/language');
		$languageObj = $this->model_localisation_language->getLanguage($language_id);
		$this->load->model('setting/setting');
		$this->load->model('setting/extension');
		$payment_codes = json_decode($this->model_setting_setting->getSettingValue('config_payments', $store_id));

		$results = $this->model_setting_extension->getExtensions('payment');

		$method_data = array();


		foreach ($results as $result) {
			$code = $result['code'];
			if ($this->config->get('payment_' . $code . '_status') && in_array($result['code'], $payment_codes)) {


				$this->load->language('extension/payment/' . $code);

				$method_data[] = array(
					'code' => $code,
					'title' => $this->language->get('text_title'),
					'instructions' => $this->config->get('payment_' . $code . '_bank' . $language_id),
					'sort_order' => $this->config->get('payment_' . $code . '_sort_order')
				);

			}
		}
		if (count($method_data)) {
			$json['success'] = true;
			$json['PaymentMethod'] = $method_data;
		} else {
			$json['success'] = false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	} //seo
	public function totalmethod()
	{
		$this->checkPlugin();
		$this->load->model('extension/extension');
		$results = $this->model_extension_extension->getExtensions('total');
		if (count($results)) {
			$json['success'] = true;
			$json['PaymentMethod'] = $results;
		} else {
			$json['success'] = false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	} //seo


	public function getProductIdsByPosId()
	{

		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}

		$this->load->language('checkout/cart');
		$inputjson = file_get_contents('php://input');
		$data = json_decode($inputjson, true);

		$this->load->model('catalog/product');
		$poss_ids = $data['pos_product_ids'];
		$status = $data['status'];
		$result = $this->model_catalog_product->getProductIds($poss_ids, $status);
		$json = array();
		if ($poss_ids != "") {
			$json['success'] = true;
			$json['data'] = $result;
		} else {
			$json['success'] = false;
		}



		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}

	}
	public function cartproduct()
	{

		$this->checkPlugin();
		$this->load->model('account/customer');
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}
		$customer_id = 0;
		if (isset($this->request->post['customer_id'])) {
			$customer_id = $this->request->post['customer_id'];
		}
		$res_data = $this->getCartProducts($customer_id, $store_id);

		if (count($res_data)) {
			$json['success'] = true;
			$json['cartproduct'] = $res_data;
		} else {
			$json['success'] = false;
			$json['cartproduct'] = (object) [];
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	} //seo


	//updateTableData
	public function updateTableData()
	{

		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}
		$this->load->language('checkout/cart');
		$inputjson = file_get_contents('php://input');
		$data = json_decode($inputjson, true);

		if (isset($data['customer_id'])) {
			$customer_id = (int) $data['customer_id'];
		} else {
			$customer_id = 0;
		}

		$this->load->model('customer/customer');

		$tableData = $this->model_customer_customer->updateTableData($data['customer_id'], $data);


		$json = array();
		if ($customer_id != "") {
			$json['success'] = true;
			$json['message'] = "data updated successfully!";
		} else {
			$json['success'] = false;
		}



		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}

	}
	public function getCartProducts($customer_id, $store_id)
	{
		$this->load->model('account/customer');

		$res_data = array();
		$cartresults = array();
		if (isset($customer_id) && $customer_id != '') {
			$cartresults = $this->model_account_customer->getCustomer($customer_id, $store_id);
		}
		$this->load->model('setting/setting');
		$storecurrency = $this->model_setting_setting->getSettingValue('config_currency', $store_id);



		if (!empty($cartresults)) {
			$cart_array = unserialize($cartresults['cart']);
			if (is_array($cart_array) || is_object($cart_array)) {



				$cartdata = array();
				foreach ($cart_array as $key => $value) {
					if (!array_key_exists($key, $cartdata)) {
						$cartdata[$key] = $value;
					} else {
						$cartdata[$key] += $value;
					}
				}
				foreach ($cartdata as $key => $quantity) {
					$product = unserialize(base64_decode($key));

					$product_id = $product['product_id'];

					$stock = true;

					// Options
					if (!empty($product['option'])) {
						$options = $product['option'];
					} else {
						$options = array();
					}

					// Profile
					if (!empty($product['recurring_id'])) {
						$recurring_id = $product['recurring_id'];
					} else {
						$recurring_id = 0;
					}


					// $product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int) $product_id . "' AND pd.language_id = '" . (int) $this->config->get('config_language_id') . "' AND p.date_available <= NOW() AND p.status = '1'");

					$product_query = $this->db->query("SELECT *,p.image as product_Image FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id) LEFT JOIN " . DB_PREFIX . "category c ON c.category_id = p2c.category_id WHERE p.product_id = '" . (int) $product_id . "' AND pd.language_id = '" . (int) $this->config->get('config_language_id') . "' AND p.date_available <= NOW() AND p.status = '1' AND c.status=1");

					if ($product_query->num_rows) {
						$option_price = 0;
						$option_points = 0;
						$option_weight = 0;

						$option_data = array();

						foreach ($options as $product_option_id => $value) {

							$option_query = $this->db->query("SELECT po.product_option_id, po.pos_option_id, po.option_id, od.name, o.type FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_option_id = '" . (int) $product_option_id . "' AND po.product_id = '" . (int) $product_id . "' AND od.language_id = '" . (int) $this->config->get('config_language_id') . "'");

							if ($option_query->num_rows) {
								if ($option_query->row['type'] == 'select' || $option_query->row['type'] == 'radio' || $option_query->row['type'] == 'image') {
									$option_value_query = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points,pov.pos_option_value_id,pov.parent_id, pov.is_children, pov.points_prefix, pov.weight, pov.weight_prefix FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_value_id = '" . (int) $value . "' AND pov.product_option_id = '" . (int) $product_option_id . "' AND ovd.language_id = 1");

									if ($option_value_query->num_rows) {
										if ($option_value_query->row['price_prefix'] == '+') {
											$option_price += $option_value_query->row['price'];
										} elseif ($option_value_query->row['price_prefix'] == '-') {
											$option_price -= $option_value_query->row['price'];
										}

										if ($option_value_query->row['points_prefix'] == '+') {
											$option_points += $option_value_query->row['points'];
										} elseif ($option_value_query->row['points_prefix'] == '-') {
											$option_points -= $option_value_query->row['points'];
										}

										if ($option_value_query->row['weight_prefix'] == '+') {
											$option_weight += $option_value_query->row['weight'];
										} elseif ($option_value_query->row['weight_prefix'] == '-') {
											$option_weight -= $option_value_query->row['weight'];
										}

										if ($option_value_query->row['subtract'] && (!$option_value_query->row['quantity'] || ($option_value_query->row['quantity'] < $quantity))) {
											$stock = false;
										}

										$option_data[] = array(
											'product_option_id' => $product_option_id,
											'product_option_value_id' => $value,
											'option_id' => $option_query->row['option_id'],
											'option_value_id' => $option_value_query->row['option_value_id'],
											'name' => $option_query->row['name'],
											'value' => $option_value_query->row['name'],
											'pos_option_value_id' => $option_value_query->row['pos_option_value_id'],
											'parent_id' => $option_value_query->row['parent_id'],
											'is_children' => $option_value_query->row['is_children'],
											'type' => $option_query->row['type'],
											'quantity' => $option_value_query->row['quantity'],
											'subtract' => $option_value_query->row['subtract'],
											'price' => $option_value_query->row['price'],
											'price_prefix' => $option_value_query->row['price_prefix'],
											'points' => $option_value_query->row['points'],
											'points_prefix' => $option_value_query->row['points_prefix'],
											'weight' => $option_value_query->row['weight'],
											'weight_prefix' => $option_value_query->row['weight_prefix'],
											'pos_option_id' => $option_query->row['pos_option_id']
										);
									}
								} elseif ($option_query->row['type'] == 'checkbox' && is_array($value)) {
									foreach ($value as $product_option_value_id) {
										$option_value_query = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points,pov.pos_option_value_id, pov.parent_id, pov.is_children, pov.points_prefix, pov.weight, pov.weight_prefix FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_value_id = '" . (int) $product_option_value_id . "' AND pov.product_option_id = '" . (int) $product_option_id . "' AND ovd.language_id = 1");

										if ($option_value_query->num_rows) {
											if ($option_value_query->row['price_prefix'] == '+') {
												$option_price += $option_value_query->row['price'];
											} elseif ($option_value_query->row['price_prefix'] == '-') {
												$option_price -= $option_value_query->row['price'];
											}

											if ($option_value_query->row['points_prefix'] == '+') {
												$option_points += $option_value_query->row['points'];
											} elseif ($option_value_query->row['points_prefix'] == '-') {
												$option_points -= $option_value_query->row['points'];
											}

											if ($option_value_query->row['weight_prefix'] == '+') {
												$option_weight += $option_value_query->row['weight'];
											} elseif ($option_value_query->row['weight_prefix'] == '-') {
												$option_weight -= $option_value_query->row['weight'];
											}

											if ($option_value_query->row['subtract'] && (!$option_value_query->row['quantity'] || ($option_value_query->row['quantity'] < $quantity))) {
												$stock = false;
											}

											$option_data[] = array(
												'product_option_id' => $product_option_id,
												'product_option_value_id' => $product_option_value_id,
												'option_id' => $option_query->row['option_id'],
												'option_value_id' => $option_value_query->row['option_value_id'],
												'name' => $option_query->row['name'],
												'value' => $option_value_query->row['name'],
												'type' => $option_query->row['type'],
												'pos_option_value_id' => $option_value_query->row['pos_option_value_id'],
												'parent_id' => $option_value_query->row['parent_id'],
												'is_children' => $option_value_query->row['is_children'],
												'quantity' => $option_value_query->row['quantity'],
												'subtract' => $option_value_query->row['subtract'],
												'price' => $option_value_query->row['price'],
												'price_prefix' => $option_value_query->row['price_prefix'],
												'points' => $option_value_query->row['points'],
												'points_prefix' => $option_value_query->row['points_prefix'],
												'weight' => $option_value_query->row['weight'],
												'weight_prefix' => $option_value_query->row['weight_prefix'],
												'pos_option_id' => $option_query->row['pos_option_id']
											);
										}
									}
								} elseif ($option_query->row['type'] == 'text' || $option_query->row['type'] == 'textarea' || $option_query->row['type'] == 'file' || $option_query->row['type'] == 'date' || $option_query->row['type'] == 'datetime' || $option_query->row['type'] == 'time') {
									$option_data[] = array(
										'product_option_id' => $product_option_id,
										'product_option_value_id' => '',
										'option_id' => $option_query->row['option_id'],
										'option_value_id' => '',
										'name' => $option_query->row['name'],
										'value' => $value,
										'type' => $option_query->row['type'],
										'pos_option_value_id' => $option_value_query->row['pos_option_value_id'],
										'parent_id' => $option_value_query->row['parent_id'],
										'is_children' => $option_value_query->row['is_children'],
										'quantity' => '',
										'subtract' => '',
										'price' => '',
										'price_prefix' => '',
										'points' => '',
										'points_prefix' => '',
										'weight' => '',
										'weight_prefix' => '',
										'pos_option_id' => ''
									);
								}
							}
						}

						$price = $product_query->row['price'];

						// Product Discounts
						$discount_quantity = 0;



						$product_discount_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int) $product_id . "' AND customer_group_id = '" . (int) $this->config->get('config_customer_group_id') . "' AND quantity <= '" . (int) $discount_quantity . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity DESC, priority ASC, price ASC LIMIT 1");

						if ($product_discount_query->num_rows) {
							$price = $product_discount_query->row['price'];
						}

						// Product Specials
						$product_special_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int) $product_id . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY priority ASC, price ASC LIMIT 1");

						$special = false;
						$total = $price;
						if ($product_special_query->num_rows) {
							$special = $product_special_query->row['price'];
							$total = $special;
						}

						if ($special != 0) {
							$total = $special;
						} else {
							$special = false;
							$total = $price;
						}

						$avg = ($product_query->row['sprice'] + $product_query->row['zprice']) / 2;
						$discount = "";
						if ($avg > $product_query->row['price']) {
							$discount = $avg - $product_query->row['price'];
						} else {
							$discount = 0;
						}

						// Reward Points
						$product_reward_query = $this->db->query("SELECT points FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int) $product_id . "' AND customer_group_id = '" . (int) $this->config->get('config_customer_group_id') . "'");

						if ($product_reward_query->num_rows) {
							$reward = $product_reward_query->row['points'];
						} else {
							$reward = 0;
						}

						// Downloads
						$download_data = array();

						$download_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_download p2d LEFT JOIN " . DB_PREFIX . "download d ON (p2d.download_id = d.download_id) LEFT JOIN " . DB_PREFIX . "download_description dd ON (d.download_id = dd.download_id) WHERE p2d.product_id = '" . (int) $product_id . "' AND dd.language_id = '" . (int) $this->config->get('config_language_id') . "'");

						foreach ($download_query->rows as $download) {
							$download_data[] = array(
								'download_id' => $download['download_id'],
								'name' => $download['name'],
								'filename' => $download['filename'],
								'mask' => $download['mask']
							);
						}



						$this->load->model('catalog/product');
						$productAttributes = $this->model_catalog_product->getProductAttributes($product_id);



						if ($productAttributes != array()) {
							$attribute = $productAttributes[0]['attribute'][0]['text'];
						} else {
							$attribute = '';
						}


						if (!$product_query->row['quantity'] || ($product_query->row['quantity'] < $quantity)) {
							$stock = false;
						}

						$recurring_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "recurring` `p` JOIN `" . DB_PREFIX . "product_recurring` `pp` ON `pp`.`recurring_id` = `p`.`recurring_id` AND `pp`.`product_id` = " . (int) $product_query->row['product_id'] . " JOIN `" . DB_PREFIX . "recurring_description` `pd` ON `pd`.`recurring_id` = `p`.`recurring_id` AND `pd`.`language_id` = " . (int) $this->config->get('config_language_id') . " WHERE `pp`.`recurring_id` = " . (int) $recurring_id . " AND `status` = 1 AND `pp`.`customer_group_id` = " . (int) $this->config->get('config_customer_group_id'));

						if ($recurring_query->num_rows) {
							$recurring = array(
								'recurring_id' => $recurring_id,
								'name' => $recurring_query->row['name'],
								'frequency' => $recurring_query->row['frequency'],
								'price' => $recurring_query->row['price'],
								'cycle' => $recurring_query->row['cycle'],
								'duration' => $recurring_query->row['duration'],
								'trial' => $recurring_query->row['trial_status'],
								'trial_frequency' => $recurring_query->row['trial_frequency'],
								'trial_price' => $recurring_query->row['trial_price'],
								'trial_cycle' => $recurring_query->row['trial_cycle'],
								'trial_duration' => $recurring_query->row['trial_duration']
							);
						} else {
							$recurring = false;
						}
						if (($product_query->row['tax_class_id'] == 0)) {
							$rate = 0;
						} else {
							$tax_class = $this->getTaxClassapi($product_query->row['tax_class_id']);


							$tax_rates1 = $this->getTaxRulesapi($product_query->row['tax_class_id']);

							$tax_rates = array();
							foreach ($tax_rates1 as $tax_rate) {
								$tax_rates['value'][] = $this->getTaxRateapi($tax_rate['tax_rate_id']);
							}
							$product_query->row['taxs']['tax_class'] = array('tax_class_id' => 0);
							$product_query->row['taxs']['tax_rate']['rate'] = array('tax_rate_id' => 0);
							if (!empty($tax_class)) {
								$product_query->row['taxs']['tax_class'] = $tax_class;
							}
							if (!empty($tax_rates)) {
								$product_query->row[0]['tax_rate'] = $tax_rates;
							}
							$rate = $tax_rates['value'][0]['rate'];

							if ($tax_rates['value'][0]['type'] == 'P') {
								$rate = $total / 100 * $tax_rates['value'][0]['rate'];

							}


						}



						$this->load->model('tool/image');
						$image = "";
						$thumb = "";


						if (isset($product_query->row['product_Image']) && is_file(DIR_IMAGE . $product_query->row['product_Image'])) {
							$thumb = $this->model_tool_image->resize($product_query->row['product_Image'], 200, 200);
							$image = $this->model_tool_image->resize($product_query->row['product_Image'], 76, 76);

						} else {
							$image = $this->model_tool_image->resize('no_image.png', 76, 76);
							$thumb = $this->model_tool_image->resize('no_image.png', 200, 200);
						}

						$this->load->model('customer/customer');
						$tabledata = array();
						// $len = is_null($tabledata) ? 0 : count($tabledata['char_data']);
						$tableData = $this->model_customer_customer->getTableData($customer_id);
						// print_r($tableData);die;
						if ($tableData == array()) {
							$tableData['type'] = "";
							$tableData['table_number'] = 0;
							$tableData['service_charge_type'] = "";
							$tableData['comment'] = "";
						}
						//    print_r($tableData);die;
						// $tableData['type'] = "deliver";
						// $tableData['table_number'] = 8;
						// $tableData['comment'] = "test";

						// print_r(json_encode($product_attribute_data[0]['text']));
						// die;
						$res_data[$key] = array(
							'key' => $key,
							'product_id' => $product_query->row['product_id'],
							'name' => $product_query->row['name'],
							'model' => $product_query->row['model'],
							'shipping' => $product_query->row['shipping'],
							'thumb' => $thumb,
							'image' => $image,
							'option' => $option_data,
							'attribute' => json_encode($attribute),
							'download' => $download_data,
							'quantity' => $quantity,
							'minimum' => $product_query->row['minimum'],
							'subtract' => $product_query->row['subtract'],
							'stock' => $stock,
							'formatted_price' => $this->currency->format((float) $price, $storecurrency, 1.00000),
							'special' => $special,
							'total' => ($total + $option_price + $rate) * $quantity,
							'discount' => $discount,
							'tax_total' => $rate,
							'product_tax' => $rate * $quantity,
							'reward' => $reward * $quantity,
							'points' => ($product_query->row['points'] ? ($product_query->row['points'] + $option_points) * $quantity : 0),
							'tax_class_id' => $product_query->row['tax_class_id'],
							'tax_total' => $rate,
							'product_tax' => ($rate * $quantity),
							'tax' => $product_query->row['tax_class_id'],
							'weight' => ($product_query->row['weight'] + $option_weight) * $quantity,
							'weight_class_id' => $product_query->row['weight_class_id'],
							'length' => $product_query->row['length'],
							'width' => $product_query->row['width'],
							'height' => $product_query->row['height'],
							'length_class_id' => $product_query->row['length_class_id'],
							'recurring' => $recurring,
							'pos_product_id' => $product_query->row['pos_product_id'],
							'type' => $tableData['type'],
							'tableNumber' => $tableData['table_number'],
							'serviceChargeType' => $tableData['service_charge_type'],
							'comments' => $tableData['comment'],
						);
					} else {
						//	$this->remove($key);
					}
				}
			}
		}

		return $res_data;

	}
	public function getSpecial()
	{
		$this->checkPlugin();
		$this->load->model('catalog/product');
		$limit = $this->request->get['limit'];

		$filter_data = array(
			'sort' => 'pd.name',
			'order' => 'ASC',
			'start' => 0,
			'limit' => $limit
		);
		$results1 = $this->model_catalog_product->getProductSpecials($filter_data);
		foreach ($results1 as $result) {

			if ($result['image']) {
				$image = $result['image'];
			} else {
				$image = 'placeholder.png';
			}

			$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);

			if ((float) $result['special']) {
				$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
			} else {
				$special = false;
			}

			if ($this->config->get('config_tax')) {
				$tax = $result['special'];
			} else {
				$tax = false;
			}

			if ($this->config->get('config_review_status')) {
				$rating = $result['rating'];
			} else {
				$rating = false;
			}

			$results[] = array(
				'product_id' => $result['product_id'],
				'thumb' => $image,
				'name' => $result['name'],
				'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
				'price' => $price,
				'special' => $special,
				'tax' => $tax,
				'stock_status' => $result['stock_status'],
				'manufacturer' => $result['manufacturer'],
				'rating' => $rating,
				'quantity' => $result['quantity'],
				'reviews' => $result['reviews'],
				'href' => $this->url->link('product/product', 'product_id=' . $result['product_id'])
			);

			//$results[] = $result;
		}

		if (count($results)) {
			$json['success'] = true;
			$json['Special'] = $results;
		} else {
			$json['success'] = false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	} //seo
	public function getLatest()
	{
		$this->checkPlugin();
		$this->load->model('catalog/product');

		$this->load->model('tool/image');
		$limit = $this->request->get['limit'];
		$filter_data = array(
			'sort' => 'p.date_added',
			'order' => 'DESC',
			'start' => 0,
			'limit' => $limit
		);
		$results1 = $this->model_catalog_product->getProducts($filter_data);
		/*foreach($results1 as $result){
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						if ($result['image']) {
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						$image = $result['image'];
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						} else {
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						$image = 'placeholder.png';
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						}
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						if ((float)$result['special']) {
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						} else {
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						$special = false;
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						}
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						if ($this->config->get('config_tax')) {
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price']);
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						} else {
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						$tax = false;
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						}
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						if ($this->config->get('config_review_status')) {
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						$rating = $result['rating'];
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						} else {
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						$rating = false;
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						}
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						$results[] = array(
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						'product_id'  => $result['product_id'],
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						'thumb'       => $image,
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						'name'        => $result['name'],
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						'price'       => $price,
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						'special'     => $special,
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						'tax'         => $tax,
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						'stock_status'=> $result['stock_status'],
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						'manufacturer'=> $result['manufacturer'],
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						'rating'      => $rating,
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						'quantity'     => $result['quantity'],
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						'reviews'     => $result['reviews'],
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id']),
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						);
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						//$results[] = $result;
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						}*/
		foreach ($results1 as $result) {
			/*if ($result['image']) {
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																									$image = $this->model_tool_image->resize($result['image'], 200, 200);
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																									} else {
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																									$image = $this->model_tool_image->resize('placeholder.png', 200, 200);
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																									}*/
			if ($result['image']) {
				$image = $result['image'];
			} else {
				$image = 'placeholder.png';
			}

			if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
				$price = $result['price'];
			} else {
				$price = false;
			}

			if ((float) $result['special']) {
				$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
			} else {
				$special = false;
			}

			if ($this->config->get('config_tax')) {
				$tax = $result['special'];
			} else {
				$tax = false;
			}

			if ($this->config->get('config_review_status')) {
				$rating = $result['rating'];
			} else {
				$rating = false;
			}

			$results[] = array(
				'product_id' => $result['product_id'],
				'thumb' => $image,
				'name' => $result['name'],
				'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')) . '..',
				'price' => $price,
				'special' => $special,
				'tax' => $tax,
				'rating' => $rating,
				'href' => $this->url->link('product/product', 'product_id=' . $result['product_id'])
			);
		}
		if (count($results)) {
			$json['success'] = true;
			$json['Latest'] = $results;
		} else {
			$json['success'] = false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	} //seo
	public function getFeature()
	{
		$this->checkPlugin();
		$this->load->model('catalog/product');
		//$this->load->model('extension/module');
		$results = array();
		$limit = $this->request->get['limit'];
		$mod_name = $this->request->get['mod_name'];
		$setting = $this->getModuleapi($mod_name, 'featured');

		if (!empty($setting['product'])) {
			$products = array_slice($setting['product'], 0, (int) $limit);
			foreach ($products as $product_id) {

				$product_info = $this->model_catalog_product->getProduct($product_id);

				if ($product_info) {
					if ($product_info['image']) {
						$image = $product_info['image'];
					} else {
						$image = 'placeholder.png';
					}

					$price = $product_info['price'];

					if ((float) $product_info['special']) {
						$special = $product_info['special'];
					} else {
						$special = false;
					}

					if ($this->config->get('config_tax')) {
						$tax = $product_info['special'];
					} else {
						$tax = false;
					}

					if ($this->config->get('config_review_status')) {
						$rating = $product_info['rating'];
					} else {
						$rating = false;
					}

					$results[] = array(
						'product_id' => $product_info['product_id'],
						'thumb' => $image,
						'name' => $product_info['name'],
						'description' => utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
						'price' => $price,
						'special' => $special,
						'tax' => $tax,
						'stock_status' => $product_info['stock_status'],
						'manufacturer' => $product_info['manufacturer'],
						'rating' => $rating,
						'quantity' => $product_info['quantity'],
						'reviews' => $product_info['reviews'],
						'href' => $this->url->link('product/product', 'product_id=' . $product_info['product_id'])
					);
				}

				//$results[] = $this->model_catalog_product->getProduct($product_id);
			}
		}
		if (count($results)) {
			$json['success'] = true;
			$json['Feature'] = $results;
		} else {
			$json['success'] = false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	} //seo
	public function getBestseller()
	{
		$this->checkPlugin();
		$this->load->model('catalog/product');
		$limit = $this->request->get['limit'];
		$results1 = $this->model_catalog_product->getBestSellerProducts($limit);
		foreach ($results1 as $result) {

			if ($result['image']) {
				$image = $result['image'];
			} else {
				$image = 'placeholder.png';
			}

			$price = $result['price'];

			if ((float) $result['special']) {
				$special = $result['special'];
			} else {
				$special = false;
			}

			if ($this->config->get('config_tax')) {
				$tax = ($result['special'] ? $result['special'] : $result['price']);
			} else {
				$tax = false;
			}

			if ($this->config->get('config_review_status')) {
				$rating = $result['rating'];
			} else {
				$rating = false;
			}

			$results[] = array(
				'product_id' => $result['product_id'],
				'thumb' => $image,
				'name' => $result['name'],
				'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
				'price' => $price,
				'special' => $special,

				'tax' => $tax,
				'stock_status' => $result['stock_status'],
				'manufacturer' => $result['manufacturer'],
				'rating' => $rating,
				'quantity' => $result['quantity'],
				'reviews' => $result['reviews'],
				'href' => $this->url->link('product/product', 'product_id=' . $result['product_id']),
			);

			//$results[] = $result;
		}
		if (count($results)) {
			$json['success'] = true;
			$json['Bestseller'] = $results;
		} else {
			$json['success'] = false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	} //seo
	public function productreturn()
	{
		$this->checkPlugin();
		if (isset($this->request->post['return_data']) && $this->request->post['return_data'] != '') {
			$this->load->language('account/return');
			$this->load->model('account/return');
			$re_data = (array) json_decode(htmlspecialchars_decode($this->request->post['return_data']));
			$return_id = $this->model_account_return->addReturn($re_data);
		}
		if (count($return_id)) {
			$json['success'] = true;
			$json['return_id'] = $return_id;
		} else {
			$json['success'] = false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	} //seo
	public function taxclassapi()
	{
		$this->checkPlugin();
		//$this->load->model('catalog/product');
		$tax_class_id = $this->request->get['tax_class_id'];
		$tax_class = $this->getTaxClassapi($tax_class_id);
		$tax_rates1 = $this->getTaxRulesapi($tax_class_id);
		$tax_rates = array();
		foreach ($tax_rates1 as $tax_rate) {
			//$tax_rates[$tax_class_id] = $tax_rate;
			$tax_rates['value'][] = $this->getTaxRateapi($tax_rate['tax_rate_id']);
		}
		//echo "<pre>"; print_r($tax_rates);
		$results = array('tax_class' => $tax_class, 'tax_rate' => $tax_rates);

		if (count($results)) {
			$json['success'] = true;
			$json['taxvalue'] = $results;
		} else {
			$json['success'] = false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	} //seo
	public function searchproduct()
	{
		$this->checkPlugin();
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = 15;
		}

		$this->load->model('setting/setting');
		$storecurrency = $this->model_setting_setting->getSettingValue('config_currency', $store_id);

		$this->load->model('catalog/product');
		$this->load->model('catalog/category');

		$search = '';
		if (isset($this->request->get['seakeyword']) && $this->request->get['seakeyword'] != '') {
			$search = $this->request->get['seakeyword'];
		}


		$filter_data = array(
			'filter_name' => $search,
			'filter_tag' => '',
			'filter_description' => '',
			'filter_category_id' => '',
			'filter_sub_category' => '',
			'sort' => '',
			'order' => '',
			'start' => ($page - 1) * $limit,
			'store_id' => $store_id,
			'limit' => $limit
		);

		$seakeyword = '';
		$productsjson = array();
		$product_id = "";
		$product_options = $this->model_catalog_product->getProductOptions($product_id);
		$product_total = $this->model_catalog_product->getTotalProducts($filter_data);
		$results = $this->model_catalog_product->getProducts($filter_data);

		foreach ($results as $product) {



			$this->load->model('tool/image');

			if (isset($product['image']) && is_file(DIR_IMAGE . $product['image'])) {
				$product['image_path'] = $product['image'];
				$product['thumb'] = $this->model_tool_image->resize($product['image'], 200, 200);
				$product['image'] = $this->model_tool_image->resize($product['image'], 76, 76);

			} else {
				$product['image'] = $image = $this->model_tool_image->resize('no_image.png', 76, 76);
				$product['thumb'] = $image = $this->model_tool_image->resize('no_image.png', 200, 200);
				$product['image_path'] = "";
			}




			if ((float) $product['special']) {
				$special = floatval($product['special']);
			} else {
				$special = false;
			}


			if ($product['sprice'] != 0 && $product['zprice'] != 0 && $product['sprice'] > $product['price'] && $product['zprice'] > $product['price']) {
				$avg = ($product['sprice'] + $product['zprice']) / 2;
			} elseif ($product['sprice'] >= $product['price'] && $product['zprice'] <= $product['price']) {
				$avg = $product['sprice'];
			} elseif ($product['zprice'] >= $product['price'] && $product['sprice'] <= $product['price']) {
				$avg = $product['zprice'];
			} else {
				$avg = 0;
			}



			if ($special != false && $avg > $product['special']) {
				$discount = $avg - $product['special'];
			} else {
				if ($avg > $product['price'])
					$discount = $avg - $product['price'];
				else
					$discount = 0;
			}

			$categoryInfo = $this->model_catalog_product->getCategoryByProductId($product['product_id']);
			$catId = 0;
			$catName = '';

			if ($categoryInfo) {
				$catId = $categoryInfo['category_id'];
				$category_name = $this->model_catalog_category->getCategoryName($catId);
				if ($category_name)
					$catName = $category_name['name'];

			}
			$options = $this->model_catalog_product->getProductOptions($product['product_id']);

			$productsjson[] = array(
				'id' => $product['product_id'],
				'name' => $product['name'],
				'description' => $product['description'],
				'stock_status' => $product['stock_status'],
				'manufacturer' => $product['manufacturer'],
				'quantity' => $product['quantity'],
				'reviews' => $product['reviews'],
				'formatted_price' => $this->currency->format((float) $product['price'], $storecurrency, 1.00000),
				'price' => floatval($product['price']),
				'sprice' => $product['sprice'],
				'zprice' => $product['zprice'],
				'average' => $avg,
				'discount' => $discount,
				'href' => $this->url->link('product/product', 'product_id=' . $product['product_id']),
				'thumb' => $product['thumb'],
				'image' => $product['image'],
				'image_path' => $product['image_path'],
				'sku' => $product['sku'],
				'special' => $special,
				'rating' => $product['rating'],
				'status' => $product['status'],
				'type' => $product['type'],
				'category_id' => $catId,
				'category_name' => $catName,
				'options' => $options,
				'product_description' => $product['product_description']
			);
		}

		if (count($productsjson)) {
			$json['success'] = true;
			$json['products'] = $productsjson;
			$json['limit'] = $limit;
			$json['total'] = $product_total;

		} else {
			$json['success'] = false;
			$json['products'] = array();
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	} //seo

	public function searchproductStore()
	{
		$this->checkPlugin();
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = 15;
		}

		$this->load->model('setting/setting');
		$storecurrency = $this->model_setting_setting->getSettingValue('config_currency', $store_id);

		$this->load->model('catalog/product');
		$this->load->model('catalog/category');

		$search = '';
		if (isset($this->request->get['seakeyword']) && $this->request->get['seakeyword'] != '') {
			$search = $this->request->get['seakeyword'];
		}


		$filter_data = array(
			'filter_name' => $search,
			'filter_tag' => '',
			'filter_description' => '',
			'filter_category_id' => '',
			'filter_sub_category' => '',
			'sort' => '',
			'order' => '',
			'start' => ($page - 1) * $limit,
			'store_id' => $store_id,
			'limit' => $limit
		);

		$seakeyword = '';
		$productsjson = array();
		$product_id = "";
		$product_options = $this->model_catalog_product->getProductOptions($product_id);
		$product_total = $this->model_catalog_product->getTotalProducts($filter_data);
		$results = $this->model_catalog_product->getProductsStore($filter_data);

		foreach ($results as $product) {



			$this->load->model('tool/image');

			if (isset($product['image']) && is_file(DIR_IMAGE . $product['image'])) {
				$product['thumb'] = $this->model_tool_image->resize($product['image'], 200, 200);
				$product['image'] = $this->model_tool_image->resize($product['image'], 76, 76);

			} else {
				$product['image'] = $this->model_tool_image->resize('no_image.png', 76, 76);
				$product['thumb'] = $this->model_tool_image->resize('no_image.png', 200, 200);
			}



			if ((float) $product['special']) {
				$special = floatval($product['special']);
			} else {
				$special = false;
			}


			if ($product['sprice'] && $product['zprice'] != 0) {
				$avg = ($product['sprice'] + $product['zprice']) / 2;
			} elseif ($product['sprice'] != 0) {
				$avg = $product['sprice'];
			} elseif ($product['zprice'] != 0) {
				$avg = $product['zprice'];
			} else {
				$avg = 0;
			}



			if ($special != false && $avg > $product['special']) {
				$discount = $avg - $product['special'];
			} else {
				if ($avg > $product['price'])
					$discount = $avg - $product['price'];
				else
					$discount = 0;
			}


			$categoryInfo = $this->model_catalog_product->getCategoryByProductId($product['product_id']);
			$catId = 0;
			$catName = '';

			if ($categoryInfo) {
				$catId = $categoryInfo['category_id'];
				$category_name = $this->model_catalog_category->getCategoryName($catId);
				if ($category_name)
					$catName = $category_name['name'];

			}
			$options = $this->model_catalog_product->getProductOptions($product['product_id']);

			$productsjson[] = array(
				'id' => $product['product_id'],
				'name' => $product['name'],
				'description' => $product['description'],
				'stock_status' => $product['stock_status'],
				'manufacturer' => $product['manufacturer'],
				'quantity' => $product['quantity'],
				'reviews' => $product['reviews'],
				'formatted_price' => $this->currency->format((float) $product['price'], $storecurrency, 1.00000),
				'price' => floatval($product['price']),
				'sprice' => $product['sprice'],
				'zprice' => $product['zprice'],
				'average' => $avg,
				'discount' => $discount,
				'href' => $this->url->link('product/product', 'product_id=' . $product['product_id']),
				'thumb' => $product['thumb'],
				'image' => $product['image'],
				'sku' => $product['sku'],
				'special' => $special,
				'rating' => $product['rating'],
				'status' => $product['status'],
				'category_id' => $catId,
				'category_name' => $catName,
				'options' => $options,
				'product_description' => $product['product_description']
			);
		}

		if (count($productsjson)) {
			$json['success'] = true;
			$json['products'] = $productsjson;
			$json['limit'] = $limit;
			$json['total'] = $product_total;

		} else {
			$json['success'] = false;
			$json['products'] = array();
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	} //seo




	//search order

	public function searchOrder()
	{

		$this->checkPlugin();
		$this->load->model('account/order');
		$order_id = '';
		$customer = '';
		$total = '';
		$order_status_id = '';
		$date_added = '';
		if (isset($this->request->post['order_id']) && $this->request->post['order_id'] != '') {
			$order_id = $this->request->post['order_id'];
		}
		if (isset($this->request->post['customer']) && $this->request->post['customer'] != '') {
			$customer = $this->request->post['customer'];
		}
		if (isset($this->request->post['total']) && $this->request->post['total'] != '') {
			$total = $this->request->post['total'];
		}
		if (isset($this->request->post['order_status_id']) && $this->request->post['order_status_id'] != '') {
			$order_status_id = $this->request->post['order_status_id'];
		}
		if (isset($this->request->post['date_added']) && $this->request->post['date_added'] != '') {
			$date_added = $this->request->post['date_added'];
		}
		$filter_data = array(
			'filter_order_id' => $order_id,
			'filter_customer' => $customer,
			'filter_order_status_id' => $order_status_id,
			'filter_total' => $total,
			'filter_date_added' => $date_added

		);
		$orders = array();
		//$results = $this->model_account_order->getTotalOrdersSearch($filter_data);

		$results = $this->model_account_order->getOrderssearch($filter_data);
		if (count($results)) {
			foreach ($results as $result) {

				$orders[] = array(
					'order_id' => $result['order_id'],
					'customer' => $result['customer'],
					/*'order_status_id'  => $result['order_status_id'] ? $result['order_status_id'] : $this->language->get('text_missing'),*/
					'total' => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
					'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				);
			}

			$json['success'] = true;
			$json['orders'] = $results;
		} else {
			$json['success'] = false;
		}


		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}


	}







	public function orders()
	{

		$this->checkPlugin();

		$orderData['orders'] = array();

		$this->load->model('account/order');

		/*check offset parameter*/
		if (isset($this->request->get['offset']) && $this->request->get['offset'] != "" && ctype_digit($this->request->get['offset'])) {
			$offset = $this->request->get['offset'];
		} else {
			$offset = 0;
		}

		/*check limit parameter*/
		if (isset($this->request->get['limit']) && $this->request->get['limit'] != "" && ctype_digit($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = 10000;
		}

		/*get all orders of user*/
		$results = $this->model_account_order->getAllOrders($offset, $limit);

		$orders = array();

		if (count($results)) {
			foreach ($results as $result) {

				$product_total = $this->model_account_order->getTotalOrderProductsByOrderId($result['order_id']);
				$voucher_total = $this->model_account_order->getTotalOrderVouchersByOrderId($result['order_id']);

				$orders[] = array(
					'order_id' => $result['order_id'],
					'name' => $result['firstname'] . ' ' . $result['lastname'],
					'status' => $result['status'],
					'date_added' => $result['date_added'],
					'products' => ($product_total + $voucher_total),
					'total' => $result['total'],
					'currency_code' => $result['currency_code'],
					'currency_value' => $result['currency_value'],
				);
			}

			$json['success'] = true;
			$json['orders'] = $orders;
		} else {
			$json['success'] = false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}



	private function checkPlugin()
	{

		$json = array("success" => false);

		/*check rest api is enabled*/
		if (!$this->config->get('feed_rest_api_status')) {
			$json["error"] = 'API is disabled. Enable it!';
		}

		/*validate api security key*/
		if ($this->config->get('feed_rest_api_key') && (!isset($this->request->get['key']) || $this->request->get['key'] != $this->config->get('feed_rest_api_key'))) {
			$json["error"] = 'Invalid secret key';
		}

		if (isset($json["error"])) {
			$this->response->addHeader('Content-Type: application/json');
			echo (json_encode($json));
			exit;
		} else {
			$this->response->setOutput(json_encode($json));
		}
	}
	private function getOrdersapi($user_id, $start = 0, $limit = 20, $order_status_id = '', $store_id = 0, $shippingtype)
	{

		if ($start < 0) {
			$start = 0;
		}
		if ($start != 0) {
			$start = ($start - 1) * $limit;
		}
		if ($limit < 1) {
			$limit = 1;
		}
		if ($order_status_id == '' && $user_id == 0)

			$result_query = "SELECT CONCAT(o.shipping_address_1,',',o.shipping_city,',',o.shipping_country,'-',o.shipping_postcode) as shipping_address, o.order_status_id,o.custom_field, o.payment_method,o.order_id, o.firstname, o.lastname, o.telephone,o.customer_id, o.deliveryType,o.shippingProvider,o.deliveryMethod,o.bid_id,o.source,o.source_value,o.cooking_instructions,o.receiver_name,o.receiver_phone,o.table_number,o.service_charge_type,o.order_comments,o.type,o.service_charge,o.shippingtype,o.shippingsubtype,o.comment, os.name as status, o.date_added, o.total, o.currency_code, o.currency_value FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE  o.order_status_id > '0' AND o.store_id = '" . (int) $store_id . "' AND os.language_id = '" . (int) $this->config->get('config_language_id') . "'";
		else if ($order_status_id == '' && $user_id != 0)
			$result_query = "SELECT CONCAT(o.shipping_address_1,',',o.shipping_city,',',o.shipping_country,'-',o.shipping_postcode) as shipping_address, o.order_status_id,o.custom_field, o.payment_method,o.order_id, o.firstname, o.lastname, o.telephone,o.customer_id,o.deliveryType, o.shippingProvider,o.deliveryMethod,o.bid_id,o.source,o.source_value,o.cooking_instructions,o.receiver_name,o.receiver_phone,o.table_number,o.service_charge_type,o.order_comments,o.type,o.service_charge,o.shippingtype,o.shippingsubtype,o.comment, os.name as status, o.date_added, o.total, o.currency_code, o.currency_value FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE  o.order_status_id > '0' AND o.store_id = '" . (int) $store_id . "' AND os.language_id = '" . (int) $this->config->get('config_language_id') . "' AND o.customer_id='" . (int) $user_id . "'";
		else if ($user_id != 0)
			$result_query = "SELECT CONCAT(o.shipping_address_1,',',o.shipping_city,',',o.shipping_country,'-',o.shipping_postcode) as shipping_address, o.order_status_id, o.custom_field, o.payment_method,o.order_id, o.firstname, o.lastname, o.telephone,o.customer_id,o.deliveryType, o.shippingProvider,o.deliveryMethod,o.bid_id,o.source,o.source_value,o.cooking_instructions,o.receiver_name,o.receiver_phone,o.table_number,o.service_charge_type,o.order_comments,o.type,o.service_charge,o.shippingtype,o.shippingsubtype,o.comment, os.name as status, o.date_added, o.total, o.currency_code, o.currency_value FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE  o.order_status_id IN (" . $order_status_id . ") AND o.store_id = '" . (int) $store_id . "' AND os.language_id = '" . (int) $this->config->get('config_language_id') . "' AND o.customer_id='" . (int) $user_id . "'";
		else
			$result_query = "SELECT CONCAT(o.shipping_address_1,',',o.shipping_city,',',o.shipping_country,'-',o.shipping_postcode) as shipping_address,o.custom_field, o.order_status_id, o.payment_method,o.order_id, o.firstname, o.lastname, o.telephone,o.customer_id,o.deliveryType, o.shippingProvider,o.deliveryMethod,o.bid_id,o.source,o.source_value,o.cooking_instructions,o.receiver_name,o.receiver_phone,o.table_number,o.service_charge_type,o.order_comments,o.type,o.service_charge,o.shippingtype,o.shippingsubtype,o.comment, os.name as status, o.date_added, o.total, o.currency_code, o.currency_value FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE  o.order_status_id IN (" . $order_status_id . ") AND o.store_id = '" . (int) $store_id . "' AND os.language_id = '" . (int) $this->config->get('config_language_id') . "'";


		if ($shippingtype != "") {
			$result_query = $result_query . " AND o.shippingtype = '" . $shippingtype . "'";
		}

		$result_query = $result_query . " ORDER BY o.order_id DESC LIMIT " . (int) $start . "," . (int) $limit;

		$query = $this->db->query($result_query);

		return $query->rows;
	}


	private function getMerchantOrdersapi($user_id, $start = 0, $limit = 20, $order_status_ids = '', $store_id = 0, $stores)
	{

		if ($start < 0) {
			$start = 0;
		}
		if ($start != 0) {
			$start = ($start - 1) * $limit;
		}
		if ($limit < 1) {
			$limit = 1;
		}
		// print_r($stores);die;
		$ids = implode(', ', $stores);
		$status_id = implode(', ', $order_status_ids);

		if ($order_status_ids == '' && $user_id == 0)

			$result_query = "SELECT CONCAT(o.shipping_address_1,',',o.shipping_city,',',o.shipping_country,'-',o.shipping_postcode) as shipping_address, o.order_status_id,o.custom_field, o.payment_method,o.order_id, o.firstname, o.lastname,o.store_id,o.store_name, o.telephone, o.deliveryType,o.shippingProvider,o.deliveryMethod,o.bid_id,o.source,o.source_value,o.cooking_instructions,o.receiver_name,o.receiver_phone,o.table_number,o.service_charge_type,o.order_comments,o.type,o.service_charge,o.shippingtype,o.shippingsubtype,o.comment, os.name as status, o.date_added, o.total, o.currency_code, o.currency_value FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status in ($status_id) WHERE  o.order_status_id > '0' AND o.store_id  in ($ids) AND os.language_id = '" . (int) $this->config->get('config_language_id') . "'";
		else if ($order_status_ids == '' && $user_id != 0)
			$result_query = "SELECT CONCAT(o.shipping_address_1,',',o.shipping_city,',',o.shipping_country,'-',o.shipping_postcode) as shipping_address, o.order_status_id,o.custom_field, o.payment_method,o.order_id, o.firstname, o.lastname, o.store_id,o.store_name,o.telephone,o.deliveryType, o.shippingProvider,o.deliveryMethod,o.bid_id,o.source,o.source_value,o.cooking_instructions,o.receiver_name,o.receiver_phone,o.table_number,o.service_charge_type,o.order_comments,o.type,o.service_charge,o.shippingtype,o.shippingsubtype,o.comment, os.name as status, o.date_added, o.total, o.currency_code, o.currency_value FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE  o.order_status_id in ($status_id) AND o.store_id  in ($ids) AND os.language_id = '" . (int) $this->config->get('config_language_id') . "' AND o.customer_id='" . (int) $user_id . "'";
		else if ($user_id != 0)
			$result_query = "SELECT CONCAT(o.shipping_address_1,',',o.shipping_city,',',o.shipping_country,'-',o.shipping_postcode) as shipping_address, o.order_status_id, o.custom_field, o.payment_method,o.order_id, o.firstname, o.lastname,o.store_id, o.store_name,o.telephone,o.deliveryType, o.shippingProvider,o.deliveryMethod,o.bid_id,o.source,o.source_value,o.cooking_instructions,o.receiver_name,o.receiver_phone,o.table_number,o.service_charge_type,o.order_comments,o.type,o.service_charge,o.shippingtype,o.shippingsubtype,o.comment, os.name as status, o.date_added, o.total, o.currency_code, o.currency_value FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE  o.order_status_id IN (" . $status_id . ") AND o.store_id in ($ids) AND os.language_id = '" . (int) $this->config->get('config_language_id') . "' AND o.customer_id='" . (int) $user_id . "'";
		else
			$result_query = "SELECT CONCAT(o.shipping_address_1,',',o.shipping_city,',',o.shipping_country,'-',o.shipping_postcode) as shipping_address,o.custom_field, o.order_status_id, o.payment_method,o.order_id, o.firstname, o.lastname,o.store_id, o.store_name,o.telephone,o.deliveryType, o.shippingProvider,o.deliveryMethod,o.bid_id,o.source,o.source_value,o.cooking_instructions,o.receiver_name,o.receiver_phone,o.table_number,o.service_charge_type,o.order_comments,o.type,o.service_charge,o.shippingtype,o.shippingsubtype,o.comment, os.name as status, o.date_added, o.total, o.currency_code, o.currency_value FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE  o.order_status_id IN (" . $status_id . ") AND o.store_id in ($ids) AND os.language_id = '" . (int) $this->config->get('config_language_id') . "'";



		$result_query = $result_query . " ORDER BY o.order_id DESC LIMIT " . (int) $start . "," . (int) $limit;

		$query = $this->db->query($result_query);

		return $query->rows;
	}

	private function getOrdersTotalapi($user_id, $order_status_id = '', $store_id = 0)
	{


		if ($order_status_id == '' && $user_id == 0)
			$query = $this->db->query("SELECT count(o.order_id) AS total  FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE  o.order_status_id > '0' AND o.store_id = '" . (int) $store_id . "' AND os.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY o.order_id DESC ");
		else if ($order_status_id == '' && $user_id != 0)
			$query = $this->db->query("SELECT count(o.order_id) AS total FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE  o.order_status_id > '0' AND o.store_id = '" . (int) $store_id . "' AND os.language_id = '" . (int) $this->config->get('config_language_id') . "' AND o.customer_id='" . (int) $user_id . "' ORDER BY o.order_id DESC  ");
		else if ($user_id != 0)
			$query = $this->db->query("SELECT count(o.order_id) AS total  FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE  o.order_status_id =" . (int) $order_status_id . " AND o.store_id = '" . (int) $store_id . "' AND os.language_id = '" . (int) $this->config->get('config_language_id') . "' AND o.customer_id='" . (int) $user_id . "' ORDER BY o.order_id DESC  ");
		else
			$query = $this->db->query("SELECT count(o.order_id) AS total  FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE  o.order_status_id =" . (int) $order_status_id . " AND o.store_id = '" . (int) $store_id . "' AND os.language_id = '" . (int) $this->config->get('config_language_id') . "'  ORDER BY o.order_id DESC  ");

		return $query->row['total'];
	}
	private function getOrdersTotalapiMerchant($user_id, $order_status_ids, $stores)
	{
		$ids = implode(', ', $stores);

		$ord_ids = implode(', ', $order_status_ids);

		if ($order_status_ids == '' && $user_id == 0)
			$query = $this->db->query("SELECT count(o.order_id) AS total  FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE  o.order_status_id > '0' AND o.store_id  in ($ids) AND os.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY o.order_id DESC ");
		else if ($order_status_ids == '' && $user_id != 0)
			$query = $this->db->query("SELECT count(o.order_id) AS total FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE  o.order_status_id > '0' AND o.store_id  in ($ids) AND os.language_id = '" . (int) $this->config->get('config_language_id') . "' AND o.customer_id='" . (int) $user_id . "' ORDER BY o.order_id DESC  ");
		else if ($user_id != 0)
			$query = $this->db->query("SELECT count(o.order_id) AS total  FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE  o.order_status_id in ($ord_ids) AND o.store_id  in ($ids) AND os.language_id = '" . (int) $this->config->get('config_language_id') . "' AND o.customer_id='" . (int) $user_id . "' ORDER BY o.order_id DESC  ");
		else
			$query = $this->db->query("SELECT count(o.order_id) AS total  FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE  o.order_status_id in ($ord_ids) AND o.store_id  in ($ids) AND os.language_id = '" . (int) $this->config->get('config_language_id') . "'  ORDER BY o.order_id DESC  ");

		return $query->row['total'];
	}
	private function getOrdersCountApi($user_id, $order_status_id = '')
	{

		if ($order_status_id == '')
			$query = $this->db->query("SELECT count(*) as count FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE  o.order_status_id > '0' AND o.store_id = '" . (int) $this->config->get('config_store_id') . "' AND os.language_id = '" . (int) $this->config->get('config_language_id') . "' AND o.customer_id='" . (int) $user_id . "' ORDER BY o.order_id DESC");
		else if ($user_id != -1)
			$query = $this->db->query("SELECT count(*) as count FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE  o.order_status_id =" . (int) $order_status_id . " AND o.store_id = '" . (int) $this->config->get('config_store_id') . "' AND os.language_id = '" . (int) $this->config->get('config_language_id') . "' AND o.customer_id='" . (int) $user_id . "' ORDER BY o.order_id DESC");


		return $query->rows;
	}
	private function editCustomers($data)
	{

		$this->db->query("UPDATE " . DB_PREFIX . "customer SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', customer_group_id = '" . $this->db->escape($data['customer_group_id']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? serialize($data['custom_field']) : '') . "' WHERE customer_id = '" . (int) $data['customer_id'] . "'");

	}
	private function getAddressesapi($cust_id)
	{
		$address_data = array();
		//echo $this->customer->getId()
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int) $cust_id . "' order by address_id desc");

		foreach ($query->rows as $result) {
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int) $result['country_id'] . "'");

			if ($country_query->num_rows) {
				$country = $country_query->row['name'];
				$iso_code_2 = $country_query->row['iso_code_2'];
				$iso_code_3 = $country_query->row['iso_code_3'];
				$address_format = $country_query->row['address_format'];
			} else {
				$country = '';
				$iso_code_2 = '';
				$iso_code_3 = '';
				$address_format = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int) $result['zone_id'] . "'");

			if ($zone_query->num_rows) {
				$zone = $zone_query->row['name'];
				$zone_code = $zone_query->row['code'];
			} else {
				$zone = '';
				$zone_code = '';
			}

			//$address_data[$result['address_id']] = array(
			$address_data[] = array(
				'address_id' => $result['address_id'],
				'firstname' => $result['firstname'],
				'lastname' => $result['lastname'],
				'company' => $result['company'],
				'address_1' => $result['address_1'],
				'address_2' => $result['address_2'],
				'postcode' => $result['postcode'],
				'city' => $result['city'],
				'zone_id' => $result['zone_id'],
				'zone' => $zone,
				'zone_code' => $zone_code,
				'country_id' => $result['country_id'],
				'country' => $country,
				'iso_code_2' => $iso_code_2,
				'iso_code_3' => $iso_code_3,
				'address_format' => $address_format,
				'custom_field' => $result['custom_field']

			);
		}

		return $address_data;
	}
	private function addAddressapi($data)
	{
		$this->db->query("INSERT INTO " . DB_PREFIX . "address SET customer_id = '" . (int) $data['customer_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', company = '" . $this->db->escape($data['company']) . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" . $this->db->escape($data['address_2']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', city = '" . $this->db->escape($data['city']) . "', zone_id = '" . (int) $data['zone_id'] . "', country_id = '" . (int) $data['country_id'] . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? serialize($data['custom_field']) : '') . "'");

		$address_id = $this->db->getLastId();

		if (($data['set_default'] == 1)) {
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int) $address_id . "' WHERE customer_id = '" . (int) $data['customer_id'] . "'");
		}
		return $address_id;
	}
	private function getAddressapi($address_id, $cust_id)
	{
		$address_query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "address WHERE address_id = '" . (int) $address_id . "' AND customer_id = '" . (int) $cust_id . "'");

		if ($address_query->num_rows) {
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int) $address_query->row['country_id'] . "'");

			if ($country_query->num_rows) {
				$country = $country_query->row['name'];
				$iso_code_2 = $country_query->row['iso_code_2'];
				$iso_code_3 = $country_query->row['iso_code_3'];
				$address_format = $country_query->row['address_format'];
			} else {
				$country = '';
				$iso_code_2 = '';
				$iso_code_3 = '';
				$address_format = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int) $address_query->row['zone_id'] . "'");

			if ($zone_query->num_rows) {
				$zone = $zone_query->row['name'];
				$zone_code = $zone_query->row['code'];
			} else {
				$zone = '';
				$zone_code = '';
			}

			$address_data = array(
				'address_id' => $address_query->row['address_id'],
				'firstname' => $address_query->row['firstname'],
				'lastname' => $address_query->row['lastname'],
				'company' => $address_query->row['company'],
				'address_1' => $address_query->row['address_1'],
				'address_2' => $address_query->row['address_2'],
				'postcode' => $address_query->row['postcode'],
				'city' => $address_query->row['city'],
				'zone_id' => $address_query->row['zone_id'],
				'zone' => $zone,
				'zone_code' => $zone_code,
				'country_id' => $address_query->row['country_id'],
				'country' => $country,
				'iso_code_2' => $iso_code_2,
				'iso_code_3' => $iso_code_3,
				'address_format' => $address_format,
				'custom_field' => unserialize($address_query->row['custom_field'])
			);

			return $address_data;
		} else {
			return false;
		}
	}
	private function editAddressapi($data)
	{

		$this->db->query("UPDATE " . DB_PREFIX . "address SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', company = '" . $this->db->escape($data['company']) . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" . $this->db->escape($data['address_2']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', city = '" . $this->db->escape($data['city']) . "', zone_id = '" . (int) $data['zone_id'] . "', country_id = '" . (int) $data['country_id'] . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? serialize($data['custom_field']) : '') . "' WHERE address_id  = '" . (int) $data['address_id'] . "' AND customer_id = '" . (int) $data['customer_id'] . "'");

		if (($data['set_default'] == 1)) {
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int) $data['address_id'] . "' WHERE customer_id = '" . (int) $data['customer_id'] . "'");
		}
		return true;

	}

	private function logindeliveryboyapi($email, $pass)
	{
		$customer_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "deliveryboy WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "' AND status = '1'");

		return $customer_query->num_rows;
	}
	private function deliveryboyorderlistapi($deliveryboyid, $status = '')
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "delivery_order WHERE delivery_boy_id = '" . $deliveryboyid . "'";
		if ($status && $status != '') {
			$sql .= " AND status = " . $status;
		}
		$customer_query = $this->db->query($sql);
		return $customer_query->num_rows;
	}
	private function deleteAddressapi($address_id, $cust_id)
	{
		$this->event->trigger('pre.customer.delete.address', $address_id);

		$this->db->query("DELETE FROM " . DB_PREFIX . "address WHERE address_id = '" . (int) $address_id . "' AND customer_id = '" . (int) $cust_id . "'");
		return true;
	}
	private function getSettingsapi($store_id = 0)
	{
		$data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int) $store_id . "'");

		foreach ($query->rows as $result) {
			if (!$result['serialized']) {
				$data[$result['key']] = $result['value'];
			} else {
				$data[$result['key']] = unserialize($result['value']);
			}
		}

		return $data;
	}
	public function getModuleapi($mod_name, $code)
	{
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "module WHERE name = '" . $mod_name . "' and code = '" . $code . "' ");
		//print_r($query->row['setting']);
		if ($query->row) {
			return (array) json_decode($query->row['setting']);
		} else {
			return array();
		}
	}
	public function getLanguageapi($code)
	{
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "language WHERE code = '" . $code . "'");

		return $query->row;
	}

	/*private function getBannerapi($bannername) {
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																			
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																			$query1 = $this->db->query("SELECT * FROM " . DB_PREFIX . "banner WHERE name = '" . $bannername . "' ");
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																			$ids = 0;
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																			if(!empty($query1->rows)){
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																			$ids = $query1->rows[0]['banner_id'];
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																			}
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																			
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																			if($ids){
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "banner_image bi LEFT JOIN " . DB_PREFIX . "banner_image_description bid ON (bi.banner_image_id  = bid.banner_image_id) WHERE bi.banner_id = '" . $ids . "' AND bid.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY bi.sort_order ASC");
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																			return $query->rows;
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																			}
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																			}*/
	public function forgotten()
	{
		$this->checkPlugin();
		$this->load->language('mail/forgotten');
		$this->load->model('account/customer');
		$results = array();
		$fl = 1;
		if (!isset($this->request->post['email'])) {
			$results = $this->language->get('error_email');
			$fl = 0;
		} elseif (!$this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
			$fl = 0;
			$results = 'This Email Not Register';
		}

		if ($fl == 1) {

			$password = substr(sha1(uniqid(mt_rand(), true)), 0, 10);

			$this->model_account_customer->editPassword($this->request->post['email'], $password);

			$subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));

			$message = sprintf($this->language->get('text_greeting'), $this->config->get('config_name')) . "\n\n";
			$message .= $this->language->get('text_password') . "\n\n";
			$message .= $password;

			$mail = new Mail($this->config->get('config_mail'));
			$mail->setTo($this->request->post['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($this->config->get('config_name'));
			$mail->setSubject($subject);
			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->send();

			$results = 'Success forgot password check Email Address';

			// Add to activity log
			$customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);

			if ($customer_info) {
				$this->load->model('account/activity');

				$activity_data = array(
					'customer_id' => $customer_info['customer_id'],
					'name' => $customer_info['firstname'] . ' ' . $customer_info['lastname']
				);

				$this->model_account_activity->addActivity('forgotten', $activity_data);
			}
		}
		if (count($results)) {
			$json['success'] = true;
			$json['forgetpsw'] = $results;
		} else {
			$json['success'] = false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}
	public function wishlist()
	{
		$this->checkPlugin();
		$this->load->model('account/customer');
		$results = array();
		$fl = 1;
		if (isset($this->request->post['customer_id']) && $this->request->post['customer_id'] != '') {
			$customer_info = $this->model_account_customer->getCustomer($this->request->post['customer_id']);
			if (!empty($customer_info)) {
				if ($customer_info['wishlist'] && is_string($customer_info['wishlist'])) {

					$wishlist = unserialize($customer_info['wishlist']);
					if (!empty($wishlist)) {
						$this->load->model('catalog/product');
						foreach ($wishlist as $wish_id) {
							$results[] = $this->model_catalog_product->getProduct($wish_id);
						}
					}

				} else {
					$results = 'Wishlist Empty';
				}
			} else {
				$results = 'Customer Not Available';

			}
			if (count($results)) {
				$json['success'] = true;
				$json['wishlist'] = $results;
			} else {
				$json['success'] = false;
			}

			if ($this->debugIt) {
				echo '<pre>';
				print_r($json);
				echo '</pre>';

			} else {
				$this->response->setOutput(json_encode($json));
			}
		}
	}

	public function addtocart()
	{


		$this->checkPlugin();
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}
		$this->load->language('checkout/cart');
		$inputjson = file_get_contents('php://input');
		$data = json_decode($inputjson, true);

		if (isset($data['customer_id'])) {
			$customer_id = (int) $data['customer_id'];
		} else {
			$customer_id = 0;
		}

		if (isset($data['product_id'])) {
			$product_id = (int) ($data['product_id']);
		} else {
			$product_id = 0;
		}

		$this->load->model('catalog/product');
		$sstaus = false;

		if ($customer_id != '' && $product_id != '') {
			$product_info = $this->model_catalog_product->getProduct($product_id, 1, $store_id);


			if ($product_info) {
				if (isset($data['quantity'])) {
					$quantity = (int) $data['quantity'];
				} else {
					$quantity = 1;
				}


				if (isset($data['option']) && $data['option'] != '') {
					$option = array_filter($data['option']);
				} else {
					$option = "";
				}




				$results = $this->cartnewadd($data['product_id'], $customer_id, $quantity, $option, 0);

				$sstaus = true;


			} else {
				$results = 'Product Not Exist';
			}
		} else {
			$results = 'Please Post customer_id and product_id';
		}

		if (count(array($results))) {
			$json['success'] = $sstaus;
			$json['cartproduct'] = $results;
		} else {
			$json['success'] = false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}

	public function addmultipletocart()
	{


		$this->checkPlugin();
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}
		$this->load->language('checkout/cart');
		$inputjson = file_get_contents('php://input');
		$data = json_decode($inputjson, true);

		if (isset($data['customer_id'])) {
			$customer_id = (int) $data['customer_id'];
		} else {
			$customer_id = 0;
		}

		$products = $data['products'];


		foreach ($products as $product) {

			$this->load->model('catalog/product');
			$sstaus = false;

			if ($customer_id != '' && $product['product_id'] != '') {
				$product_info = $this->model_catalog_product->getProduct($product['product_id'], 1, $store_id);

				if ($product_info) {
					if (isset($product['quantity'])) {
						$quantity = (int) $product['quantity'];
					} else {
						$quantity = 1;
					}


					if (isset($product['option']) && $product['option'] != '') {
						$option = array_filter($product['option']);
					} else {
						$option = "";
					}




					$results = $this->cartnewadd($product['product_id'], $customer_id, $quantity, $option, 0);

					$sstaus = true;


				} else {
					$results = 'Product Not Exist';
				}

			} else {
				$results = 'Please Post customer_id and product_id';
			}
		}



		if (count(array($results))) {
			$json['success'] = $sstaus;
			$json['cartproduct'] = $results;
		} else {
			$json['success'] = false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}

	public function updateCartItems()
	{

		$this->checkPlugin();
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}
		$this->load->language('checkout/cart');
		$inputjson = file_get_contents('php://input');
		$data = json_decode($inputjson, true);



		if (isset($data['customer_id'])) {
			$customer_id = (int) $data['customer_id'];
		} else {
			$customer_id = 0;
		}

		if (isset($data['product_id'])) {
			$product_id = (int) $data['product_id'];
		} else {
			$product_id = 0;
		}


		$this->load->model('catalog/product');
		$sstaus = false;

		if ($customer_id != '' && $product_id != '') {
			$product_info = $this->model_catalog_product->getProduct($product_id, 1, $store_id);


			if ($product_info) {
				if (isset($data['quantity'])) {
					$quantity = (int) $data['quantity'];
				} else {
					$quantity = 1;
				}
				if (isset($data['key'])) {
					$key = $data['key'];
				} else {
					$key = 1;
				}



				$results = $this->cartnewadd1($data['product_id'], $customer_id, $key, $quantity);

				$sstaus = true;


			} else {
				$results = 'Product Not Exist';
			}
		} else {
			$results = 'Please Post customer_id and product_id';
		}


		if (count(array($results))) {
			$json['success'] = $sstaus;
			$json['cartproduct'] = $results;
		} else {
			$json['success'] = false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}




	public function addcart()
	{

		$this->checkPlugin();
		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}
		$this->load->language('checkout/cart');
		if (isset($this->request->post['customer_id'])) {
			$customer_id = (int) $this->request->post['customer_id'];
		} else {
			$customer_id = 0;
		}

		if (isset($this->request->post['product_id'])) {
			$product_id = (int) $this->request->post['product_id'];
		} else {
			$product_id = 0;
		}
		$this->load->model('catalog/product');
		$sstaus = false;

		if ($customer_id != '' && $product_id != '') {
			$product_info = $this->model_catalog_product->getProduct($product_id, 1, $store_id);


			if ($product_info) {
				if (isset($this->request->post['quantity'])) {
					$quantity = (int) $this->request->post['quantity'];
				} else {
					$quantity = 1;
				}


				if (isset($this->request->post['option']) && $this->request->post['option'] != '') {
					$option = array_filter($this->request->post['option']);
				} else {
					$option = array();
				}

				$results = $this->cartnewadd($this->request->post['product_id'], $customer_id, $quantity, $option, 0);

				$sstaus = true;


			} else {
				$results = 'Product Not Exist';
			}
		} else {
			$results = 'Please Post customer_id and product_id';
		}


		if (count(array($results))) {
			$json['success'] = $sstaus;
			$json['cartproduct'] = $results;
		} else {
			$json['success'] = false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}
	public function removecart()
	{

		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}
		$this->load->language('checkout/cart');
		if (isset($this->request->post['customer_id'])) {
			$customer_id = (int) $this->request->post['customer_id'];
		} else {
			$customer_id = 0;
		}

		if (isset($this->request->post['key'])) {
			$key = $this->request->post['key'];
		} else {
			$key = 0;
		}

		$sstatus = false;
		if ($customer_id != '' && $key != '') {
			$results = (object) [];
			$this->load->model('account/customer');
			$customer_info = $this->model_account_customer->getCustomer($customer_id);
			$cart = unserialize($customer_info['cart']);

			unset($cart[$key]);
			if ($this->db->query("UPDATE " . DB_PREFIX . "customer SET cart = '" . serialize($cart) . "' WHERE customer_id = '" . (int) $customer_id . "'")) {
				if (!empty($cart) && $cart != '') {
					$results = $this->apigetProducts($cart);
					$sstatus = true;
				}
			}
		} else {
			$results = 'please post customer_id and key';
		}



		if (is_countable($results) && count($results)) {
			$json['success'] = $sstatus;
			$json['cartproduct'] = $results;
		} else {
			$json['success'] = false;
			$json['cartproduct'] = $results;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}
	private function getBannerapi()
	{
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "banner b LEFT JOIN " . DB_PREFIX . "banner_image bi ON (b.banner_id = bi.banner_id) WHERE  b.status = '1' AND bi.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY bi.sort_order ASC");
		return $query->rows;
	}
	private function apigetProducts($cartdata)
	{

		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}
		$this->load->model('setting/setting');
		$storecurrency = $this->model_setting_setting->getSettingValue('config_currency', $store_id);


		$cartprod = array();

		if (!$cartprod) {

			foreach ($cartdata as $key => $quantity) {

				$product = unserialize(base64_decode($key));


				$product_id = $product['product_id'];

				$stock = true;

				// Options

				if (!empty($product['option'])) {
					$options = $product['option'];
				} else {
					$options = array();
				}

				// Profile
				if (!empty($product['recurring_id'])) {
					$recurring_id = $product['recurring_id'];
				} else {
					$recurring_id = 0;
				}


				$product_query = $this->db->query("SELECT *,p.image as product_Image FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id) LEFT JOIN " . DB_PREFIX . "category c ON c.category_id = p2c.category_id WHERE p.product_id = '" . (int) $product_id . "' AND pd.language_id = '" . (int) $this->config->get('config_language_id') . "' AND p.date_available <= NOW() AND p.status = '1' AND c.status=1");

				if ($product_query->num_rows) {
					$option_price = 0;
					$option_points = 0;
					$option_weight = 0;


					$option_data = array();

					foreach ($options as $product_option_id => $value) {

						$option_query = $this->db->query("SELECT po.product_option_id, po.option_id, od.name, o.type FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_option_id = '" . (int) $product_option_id . "' AND po.product_id = '" . (int) $product_id . "' AND od.language_id = '" . (int) $this->config->get('config_language_id') . "'");

						if ($option_query->num_rows) {

							if ($option_query->row['type'] == 'select' || $option_query->row['type'] == 'radio' || $option_query->row['type'] == 'image') {



								$option_value_query = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.parent_id, pov.is_children, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix  FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_value_id = '" . (int) $value . "' AND pov.product_option_id = '" . (int) $product_option_id . "' AND ovd.language_id = '" . (int) $this->config->get('config_language_id') . "'");

								if ($option_value_query->num_rows) {
									if ($option_value_query->row['price_prefix'] == '+') {
										$option_price += $option_value_query->row['price'];
									} elseif ($option_value_query->row['price_prefix'] == '-') {
										$option_price -= $option_value_query->row['price'];
									}

									if ($option_value_query->row['points_prefix'] == '+') {
										$option_points += $option_value_query->row['points'];
									} elseif ($option_value_query->row['points_prefix'] == '-') {
										$option_points -= $option_value_query->row['points'];
									}

									if ($option_value_query->row['weight_prefix'] == '+') {
										$option_weight += $option_value_query->row['weight'];
									} elseif ($option_value_query->row['weight_prefix'] == '-') {
										$option_weight -= $option_value_query->row['weight'];
									}

									if ($option_value_query->row['subtract'] && (!$option_value_query->row['quantity'] || ($option_value_query->row['quantity'] < $quantity))) {
										$stock = false;
									}

									$option_data[] = array(
										'product_option_id' => $product_option_id,
										'product_option_value_id' => $value,
										'option_id' => $option_query->row['option_id'],
										'option_value_id' => $option_value_query->row['option_value_id'],
										'name' => $option_query->row['name'],
										'value' => $option_value_query->row['name'],
										'type' => $option_query->row['type'],
										'parent_id' => $option_value_query->row['parent_id'],
										'is_children' => $option_value_query->row['is_children'],
										'quantity' => $option_value_query->row['quantity'],
										'subtract' => $option_value_query->row['subtract'],
										'price' => $option_value_query->row['price'],
										'price_prefix' => $option_value_query->row['price_prefix'],
										'points' => $option_value_query->row['points'],
										'points_prefix' => $option_value_query->row['points_prefix'],
										'weight' => $option_value_query->row['weight'],
										'weight_prefix' => $option_value_query->row['weight_prefix']
									);
								}

							} elseif ($option_query->row['type'] == 'checkbox' && is_array($value)) {


								foreach ($value as $product_option_value_id) {

									$option_value_query = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.parent_id, pov.is_children, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_value_id = '" . (int) $product_option_value_id . "' AND pov.product_option_id = '" . (int) $product_option_id . "' AND ovd.language_id = '" . (int) $this->config->get('config_language_id') . "'");

									if ($option_value_query->num_rows) {
										if ($option_value_query->row['price_prefix'] == '+') {
											$option_price += $option_value_query->row['price'];
										} elseif ($option_value_query->row['price_prefix'] == '-') {
											$option_price -= $option_value_query->row['price'];
										}

										if ($option_value_query->row['points_prefix'] == '+') {
											$option_points += $option_value_query->row['points'];
										} elseif ($option_value_query->row['points_prefix'] == '-') {
											$option_points -= $option_value_query->row['points'];
										}

										if ($option_value_query->row['weight_prefix'] == '+') {
											$option_weight += $option_value_query->row['weight'];
										} elseif ($option_value_query->row['weight_prefix'] == '-') {
											$option_weight -= $option_value_query->row['weight'];
										}

										if ($option_value_query->row['subtract'] && (!$option_value_query->row['quantity'] || ($option_value_query->row['quantity'] < $quantity))) {
											$stock = false;
										}

										$option_data[] = array(
											'product_option_id' => $product_option_id,
											'product_option_value_id' => $product_option_value_id,
											'option_id' => $option_query->row['option_id'],
											'option_value_id' => $option_value_query->row['option_value_id'],
											'name' => $option_query->row['name'],
											'value' => $option_value_query->row['name'],
											'type' => $option_query->row['type'],
											'parent_id' => $option_value_query->row['parent_id'],
											'is_children' => $option_value_query->row['is_children'],
											'quantity' => $option_value_query->row['quantity'],
											'subtract' => $option_value_query->row['subtract'],
											'price' => $option_value_query->row['price'],
											'price_prefix' => $option_value_query->row['price_prefix'],
											'points' => $option_value_query->row['points'],
											'points_prefix' => $option_value_query->row['points_prefix'],
											'weight' => $option_value_query->row['weight'],
											'weight_prefix' => $option_value_query->row['weight_prefix']
										);
									}
								}
							} elseif ($option_query->row['type'] == 'text' || $option_query->row['type'] == 'textarea' || $option_query->row['type'] == 'file' || $option_query->row['type'] == 'date' || $option_query->row['type'] == 'datetime' || $option_query->row['type'] == 'time') {
								$option_data[] = array(
									'product_option_id' => $product_option_id,
									'product_option_value_id' => '',
									'option_id' => $option_query->row['option_id'],
									'option_value_id' => '',
									'name' => $option_query->row['name'],
									'value' => $value,
									'type' => $option_query->row['type'],
									'parent_id' => $option_value_query->row['parent_id'],
									'is_children' => $option_value_query->row['is_children'],
									'quantity' => '',
									'subtract' => '',
									'price' => '',
									'price_prefix' => '',
									'points' => '',
									'points_prefix' => '',
									'weight' => '',
									'weight_prefix' => ''
								);
							}
						}
					}

					$price = $product_query->row['price'];

					// Product Discounts
					$discount_quantity = 0;




					$product_discount_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int) $product_id . "' AND customer_group_id = '" . (int) $this->config->get('config_customer_group_id') . "' AND quantity <= '" . (int) $discount_quantity . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity DESC, priority ASC, price ASC LIMIT 1");

					if ($product_discount_query->num_rows) {
						$price = $product_discount_query->row['price'];
					}

					// Product Specials
					$product_special_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int) $product_id . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY priority ASC, price ASC LIMIT 1");


					$special = false;
					$total = $price;
					if ($product_special_query->num_rows && $product_special_query->row['price'] > 0) {
						$special = $product_special_query->row['price'];
						// $price = $special;
						$total = $special;
					}

					// 					Calculating GST from the Amount Excluding GST:
// The GST exclusive amount means the original amount before the Goods and Services Tax (GST) is applied.

					// GST Amount = (Tax Rate/100) x Amount Excluding GST
// Total Amount (including GST) = Amount Excluding GST + GST Amount


					// Example

					// Let's say you have a product with a price of ₹1,000, and the applicable GST rate is 18%.

					// GST Amount = (18/100) x ₹1,000 = ₹180
// Total Amount (including GST) = ₹1,000 + ₹180 = ₹1,180
					if (($product_query->row['tax_class_id'] == 0)) {
						$rate = 0;
					} else {
						$rate = 0;
						$tax_class = $this->getTaxClassapi($product_query->row['tax_class_id']);


						$tax_rates1 = $this->getTaxRulesapi($product_query->row['tax_class_id']);

						$tax_rates = array();
						foreach ($tax_rates1 as $tax_rate) {
							$tax_rates['value'][] = $this->getTaxRateapi($tax_rate['tax_rate_id']);
						}
						$product_query->row['taxs']['tax_class'] = array('tax_class_id' => 0);
						$product_query->row['taxs']['tax_rate']['rate'] = array('tax_rate_id' => 0);
						if (!empty($tax_class)) {
							$product_query->row['taxs']['tax_class'] = $tax_class;
						}
						if (!empty($tax_rates)) {
							$product_query->row[0]['tax_rate'] = $tax_rates;
							$rate = $tax_rates['value'][0]['rate'];

							if ($tax_rates['value'][0]['type'] == 'P') {
								$rate = $tax_rates['value'][0]['rate'] / 100 * $total;

							}
						}

					}
					// print_r($rate);
					// die;



					$product_reward_query = $this->db->query("SELECT points FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int) $product_id . "' AND customer_group_id = '" . (int) $this->config->get('config_customer_group_id') . "'");

					if ($product_reward_query->num_rows) {
						$reward = $product_reward_query->row['points'];
					} else {
						$reward = 0;
					}

					// Downloads
					$download_data = array();

					$download_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_download p2d LEFT JOIN " . DB_PREFIX . "download d ON (p2d.download_id = d.download_id) LEFT JOIN " . DB_PREFIX . "download_description dd ON (d.download_id = dd.download_id) WHERE p2d.product_id = '" . (int) $product_id . "' AND dd.language_id = '" . (int) $this->config->get('config_language_id') . "'");

					foreach ($download_query->rows as $download) {
						$download_data[] = array(
							'download_id' => $download['download_id'],
							'name' => $download['name'],
							'filename' => $download['filename'],
							'mask' => $download['mask']
						);
					}

					// Stock
					if (!$product_query->row['quantity'] || ($product_query->row['quantity'] < $quantity)) {
						$stock = false;
					}

					$recurring_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "recurring` `p` JOIN `" . DB_PREFIX . "product_recurring` `pp` ON `pp`.`recurring_id` = `p`.`recurring_id` AND `pp`.`product_id` = " . (int) $product_query->row['product_id'] . " JOIN `" . DB_PREFIX . "recurring_description` `pd` ON `pd`.`recurring_id` = `p`.`recurring_id` AND `pd`.`language_id` = " . (int) $this->config->get('config_language_id') . " WHERE `pp`.`recurring_id` = " . (int) $recurring_id . " AND `status` = 1 AND `pp`.`customer_group_id` = " . (int) $this->config->get('config_customer_group_id'));

					if ($recurring_query->num_rows) {
						$recurring = array(
							'recurring_id' => $recurring_id,
							'name' => $recurring_query->row['name'],
							'frequency' => $recurring_query->row['frequency'],
							'price' => $recurring_query->row['price'],
							'cycle' => $recurring_query->row['cycle'],
							'duration' => $recurring_query->row['duration'],
							'trial' => $recurring_query->row['trial_status'],
							'trial_frequency' => $recurring_query->row['trial_frequency'],
							'trial_price' => $recurring_query->row['trial_price'],
							'trial_cycle' => $recurring_query->row['trial_cycle'],
							'trial_duration' => $recurring_query->row['trial_duration']
						);
					} else {
						$recurring = false;
					}

					$this->load->model('tool/image');

					$image = "";
					$thumb = "";

					if (isset($product_query->row['product_Image']) && is_file(DIR_IMAGE . $product_query->row['product_Image'])) {
						$thumb = $this->model_tool_image->resize($product_query->row['product_Image'], 200, 200);
						$image = $this->model_tool_image->resize($product_query->row['product_Image'], 76, 76);

					} else {
						$image = $this->model_tool_image->resize('no_image.png', 76, 76);
						$thumb = $this->model_tool_image->resize('no_image.png', 200, 200);
					}

					$cartprod[$key] = array(
						'key' => $key,
						'product_id' => $product_query->row['product_id'],
						'name' => $product_query->row['name'],
						'model' => $product_query->row['model'],
						'shipping' => $product_query->row['shipping'],
						'thumb' => $thumb,
						'image' => $image,
						'option' => $option_data,
						'download' => $download_data,
						'quantity' => $quantity,
						'minimum' => $product_query->row['minimum'],
						'subtract' => $product_query->row['subtract'],
						'stock' => $stock,
						'formatted_price' => $this->currency->format((float) $price, $storecurrency, 1.00000),
						'special' => $special,
						'total' => ($total + $option_price + $rate) * $quantity,
						'reward' => $reward * $quantity,
						'points' => ($product_query->row['points'] ? ($product_query->row['points'] + $option_points) * $quantity : 0),
						'tax_class_id' => $product_query->row['tax_class_id'],
						'tax_total' => $rate,
						'product_tax' => ($rate * $quantity),
						'weight' => ($product_query->row['weight'] + $option_weight) * $quantity,
						'weight_class_id' => $product_query->row['weight_class_id'],
						'length' => $product_query->row['length'],
						'width' => $product_query->row['width'],
						'height' => $product_query->row['height'],
						'length_class_id' => $product_query->row['length_class_id'],
						'recurring' => $recurring
					);

				} else {
					//	$this->remove($key);
				}
			}
		}

		return $cartprod;
	}
	private function cartnewadd($product_id, $customer_id, $qty = 1, $option = array(), $recurring_id = 0)
	{


		$store_id = 0;
		if (isset($this->request->get['store_id']) && $this->request->get['store_id'] != "") {
			$store_id = $this->request->get['store_id'];
		}
		$this->data = array();

		$product['product_id'] = (int) $product_id;


		if ($option) {

			$product['option'] = $option;
		}

		if ($recurring_id) {
			$product['recurring_id'] = (int) $recurring_id;
		}
		$key = base64_encode(serialize($product));
		$this->load->model('account/customer');
		$sstaus = false;
		$customer_info = $this->model_account_customer->getCustomer($customer_id, $store_id);
		if ($customer_info != array()) {

			$cart = unserialize($customer_info['cart']);

			// $cart[$source] = array('source' => $source);

			// $cart[$source_value] = array('source_value' => $source_value);

			// $cart = array('source' => $source);
			// $cart = $cart[$source];
			if ((int) $qty && ((int) $qty > 0)) {
				if (!isset($cart[$key])) {
					$cart[$key] = (int) $qty;
				} else {
					$cart[$key] = (int) $qty;
				}





				// $cart = false;
				// if (isset($this->request->post['source'])) {
				// 	$cart = array('source' => $this->request->post['source']);
				// }


				// if (isset($this->request->post['source_value'])) {
				// 	$source_value = array('source' => $this->request->post['source_value']);
				// }

				if ($this->db->query("UPDATE " . DB_PREFIX . "customer SET cart = '" . serialize($cart) . "' WHERE customer_id = '" . (int) $customer_id . "'")) {

					if (!empty($cart) && $cart != '') {

						return $this->apigetProducts($cart);

					}
				}
			}

		}
	}
	private function cartnewadd1($product_id, $customer_id, $key, $qty = 1, $recurring_id = 0)
	{
		$this->data = array();

		$product['product_id'] = (int) $product_id;


		if ($recurring_id) {
			$product['recurring_id'] = (int) $recurring_id;
		}


		$this->load->model('account/customer');
		$customer_info = $this->model_account_customer->getCustomer($customer_id);


		$cart = unserialize($customer_info['cart']);

		if ((int) $qty && ((int) $qty > 0)) {

			if (isset($cart[$key]) == $key) {

				$cart[$key] = $qty;

			}


			if ($this->db->query("UPDATE " . DB_PREFIX . "customer SET cart = '" . serialize($cart) . "' WHERE customer_id = '" . (int) $customer_id . "'")) {

				if (!empty($cart) && $cart != '') {

					return $this->apigetProducts($cart);

				}
			}
		}


	}
	private function getTaxClassapi($tax_class_id)
	{
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tax_class WHERE tax_class_id = '" . (int) $tax_class_id . "'");

		return $query->row;
	}
	private function getTaxRulesapi($tax_class_id)
	{
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tax_rule WHERE tax_class_id = '" . (int) $tax_class_id . "'");

		return $query->rows;
	}
	private function getTaxRateapi($tax_rate_id)
	{

		$query = $this->db->query("SELECT tr.tax_rate_id, tr.name AS name, tr.rate, tr.type, tr.geo_zone_id, gz.name AS geo_zone, tr.date_added, tr.date_modified FROM " . DB_PREFIX . "tax_rate tr LEFT JOIN " . DB_PREFIX . "geo_zone gz ON (tr.geo_zone_id = gz.geo_zone_id) WHERE tr.tax_rate_id = '" . (int) $tax_rate_id . "'");

		return $query->row;
	}
	private function getAddressidapi($address_id)
	{
		$address_query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "address WHERE address_id = '" . (int) $address_id . "'");

		if ($address_query->num_rows) {
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int) $address_query->row['country_id'] . "'");

			if ($country_query->num_rows) {
				$country = $country_query->row['name'];
				$iso_code_2 = $country_query->row['iso_code_2'];
				$iso_code_3 = $country_query->row['iso_code_3'];
				$address_format = $country_query->row['address_format'];
			} else {
				$country = '';
				$iso_code_2 = '';
				$iso_code_3 = '';
				$address_format = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int) $address_query->row['zone_id'] . "'");

			if ($zone_query->num_rows) {
				$zone = $zone_query->row['name'];
				$zone_code = $zone_query->row['code'];
			} else {
				$zone = '';
				$zone_code = '';
			}

			$address_data = array(
				'address_id' => $address_query->row['address_id'],
				'firstname' => $address_query->row['firstname'],
				'lastname' => $address_query->row['lastname'],
				'company' => $address_query->row['company'],
				'address_1' => $address_query->row['address_1'],
				'address_2' => $address_query->row['address_2'],
				'postcode' => $address_query->row['postcode'],
				'city' => $address_query->row['city'],
				'zone_id' => $address_query->row['zone_id'],
				'zone' => $zone,
				'zone_code' => $zone_code,
				'country_id' => $address_query->row['country_id'],
				'country' => $country,
				'iso_code_2' => $iso_code_2,
				'iso_code_3' => $iso_code_3,
				'address_format' => $address_format,
				'custom_field' => unserialize($address_query->row['custom_field'])
			);

			return $address_data;
		} else {
			return false;
		}
	}

	public function getOrderidapi($order_id, $store_id)
	{
		// echo "SELECT * FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int) $order_id . "' AND order_status_id > '0' AND store_id = '" . (int) $store_id . "'";
		// die;
		$order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int) $order_id . "' AND order_status_id > '0' AND store_id = '" . (int) $store_id . "'");

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
				'fax' => $order_query->row['fax'],
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
				'shipping_custom_field' => $order_query->row['shipping_custom_field'],
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
				'deliveryType' => $order_query->row['deliveryType'],
				'shippingProvider' => $order_query->row['shippingProvider'],
				'deliveryMethod' => $order_query->row['deliveryMethod'],
				'bid_id' => $order_query->row['bid_id'],
				'source' => $order_query->row['source'],
				'source_value' => $order_query->row['source_value'],
				'custom_field' => $order_query->row['custom_field'],
				'cooking_instructions' => $order_query->row['cooking_instructions'],
				'receiver_name' => $order_query->row['receiver_name'],
				'receiver_phone' => $order_query->row['receiver_phone'],
				'type' => $order_query->row['type'],
				'tableNumber' => $order_query->row['table_number'],
				'serviceChargeType' => $order_query->row['service_charge_type'],
				'comments' => $order_query->row['order_comments'],
				'service_charge' => $order_query->row['service_charge'],
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
	/*public	function convertobjtoarray($obj) {
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																			if(!is_array($obj) && !is_object($obj)) return $obj;
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																			if(is_object($obj)) $obj = get_object_vars($obj);
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																			return array_map(__FUNCTION__, $obj);
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																			}*/
	private function convertobjtoarray($obj, &$arr)
	{

		if (!is_object($obj) && !is_array($obj)) {
			$arr = $obj;
			return $arr;
		}

		foreach ($obj as $key => $value) {
			if (!empty($value)) {
				$arr[$key] = array();
				$this->convertobjtoarray($value, $arr[$key]);
			} else {
				$arr[$key] = $value;
			}
		}
		return $arr;
	}
	public function getReturnsapi($customer_id)
	{
		/*if ($start < 0) {
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						$start = 0;
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						}
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						if ($limit < 1) {
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						$limit = 20;
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																						}*/

		$query = $this->db->query("SELECT r.return_id, r.order_id, r.firstname, r.lastname, rs.name as status, r.date_added FROM `" . DB_PREFIX . "return` r LEFT JOIN " . DB_PREFIX . "return_status rs ON (r.return_status_id = rs.return_status_id) WHERE r.customer_id = '" . $customer_id . "' AND rs.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY r.return_id DESC");

		return $query->rows;
	}
	public function getReturnapi($return_id, $customer_id)
	{
		$query = $this->db->query("SELECT r.return_id, r.order_id, r.firstname, r.lastname, r.email, r.telephone, r.product, r.model, r.quantity, r.opened, (SELECT rr.name FROM " . DB_PREFIX . "return_reason rr WHERE rr.return_reason_id = r.return_reason_id AND rr.language_id = '" . (int) $this->config->get('config_language_id') . "') AS reason, (SELECT ra.name FROM " . DB_PREFIX . "return_action ra WHERE ra.return_action_id = r.return_action_id AND ra.language_id = '" . (int) $this->config->get('config_language_id') . "') AS action, (SELECT rs.name FROM " . DB_PREFIX . "return_status rs WHERE rs.return_status_id = r.return_status_id AND rs.language_id = '" . (int) $this->config->get('config_language_id') . "') AS status, r.comment, r.date_ordered, r.date_added, r.date_modified FROM `" . DB_PREFIX . "return` r WHERE return_id = '" . (int) $return_id . "' AND customer_id = '" . $customer_id . "'");

		return $query->row;
	}







}
