<?php

/**
 * Date Helper for WTS Landing Pages
 *
 * Provides timezone-safe date handling for message banners and time-sensitive content.
 *
 * @package WTS
 * @subpackage Helpers
 */

namespace WTS\Helpers;

use DateTimeImmutable;
use DateTimeZone;
use InvalidArgumentException;

/**
 * Helper class for date operations with explicit timezone handling.
 *
 * Ensures consistent date behavior across local dev and CI environments.
 */
class DateHelper
{
    /**
     * Default timezone for all date operations.
     */
    public const DEFAULT_TIMEZONE = 'America/Toronto';

    private DateTimeZone $timezone;

    /**
     * Create a new DateHelper instance.
     *
     * @param string|null $timezone Timezone identifier (defaults to America/Toronto)
     * @throws InvalidArgumentException If timezone is invalid
     */
    public function __construct(?string $timezone = null)
    {
        $tz = $timezone ?? self::DEFAULT_TIMEZONE;

        try {
            $this->timezone = new DateTimeZone($tz);
        } catch (\Exception $e) {
            throw new InvalidArgumentException("Invalid timezone: {$tz}");
        }
    }

    /**
     * Get the current date at midnight in the configured timezone.
     *
     * @return DateTimeImmutable Current date at 00:00:00
     */
    public function getCurrentDate(): DateTimeImmutable
    {
        return (new DateTimeImmutable('now', $this->timezone))->setTime(0, 0, 0);
    }

    /**
     * Parse a date string into a DateTimeImmutable at midnight.
     *
     * @param string $dateString Date in YYYY-MM-DD format
     * @return DateTimeImmutable Parsed date at 00:00:00
     * @throws InvalidArgumentException If date string is invalid
     */
    public function parseDate(string $dateString): DateTimeImmutable
    {
        $date = DateTimeImmutable::createFromFormat('Y-m-d', $dateString, $this->timezone);

        if ($date === false) {
            throw new InvalidArgumentException("Invalid date format: {$dateString}. Expected YYYY-MM-DD.");
        }

        return $date->setTime(0, 0, 0);
    }

    /**
     * Check if the current date is before a given date.
     *
     * @param string $dateString Target date in YYYY-MM-DD format
     * @return boolean True if current date is before target date
     */
    public function isBeforeDate(string $dateString): bool
    {
        $current = $this->getCurrentDate();
        $target = $this->parseDate($dateString);

        return $current < $target;
    }

    /**
     * Check if the current date is after a given date.
     *
     * @param string $dateString Target date in YYYY-MM-DD format
     * @return boolean True if current date is after target date
     */
    public function isAfterDate(string $dateString): bool
    {
        $current = $this->getCurrentDate();
        $target = $this->parseDate($dateString);

        return $current > $target;
    }

    /**
     * Check if the current date falls within a date range (exclusive of end date).
     *
     * @param string $startDate Start date in YYYY-MM-DD format
     * @param string $endDate   End date in YYYY-MM-DD format
     * @return boolean True if current date is >= start AND < end
     */
    public function isBetweenDates(string $startDate, string $endDate): bool
    {
        $current = $this->getCurrentDate();
        $start = $this->parseDate($startDate);
        $end = $this->parseDate($endDate);

        return $current >= $start && $current < $end;
    }

    /**
     * Get the configured timezone.
     *
     * @return DateTimeZone The timezone used for date operations
     */
    public function getTimezone(): DateTimeZone
    {
        return $this->timezone;
    }

    /**
     * Get the timezone name as a string.
     *
     * @return string Timezone identifier
     */
    public function getTimezoneName(): string
    {
        return $this->timezone->getName();
    }
}
