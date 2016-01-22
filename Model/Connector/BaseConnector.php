<?php

namespace MB\DashboardBundle\Model\Connector;

use MB\DashboardBundle\Exception\FunctionNotImplementedException;
abstract class BaseConnector implements IConnector
{
    protected $name;

    protected $host;
    protected $ch;
    protected $paramsGet;
    protected $paramsPost;

    public function __construct()
    {
        $this->init();
    }

    /**
     * (non-PHPdoc)
     * @see \MB\DashboardBundle\Model\Connector\IConnector::getName()
     */
    public function getName()
    {
        return $this->name;
    }

    public function init()
    {
        $this->paramsGet = array();
        $this->paramsPost = array();
    }

    /**
     * ! Do not call this function yourself !
     *
     * Build the curl query which will be used by execute()
     *
     * @param string $target
     */
    protected function prepareQuery($target = null)
    {
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        $this->addAuthentication();

        if (!empty($this->paramsGet)) {
            $target = $target . '?' . http_build_query($this->paramsGet);
        }

        curl_setopt($this->ch, CURLOPT_URL, $this->host . $target);
        if (!empty($this->paramsPost)) {
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, http_build_query($this->paramsPost));
        }
    }

    /**
     * (non-PHPdoc)
     * @see \MB\DashboardBundle\Model\Connector\IConnector::execute()
     */
    public function execute($target = null)
    {
        $this->prepareQuery($target);

        $result = curl_exec($this->ch);
        curl_close($this->ch);
        $this->init();
        return $result;
    }

    /**
     * (non-PHPdoc)
     * @see \MB\DashboardBundle\Model\Connector\IConnector::importAllProjects()
     */
    public function importAllProjects()
    {
        throw new FunctionNotImplementedException('importAllProjects');
    }

    /**
     * (non-PHPdoc)
     * @see \MB\DashboardBundle\Model\Connector\IConnector::addAuthentication()
     */
    public function addAuthentication()
    {
        throw new FunctionNotImplementedException('addAuthentication');
    }
}
