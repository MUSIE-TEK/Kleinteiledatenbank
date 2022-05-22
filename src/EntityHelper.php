<?php

namespace App;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Supplier;
use App\Entity\Toner;
use App\Entity\User;
//gibt eine neue Instanz des jeweiligen angefragten Entitys zurück
class EntityHelper
{
    /**
     * @return User
     */
    public function getUserEntity(): User
    {
        return new User();
    }

    /**
     * @return Category
     */
    public function getCategoryEntity(): Category
    {
        return new Category();
    }

    /**
     * @return Supplier
     */
    public function getSupplierEntity(): Supplier
    {
        return new Supplier();
    }

    /**
     * @return Article
     */
    public function getArticleEntity(): Article
    {
        return new Article();
    }

    /**
     * @return Toner
     */
    public function getTonerEntity(): Toner
    {
        return new Toner();
    }
}
