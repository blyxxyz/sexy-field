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

namespace Tardigrades\SectionField\Service;

use Tardigrades\Entity\Application;
use Tardigrades\Entity\ApplicationInterface;
use Tardigrades\SectionField\ValueObject\ApplicationConfig;
use Tardigrades\SectionField\ValueObject\Id;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineApplicationManager implements ApplicationManagerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var LanguageManagerInterface
     */
    private $languageManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        LanguageManagerInterface $languageManager
    ) {
        $this->entityManager = $entityManager;
        $this->languageManager = $languageManager;
    }

    public function create(ApplicationInterface $entity): ApplicationInterface
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $entity;
    }

    public function read(Id $id): ApplicationInterface
    {
        $applicationRepository = $this->entityManager->getRepository(Application::class);

        /** @var $application Application */
        $application = $applicationRepository->find($id->toInt());

        if (empty($application)) {
            throw new ApplicationNotFoundException();
        }

        return $application;
    }

    public function readAll(): array
    {
        $applicationRepository = $this->entityManager->getRepository(Application::class);
        $applications = $applicationRepository->findAll();

        if (empty($applications)) {
            throw new ApplicationNotFoundException();
        }

        return $applications;
    }

    public function update(): void
    {
        $this->entityManager->flush();
    }

    public function delete(ApplicationInterface $entity): void
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }

    public function createByConfig(ApplicationConfig $applicationConfig): ApplicationInterface
    {
        $application = $this->setUpByConfig($applicationConfig, new Application());
        $this->entityManager->persist($application);
        $this->entityManager->flush();

        return $application;
    }

    public function updateByConfig(
        ApplicationConfig $applicationConfig,
        ApplicationInterface $application
    ): ApplicationInterface {
        $this->setUpByConfig($applicationConfig, $application);
        $this->entityManager->flush();

        return $application;
    }

    private function setUpByConfig(
        ApplicationConfig $applicationConfig,
        ApplicationInterface $application
    ): ApplicationInterface {
        $applicationConfig = $applicationConfig->toArray();

        $installedLanguages = $this->languageManager->readByI18ns($applicationConfig['application']['languages']);

        $application->setName($applicationConfig['application']['name']);
        $application->setHandle($applicationConfig['application']['handle']);
        foreach ($installedLanguages as $language) {
            $application->addLanguage($language);
        }

        return $application;
    }
}
