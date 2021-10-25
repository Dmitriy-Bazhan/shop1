<?php

namespace NewPluginPlugin\Core\Content\NewPluginData;

use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class NewPluginDataEntity extends Entity
{
    use EntityIdTrait;

    protected $data;
    protected $itemId;

    public function getData()
    {
        return $this->data;
    }

    public function setData(string $data)
    {
        $this->data = $data;
    }

    public function getItemId()
    {
        return $this->itemId;
    }

    public function setItemId($itemId)
    {
        $this->itemId = $itemId;
    }
}