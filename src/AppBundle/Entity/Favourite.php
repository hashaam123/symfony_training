<?php

namespace AppBundle\Entity;

class Favourite
{
    private $favouriteId;
    private $productId;
    private $userId;

    public function getFavouriteId()
    {
        return $this->favouriteId;
    }

    public function setProductId(int $productId)
    {
        $this->productId = $productId;
    }

    public function getProductId()
    {
        return $this->productId;
    }

    public function setUserId(int $userId)
    {
        $this->userId = $userId;
    }

    public function getUserId()
    {
        return $this->userId;
    }
}
