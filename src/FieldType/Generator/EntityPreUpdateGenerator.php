<?php

/*
 * This file is part of the SexyField package.
 *
 * (c) Dion Snoeijen <hallo@dionsnoeijen.nl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare (strict_types=1);

namespace Tardigrades\FieldType\Generator;

use Tardigrades\Entity\FieldInterface;
use Tardigrades\FieldType\ValueObject\Template;
use Tardigrades\SectionField\Generator\Loader\TemplateLoader;

class EntityPreUpdateGenerator implements GeneratorInterface
{
    public static function generate(FieldInterface $field): Template
    {
        if (in_array('preUpdate', $field->getConfig()->getEntityEvents())) {
            $asString = (string) TemplateLoader::load(
                $field->getFieldType()->getInstance()->directory() .
                '/GeneratorTemplate/entity.preupdate.php.template'
            );

            $asString = str_replace(
                '{{ propertyName }}',
                $field->getConfig()->getPropertyName(),
                $asString
            );

            return Template::create(
                $asString
            );
        }

        throw new NoPreUpdateEntityEventDefinedInFieldConfigException();
    }
}
