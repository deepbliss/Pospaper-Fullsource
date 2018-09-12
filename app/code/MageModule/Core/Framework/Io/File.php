<?php

namespace MageModule\Core\Framework\Io;

class File extends \Magento\Framework\Filesystem\Io\File
{
    /**
     * @param string $file
     * @param string $mode
     *
     * @return $this
     */
    public function setStream($file, $mode = 'r+')
    {
        $this->_streamFileName = $file;
        $this->_streamChmod    = 0660;
        $this->_streamHandler  = fopen($file, $mode);

        return $this;
    }

    /**
     * @return $this
     */
    public function unsetStream()
    {
        $this->streamClose();
        $this->_streamFileName = null;
        $this->_streamHandler  = null;

        return $this;
    }
}
