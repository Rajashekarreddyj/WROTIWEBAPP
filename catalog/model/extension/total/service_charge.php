<?php
class ModelExtensionTotalServiceCharge extends Model
{
	public function getTotal($total)
	{
		if ($this->cart->getSubTotal() && ($this->cart->getSubTotal() < $this->config->get('total_service_charge_total'))) {
			$this->load->language('extension/total/service_charge');

			$total['totals'][] = array(
				'code' => 'service_charge',
				'title' => $this->language->get('text_service_charge'),
				'value' => $this->config->get('total_service_charge_fee'),
				'sort_order' => $this->config->get('total_service_charge_sort_order')
			);

			if ($this->config->get('total_service_charge_tax_class_id')) {
				$tax_rates = $this->tax->getRates($this->config->get('total_service_charge_fee'), $this->config->get('total_service_charge_tax_class_id'));

				foreach ($tax_rates as $tax_rate) {
					if (!isset($total['taxes'][$tax_rate['tax_rate_id']])) {
						$total['taxes'][$tax_rate['tax_rate_id']] = $tax_rate['amount'];
					} else {
						$total['taxes'][$tax_rate['tax_rate_id']] += $tax_rate['amount'];
					}
				}
			}

			$total['total'] += $this->config->get('total_service_charge_fee');
		}
	}
}