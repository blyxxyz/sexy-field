<?php

/*
 * This file is part of the SexyField package.
 *
 * (c) Dion Snoeijen <hallo@dionsnoeijen.nl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare (strict_types = 1);

namespace Tardigrades\SectionField\ValueObject;

use Assert\Assertion;
use Doctrine\Common\Util\Inflector;

final class ClassName
{
    /** @var string */
    private $className;

    private function __construct(string $className)
    {
        Assertion::string($className, 'ClassName must be a string');

        $this->className = Inflector::classify($className);
    }

    public function __toString(): string
    {
        return $this->className;
    }

    public static function fromString(string $className): self
    {
        return new self($className);
    }
}
