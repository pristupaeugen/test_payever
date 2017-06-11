<?php

namespace BusinessBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use JMS\Serializer\SerializationContext;

use AppBundle\Entity\Business;

use BusinessBundle\Form\BusinessType;
use BusinessBundle\Exception\AuthenticationException;

class DefaultController extends FOSRestController
{
    /**
     * @param Request $request
     * @return User|\Symfony\Component\Form\Form
     *
     * @ApiDoc(
     *  section     = "Business",
     *  description = "Create",
     *  parameters={
     *      {"name"="title",       "dataType"="string", "format"="example@example.com", "required"=true, "description"="Title field"},
     *      {"name"="description", "dataType"="string", "required"=true, "description" = "Description field"}
     *  },
     *  output={
     *    "class"="AppBundle\Entity\Business"
     *  },
     *  statusCodes = {
     *     200 = "Successful",
     *     400 = "Form validation error"
     *   }
     * )
     */
    public function createAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $business = new Business();
        $form = $this->createForm(BusinessType::class, $business, array('csrf_protection' => false));

        $form->submit($data);
        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $business->setUser($this->get('security.token_storage')->getToken()->getUser());

            // save the User!
            $em->persist($business);
            $em->flush();

            return $business;
        }

        return new Response($form->getErrors(), Response::HTTP_BAD_REQUEST, ['Content-Type' => 'application/json']);

    }

    /**
     * @param Request $request
     * @param Business $business
     * @return User|\Symfony\Component\Form\Form
     *
     * @ApiDoc(
     *  section     = "Business",
     *  description = "Update",
     *  parameters={
     *      {"name"="title",       "dataType"="string",  "format"="example@example.com", "required"=true, "description"="Title field"},
     *      {"name"="description", "dataType"="string",  "required"=true, "description" = "Description field"}
     *  },
     *  output={
     *    "class"="AppBundle\Entity\Business"
     *  },
     *  statusCodes = {
     *     200 = "Successful",
     *     400 = "Form validation error",
     *     401 = "Authentication error",
     *     404 = "Not found"
     *   }
     * )
     */
    public function updateAction(Request $request, Business $business)
    {
        if ($business->getUser()->getId() != $this->get('security.token_storage')->getToken()->getUser()->getId()) {

            throw new AuthenticationException('You are not allowed to update business');
        }

        $data = json_decode($request->getContent(), true);

        $form = $this->createForm(BusinessType::class, $business, array('csrf_protection' => false));

        $form->submit($data);
        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            // save the User!
            $em->persist($business);
            $em->flush();

            return $business;
        }

        return new Response($form->getErrors(), Response::HTTP_BAD_REQUEST, ['Content-Type' => 'application/json']);
    }

    /**
     * @param Business $business
     * @return User|\Symfony\Component\Form\Form
     *
     * @ApiDoc(
     *  section     = "Business",
     *  description = "Delete",
     *  output={
     *    "class"="AppBundle\Doc\OutputTrue"
     *  },
     *  statusCodes = {
     *     200 = "Successful",
     *     401 = "Authentication error",
     *     404 = "Not found"
     *   }
     * )
     */
    public function deleteAction(Business $business)
    {
        if ($business->getUser()->getId() != $this->get('security.token_storage')->getToken()->getUser()->getId()) {

            throw new AuthenticationException('You are not allowed to update business');
        }

        $em = $this->getDoctrine()->getManager();

        $em->remove($business);
        $em->flush();

        return ['result' => true];
    }

    /**
     * @param Business $business
     * @return User|\Symfony\Component\Form\Form
     *
     * @ApiDoc(
     *  section     = "Business",
     *  description = "Get",
     *  output={
     *    "class"="AppBundle\Entity\Business"
     *  },
     *  statusCodes = {
     *     200 = "Successful",
     *     401 = "Authentication error",
     *     404 = "Not found"
     *   }
     * )
     */
    public function indexAction(Business $business)
    {
        if ($business->getUser()->getId() != $this->get('security.token_storage')->getToken()->getUser()->getId()) {

            throw new AuthenticationException('You are not allowed to update business');
        }

        return $business;
    }
}
