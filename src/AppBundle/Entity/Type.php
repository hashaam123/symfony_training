<?php

namespace AppBundle\Entity;

class Type
{
    private $typeId;
    private $typeName;

    public function setTypeId(int $typeId)
    {
        $this->typeId = $typeId;
    }

    public function getTypeId()
    {
        return $this->typeId;
    }

    public function setTypeName(string $typeName)
    {
        $this->typeName = $typeName;
    }

    public function getTypeName()
    {
        return $this->typeName;
    }
}
