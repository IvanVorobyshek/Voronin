<?php

namespace Voronin\Cars\Ui\Component\Form\Cars;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class SaveButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * Get Button data
     *
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Save Car'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => ['button' => ['event' => 'save']],
                'form-role' => 'save',
            ],
            'sort_order' => 20,
        ];
    }
}
