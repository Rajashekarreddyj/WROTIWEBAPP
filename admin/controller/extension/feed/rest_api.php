<?php

class ControllerExtensionFeedRestApi extends Controller {

	public function index() {
		$this->load->language('extension/feed/rest_api');
		$this->load->model('setting/setting');

		$this->document->setTitle($this->language->get('heading_title'));

		$data = array(
			'version'             => '0.1',
			'heading_title'       => $this->language->get('heading_title'),
			
			'text_enabled'        => $this->language->get('text_enabled'),
			'text_disabled'       => $this->language->get('text_disabled'),
			'tab_general'         => $this->language->get('tab_general'),

			'entry_status'        => $this->language->get('entry_status'),
			'entry_key'           => $this->language->get('entry_key'),

			'button_save'         => $this->language->get('button_save'),
			'button_cancel'       => $this->language->get('button_cancel'),
			'text_edit'           => $this->language->get('text_edit'),

			'action'              => $this->url->link('extension/feed/rest_api', 'user_token=' . $this->session->data['user_token'], 'SSL'),
			'cancel'              => $this->url->link('extension/feed', 'user_token=' . $this->session->data['user_token'], 'SSL')
		);

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$this->model_setting_setting->editSetting('feed_rest_api', $this->request->post);				
			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=feed', true));
		}

  		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'user_token=' . $this->session->data['user_token'], 'SSL'),
			'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_feed'),
			'href'      => $this->url->link('extension/feed', 'user_token=' . $this->session->data['user_token'], 'SSL'),       		
			'separator' => ' :: '
   		);

   		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('extension/feed/rest_api', 'user_user_token=' . $this->session->data['user_token'], 'SSL'),
			'separator' => ' :: '
   		);

   		if (isset($this->request->post['feed_rest_api_status'])) {
			$data['feed_rest_api_status'] = $this->request->post['feed_rest_api_status'];
		} else {
			$data['feed_rest_api_status'] = $this->config->get('feed_rest_api_status');
		}

		if (isset($this->request->post['feed_rest_api_key'])) {
			$data['feed_rest_api_key'] = $this->request->post['feed_rest_api_key'];
		} else {
			$data['feed_rest_api_key'] = $this->config->get('feed_rest_api_key');
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		//$this->response->setOutput($this->load->view('extension/feed/rest_api.tpl', $data));
		$this->response->setOutput($this->load->view('extension/feed/rest_api', $data));
	}

}
