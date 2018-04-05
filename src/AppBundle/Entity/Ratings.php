<?php

namespace AppBundle\Entity;

class Ratings
{
    private $ratingId;
    private $productId;
    private $totalPoints;
    private $obtainedPoints;

    public function getRatingId()
    {
        return $this->ratingId;
    }

    public function setProductId(int $productId)
    {
        $this->productId = $productId;
    }

    public function getProductId()
    {
        return $this->productId;
    }

    public function setTotalPoints(int $totalPoints)
    {
        $this->totalPoints = $totalPoints;
    }

    public function getTotalPoints()
    {
        return $this->totalPoints;
    }

    public function setObtainedPoints(int $obtainedPoints)
    {
        $this->obtainedPoints = $obtainedPoints;
    }

    public function getObtainedPoints()
    {
        return $this->obtainedPoints;
    }
}
