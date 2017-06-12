<?php

namespace StoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use JMS\Serializer\SerializationContext;

use AppBundle\Entity\Business;
use AppBundle\Entity\Store;

use StoreBundle\Form\StoreType;
use StoreBundle\Exception\BusinessException;
use StoreBundle\Exception\ParamIsNotSetException;

class DefaultController extends FOSRestController
{
    /**
     * @param Request $request
     * @return User|\Symfony\Component\Form\Form
     *
     * @ApiDoc(
     *  section     = "Store",
     *  description = "Create",
     *  parameters={
     *      {"name"="title",       "dataType"="string",  "format"="example@example.com", "required"=true, "description"="Title field"},
     *      {"name"="description", "dataType"="string",  "required"=true, "description" = "Description field"},
     *      {"name"="business_id", "dataType"="integer", "required"=true, "description" = "Business id field"}
     *  },
     *  output={
     *    "class"="AppBundle\Entity\Store"
     *  },
     *  statusCodes = {
     *     200 = "Successful",
     *     400 = "Form validation error",
     *     401 = "Unauthorized"
     *   }
     * )
     */
    public function createAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['business_id'])) {

            throw new ParamIsNotSetException('Business id doesn\'t exist');
        }

        $businessData = $this->get('store.business_service')->getBusinessData($data['business_id']);
        if (!$businessData) {

            throw new BusinessException('Wrong business id');
        }

        $store = new Store();
        $form  = $this->createForm(StoreType::class, $store, array('csrf_protection' => false));

        $form->submit($data);
        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $store->setBusiness($em->getRepository('AppBundle:Business')->find($data['business_id']));

            $em->persist($store);
            $em->flush();

            return $store;
        }

        return new Response($form->getErrors(), Response::HTTP_BAD_REQUEST, ['Content-Type' => 'application/json']);
    }

    /**
     * @param Request $request
     * @param Store $store
     * @return User|\Symfony\Component\Form\Form
     *
     * @ApiDoc(
     *  section     = "Store",
     *  description = "Update",
     *  parameters={
     *      {"name"="title",       "dataType"="string",  "format"="example@example.com", "required"=true, "description"="Title field"},
     *      {"name"="description", "dataType"="string",  "required"=true, "description" = "Description field"},
     *      {"name"="business_id", "dataType"="integer", "required"=true, "description" = "Business id field"}
     *  },
     *  output={
     *    "class"="AppBundle\Entity\Store"
     *  },
     *  statusCodes = {
     *     200 = "Successful",
     *     400 = "Form validation error",
     *     401 = "Authentication error",
     *     404 = "Not found"
     *   }
     * )
     */
    public function updateAction(Request $request, Store $store)
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['business_id'])) {

            throw new ParamIsNotSetException('Business id doesn\'t exist');
        }

        $businessData = $this->get('store.business_service')->getBusinessData($data['business_id']);
        if (!$businessData) {

            throw new BusinessException('Wrong business id');
        }

        $form = $this->createForm(StoreType::class, $store, array('csrf_protection' => false));

        $form->submit($data);
        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $store->setBusiness($em->getRepository('AppBundle:Business')->find($data['business_id']));

            $em->persist($store);
            $em->flush();

            return $store;
        }

        return new Response($form->getErrors(), Response::HTTP_BAD_REQUEST, ['Content-Type' => 'application/json']);
    }

    /**
     * @param Store $store
     * @return User|\Symfony\Component\Form\Form
     *
     * @ApiDoc(
     *  section     = "Store",
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
    public function deleteAction(Store $store)
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($store);
        $em->flush();

        return ['result' => true];
    }

    /**
     * @param Store $store
     * @return User|\Symfony\Component\Form\Form
     *
     * @ApiDoc(
     *  section     = "Store",
     *  description = "Get",
     *  output={
     *    "class"="AppBundle\Entity\Store"
     *  },
     *  statusCodes = {
     *     200 = "Successful",
     *     401 = "Authentication error",
     *     404 = "Not found"
     *   }
     * )
     */
    public function indexAction(Store $store)
    {
        return $store;
    }
}
