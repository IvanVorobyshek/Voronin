<?php

namespace Voronin\Cars\Ui\Component\Form\Cars;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class DeleteButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * Get button data
     *
     * @return array
     */
    public function getButtonData()
    {
        if ($this->getCar()) {
            return [
                'id' => 'delete',
                'label' => __('Delete'),
                'on_click' => "deleteConfirm('" . __('Are you sure you want to delete this car?') . "', '"
                    . $this->getUrl('*/*/delete', ['id' => $this->getCar()]) . "', {data: {}})",
                'class' => 'delete',
                'sort_order' => 10
            ];
        }
        return [];
    }
}
