<?php

namespace TomDavidson\MembershipBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     * @Template()
     */
    public function indexAction()
    {
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery('SELECT m FROM TomDavidsonMembershipBundle:Member m WHERE m.active = :active ORDER BY m.name')->setParameter('active', '1');
		$members = $query->getResult();
        return array(
			'members' => $members
		);
    }
}
