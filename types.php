<?php

declare(strict_types=1);

include __DIR__ . '/vendor/autoload.php';

class TypeGenerator
{
    public function writeHelpers(array $files, string $basePath = __DIR__): void
    {
        $baseLength = strlen($basePath);

        foreach ($files as $name => $prefix) {
            $mixinTrait = \Cmixin\SeasonMixin::class;
            $methods = get_class_methods($mixinTrait);
            $content = '';

            foreach ($methods as $index => $method) {
                $reflectionMethod = new ReflectionMethod($mixinTrait, $method);
                $file = strtr(substr($reflectionMethod->getFileName(), $baseLength), ['\\' => '/']);
                $line = $reflectionMethod->getStartLine();

                $arguments = implode(', ', array_map(
                    $this->dumpParameter(...),
                    $reflectionMethod->getParameters(),
                ));

                if ($index) {
                    $content .= "\n\n        ";
                }

                $content .= "public$prefix function $method($arguments): static\n        {\n            // Content: see $file:$line\n        }";
            }

            file_put_contents(__DIR__ . "/types/_ide_season_$name.php", "<?php

declare(strict_types=1);

namespace Carbon
{
    class Carbon
    {
        $content
    }
}
");
        }
    }

    protected function dumpValue(mixed $value): string
    {
        if ($value === null) {
            return 'null';
        }

        $value = preg_replace('/^array\s*\(\s*\)$/', '[]', var_export($value, true));
        $value = preg_replace('/^array\s*\(([\s\S]+)\)$/', '[$1]', $value);

        return $value;
    }

    protected function dumpParameter(ReflectionParameter $parameter): string
    {
        $name = $parameter->getName();
        $output = '$' . $name;

        if ($parameter->isVariadic()) {
            $output = "...$output";
        }

        if ($parameter->getType()) {
            $name = $parameter->getType()->getName();

            if (preg_match('/^[A-Z]/', $name)) {
                $name = "\\$name";
            }

            $name = preg_replace('/^\\\\Carbon\\\\/', '', $name);
            $output = ($parameter->isOptional() ? '?' : '') . "$name $output";
        }

        try {
            if ($parameter->isDefaultValueAvailable()) {
                $output .= ' = ' . $this->dumpValue($parameter->getDefaultValue());
            }
        } catch (ReflectionException) {
            // no default value
        }

        return $output;
    }
}

$typeGenerator = new TypeGenerator();

$typeGenerator->writeHelpers([
    'instantiated' => '',
    'static' => ' static',
]);
