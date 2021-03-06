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

use Doctrine\ORM\EntityManagerInterface;
use Tardigrades\Entity\SectionHistory as SectionHistoryEntity;
use Tardigrades\Entity\SectionInterface;
use Tardigrades\SectionField\ValueObject\Handle;
use Tardigrades\SectionField\ValueObject\Id;
use Tardigrades\SectionField\ValueObject\Version;

class DoctrineSectionHistoryManager implements SectionHistoryManagerInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
    }

    public function create(SectionInterface $entity): SectionInterface
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $entity;
    }

    public function read(Id $id): SectionInterface
    {
        $sectionHistoryRepository = $this->entityManager->getRepository(SectionHistoryEntity::class);

        /** @var SectionInterface $sectionHistory */
        $sectionHistory = $sectionHistoryRepository->find($id->toInt());

        if (empty($sectionHistory)) {
            throw new SectionHistoryNotFoundException();
        }

        return $sectionHistory;
    }

    public function readAll(): array
    {
        $sectionHistoryRepository = $this->entityManager->getRepository(SectionHistoryEntity::class);

        $sections = $sectionHistoryRepository->findAll();

        if (empty($sections)) {
            throw new SectionHistoryNotFoundException();
        }

        return $sections;
    }

    public function update(SectionInterface $entity): void
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    public function delete(SectionInterface $entity): void
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }

    public function readByHandleAndVersion(Handle $handle, Version $version): SectionInterface
    {
        $sectionHistoryRepository = $this->entityManager->getRepository(SectionHistoryEntity::class);

        $section = $sectionHistoryRepository->findBy([
            'handle' => (string) $handle,
            'version' => $version->toInt()
        ]);

        if (empty($section)) {
            throw new SectionNotFoundException();
        }

        return $section[0];
    }
}
