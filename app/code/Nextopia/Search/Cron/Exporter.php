<?php

namespace Nextopia\Search\Cron;

use Magento\Framework\App\ObjectManager;
use Nextopia\Search\Cron\NxtExporter;
use Nextopia\Search\Logger\Logger;

class Exporter {

    protected $_logger;

    public function __construct(Logger $logger) {

        $this->_logger = $logger;
    }

    public function execute() {

        $om = ObjectManager::getInstance();
        $oDir = $om->get('Magento\Framework\App\Filesystem\DirectoryList');

        $baseDir = $oDir->getRoot();
        $this->_logger->debug("Root Path : " . $baseDir);
        $appDir = $oDir->getPath($oDir::APP);
        $this->_logger->debug("App Path : " . $appDir);
        $publicDir = $oDir->getPath($oDir::PUB);
        $this->_logger->debug("Public Path : " . $publicDir);
        $bootstrapFileName = $appDir . '/bootstrap.php';
        $this->_logger->debug("Bootstrap FilePath : " . $bootstrapFileName);
        $varDir = $oDir->getPath($oDir::VAR_DIR);
        $this->_logger->debug("Var Path : " . $varDir);

        if (!file_exists($bootstrapFileName)) {
            $this->_logger->err("Bootstrap NOT FOUND");
            return;
        } else {
            $this->_logger->debug("Bootstrap Ok");
        }

        new NxtExporter($publicDir, $this->_logger, $varDir);

        return $this;
    }

}
