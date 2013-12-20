<?php

namespace Loglair;

class FileWatcherTest extends \PHPUnit_Framework_TestCase
{
    const FILE_TO_WATCH = '/tmp/filewatchertest';

    protected function setUp()
    {
        touch(self::FILE_TO_WATCH);
    }

    protected function tearDown()
    {
        unlink(self::FILE_TO_WATCH);
    }

    public function testShouldReturnTrueWhenFileHasModification()
    {
        $watcher = new FileWatcher(self::FILE_TO_WATCH);

        $watcher->startWatch();
        $this->modifyFile();

        $this->assertTrue($watcher->fileHasChanged());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testShouldThrowAnExceptionWhenFileDoesNotExist()
    {
        $watcher = new FileWatcher('file/does/not/exist');
    }

    /**
     * @expectedException \LogicException
     */
    public function testShouldThrowAnExceptionWhenCallingFileHasChangedWithoutStartWatch()
    {
        $watcher = new FileWatcher(self::FILE_TO_WATCH);

        $watcher->fileHasChanged();
    }

    public function testShouldAttachAnObserverToFileWatcher()
    {
        $watcher = new FileWatcher(self::FILE_TO_WATCH);

        $observer = new DummyObserver;
        $watcher->attach($observer);

        $this->assertTrue($watcher->isRegistered($observer));
    }

    public function testShouldDetachAnObserverFromFileWatcher()
    {
        $watcher = new FileWatcher(self::FILE_TO_WATCH);

        $observer = new DummyObserver;
        $watcher->attach($observer);
        $watcher->detach($observer);

        $this->assertFalse($watcher->isRegistered($observer));
    }

    public function testShouldNotifyRegisteredObservers()
    {
        $watcher = new FileWatcher(self::FILE_TO_WATCH);
        $observerMock = $this->getMock('Loglair\DummyObserver');
        $observerMock->expects($this->once())->method('update')->with($watcher);
        $watcher->attach($observerMock);
        $watcher->startWatch();

        $watcher->notify();
    }

    /**
     * @expectedException \LogicException
     */
    public function testShouldThrowAnExceptionWhenCallingNotifyWithoutStartMonitoring()
    {
        $watcher = new FileWatcher(self::FILE_TO_WATCH);
        $observer = new DummyObserver;
        $watcher->attach($observer);

        $watcher->notify();
    }

    /**
     * @expectedException \LogicException
     */
    public function testShouldThrowAnExceptionWhenCallingNotifyWithoutAnyRegisteredObservers()
    {
        $watcher = new FileWatcher(self::FILE_TO_WATCH);
        $watcher->startWatch();

        $watcher->notify();
    }

    private function modifyFile()
    {
        //ugly... I know...
        usleep(499999);
        $fileHandler = fopen(self::FILE_TO_WATCH, 'a');
        fputs($fileHandler, "foo\n");
        fputs($fileHandler, "foo\n");
        fputs($fileHandler, "foo\n");
        fclose($fileHandler);
    }
}

class DummyObserver implements \SplObserver
{
    public function update(\SplSubject $subject)
    {
        //noop
    }
}
