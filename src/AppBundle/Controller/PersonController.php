<?php
/**
 * Created by PhpStorm.
 * User: Hellcrow
 * Date: 04.03.2018
 * Time: 19:27
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Person;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class PersonController extends Controller
{
    /**
     * @Route("/", name="people")
     */
    public function personAction(Request $request)
    {
        $pp = $this->getDoctrine()->getRepository('AppBundle:Person')->findAll();
        $person = new Person();
        $form = $this->createFormBuilder($person)
            ->add('fname', TextType::class, array(
                'attr' => array('class' => 'form-control', 'style' => 'margin-bottom: 15px')))
            ->add('lname', TextType::class, array(
                'attr' => array('class' => 'form-control', 'style' => 'margin-bottom: 15px')))
            ->add('age', TextType::class, array(
                'attr' => array('class' => 'form-control', 'style' => 'margin-bottom: 15px')))
            ->add('create', SubmitType::class, array(
                'label'=> 'Create Person', 'attr' => array('class' => 'form-control btn btn-primary', 'style' => 'margin-bottom: 15px')))
            ->add('update', SubmitType::class, array(
                'label'=> 'Update Person', 'attr' => array('class' => 'form-control btn btn-primary', 'style' => 'margin-bottom: 15px')))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()&&$form->isValid()) {
            $fname = $form['fname']->getData();
            $lname = $form['lname']->getData();
            $age = $form['age']->getData();

            $person->setFname($fname);
            $person->setLname($lname);
            $person->setAge($age);

            $em = $this->getDoctrine()->getManager();
            $em->persist($person);
            $em->flush();
            $this->addFlash('notice', 'Person Added');

            return $this->redirectToRoute('people');
        }
        return $this->render('person/index.html.twig', array(
            'form' => $form->createView(), 'pp' => $pp
        ));
    }

    /**
     * @Route("/{id}", name="person_edit")
     */
    public function editAction($id, Request $request)
    {
        $pp = $this->getDoctrine()->getRepository('AppBundle:Person')->findAll();
        $person = $this->getDoctrine()->getRepository('AppBundle:Person')->find($id);

        $person->setFname($person->getFname());
        $person->setLname($person->getLname());
        $person->setAge($person->getAge());

        $form = $this->createFormBuilder($person)
            ->add('fname', TextType::class, array(
                'attr' => array('class' => 'form-control', 'style' => 'margin-bottom: 15px')))
            ->add('lname', TextType::class, array(
                'attr' => array('class' => 'form-control', 'style' => 'margin-bottom: 15px')))
            ->add('age', TextType::class, array(
                'attr' => array('class' => 'form-control', 'style' => 'margin-bottom: 15px')))
            ->add('create', SubmitType::class, array(
                'label'=> 'Create Person', 'attr' => array('class' => 'form-control btn btn-primary', 'style' => 'margin-bottom: 15px')))
            ->add('update', SubmitType::class, array(
                'label'=> 'Update Person', 'attr' => array('class' => 'form-control btn btn-primary', 'style' => 'margin-bottom: 15px')))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()&&$form->isValid()) {
            $fname = $form['fname']->getData();
            $lname = $form['lname']->getData();
            $age = $form['age']->getData();

            $em = $this->getDoctrine()->getManager();
            $person = $em->getRepository('AppBundle:Person')->find($id);

            $person->setFname($fname);
            $person->setLname($lname);
            $person->setAge($age);

            $em->flush();
            $this->addFlash('notice', 'Person Update');

            return $this->redirectToRoute('people');
        }

        return $this->render('person/index.html.twig', array(
            'person' => $person, 'form'=> $form->createView(), 'pp' => $pp
        ));
    }

    /**
     * @Route("/person/delete/{id}", name="person_delete")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $person = $em->getRepository('AppBundle:Person')->find($id);

        $em->remove($person);
        $em->flush();

        $this->addFlash('notice', 'Person Delete');
        return $this->redirectToRoute('people');
    }
}