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

        return [
            "provider_code"               => $product->productBasic->product_id,
            "provider_name"               => "Rezb2b",
            "name"                        => $product->productDescription->name,
            "product_entity_type"         => $product->productBasic->product_entity_type,
            "is_departure_date_required"  => true,
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
            "booking_fields"              => $this->getProductBookingFields($product),
            "pickup_locations"            => $this->getPickupLocations($product),
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

                $returnRates[] = array(
                    "rate_id"      => 0,
                    "name"         => $raw->rate_type_name,
                    "label"        => $raw->rate_type_name,
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
        $bookingFields       = isset($product->productPassengerAttribute) ? $product->productPassengerAttribute : [];

        if (!empty($bookingFields)) {

            foreach ($bookingFields as $bookingField) {

                $returnBookingFields[] = array(
                    "label"                    => $bookingField->name,
                    "required_per_participant" => 1,
                    "required_per_booking"     => 0,
                    "visible_per_participant"  => 1,
                    "visible_per_booking"      => 0,
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

}
