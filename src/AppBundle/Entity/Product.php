<?php

namespace AppBundle\Entity;

class Product
{
    private $productId;
    private $name;
    private $typeId;
    private $price;
    private $description;
    private $picURL;
    private $updatedOn;
    private $updatedBy;
    private $isActive;

    public function getProductId()
    {
        return $this->productId;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setTypeId(int $typeId)
    {
        $this->typeId = $typeId;
    }

    public function getTypeId()
    {
        return $this->typeId;
    }

    public function setPrice(float $price)
    {
        $this->price = $price;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setPicURL(string $picURL)
    {
        $this->picURL = $picURL;
    }

    public function getPicURL()
    {
        return $this->picURL;
    }

    public function setUpdateOn(\DateTime $updateOn)
    {
        $this->updatedOn = $updateOn;
    }

    public function getUpdatedOn()
    {
        return $this->updatedOn;
    }

    public function setUpdatedBy(\DateTime $updatedBy)
    {
        $this->updatedBy = $updatedBy;
    }

    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    public function setIsActive(bool $isActive)
    {
        $this->isActive = $isActive;
    }

    public function getIsActive()
    {
        return $this->isActive;
    }
}
