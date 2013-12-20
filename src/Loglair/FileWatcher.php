<?php

namespace Loglair;

class FileWatcher implements \SplSubject
{
    private $lastModificationTime = 0;
    private $filePath = '';
    private $state = null;
    private $observers = null;

    public function __construct($filePath)
    {
        if (false === file_exists($filePath)) {
            throw new \InvalidArgumentException(sprintf('File %s does not exist', $filePath));
        }

        $this->filePath = $filePath;
        $this->observers = new \SplObjectStorage;
    }

    public function getState()
    {
        return $this->state;
    }

    public function startWatch()
    {
        $this->lastModificationTime = time();
        $size = filesize($this->filePath);
        $this->state = new State($size, $size, $this->filePath);
    }

    public function fileHasChanged()
    {
        if (false === $this->monitoringHasStarted()) {
            throw new \LogicException('To check if file changed you first need to start watch it');
        }

        clearstatcache();
        return (filemtime($this->filePath) > $this->lastModificationTime);
    }

    public function attach(\SplObserver $observer)
    {
        $this->observers->attach($observer);
    }

    public function detach(\SplObserver $observer)
    {
        $this->observers->detach($observer);
    }

    public function notify()
    {
        if (false === $this->monitoringHasStarted()) {
            throw new \LogicException('Cannot notify without start monitoring');
        }

        if (count($this->observers) === 0) {
            throw new \LogicException('For use notify you need to register observers first');
        }

        $this->updateState();
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }

        clearstatcache();
        if (filesize($this->filePath) > $this->state->getFileSize()) {
            $this->notify();
        }

        $this->lastModificationTime = time();
    }

    public function isRegistered(\SplObserver $observer)
    {
        return $this->observers->contains($observer);
    }

    private function updateState()
    {
        clearstatcache();
        $newCursorLocation = $this->state->getFileSize();
        $fileSize = filesize($this->filePath);

        $this->state = new State($newCursorLocation, $fileSize, $this->filePath);
    }

    private function monitoringHasStarted()
    {
        return ($this->lastModificationTime > 0);
    }
}
