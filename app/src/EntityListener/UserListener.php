<?php

namespace App\EntityListener;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserListener
{
  private UserPasswordHasherInterface $hasher;

  public function __construct(UserPasswordHasherInterface $hasher)
  {
    $this->hasher = $hasher;
  }

  public function prePersist(User $user): void
  {
    $this->encorePassword($user);
  }

  public function preUpdate(User $user): void
  {
    $this->encorePassword($user);
  }

  /**
   * Hash the password if it is not null
   * 
   * @param User $user
   * @return void
   */
  public function encorePassword(User $user): void
  {
    if ($user->getPlainPassword() === null) {
      return;
    }

    $user->setPassword(
      $this->hasher->hashPassword(
        $user,
        $user->getPlainPassword()
      )
    );

    $user->setPlainPassword(null);
  }
}
