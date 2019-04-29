<?php

namespace hiqdev\recon\dns\Model;

class Zone
{
    /**
     * @var string
     */
    public $fqdn;

    /**
     * @var Soa
     */
    public $soa;

    /**
     * @var Record[]
     */
    public $records = [];
}
