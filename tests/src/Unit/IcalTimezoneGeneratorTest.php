<?php
/**
 * Created by PhpStorm.
 * User: twhiston
 * Date: 19.01.17
 * Time: 00:05
 */

namespace Drupal\Tests\px_calendar_download;

use Drupal\px_calendar_download\Exception\IcalTimezoneInvalidTimestampException;
use Drupal\px_calendar_download\IcalTimezoneGenerator;

/**
 * @group px_calendar_download
 */
class IcalTimezoneGeneratorTest extends \PHPUnit_Framework_TestCase {

  /**
   * @expectedException \Drupal\px_calendar_download\Exception\IcalTimezoneInvalidTimestampException
   * @expectedExceptionMessage timestap format does not match Y-m-d H:i:s T
   */
  public function testInvalidTimestapFormat() {
    date_default_timezone_set('UTC');

    $ic = new IcalTimezoneGenerator();
    $this->assertEquals([0, 1],
                        $ic->getMinMaxTimestamps([
                                                   '1970-01-01 00:00:00',
                                                   '1970-01-01 00:00:01',
                                                 ]));
  }

  /**
   * Test that the initial return value of the timezone is the default value
   */
  public function testGetDefaultTimezone() {
    $ic = new IcalTimezoneGenerator();
    $this->assertAttributeEquals($ic->getTimestampFormat(),
                                 'timestampFormat',
                                 $ic);
  }

  /**
   * Test that setting the timezone works
   */
  public function testGetSetTimezone() {
    $ic = new IcalTimezoneGenerator();
    $timestamp = 'Y-m-D H:i:s';
    $ic->setTimestampFormat($timestamp);
    $this->assertEquals($timestamp, $ic->getTimestampFormat());
  }

  /**
   * Tests dates sorting.
   */
  public function testMinMaxTimestamps() {

    date_default_timezone_set('UTC');

    $ic = new IcalTimezoneGenerator();
    $this->assertEquals([
                          \DateTime::createFromFormat($ic->getTimestampFormat(),
                                                      '1970-01-01 00:00:00 Europe/Zurich'),
                          \DateTime::createFromFormat($ic->getTimestampFormat(),
                                                      '1970-01-01 00:00:01 Europe/Zurich'),
                        ],
                        $ic->getMinMaxTimestamps([
                                                   '1970-01-01 00:00:00 Europe/Zurich',
                                                   '1970-01-01 00:00:01 Europe/Zurich',
                                                 ]));

    $this->assertEquals([
                          \DateTime::createFromFormat($ic->getTimestampFormat(),
                                                      '1970-01-01 00:00:00 America/Caracas'),
                          \DateTime::createFromFormat($ic->getTimestampFormat(),
                                                      '1970-01-01 00:00:01 America/Caracas'),
                        ],
                        $ic->getMinMaxTimestamps([
                                                   '1970-01-01 00:00:01 America/Caracas',
                                                   '1970-01-01 00:00:00 America/Caracas',
                                                 ]));

    $this->assertEquals([
                          \DateTime::createFromFormat($ic->getTimestampFormat(),
                                                      '1970-01-01 00:00:00 Europe/Paris'),
                          \DateTime::createFromFormat($ic->getTimestampFormat(),
                                                      '1970-01-01 00:00:02 Europe/Paris'),
                        ],
                        $ic->getMinMaxTimestamps([
                                                   '1970-01-01 00:00:01 Europe/Paris',
                                                   '1970-01-01 00:00:02 Europe/Paris',
                                                   '1970-01-01 00:00:00 Europe/Paris',
                                                 ]));
  }

  /**
   * Test min max with a custom timestamp style
   */
  public function testWithCustomTimestamp() {

    date_default_timezone_set('UTC');

    $ic = new IcalTimezoneGenerator();
    $ic->setTimestampFormat('Y-m-d H:i:s');
    $this->assertEquals([
                          \DateTime::createFromFormat($ic->getTimestampFormat(),
                                                      '1970-01-01 00:00:00'),
                          \DateTime::createFromFormat($ic->getTimestampFormat(),
                                                      '1970-01-01 00:00:01'),
                        ],
                        $ic->getMinMaxTimestamps([
                                                   '1970-01-01 00:00:00',
                                                   '1970-01-01 00:00:01',
                                                 ]));

    $this->assertEquals([
                          \DateTime::createFromFormat($ic->getTimestampFormat(),
                                                      '1970-01-01 00:00:00'),
                          \DateTime::createFromFormat($ic->getTimestampFormat(),
                                                      '1970-01-01 00:00:01'),
                        ],
                        $ic->getMinMaxTimestamps([
                                                   '1970-01-01 00:00:01',
                                                   '1970-01-01 00:00:00',
                                                 ]));

    $this->assertEquals([
                          \DateTime::createFromFormat($ic->getTimestampFormat(),
                                                      '1970-01-01 00:00:00'),
                          \DateTime::createFromFormat($ic->getTimestampFormat(),
                                                      '1970-01-01 00:00:02'),
                        ],
                        $ic->getMinMaxTimestamps([
                                                   '1970-01-01 00:00:01',
                                                   '1970-01-01 00:00:02',
                                                   '1970-01-01 00:00:00',
                                                 ]));

  }

}