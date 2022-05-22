<?php

namespace App;

use App\Entity\Supplier;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Repository\RoleRepository;
use App\Repository\SupplierRepository;
use App\Repository\TonerRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;

//gibt eine neue Instanz des jeweiligen angefragten Repository zurück
class RepositoryHelper
{
    /**
     * @var \Doctrine\Persistence\ManagerRegistry
     */
    private ManagerRegistry $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @return UserRepository
     */
    public function getUserRepository(): UserRepository
    {
        return new UserRepository($this->registry, $this->getRoleRepository());
    }

    /**
     * @return ArticleRepository
     */
    public function getArticleRepository(): ArticleRepository
    {
        return new ArticleRepository($this->registry);
    }

    /**
     * @return CategoryRepository
     */
    public function getCategoryRepository(): CategoryRepository
    {
        return new CategoryRepository($this->registry);
    }

    /**
     * @return SupplierRepository
     */
    public function getSupplierRepository(): SupplierRepository
    {
        return new SupplierRepository($this->registry);
    }

    /**
     * @return RoleRepository
     */
    public function getRoleRepository(): RoleRepository
    {
        return new RoleRepository($this->registry);
    }

    /**
     * @return TonerRepository
     */
    public function getTonerRepository(): TonerRepository
    {
        return new TonerRepository($this->registry);
    }
}
