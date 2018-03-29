<?php

namespace Travelrez\Rezb2b\Process;

/**
 * Rezdy get product request
 */
class GetProductResponse extends Response
{

    public function isSuccessful()
    {
        if(isset($this->data->code) && $this->data->code == 200){
        	return true;
        }

        return false;
    }

    public function getResult()
    {

        $product       = $this->getProductFromData();

        list($providerId, $productId) = explode("-", $product->productBasic->product_id);

        return [
            "provider_code"               => $productId,
            "provider_id"                 => $providerId,
            "provider_name"               => "Rezb2b",
            "name"                        => $product->productDescription->name,
            "product_entity_type"         => $product->productBasic->product_entity_type,
            "is_departure_date_required"  => true,
            "display_room_option"         => isset($product->productBasic->display_room_option) ? $product->productBasic->display_room_option : false,
            "min_per_booking"             => $product->productBasic->min_guest_number,
            "max_per_booking"             => 20,
            "duration"                    => $product->productBasic->duration." ".$product->productBasic->duration_unit,
            "currency"                    => $product->productDefaultPrice->currency_code,
            "default_price"               => $product->productDefaultPrice->price,
            "rates"                       => $this->getProductRates($product->productDefaultPrice->rates),
            "description"                 => $product->productDescription->full_description,
            "short_description"           => $product->productDescription->brief_description,
            "images"                      => $this->getProductMedia($product),
            "free_sale"                   => $this->getProductFreeSale($product),
            //"attributes"                  => $this->getAttributes($product),
            "pickup_locations"            => $this->getPickupLocations($product),
            "booking_fields"              => $this->getProductBookingFields($product),
            "first_available_date"        => $product->productBasic->operationStartDate,
            
        ];

    }

    public function getProductEntityType($product)
    {
        return isset($product->productType) ? $product->productType : "";
    }

    public function getProductName($product)
    {
        return isset($product->name) ? $product->name : "";
    }

    public function getProductShortDescription($product)
    {
        return isset($product->shortDescription) ? $product->shortDescription : "";
    }

    public function getProductDescription($product)
    {
        return isset($product->description) ? $product->description : "";
    }

    public function getProductProviderCode($product)
    {
        return isset($product->productCode) ? $product->productCode : "";
    }

    public function getProductProviderName($product)
    {
        return isset($product->supplierName) ? $product->supplierName : "";
    }

    public function getProductDefaultPrice($product)
    {
        return isset($product->advertisedPrice) ? $product->advertisedPrice : 0;
    }

    public function getProductCurrency($product)
    {
        return isset($product->currency) ? $product->currency : "USD";
    }

    public function getProductRates($rates)
    {

        $returnRates = array();

        if (!empty($rates)) {
            foreach ($rates as $key => $raw) {

                $rateName = $raw->rate_type_name;
                if($raw->product_rate_type_id == "ADULT_RATE") {
                    $rateName = "Adult";
                }else if($raw->product_rate_type_id == "CHILD_RATE") {
                    $rateName = "Child";
                }

                if(isset($returnRates[$raw->product_rate_type_id])) {
                    continue;
                }


                $returnRates[$raw->product_rate_type_id] = array(
                    "rate_id"      => $raw->product_rate_type_id,
                    "name"         => $raw->rate_type_name,
                    "label"        => $rateName,
                    "seats_used"   => 1,
                    "min_quantity" => 1,
                    "max_quantity" => 30,
                    "price"        => $raw->price, 
                    "price_type"   => "ITEM",
                );

            }
        }

        return $returnRates;
    }

    public function getProductMedia($product)
    {

        $returnMedia = array();


        $returnMedia["thumbnail_url"] = $product->media->thumbnail_url;
        $returnMedia["image_url"]     = $product->media->image_url;

        if (!empty($medias)) {

            $n = 0;

            foreach ($product->media->extra_image_url as $media) {

                 $returnMedia["extra_image_url"][] = $media->extra_image_url;   

            }

        }

        return $returnMedia;

    }

    public function getProductBookingFields($product)
    {
        $returnBookingFields = array();

        $isRequiredLeadonly = isset($product->productBasic->is_required_only_lead_traveler) && $product->productBasic->is_required_only_lead_traveler == true  ? true  : false;

        $bookingFields       = isset($product->productPassengerAttribute) ? $product->productPassengerAttribute : [];

        if (!empty($bookingFields)) {

            foreach ($bookingFields as $bookingField) {

                $returnBookingFields[] = array(
                    "label"                    => $bookingField->name,
                    "required_per_participant" => ($isRequiredLeadonly) ? 0 : 1,
                    "required_per_booking"     => ($isRequiredLeadonly) ? 1 : 0,
                    "visible_per_participant"  => ($isRequiredLeadonly) ? 0 : 1,
                    "visible_per_booking"      => ($isRequiredLeadonly) ? 1 : 0,
                    "field_type"               => $bookingField->type,
                    "options"                  => $bookingField->options,
                    "tips"                     => $bookingField->tips,
                    "use_for"                  => $bookingField->use_for,
                );

            }

        }

        return $returnBookingFields;

    }

    public function getProductFreeSale($product)
    {
        return isset($product->productFreeSale) ? $product->productFreeSale->is_free_sale : false;
    }

    public function getProductFromData()
    {

        return $this->data->productInfo;
    }

    public function getProductType()
    {
        return $this->product->productType;
    }

    public function getProductDepartureRequired($product)
    {
        return true;
    }

    public function getProductMinPerBooking($product)
    {
        return 1;
    }

    public function getProductMaxPerBooking($product)
    {
        return 4;
    }

    public function getProductDuration($product)
    {
        return "0";
    }

    public function getPickupLocations($product)
    {
        return array();
    }

    public function getAttributes($product)
    {

        $attributes = array();

        if(!empty($product->productUpgrade)) {

            $upgrades = $product->productUpgrade;

            foreach($upgrades as $upgrade) {

                $attributes[] = array(
                    "attribute_id"        => $upgrade->upgrade_id,
                    "attribute_name"        => $upgrade->upgrade_name,
                    "attribute_description" => $upgrade->upgrade_description,
                    "is_multiple"           => $upgrade->is_multi,
                    "minimum_required"      => $upgrade->required,
                    "options"               => $this->getAttributeOptions($upgrade->options)
                ); 

            }


        }

        return $attributes;


    }

    public function getAttributeOptions($options = array())
    {

        $returnOptions = array();

        if(!empty($options)){
            foreach($options as $option) {
                $returnOptions[] = array(
                    "option_id"   => $option->option_id,
                    "option_name" => $option->option_name,
                    "sub_options" => isset($option->options) ? $this->getAttributeSubOptions($option->options) : array()
                );

            }
        }

        return $returnOptions; 


    }

    public function getAttributeSubOptions($options)
    {

        $returnOptions = array();

        if(!empty($options)){
            foreach($options as $option) {

                $returnOptions[] = array(
                    "option_id"   => $option->parent_id."_".$option->option_id,
                    "option_name" => $option->option_name,
                );

            }
        }

        return $returnOptions; 


    }

}
