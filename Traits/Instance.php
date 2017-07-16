<?php

namespace beabys\PaybookBundle\Traits;

/**
 * Class Instance
 * @package beabys\PaybookBundle\Traits
 * @author Alfonso Rodriguez <beabys@gmail.com>
 */
trait Instance
{

    protected $apiKey;

    protected $savePath;

    /**
     * Instance constructor.
     * @param $apiKey
     * @param null $savePath
     */
    public function __construct($apiKey, $savePath = null)
    {
        $this
            ->setApiKey($apiKey)
            ->setSavePath($savePath)
        ;
    }

    /**
     * @return mixed
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param $apiKey
     * @return $this
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSavePath()
    {
        return $this->savePath;
    }

    /**
     * @param $savePath
     * @return $this
     */
    public function setSavePath($savePath)
    {
        $this->savePath = $savePath;

        return $this;
    }

}