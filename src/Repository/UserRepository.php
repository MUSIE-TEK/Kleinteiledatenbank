<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */

//Hier sind die für das User-Entity verfügbare Methoden zu finden.
//Also nicht Getter und Setter sondern die komplexere Queries für diese Entität)
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    private RoleRepository $roleRepository;

    public function __construct(ManagerRegistry $registry, RoleRepository $roleRepository)
    {
        parent::__construct($registry, User::class);
        $this->roleRepository = $roleRepository;
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function updateRoles(User $user)
    {
        $getAllRoles = $this->roleRepository->findAll();
        $updateRoles = $user->getRolesObject();

        foreach ($getAllRoles as $allRoles) {
            if ($updateRoles->contains($allRoles)) {
                $user->addRole($allRoles);
            } else {
                $user->removeRole($allRoles);
            }
        }

        $this->_em->persist($user);
        $this->_em->flush();
    }
}
