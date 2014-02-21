<?php

namespace TomDavidson\MembershipBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TomDavidson\MembershipBundle\Entity\Member
 *
 * @ORM\Entity
 * @ORM\Table(name="members")
 */
class Member
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=64)
     */
    private $name;

    /**
     * @var string $organisation
     *
     * @ORM\Column(name="organisation", type="string", length=64)
     */
    private $organisation;

    /**
     * @var string $email
     *
     * @ORM\Column(name="email", type="string", length=64)
     */
    private $email;

    /**
     * @var string $active
     *
     * @ORM\Column(name="active", type="integer")
     */
    private $active = 1;

    /**
     * @var datetime $date
     *
     * @ORM\Column(name="date", type="string")
     */
    private $date;

    /**
     * @var string $ip
     *
     * @ORM\Column(name="ip", type="string", length=64)
     */
    private $ip;

    /**
     * Get name
     *
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * Get email
     *
     */
    public function getEmail()
    {
        return $this->email;
    }
    /**
     * Set email
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Set active
     *
     * @param boolean $active
     */
    public function setActive($active)
    {
		switch($active){
			case true;
				$this->active = 1;
			break;
			case false;
				$this->active = 0;
			break;
			default;
				throw new \Exception('Subscriber::active must be either true or false.');
			break;
		}
    }

    /**
     * Is active
     */
    public function isActive()
    {
		if($this->active == 1){
			return true;
		}else{
			return false;
		}
	}

    /**
     * Set date
     *
     * @param string $date
     */
    public function setDate($date = null)
    {
		if($date == null){
			$this->date = date('Y-m-d');
		}else{
			if (($timestamp = strtotime($str)) === false) {
				throw new \Exception('Date-like expected for Subscriber::setDate, given "'.$date.'"');
			}else{
				$this->date = date('Y-m-d', $timestamp);
			}
		}
    }

    /**
     * Set ip
     *
     * @param string $ip
     */
    public function setIP($ip)
    {
        $this->ip = $ip;
    }

}