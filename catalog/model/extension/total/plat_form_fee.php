<?php
class ModelExtensionTotalPlatFormFee extends Model
{
	public function getTotal($total)
	{
		if ($this->cart->getSubTotal() && ($this->cart->getSubTotal() < $this->config->get('total_plat_form_fee_total'))) {
			$this->load->language('extension/total/plat_form_fee');

			$total['totals'][] = array(
				'code' => 'plat_form_fee',
				'title' => $this->language->get('text_plat_form_fee'),
				'value' => $this->config->get('total_plat_form_fee_fee'),
				'sort_order' => $this->config->get('total_plat_form_fee_sort_order')
			);

			if ($this->config->get('total_plat_form_fee_tax_class_id')) {
				$tax_rates = $this->tax->getRates($this->config->get('total_plat_form_fee_fee'), $this->config->get('total_plat_form_fee_tax_class_id'));

				foreach ($tax_rates as $tax_rate) {
					if (!isset($total['taxes'][$tax_rate['tax_rate_id']])) {
						$total['taxes'][$tax_rate['tax_rate_id']] = $tax_rate['amount'];
					} else {
						$total['taxes'][$tax_rate['tax_rate_id']] += $tax_rate['amount'];
					}
				}
			}

			$total['total'] += $this->config->get('total_plat_form_fee_fee');
		}
	}
}