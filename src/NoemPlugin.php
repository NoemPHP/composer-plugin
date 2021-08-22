<?php
declare(strict_types=1);

namespace Noem\Composer;

use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Plugin\Capability\CommandProvider;
use Composer\Plugin\Capable;
use Composer\Plugin\PluginInterface;
use Composer\Script\Event;
use Composer\Script\ScriptEvents;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;

class NoemPlugin implements PluginInterface, Capable, EventSubscriberInterface
{

    public function getCapabilities()
    {
        return [
            CommandProvider::class => NoemCommandProvider::class,
        ];
    }

    public static function getSubscribedEvents()
    {
        return [
            ScriptEvents::POST_INSTALL_CMD => 'postAutoload',
            ScriptEvents::POST_UPDATE_CMD => 'postAutoload',
        ];
    }

    public function postAutoload(Event $event)
    {
        (new NoemCommand($event->getComposer()))->run(
            new ArrayInput([]),
            new ConsoleOutput()
        );
    }

    public function activate(\Composer\Composer $composer, \Composer\IO\IOInterface $io)
    {
        // TODO: Implement activate() method.
    }

    public function deactivate(\Composer\Composer $composer, \Composer\IO\IOInterface $io)
    {
        // TODO: Implement deactivate() method.
    }

    public function uninstall(\Composer\Composer $composer, \Composer\IO\IOInterface $io)
    {
        // TODO: Implement uninstall() method.
    }
}
