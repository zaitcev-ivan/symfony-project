<?php

namespace AppBundle\Helpers;

use AppBundle\Entity\Product;

class CategoryHelper
{
    /**
     * @param Product $product
     * @return array
     */
    public static function getAssignmentList(Product $product): array
    {
        $assignmentList = [];
        foreach($product->getAssignmentsCategory() as $assignment) {
            $id = $assignment->getId();
            $name = $assignment->getName();
            $assignmentList[$name] = $id;
        }
        return $assignmentList;
    }
}