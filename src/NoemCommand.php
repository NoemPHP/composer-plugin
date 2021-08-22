<?php
declare(strict_types=1);

namespace Noem\Composer;

use Composer\Command\BaseCommand;
use Composer\Composer;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class NoemCommand extends BaseCommand
{
    public function __construct(private Composer $composer)
    {
        $this->config = new Config('noem', $this->composer);
        parent::__construct('noem');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $installedRepository = $this->composer->getRepositoryManager()->getLocalRepository();
        $packages = $installedRepository->getPackages();
        foreach ($packages as $package) {
            $extra = $package->getExtra();
            if (!array_key_exists('noem', $extra)) {
                continue;
            }
        }
    }
}
