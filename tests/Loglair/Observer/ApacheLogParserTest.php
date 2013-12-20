<?php

namespace Loglair\Observer;

class ApacheLogParserTest extends \PHPUnit_Framework_TestCase
{
    public function testShouldReturnAnArrayOfPatternMatches()
    {
      $expectedResult = array(
          'clientip' => '10.0.2.2',
          'ident' => '-',
          'auth' => '-',
          'timestamp' => '17/Nov/2013:21:32:48 +0000',
          'verb' => 'GET',
          'request' => '/',
          'httpversion' => '1.1',
          'response' => '200',
          'bytes' => '484',
          'port' => '',
          'referrer' => '',
          'agent' => '"Mozilla/5.0 (Macintosh; Intel Mac OS X 10.9; rv:25.0) Gecko/20100101 Firefox/25.0"',
      );

      $content = '10.0.2.2 - - [17/Nov/2013:21:32:48 +0000] "GET / HTTP/1.1" 200 484 "-" "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.9; rv:25.0) Gecko/20100101 Firefox/25.0"';
        $apacheLogParser = new ApacheLogParser;

        $result = $apacheLogParser->parse($content);

        $this->assertEquals($expectedResult, $result);
    }
}
