<?php

require_once DIR_UPLOADCOM . 'storage/vendor/firebase/php-jwt/src/BeforeValidException.php';
require_once DIR_UPLOADCOM . 'storage/vendor/firebase/php-jwt/src/ExpiredException.php';
require_once DIR_UPLOADCOM . 'storage/vendor/firebase/php-jwt/src/SignatureInvalidException.php';
require_once DIR_UPLOADCOM . 'storage/vendor/firebase/php-jwt/src/JWT.php';
require_once DIR_UPLOADCOM . 'storage/vendor/firebase/php-jwt/src/Key.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class ControllerAccountAutologin extends Controller {
	private $error = array();
	
	function generateToken($plaintext,$redirect_uri){
        $date   = new DateTimeImmutable();
        $expire_at     = $date->modify('+'.token_expiration.' minutes')->getTimestamp();
        $payload = array(
            'iat'  => $date->getTimestamp(),         // Issued at: time when the token was generated
            'iss'  => domain_name,                       // Issuer
            'nbf'  => $date->getTimestamp(),         // Not before
            'exp'  => $expire_at,
			'mobile_number' => $plaintext,
			"redirect_uri" => $redirect_uri
        );
        
        $token = JWT::encode($payload, encryption_saltkey, 'HS256');
       
        return $token;
	}
	
	function decryptToken($token){
		// JWT::$leeway = 60; // $leeway in seconds
		$decoded = JWT::decode($token, new Key(encryption_saltkey, 'HS256'));
		// $now = new DateTimeImmutable();
		// $serverName = domain_name;
		
		// if ($decoded->iss !== $serverName ||
		//     $decoded->nbf > $now->getTimestamp() ||
		//     $decoded->exp < $now->getTimestamp())
		// {
		//     throw new Exception('THE LINK YOU ARE TRYING TO ACCESS HAS EXPIRED',401);
		// }
		// print_r($decoded);
		return $decoded;
	}

	public function index() {
		// $this->generateToken("7702385589");
		$cutomer_data = $this->decryptToken($this->request->get['token']);
		// print_r($cutomer_data);
		$telephone = $cutomer_data->mobile_number;
		$redirect_uri = $cutomer_data->redirect_uri;

		$this->load->model('account/customer');

		if ($this->customer->login_new($telephone)) {
			// Unset guest
			unset($this->session->data['guest']);

			// Default Shipping Address
			$this->load->model('account/address');

			if ($this->config->get('config_tax_customer') == 'payment') {
				$this->session->data['payment_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
			}

			if ($this->config->get('config_tax_customer') == 'shipping') {
				$this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
			}

			// Wishlist
			if (isset($this->session->data['wishlist']) && is_array($this->session->data['wishlist'])) {
				$this->load->model('account/wishlist');

				foreach ($this->session->data['wishlist'] as $key => $product_id) {
					$this->model_account_wishlist->addWishlist($product_id);

					unset($this->session->data['wishlist'][$key]);
				}
			}

			$this->response->redirect($this->url->link($redirect_uri, '', true));
		}
	}
}
