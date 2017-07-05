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

    /**
     * InvoicesController constructor.
     * @param $apiKey
     */
    public function __construct($apiKey)
    {
        $this
            ->setApiKey($apiKey)
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
}