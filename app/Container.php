<?php

declare(strict_types = 1);

namespace App;

use App\Exceptions\Container\BuiltinTypeHintException;
use App\Exceptions\Container\MissingTypeHintException;
use App\Exceptions\Container\NotInstantiableException;
use App\Exceptions\Container\UnionTypeHintException;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionUnionType;

class Container implements ContainerInterface
{
    private array $entries = [];

    public function get(string $id)
    {
        if($this->has($id)) {
            $entry = $this->entries[$id];

            if(is_callable($this->entries[$id])) {

                return $entry($this);
            }

            $id = $entry;
        }

        return $this->resolve($id);
    }

    public function has(string $id): bool
    {
        return isset($this->entries[$id]);
    }

    public function set(string $id, callable|string $concrete): void
    {
        $this->entries[$id] = $concrete;
    }

    public function resolve(string $id) 
    {
        // исследуем класс

        $reflectionClass = new ReflectionClass($id);

        if(!$reflectionClass->isInstantiable()) {
            throw new NotInstantiableException('this structure is not instantiable');
        }

        // исследуем конструктор

        $constructor = $reflectionClass->getConstructor();

        if(!$constructor) {
            return new $id;
        }

        // исследуем параметры конструктора

        $parameters = $constructor->getParameters();

        if(!$parameters) {
            return new $id;
        }

        // проверяем, являются ли параметры классом
        $dependencies = array_map(
            function(ReflectionParameter $param) {
            $type = $param->getType();

            /* всего 3 случая, которые должны вызывать ошибку:
            1. типа нет
            2. тип встроенный
            3. тип объединенный
            */

            if(!$type) { // если типа нет, то getType возвращает null
                throw new MissingTypeHintException('there is no type hint in container parameter');
            }

            if($type instanceof ReflectionUnionType) {
                throw new UnionTypeHintException('this parameter has union type hint');
            }

            if($type instanceof ReflectionNamedType && !$type->isBuiltin() ) {
                return $this->get($type->getName());
            }

            throw new BuiltinTypeHintException('this parameter has builtin type hint');
        }, $parameters);

        return $reflectionClass->newInstanceArgs($dependencies);
    }
}