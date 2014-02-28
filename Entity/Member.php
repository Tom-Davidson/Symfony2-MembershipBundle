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
	 * @ORM\OneToOne(targetEntity="Member", inversedBy="sponsor1")
	 * @ORM\OneToOne(targetEntity="Member", inversedBy="sponsor2")
     */
    private $id;
	public function getId(){ return $this->id; }

    /**
     * @var string $name
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;
	public function getName(){ return $this->name; }
    public function setName($name) { $this->name = $name; }

    /**
     * @var string $organisation
     *
     * @ORM\Column(name="organisation", type="string", length=254)
     */
    private $organisation;
	public function getOrganisation(){ return $this->organisation; }
    public function setOrganisation($organisation) { $this->organisation = $organisation; }

    /**
     * @var string $email
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="email", type="string", length=254)
     */
    private $email;
	public function getEmail(){ return $this->email; }
    public function setEmail($email) { $this->email = $email; }

    /**
     * @var string $password
     *
     * @ORM\Column(name="password", type="string", length=254)
     */
    private $password;
	//public function getPassword(){ return $this->password; }
    public function setPassword($password) { $this->password = crypt($password, 'p3PP3r'); }
	public function checkPassword($password){
		if($this->password == crypt($password, 'p3PP3r')){
			return true;
		}else{
			return false;
		}
	}

    /**
     * @var string $status
     *
     * @ORM\Column(name="status", type="string", length=1)
     */
    private $status = 'U';
	public function getStatus(){ return $this->status; }
    public function setStatus($status) {
		// A = active, I = inactive, U = unverified
		if(in_array($status, array('A','I','U'))){
			$this->status = $status;
		}else{
			throw new \Exception('Member::status must be one of: A/I/U.');
		}
    }

    /**
     * @var object $sponsor1
     *
     * @ORM\OneToOne(targetEntity="Member")
	 * @ORM\JoinColumn(name="sponsor1_id", referencedColumnName="id", nullable=false)
     */
    private $sponsor1;
	public function getSponsor1(){ return $this->sponsor1; }
    public function setSponsor1($sponsor1) { $this->sponsor1 = $sponsor1; }

    /**
     * @var object $sponsor2
     *
     * @ORM\OneToOne(targetEntity="Member")
	 * @ORM\JoinColumn(name="sponsor2_id", referencedColumnName="id", nullable=false)
     */
    private $sponsor2;
	public function getSponsor2(){ return $this->sponsor2; }
    public function setSponsor2($sponsor2) { $this->sponsor2 = $sponsor2; }

    /**
     * @var datetime $joined
     *
	 * @Assert\Type("\DateTime")
     * @ORM\Column(name="joined", type="datetime")
     */
    private $joined;
	public function getJoined(){ return $this->joined; }
    public function setJoined($joined = null) {
		if($joined == null){
			//$this->joined = date('Y-m-d H:i:s');
			$this->joined = new \Datetime();
		}else{
			if (($timestamp = strtotime($joined)) === false) {
				throw new \Exception('Date-like string expected for Member::setDate, given "'.$joined.'"');
			}else{
				//$this->date = date('Y-m-d', $timestamp);
				$this->date = new \Datetime($timestamp);
			}
		}
    }

	public function getSponsors(){
		$sponsor1 = \Doctrine\Common\Util\Debug::export($this->sponsor1, 1);
		if($sponsor1 === null){
			$sponsor1 = '[DELETED MEMBER]';
		}else{
			$sponsor1 = $sponsor1->name;
		}
		$sponsor2 = \Doctrine\Common\Util\Debug::export($this->sponsor2, 1);
		if($sponsor2 === null){
			$sponsor2 = '[DELETED MEMBER]';
		}else{
			$sponsor2 = $sponsor2->name;
		}
		return $sponsor1.','.$sponsor2;
	}
}

/*

-- MySQL table creation
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
DROP TABLE IF EXISTS `members`;
CREATE TABLE IF NOT EXISTS `members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `organisation` varchar(254) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(254) COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `password` varchar(254) COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `status` varchar(1) NOT NULL,
  `sponsor1_id` int(11) NULL DEFAULT NULL,
  `sponsor2_id` int(11) NULL DEFAULT NULL,
  `joined` DATETIME COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;
ALTER TABLE `website_transportmodeller_com`.`members` ADD INDEX ( `status` );
INSERT INTO `website_transportmodeller_com`.`members` (`id`, `name`, `organisation`, `email`, `password`, `status`, `sponsor1_id`, `sponsor2_id`, `joined`) VALUES ('1', 'Tom Davidson', 'Test Organisation', 'tom@testdomain.com', NULL, 'A', 1, 1, NOW());

 */