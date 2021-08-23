<?php

declare(strict_types=1);


namespace Noem\Composer;


class ComposerServiceProviderDefinition
{
    public function __construct(
        private string $package,
        private ?string $factories = null,
        private ?string $extensions = null
    ) {
    }

    /**
     * @return string
     */
    public function package(): string
    {
        return $this->package;
    }

    /**
     * @return string|null
     */
    public function factories(): ?string
    {
        return $this->factories;
    }

    /**
     * @return string|null
     */
    public function extensions(): ?string
    {
        return $this->extensions;
    }
}
