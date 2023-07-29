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
                    $content .= "\n\n    ";
                }

                $content .= "public$prefix function $method($arguments): static\n    {\n        // Content: see $file:$line\n    }";
            }

            file_put_contents(__DIR__ . "/types/_ide_season_$name.php", "<?php

declare(strict_types=1);

namespace Carbon;

class Carbon
{
    $content
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
            $type = $parameter->getType();
            $name = $this->getTypeName($type);
            $output = ($parameter->isOptional() && !($type instanceof ReflectionUnionType) && !($type instanceof ReflectionIntersectionType) ? '?' : '') . "$name $output";
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

    protected function getInnerTypeName(ReflectionType $type): string
    {
        return $this->getTypeName($type, '(', ')');
    }

    protected function getTypeName(ReflectionType $type, string $start = '', string $end = ''): string
    {
        if ($type instanceof ReflectionIntersectionType) {
            return $start . implode('&', array_map(
                $this->getInnerTypeName(...),
                $type->getTypes(),
            )) . $end;
        }

        if ($type instanceof ReflectionUnionType) {
            return $start . implode('|', array_map(
                $this->getInnerTypeName(...),
                $type->getTypes(),
            )) . $end;
        }

        $name = $type->getName();

        if (preg_match('/^[A-Z]/', $name)) {
            $name = "\\$name";
        }

        return preg_replace('/^\\\\Carbon\\\\/', '', $name);
    }
}

$typeGenerator = new TypeGenerator();

$typeGenerator->writeHelpers([
    'instantiated' => '',
    'static'       => ' static',
]);
