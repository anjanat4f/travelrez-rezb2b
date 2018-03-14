# travelrez-rezb2b

How to use code?

Connect APi

```php

use Travelrez\Travelrez;

$gateway = Travelrez::create('Rezb2b');

$gateway->setTestMode(1);
$gateway->setApiKey('YOUR_API_KEY');

```

1 . Get Product Information


```php

$response = $gateway->getProduct(array(
   "product_code" => "3"
))->send();

if($response->isSuccessful()){
   echo '<pre>';print_r($response->getResult());
}else{
   echo  "Error " .$response->getCode() . ': ' . $response->getMessage();
}

```

## Request
Key  |  Type | Information
--- | --- | ---
product_code | string |  product code unique identifier (some time same code but diffrent channel)
channel_id | string |  channel id for that tour called provider id as well

## Response
Key  |  Type | Information
--- | --- | ---
provider_code | string | tour provider code
provider_id | string | tour provider id or channel_id
provider_name | string | tour provider name
name | string | tour name
product_entity_type | string | tour type
is_departure_date_required | bool | departure date required for booking ?
min_per_booking | int | minimum person per booking
max_per_booking | int | maximum person per booking
duration | mix | duration of tour
currency | char | currency 3 char code
default_price | decimal | default tour price
rates | array | [see here](#rates)
description | text | tour description
short_description | text | tour short description
images | array | [see here](#images)
free_sale | bool | is tour free sale ?
booking_fields | array | [see here](#bookingfields)
pickup_locations | array | pickup locations

## Rates
Key  |  Type | Information
--- | --- | ---
rate_id | string | rate identifier
name | string | rate name
label | string | rate label
seats_used | int | seats will use for when select this rate
min_quantity | int | min quantity needed for select this rate
max_quantity | int | max quantity needed for select this rate
from_price | decimal | rate price start from (price is as per tour currency)
price_type   | string | identify rates is per order , per person etc (future updates)

## Images
Key  |  Type | Information
--- | --- | ---
thumbnail_url | string | url of thubmnail of product 
image_url | string | url of image of product 
extra_image_url | array | extra images if there

## BookingFields
Key  |  Type | Information
--- | --- | ---
label | string | Booking field label
required_per_participant | int | is field required per participant
required_per_booking | int | is field required per booking
visible_per_participant | int | is field visible per participant 
visible_per_booking | int | is field visible per participant 
field_type | string | field type like TEXT 



2 . Get Product Avaiability


```php

$response = $gateway->getProductAvailability(array(
	"product_code" => "3", // example product code
	"start_date"   => "YYYY-MM-DD",
	"end_date"     => "YYYY-MM-DD"
))->send();

if($response->isSuccessful()){
   echo '<pre>';print_r($response->getResult());die;	
}else{
   echo  "Error " .$response->getCode() . ': ' . $response->getMessage();
}
```

## Request
Key  |  Type | Information
--- | --- | ---
product_code | string |  product code unique identifier
start_date | date | start date to find certain periods avaibility
end_date | date | end date to find certain periods avaibility

## Response
Key  |  Type | Information
--- | --- | ---
available_dates | array | list of all available tours [see here](#availability) 

## Availability
Key  |  Type | Information
--- | --- | ---
departure_date | date | date available for booking 
is_need_departure_time | bool | is departure time need when booking ?
departure_times | array |  departure time available for that dates [see here](#departuretimes) 

## DepartureTimes
Key  |  Type | Information
--- | --- | ---
departure_time | time | departure time for date
rates | array | list of all availble rates for specific departure times [see here](#rates)


3 . Get Product Booking Request

```php

$items = array(
	"product_code" => "3", // example product code
	"departure_start_date" => "YYYY-MM-DD",	
);

$response = $gateway->getProductBookingRequest($items)->send();

if($response->isSuccessful()){
   echo '<pre>';print_r($response->getResult());die;	
}else{
   echo  "Error " .$response->getCode() . ': ' . $response->getMessage();
}
```

## Request

Key  |  Type | Information
--- | --- | ---
product_code | string | product code unique identifier
departure_start_date | datetime | please add date time format for the select departure date , if there is no departure time than you can use 00:00:00

## Response 

Key  |  Type | Information
--- | --- | ---
product_code | int | product id
product_name | string | product name 
sale_quantity_rule | string | product sale quantity rule like person wise 
price | string | product price [see here](#price)
attributes | array | product attributes [see here](#attributes)
pickup_points | array | product attributes [see here](#pickup)

## Price

Key  |  Type | Information
--- | --- | ---
sale_currency | char | price currency
min_booking_size | int | minimum booking person can selected
spaces_remaining | int | space remaining for tour 
total_price | decimal | product total price
total_price_display | string | only display purpose if you are converting price than do not use
price_breakdown | string | only display purpose if you are converting price than do not use

## Attributes

Key  |  Type | Information
--- | --- | ---
option_name | string | attribute option name
option_id | string | attribute option id
short_description | text | short description of option 
option_selections | array | attributes options [see here](#options)
total_price_display | string | only display purpose if you are converting price than do not use
price_breakdown | string | only display purpose if you are converting price than do not use

## Options

Key  |  Type | Information
--- | --- | ---
value | string | need to be used when option select
price | double | option price (Please note this is default price it will multiplied by person/qty/booking/duration)
option_sale_currency | char | price added on currecy 
text | string | option name

## Pickup

Key  |  Type | Information
--- | --- | ---
pickup_key | string | send when booking it is value of that pickup
time | string | pickup time 
pickup_name | string | pickup name
description | string | pickup description
address1 | srting | pickup address
address2 | srting | pickup address
postcode | string | postal code of pickup
geocode | string  | geo code of location


4 . Get Product Price Calculation


```php

$items = array(
	"product_code" => "3", // example product code
	"departure_start_date" => "YYYY-MM-DD h:i:00",	
	"rates" => array(
	    array(
	    	array(
		     "rate_id" => RATE_ID,
		     "qty"     => QTY
		),
		array(
		     "rate_id" => RATE_ID,
		     "qty"     => QTY
		)
	    )
	),
    "start_time" => "value",	
    "duration" => "8 days 7 night",
    "options" => array(
        "490" => array("280") // as rezb2b allow multiple upgrade
    )
);

$response = $gateway->getProductPriceCalculation($items)->send();

if($response->isSuccessful()){
   echo '<pre>';print_r($response->getResult());die;	
}else{
   echo  "Error " .$response->getCode() . ': ' . $response->getMessage();
}
```

## Request

Key  |  Type | Information
--- | --- | ---
product_code | string | product code unique identifier
departure_start_date | datetime | please add date time format for the select departure date , if there is no departure time than you can use 00:00:00
rates | array | send booking array of rate with array("rate_id" => "ID of rate" , "qty" => 1)
duration | string | send tour total duration sring like "8 days and 7 nights see product response , check 1 . Get Product Information"
start_time | string | not required but if tour has multiple start time than add it
options | array | send option and value together like ``` "options" => array("490" => array("280")) ```


## Response 

Key  |  Type | Information
--- | --- | ---
product_code | int | product code
provider_id | int | tour provider id or channel id
departure_start_date  | string | tour start date in date time format like "2018-03-15 08:00"
departure_end_date  | string | tour end date in date time format like "2018-03-15 08:00"
sale_currency   | string | product currency   
price | array | product price [see here](#productprice)

## ProductPrice

Key  |  Type | Information
--- | --- | ---
total | double | tour total 
sub_total | double | tour sub total
price_breakdown | array | only display purpose return how price was calculated in array

4 . Create order


```php

$data = array(
  "items" => array(
    	"product_code" => "3", // example product code
    	"departure_start_date" => "YYYY-MM-DD h:i:00",	
    	"rates" => array(
	   1 => array(
	    	array(
		    "rate_id" => $firstRates["rate_id"],
		    "qty"     => "2",
	    	)
	   ),
    	),
	"options" => array(
            "490" => array(
	    	1680
	    )
        ),
	"participants" => array(
	    array(
		"first_name"  => "Foo1",
		"last_name"   => "Bar1"  
	    ),
	    array(
		"first_name"  => "Foo2",
		"last_name"   => "Bar2"  
	    )
	)
   ),
   "customers" => array(
       array(
            "title"      => "Mr",
            "first_name" => "Pravin",
            "last_name"  => "Solanki",
            "email"      => "iipl.pravins@gmail.com",
       )
   )   
);

$response = $gateway->createBooking($data)->send();

if($response->isSuccessful()){
   echo '<pre>';print_r($response->getResult());die;	
}else{
   echo  "Error " .$response->getCode() . ': ' . $response->getMessage();
}
```


## Request

Key  |  Type | Information
--- | --- | ---
items | array | item array see in example
customers | array | customers (travellers checkout options) array see in example


## Response 

Key  |  Type | Information
--- | --- | ---
booking_id | string | booking number
status | string | booking status string like confirmed or quatation
