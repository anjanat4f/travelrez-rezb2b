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

## Response
Key  |  Type | Information
--- | --- | ---
provider_code | string | tour provider code
provider_id | string | tour provider id or channel_id
provider_name | string | tour provider name
name | string | tour name
product_entity_type | string | tour type
is_departure_date_required | bool | departure date required for booking ?
display_room_option | bool | display room option for booking?
min_per_booking | int | minimum person per booking
max_per_booking | int | maximum person per booking
duration | string | duration of tour
currency | char | currency 3 char code
default_price | decimal | default tour price
rates | array | [see here](#rates)
description | text | tour description
short_description | text | tour short description
images | array | [see here](#images)
free_sale | bool | is tour free sale ?
pickup_locations | array | pickup locations
booking_fields | array | [see here](#bookingfields)

## Rates
Key  |  Type | Information
--- | --- | ---
rate_id | string | rate identifier
name | string | rate name
label | string | rate label
seats_used | int | seats will use for when select this rate
min_quantity | int | min quantity needed for select this rate
max_quantity | int | max quantity needed for select this rate
price | decimal | rate price start from (price is as per tour currency)
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
required_per_participant | bool | is field required per participant
required_per_booking | bool | is field required per booking
visible_per_participant | bool | is field visible per participant 
visible_per_booking | bool | is field visible per booking 
field_type | string | field type like TEXT 
options | array | available options to choose
tips | string | tips for the field
use_for | array | need to display this field for which rate id eg. ALL / ADULT_RATE


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
isAutoConfirm | bool | will tour be auto confirmed based on start date?

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
departure_start_date | date | departure start date to get booking fields for

## Response 

Key  |  Type | Information
--- | --- | ---
product_code | int | product id
product_name | string | product name 
sale_quantity_rule | string | product sale quantity rule like person wise 
price | array | product price [see here](#price)
attributes | array | product attributes [see here](#attributes)
pickup_on_request | bool | need pickup location from customer?
pickup_points | array | product pickup points [see here](#pickup)

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
option_id | int | attribute option id
is_multi | bool | multiple option values can be selected?
short_description | text | short description of option 
option_selections | array | attributes options [see here](#options)
total_price_display | string | only display purpose if you are converting price than do not use
price_breakdown | string | only display purpose if you are converting price than do not use

## Options

Key  |  Type | Information
--- | --- | ---
value | int | need to be used when option select
price | double | option price (Please note this is default price it will multiplied by person/qty/booking/duration)
option_sale_currency | char | currency used in price
text | string | option name
is_has_sub | bool | has sub options?
sub_options | array | available sub options [see here](#suboptions) 

## SubOptions

Key  |  Type | Information
--- | --- | ---
value | int | need to be used when sub option select
price | double | sub option price (Please note this is default price it will multiplied by person/qty/booking/duration)
option_sale_currency | char | currency used in price
text | string | sub option name

## Pickup

Key  |  Type | Information
--- | --- | ---
pickup_key | string | send when booking it is value of that pickup
time | string | pickup time 
pickup_name | string | pickup name
description | string | pickup description
address1 | srting | pickup address line 1
address2 | srting | pickup address line 2
postcode | string | postal code of pickup
geocode | string  | geo code of location
pickup_on_request | bool | need pickup location from customer?


4 . Get Product Price Calculation


```php

$items = array(
	"product_code" => "3", // example product code
	"departure_start_date" => "YYYY-MM-DD",	
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
departure_start_date | date | date of departure
rates | array | send booking array of rate with array("rate_id" => "ID of rate" , "qty" => 1)
options | array | send option and value together like ``` "options" => array("490" => array("280")) ```


## Response 

Key  |  Type | Information
--- | --- | ---
product_code | int | product code
provider_id | int | tour provider id
departure_start_date  | string | tour start date in date format like "2018-03-15"
departure_end_date  | string | tour end date in date format like "2018-03-15"
sale_currency   | char | product currency   
price | array | product price [see here](#productprice)

## ProductPrice

Key  |  Type | Information
--- | --- | ---
total | double | tour total 
sub_total | double | tour sub total
price_breakdown | array | only display purpose return how price was calculated
attribute_total | double | selected attribute's total

4 . Create order


```php

$data = array(
  "items" => array(
    	"product_code" => "3", // example product code
    	"departure_start_date" => "YYYY-MM-DD",	
    	"rates" => array(
	   1 => array(
	    	array(
		    "rate_id" => ADULT_RATE,
		    "qty"     => 2,
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
	),
	"pickup_location" => "", // customer's pickup in case of pickup_on_request = 1 see 3. Get Product Booking Request 
	"departure_location" => "11:30 AM::Golden era park"
   ),
   "customers" => array(
       "title"      => "Mr",
       "first_name" => "Pravin",
       "last_name"  => "Solanki",
       "email"      => "iipl.pravins@gmail.com",
       "telephone"  => +91 7896541230
   ),
   "note" => ""
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
note | string | Special note from customer if any


## Response 

Key  |  Type | Information
--- | --- | ---
booking_id | string | booking number
status | string | booking status string like confirmed or New

23-03-2018
