<?php

namespace vincentdieltiens\DoctrineUTCDateTime;

use Doctrine\DBAL\Platforms\AbtractPlatform;
use Doctrine\DBAL\Types\ConversionException;

class UTCDateTimeType extends DateTimeType
{
    static private $utc = null;

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }


        return $value->format($platform->getDateTimeFormatString(),
            (self::$utc) ? self::$utc : (self::$utc = new \DateTimeZone(\DateTimeZone::UTC))
        );
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        $val = \DateTime::createFromFormat(
            $platform->getDateTimeFormatString(),
            $value,
            (self::$utc) ? self::$utc : (self::$utc = new \DateTimeZone(\DateTimeZone::UTC))
        );
        if (!$val) {
            throw ConversionException::conversionFailed($value, $this->getName());
        }
        return $val;
    }
}