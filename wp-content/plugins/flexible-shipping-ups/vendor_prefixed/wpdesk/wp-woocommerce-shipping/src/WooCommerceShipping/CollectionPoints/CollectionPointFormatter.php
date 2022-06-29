<?php

namespace UpsFreeVendor\WPDesk\WooCommerceShipping\CollectionPoints;

use UpsFreeVendor\WPDesk\AbstractShipping\CollectionPoints\CollectionPoint;
class CollectionPointFormatter
{
    /**
     * Get collection point as label.
     *
     * @param CollectionPoint $collection_point .
     *
     * @return string
     */
    public function get_collection_point_as_label(\UpsFreeVendor\WPDesk\AbstractShipping\CollectionPoints\CollectionPoint $collection_point)
    {
        $label = $collection_point->collection_point_name;
        $label .= ', ' . $collection_point->collection_point_address->address_line1;
        if (!empty($collection_point->collection_point_address->address_line2)) {
            $label .= ', ' . $collection_point->collection_point_address->address_line2;
        }
        $label .= ', ' . $collection_point->collection_point_address->postal_code;
        $label .= ' ' . $collection_point->collection_point_address->city;
        return $label;
    }
}
