<?php

declare(strict_types=1);

namespace Noem\Composer;

use Composer\Composer;
use Composer\Factory;

class Config implements \ArrayAccess
{

    private $conf;

    public function __construct(string $key, Composer $composer)
    {
        $root = dirname(Factory::getComposerFile());
        $conf = [
            'dump-modules' => $composer->getConfig()->get('vendor-dir') . '/modules.php',
            'autocomplete-services' => $root . '/.phpstorm.meta.php/serviceAutocomplete.php',
            'type-services' => $root . '/.phpstorm.meta.php/serviceTypes.php',
        ];
        $composerExtra = $composer->getPackage()->getExtra();
        if (isset($composerExtra[$key])) {
            $conf = array_merge($conf, $composerExtra[$key]);
        }
        $this->conf = $conf;
    }

    public function offsetExists($offset)
    {
        return isset($this->conf[$offset]);
    }

    public function offsetGet($offset)
    {
        if (!$this->offsetExists($offset)) {
            return null;
        }

        return $this->conf[$offset];
    }

    public function offsetSet($offset, $value)
    {
        throw new \RuntimeException('readonly');
    }

    public function offsetUnset($offset)
    {
        throw new \RuntimeException('readonly');
    }
}
