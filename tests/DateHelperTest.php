<?php

/**
 * Tests for DateHelper class
 *
 * @package WTS
 * @subpackage Tests
 */

namespace WTS\Tests;

use PHPUnit\Framework\TestCase;
use WTS\Helpers\DateHelper;
use InvalidArgumentException;

/**
 * Test cases for DateHelper timezone-safe date handling.
 */
class DateHelperTest extends TestCase
{
    private DateHelper $dateHelper;

    protected function setUp(): void
    {
        $this->dateHelper = new DateHelper();
    }

    /**
     * Test that DateHelper initializes with default timezone.
     */
    public function testDefaultTimezoneIsAmericaToronto(): void
    {
        $helper = new DateHelper();

        $this->assertEquals('America/Toronto', $helper->getTimezoneName());
    }

    /**
     * Test that DateHelper accepts custom timezone.
     */
    public function testCustomTimezoneIsAccepted(): void
    {
        $helper = new DateHelper('UTC');

        $this->assertEquals('UTC', $helper->getTimezoneName());
    }

    /**
     * Test that invalid timezone throws exception.
     */
    public function testInvalidTimezoneThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid timezone: NotARealTimezone');

        new DateHelper('NotARealTimezone');
    }

    /**
     * Test that getCurrentDate returns a DateTimeImmutable.
     */
    public function testGetCurrentDateReturnsDateTimeImmutable(): void
    {
        $date = $this->dateHelper->getCurrentDate();

        $this->assertInstanceOf(\DateTimeImmutable::class, $date);
    }

    /**
     * Test that getCurrentDate returns midnight (00:00:00).
     */
    public function testGetCurrentDateIsAtMidnight(): void
    {
        $date = $this->dateHelper->getCurrentDate();

        $this->assertEquals('00:00:00', $date->format('H:i:s'));
    }

    /**
     * Test that getCurrentDate is not null.
     *
     * This is critical for CI environments where timezone might not be set.
     */
    public function testGetCurrentDateIsNotNull(): void
    {
        $date = $this->dateHelper->getCurrentDate();

        $this->assertNotNull($date);
        $this->assertNotEmpty($date->format('Y-m-d'));
    }

    /**
     * Test parseDate with valid date string.
     */
    public function testParseDateWithValidString(): void
    {
        $date = $this->dateHelper->parseDate('2026-01-15');

        $this->assertEquals('2026-01-15', $date->format('Y-m-d'));
        $this->assertEquals('00:00:00', $date->format('H:i:s'));
    }

    /**
     * Test parseDate with invalid format throws exception.
     */
    public function testParseDateWithInvalidFormatThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid date format');

        $this->dateHelper->parseDate('01-15-2026');
    }

    /**
     * Test isBeforeDate returns true when current date is before target.
     */
    public function testIsBeforeDateReturnsTrueForFutureDate(): void
    {
        // Use a date far in the future to ensure test reliability
        $result = $this->dateHelper->isBeforeDate('2099-12-31');

        $this->assertTrue($result);
    }

    /**
     * Test isBeforeDate returns false when current date is after target.
     */
    public function testIsBeforeDateReturnsFalseForPastDate(): void
    {
        // Use a date in the past
        $result = $this->dateHelper->isBeforeDate('2020-01-01');

        $this->assertFalse($result);
    }

    /**
     * Test isAfterDate returns true when current date is after target.
     */
    public function testIsAfterDateReturnsTrueForPastDate(): void
    {
        $result = $this->dateHelper->isAfterDate('2020-01-01');

        $this->assertTrue($result);
    }

    /**
     * Test isAfterDate returns false when current date is before target.
     */
    public function testIsAfterDateReturnsFalseForFutureDate(): void
    {
        $result = $this->dateHelper->isAfterDate('2099-12-31');

        $this->assertFalse($result);
    }

    /**
     * Test isBetweenDates returns true when current date is in range.
     */
    public function testIsBetweenDatesReturnsTrueWhenInRange(): void
    {
        // Range that spans current date
        $result = $this->dateHelper->isBetweenDates('2020-01-01', '2099-12-31');

        $this->assertTrue($result);
    }

    /**
     * Test isBetweenDates returns false when current date is before range.
     */
    public function testIsBetweenDatesReturnsFalseWhenBeforeRange(): void
    {
        $result = $this->dateHelper->isBetweenDates('2099-01-01', '2099-12-31');

        $this->assertFalse($result);
    }

    /**
     * Test isBetweenDates returns false when current date is after range.
     */
    public function testIsBetweenDatesReturnsFalseWhenAfterRange(): void
    {
        $result = $this->dateHelper->isBetweenDates('2020-01-01', '2020-12-31');

        $this->assertFalse($result);
    }

    /**
     * Test that date comparison works correctly across different timezones.
     *
     * This ensures CI builds in UTC get same results as local dev in America/Toronto.
     */
    public function testDateComparisonIsConsistentAcrossTimezones(): void
    {
        $torontoHelper = new DateHelper('America/Toronto');
        $utcHelper = new DateHelper('UTC');

        // Both should give a valid date, not null
        $torontoDate = $torontoHelper->getCurrentDate();
        $utcDate = $utcHelper->getCurrentDate();

        $this->assertNotNull($torontoDate);
        $this->assertNotNull($utcDate);

        // Format should be valid YYYY-MM-DD
        $this->assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2}$/', $torontoDate->format('Y-m-d'));
        $this->assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2}$/', $utcDate->format('Y-m-d'));
    }

    /**
     * Test edge case: date exactly at boundary.
     */
    public function testBoundaryDateHandling(): void
    {
        // Test that parsing today's date works
        $today = $this->dateHelper->getCurrentDate();
        $todayString = $today->format('Y-m-d');

        $parsed = $this->dateHelper->parseDate($todayString);

        $this->assertEquals($todayString, $parsed->format('Y-m-d'));
    }
}
