<?php

namespace NewPluginPlugin\Core\Content\NewPluginItem;

use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class NewPluginItemEntity extends Entity
{
    use EntityIdTrait;

    protected $item;

    public function getItem()
    {
        return $this->item;
    }

    public function setItem(string $item)
    {
        $this->item = $item;
    }

}