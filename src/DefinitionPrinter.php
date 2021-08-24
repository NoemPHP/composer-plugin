<?php

declare(strict_types=1);


namespace Noem\Composer;


class DefinitionPrinter
{
    public function __construct(private PathResolver $resolver)
    {
    }

    public function print(ComposerServiceProviderDefinition ...$definitions): string
    {
        return <<<PHP
<?php

declare(strict_types=1);

return [
{$this->loopDefinitions(...$definitions)}
];

PHP;
    }

    private function loopDefinitions(ComposerServiceProviderDefinition ...$definitions)
    {
        ob_start();
        foreach ($definitions as $definition) {
            $factories = $this->getParam($definition->package(), $definition->factories());
            $extensions = $this->getParam($definition->package(), $definition->extensions());

            echo <<<PHP
    '{$definition->package()}' => new \Noem\Container\ServiceProvider(
        {$factories},
        {$extensions}
    ),

PHP;
        }
        return ob_get_clean();
    }

    private function getParam(string $packageName, ?string $param): string
    {
        if (!$param) {
            return '[]';
        }
        if (is_callable($param)) {
            return "call_user_func( $param )";
        }
        return "require '{$this->resolver->getPath($packageName, $param)}'";
    }
}
