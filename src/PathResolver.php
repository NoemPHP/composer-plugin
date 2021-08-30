<?php

declare(strict_types=1);


namespace Noem\Composer;


use Composer\Composer;
use Composer\Factory;
use Composer\Semver\Constraint\MatchAllConstraint;

class PathResolver
{
    public function __construct(private Composer $composer)
    {
    }

    public function getPath(string $packageName, string $relativePath): string
    {
        $pkg = $this->composer->getRepositoryManager()
            ->getLocalRepository()
            ->findPackage($packageName, new MatchAllConstraint());
        if (!$pkg) {
            $composerFile = Factory::getComposerFile();
            $composerFile = rtrim(dirname($composerFile), '/') . '/';
            return realpath($composerFile . $relativePath);;
        }

        $targetDir = $this->composer->getInstallationManager()->getInstallPath($pkg);
        $targetDir = rtrim($targetDir, '/') . '/';
        return $targetDir . $relativePath;
    }
}
