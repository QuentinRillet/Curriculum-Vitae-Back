<?php
/**
 * Created by PhpStorm.
 * User: rilletq
 * Date: 07/02/17
 * Time: 09:53
 */

namespace CvBundle\Entity;


use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use FOS\UserBundle\Model\User as BaseUser;

/**
 *
 */
class User extends BaseUser
{
        protected $id;

    /**
     * @Groups({"user"})
     */
    protected $email;

    /**
     * @Groups({"user"})
     */
    protected $fullname;

    /**
     * @Groups({"user-write"})
     */
    protected $plainPassword;

    /**
     * @Groups({"user"})
     */
    protected $username;

    public function setFullname($fullname)
    {
        $this->fullname = $fullname;

        return $this;
    }
    public function getFullname()
    {
        return $this->fullname;
    }

    public function isUser(UserInterface $user = null)
    {
        return $user instanceof self && $user->id === $this->id;
    }
}