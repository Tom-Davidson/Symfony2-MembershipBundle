<?php

namespace TomDavidson\MembershipBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="members_home")
     * @Template()
     */
    public function indexAction(Request $request) {
		// Get a list of all members
		$em = $this->getDoctrine()->getManager();
		$query = $em->createQuery('SELECT m FROM TomDavidsonMembershipBundle:Member m WHERE m.status = :status ORDER BY m.name')->setParameter('status', 'A');
		$members = $query->getResult();
		// Set up the 'add' form
		$newMember = new \TomDavidson\MembershipBundle\Entity\Member();
		$form = $this->createFormBuilder($newMember)
			->add('name',			'text',		array('label'=>'Your Name', 'required'=>true, 'max_length'=>100))
			->add('organisation',	'text',		array('label'=>'Your Organisation', 'required'=>true, 'max_length'=>254))
			->add('email',			'email',	array('label'=>'Your Email', 'required'=>true, 'max_length'=>100))
			->add('sponsor1',		'entity',	array(
				'class'			=> 'TomDavidsonMembershipBundle:Member',
				'query_builder'	=> function($repository) { return $repository->createQueryBuilder('p')->where('p.status=:status')->setParameter('status', 'A')->orderBy('p.name', 'ASC'); },
				'property'		=> 'name',
				'label'			=> 'Sponsor 1'
			))
			->add('sponsor2',		'entity',	array(
				'class'			=> 'TomDavidsonMembershipBundle:Member',
				'query_builder'	=> function($repository) { return $repository->createQueryBuilder('p')->where('p.status=:status')->setParameter('status', 'A')->orderBy('p.name', 'ASC'); },
				'property'		=> 'name',
				'label'			=> 'Sponsor 2'
			))
			->getForm();
		// Form handling
		if ($request->getMethod() == 'POST') {
			//$form->bindRequest($request);
			$form->handleRequest($request);
			$newMember->setJoined();
			if ($form->isValid()) {
				// Save to DB
				$em->persist($newMember);
				$em->flush();
				// Redirect
				return $this->redirect($this->generateUrl('members_home'));
			}
		}
        return array(
			'members'	=> $members,
			'form'		=> $form->createView()
		);
    }
    /**
     * @Route("/admin", name="members_admin")
     * @Template()
     */
    public function adminAction(Request $request) {
		// Get a list of all members
		$em = $this->getDoctrine()->getManager();
		$query = $em->createQuery('SELECT m FROM TomDavidsonMembershipBundle:Member m ORDER BY m.name');
		$members = $query->getResult();
//$vd = $members[0]->getSponsor1();
//var_dump($vd);
        return array(
			'members'	=> $members
		);
	}
    /**
     * @Route("/admin/authorise/{id}", name="members_admin_authorise")
     */
    public function adminAuthoriseAction(Request $request, $id) {
		$em = $this->getDoctrine()->getEntityManager();
		$member = $em->getRepository('TomDavidsonMembershipBundle:Member')->find($id);
		if ($member) {
			$member->setStatus('A');
			$em->flush();
			return $this->redirect($this->generateUrl('members_admin'));
		}else{
			throw new \Exception('Member not found');
		}
	}
    /**
     * @Route("/admin/deactivate/{id}", name="members_admin_deactivate")
     */
    public function adminDeactivateAction(Request $request, $id) {
		$em = $this->getDoctrine()->getEntityManager();
		$member = $em->getRepository('TomDavidsonMembershipBundle:Member')->find($id);
		if ($member) {
			$member->setStatus('I');
			$em->flush();
			return $this->redirect($this->generateUrl('members_admin'));
		}else{
			throw new \Exception('Member not found');
		}
	}
    /**
     * @Route("/admin/delete/{id}", name="members_admin_delete")
     */
    public function adminDeleteAction(Request $request, $id) {
		$em = $this->getDoctrine()->getEntityManager();
		$member = $em->getRepository('TomDavidsonMembershipBundle:Member')->find($id);
		if ($member) {
			// Null out any other member's sponsor1_id for this deleted record to avoid orphans.
			$qb = $em->createQueryBuilder();
			$q = $qb->update('TomDavidsonMembershipBundle:Member', 'm')
				->set('m.sponsor1', ':newSponsor')
				->where('m.sponsor1 = :deletingMember')
				->setParameter('newSponsor', null)
				->setParameter('deletingMember', $member)
				->getQuery();
			$p = $q->execute();
			// Null out any other member's sponsor2_id for this deleted record to avoid orphans.
			$qb = $em->createQueryBuilder();
			$q = $qb->update('TomDavidsonMembershipBundle:Member', 'm')
				->set('m.sponsor2', ':newSponsor')
				->where('m.sponsor2 = :deletingMember')
				->setParameter('newSponsor', null)
				->setParameter('deletingMember', $member)
				->getQuery();
			$p = $q->execute();
			// Remove the member
			$em->remove($member);
			$em->flush();
			return $this->redirect($this->generateUrl('members_admin'));
		}else{
			throw new \Exception('Member not found');
		}
	}
}
