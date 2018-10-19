# Get Availability

## Description

The Availability Request message requests Flight Availability for a city pair on a specific date for a specific number and type of passengers. Calendar with best price of each day in a week and full flight list with their price depending on cabin will be provided. 
## Endpoint Documentation

See [the API documentation page](https://developer.turkishairlines.com/documentation/GetAvailability) on Turkish Airlines Developer Portal

## Endpoint Method
```php
$client->getAvailability($getAvailabilityParametersObject);

```


### Example with ValueObjects
```php
<?php

use DateTimeImmutable;
use TK\SDK\ValueObject\Location;
use TK\SDK\ValueObject\DepartureDateTime;
use TK\SDK\ValueObject\OriginDestinationInformation;
use TK\SDK\ValueObject\AirScheduleRQ;
use TK\SDK\ValueObject\GetTimetableParameters;

$originLocation = new ValueObject\Location('IST', true);
$destinationLocation  = new ValueObject\Location('ESB', true);

$departureTime = gmdate('Y-m-d H:i:s', strtotime('+4 days'));
$departureDateTime = (new ValueObject\DepartureDateTime(
	new DateTimeImmutable($departureTime),
	'P3D',
	'P3D'
))->withDateFormat('dM');

$originDestinationInformation = (new ValueObject\OriginDestinationInformation(
	$departureDateTime,
	$originLocation,
	$destinationLocation
))->withCabinPreferences(ValueObject\OriginDestinationInformation::CABIN_PREFERENCE_ECONOMY);

$passengerTypeQuantity = (new ValueObject\PassengerTypeQuantity())
	->withQuantity(ValueObject\PassengerTypeQuantity::PASSENGER_TYPE_ADULT, 1)
	->withQuantity(ValueObject\PassengerTypeQuantity::PASSENGER_TYPE_CHILD, 2);
	
$getAvailabilityParameters = new ValueObject\GetAvailabilityParameters(
	ValueObject\GetAvailabilityParameters::REDUCED_DATA_INDICATOR_FALSE,
	ValueObject\GetAvailabilityParameters::ROUTING_TYPE_ONE_WAY,
	$passengerTypeQuantity
);


$getAvailabilityParameters = $getAvailabilityParameters
	->withOriginDestinationInformation($originDestinationInformation);
        
$response = $this->client->getAvailability($getAvailabilityParameters);

```
