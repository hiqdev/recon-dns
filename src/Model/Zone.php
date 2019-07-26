<?php

namespace hiqdev\recon\dns\Model;

class Zone
{
    /**
     * @var string The FQDN. Stores punycode for IDN
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
