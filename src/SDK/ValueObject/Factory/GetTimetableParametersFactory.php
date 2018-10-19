<?php
declare(strict_types=1);

namespace TK\SDK\ValueObject\Factory;

use DateTimeImmutable;
use TK\SDK\ValueObject\Location;
use TK\SDK\ValueObject\DepartureDateTime;
use TK\SDK\ValueObject\OriginDestinationInformation;
use TK\SDK\ValueObject\AirScheduleRQ;
use TK\SDK\ValueObject\GetTimetableParameters;

final class GetTimetableParametersFactory implements ValueObjectFactoryInterface
{

    /**
     * @param array $parameters
     * @return GetTimetableParameters
     * @throws \Exception
     */
    public static function createFromArray(array $parameters) : GetTimetableParameters
    {
        $originDestinationInformation = $parameters['OTA_AirScheduleRQ']['OriginDestinationInformation'];
        $departureTime = $originDestinationInformation['DepartureDateTime']['Date'];
        $originLocation = new Location(
            $originDestinationInformation['OriginLocation']['LocationCode'],
            $originDestinationInformation['OriginLocation']['MultiAirportCityInd']
        );
        $destinationLocation = new Location(
            $originDestinationInformation['DestinationLocation']['LocationCode'],
            $originDestinationInformation['DestinationLocation']['MultiAirportCityInd']
        );
        $departureDateTime = new DepartureDateTime(
            new DateTimeImmutable($departureTime),
            'P3D',
            'P3D'
        );
        $originDestinationInformationObject = new OriginDestinationInformation(
            $departureDateTime,
            $originLocation,
            $destinationLocation
        );
        $airScheduleRQ = new AirScheduleRQ($originDestinationInformationObject);
        if (array_key_exists('AirlineCode', $parameters['OTA_AirScheduleRQ'])) {
            $airScheduleRQ = $airScheduleRQ->withAirlineCode($parameters['OTA_AirScheduleRQ']['AirlineCode']);
        }
        if (array_key_exists('FlightTypePref', $parameters['OTA_AirScheduleRQ']) &&
            $parameters['OTA_AirScheduleRQ']['FlightTypePref']['DirectAndNonStopOnlyInd'] === true ) {
            $airScheduleRQ = $airScheduleRQ->withDirectAndNonStopOnlyInd();
        }
        $getTimetableParameters =  new GetTimetableParameters(
            $airScheduleRQ,
            $parameters['scheduleType'],
            $parameters['tripType']
        );
        if (array_key_exists('returnDate', $parameters)) {
            $returnDate = new DateTimeImmutable($parameters['returnDate']);
            $getTimetableParameters =  $getTimetableParameters->withReturnDate($returnDate);
        }
        return $getTimetableParameters;
    }

    /**
     * @param string $json
     * @return GetTimetableParameters
     * @throws \Exception
     */
    public static function createFromJson(string $json) : GetTimetableParameters
    {
        return self::createFromArray(json_decode($json, (bool) JSON_OBJECT_AS_ARRAY));
    }
}
