<?php
declare(strict_types=1);

namespace Noem\Composer;

use Composer\Composer;
use Composer\Plugin\Capability\CommandProvider;

class NoemCommandProvider implements CommandProvider
{
    private Composer $composer;


    public function __construct(array $args)
    {
        $this->composer = $args['composer'];
    }

    public function getCommands(): array
    {
        return [new NoemCommand($this->composer)];
    }
}
