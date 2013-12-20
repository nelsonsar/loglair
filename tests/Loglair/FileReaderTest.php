<?php

namespace Loglair;

class FileReaderTest extends \PHPUnit_Framework_TestCase
{
    const FILE_TO_READ = '/tmp/filetoread';

    protected function setUp()
    {
        touch(self::FILE_TO_READ);
        $fileHandler = fopen(self::FILE_TO_READ, 'r+');
        fputs($fileHandler, "foo\nbar\nbaz\n");
        fclose($fileHandler);
    }

    protected function tearDown()
    {
        unlink(self::FILE_TO_READ);
    }

    public function testShouldReturnAFileExcerptFromGivenCoordinates()
    {
        $excerpt = "foo";

        $from = 0;
        $to = 3;

        $fileReader = new FileReader(self::FILE_TO_READ);

        $fileExcerpt = $fileReader->readExcerpt($from, $to);

        $this->assertEquals($excerpt, $fileExcerpt);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testShouldThrowAnExceptionWhenFromItsNotAPositiveInteger()
    {
        $fileReader = new FileReader(self::FILE_TO_READ);

        $fileReader->readExcerpt(-1, 3);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testShouldThrowAnExceptionWhenToItsNotAPositiveInteger()
    {
        $fileReader = new FileReader(self::FILE_TO_READ);

        $fileReader->readExcerpt(1, -3);
    }
}
