<?php


namespace App\Services;


use App\Entity\Technology;
use App\Entity\TechnologyRequest;
use App\Repository\TechnologyRequestRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class TechnologyCreator
{
    /** @var EntityManager */
    private $entityManager;

    /** @var TechnologyRequestRepository */
    private $technologyRequestRepository;

    public function __construct(EntityManagerInterface $entityManager, TechnologyRequestRepository $technologyRequestRepository)
    {
        $this->entityManager = $entityManager;
        $this->technologyRequestRepository = $technologyRequestRepository;
    }

    public function createTechnology($name, $description, $requestId)
    {
        $request = $this->technologyRequestRepository->findOneBy(['requestId' => (int)$requestId]);

        $request->setOptions(TechnologyRequest::OPTION_RESOLVED);

        $technology = new Technology();

        $technology->setDescription($description);
        $technology->setName($name);

        $this->entityManager->persist($request);
        $this->entityManager->flush();

        $this->entityManager->persist($technology);
        $this->entityManager->flush();
    }

    public function deleteRequest($requestId)
    {
        $request = $this->technologyRequestRepository->findOneBy(['requestId' => (int)$requestId]);

        $this->entityManager->remove($request);
        $this->entityManager->flush();
    }
}