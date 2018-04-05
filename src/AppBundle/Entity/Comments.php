<?php

namespace AppBundle\Entity;

class Comments
{
    private $commentId;
    private $userId;
    private $productId;
    private $commentString;

    public function getCommentId()
    {
        return $this->commentId;
    }

    public function setUserId(int $userId)
    {
        $this->userId = $userId;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function setProductId(int $productId)
    {
        $this->productId = $productId;
    }

    public function getProductId()
    {
        return $this->productId;
    }

    public function setCommentString(string $commentString)
    {
        $this->commentString = $commentString;
    }

    public function getCommentString()
    {
        return $this->commentString;
    }

}
