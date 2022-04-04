<?php

declare(strict_types=1);

namespace Noem\Composer;

use Composer\Command\BaseCommand;
use Composer\Composer;
use Composer\Package\RootPackageInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class NoemCommand extends BaseCommand
{

    private Config $config;

    private RootPackageInterface $package;

    public function __construct(private Composer $composer)
    {
        $this->config = new Config('noem', $this->composer);
        $this->package = $this->composer->getPackage();
        parent::__construct('noem');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Noem Plugin executing...');
        $installedRepository = $this->composer->getRepositoryManager()->getLocalRepository();
        $packages = $installedRepository->getPackages();
        $packages[] = $this->package;
        $definitions = [];
        foreach ($packages as $package) {
            $extra = $package->getExtra();
            if (!array_key_exists('noem', $extra)) {
                continue;
            }
            $name = $package->getName();
            if (isset($definitions[$name])) {
                continue;
            }
            $noem = $extra['noem'];
            $factories = $noem['factories'] ?? null;
            $extensions = $noem['extensions'] ?? null;
            $output->writeln(sprintf('Found %s', $package->getName()));
            $definitions[$name] = new ComposerServiceProviderDefinition(
                $package->getName(),
                $factories,
                $extensions
            );
        }
        if (empty($definitions)) {
            return 0;
        }
        $pathResolver = new PathResolver($this->composer);
        $loader = (new DefinitionPrinter($pathResolver))->print(...$definitions);
        $loaderPath = $this->config['dump-modules'];
        file_put_contents($loaderPath, $loader);
        return 0;
    }
}
