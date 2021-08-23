<?php

declare(strict_types=1);

namespace Noem\Composer;

use Composer\Command\BaseCommand;
use Composer\Composer;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class NoemCommand extends BaseCommand
{
    private Config $config;

    public function __construct(private Composer $composer)
    {
        $this->config = new Config('noem', $this->composer);
        parent::__construct('noem');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $installedRepository = $this->composer->getRepositoryManager()->getLocalRepository();
        $packages = $installedRepository->getPackages();
        $definitions = [];
        foreach ($packages as $package) {
            $extra = $package->getExtra();
            if (!array_key_exists('noem', $extra)) {
                continue;
            }
            $noem = $extra['noem'];
            $factories = $noem['factories'] ?? null;
            $extensions = $noem['extensions'] ?? null;

            $definitions[] = new ComposerServiceProviderDefinition(
                $package->getName(),
                $factories,
                $extensions
            );
        }
        if (empty($definitions)) {
            return;
        }
        $pathResolver = new PathResolver($this->composer);
        $loader = (new DefinitionPrinter($pathResolver))->print(...$definitions);
        $loaderPath = $this->config['dump-modules'];
        file_put_contents($loaderPath, $loader);
    }
}
