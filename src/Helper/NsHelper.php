<?php

namespace hiqdev\recon\dns\Helper;

/**
 * Class NsHelper
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
final class NsHelper
{
    /**
     * Makes sure FQDN is canonical (has trailing dot)
     *
     * @param string $fqdn
     * @return string
     */
    public static function canonical(string $fqdn): string
    {
        return trim($fqdn, '.') . '.';
    }
}
