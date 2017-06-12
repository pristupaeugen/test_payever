<?php

namespace ProductBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use JMS\Serializer\SerializationContext;

use AppBundle\Entity\Product;
use AppBundle\Entity\Store;

use ProductBundle\Form\ProductType;
use ProductBundle\Exception\StoreException;
use ProductBundle\Exception\ParamIsNotSetException;

class DefaultController extends FOSRestController
{
    /**
     * @param Request $request
     * @return User|\Symfony\Component\Form\Form
     *
     * @ApiDoc(
     *  section     = "Product",
     *  description = "Create",
     *  parameters={
     *      {"name"="title",       "dataType"="string",  "format"="example@example.com", "required"=true, "description"="Title field"},
     *      {"name"="description", "dataType"="string",  "required"=true, "description" = "Description field"},
     *      {"name"="store_id", "dataType"="integer", "required"=true, "description" = "Store id field"}
     *  },
     *  output={
     *    "class"="AppBundle\Entity\Product"
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

        if (empty($data['store_id'])) {

            throw new ParamIsNotSetException('Store id doesn\'t exist');
        }

        $providerInfo = $this->get('product.store_service')->getStoreProvider($data['store_id']);
        if (!$providerInfo || ($providerInfo['id'] != $this->get('security.token_storage')->getToken()->getUser()->getId())) {

            throw new StoreException('Wrong store id');
        }

        $product = new Product();
        $form  = $this->createForm(ProductType::class, $product, array('csrf_protection' => false));

        $form->submit($data);
        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $product->setStore($em->getRepository('AppBundle:Store')->find($data['store_id']));

            $em->persist($product);
            $em->flush();

            $this->get('product.store_service')->updateProductDate($data['store_id']);

            return $product;
        }

        return new Response($form->getErrors(), Response::HTTP_BAD_REQUEST, ['Content-Type' => 'application/json']);
    }

    /**
     * @param Request $request
     * @param Product $product
     * @return User|\Symfony\Component\Form\Form
     *
     * @ApiDoc(
     *  section     = "Product",
     *  description = "Update",
     *  parameters={
     *      {"name"="title",       "dataType"="string",  "format"="example@example.com", "required"=true, "description"="Title field"},
     *      {"name"="description", "dataType"="string",  "required"=true, "description" = "Description field"},
     *      {"name"="store_id", "dataType"="integer", "required"=true, "description" = "Store id field"}
     *  },
     *  output={
     *    "class"="AppBundle\Entity\Product"
     *  },
     *  statusCodes = {
     *     200 = "Successful",
     *     400 = "Form validation error",
     *     401 = "Authentication error",
     *     404 = "Not found"
     *   }
     * )
     */
    public function updateAction(Request $request, Product $product)
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['store_id'])) {

            throw new ParamIsNotSetException('Store id doesn\'t exist');
        }

        $providerInfo = $this->get('product.store_service')->getStoreProvider($data['store_id']);
        if (!$providerInfo || ($providerInfo['id'] != $this->get('security.token_storage')->getToken()->getUser()->getId())) {

            throw new StoreException('Wrong store id');
        }

        $form = $this->createForm(ProductType::class, $product, array('csrf_protection' => false));

        $form->submit($data);
        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $product->setStore($em->getRepository('AppBundle:Store')->find($data['store_id']));

            $em->persist($product);
            $em->flush();

            $this->get('product.store_service')->updateProductDate($data['store_id']);

            return $product;
        }

        return new Response($form->getErrors(), Response::HTTP_BAD_REQUEST, ['Content-Type' => 'application/json']);
    }

    /**
     * @param Product $product
     * @return User|\Symfony\Component\Form\Form
     *
     * @ApiDoc(
     *  section     = "Product",
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
    public function deleteAction(Product $product)
    {
        $providerInfo = $this->get('product.store_service')->getStoreProvider($product->getStore()->getId());
        if (!$providerInfo || ($providerInfo['id'] != $this->get('security.token_storage')->getToken()->getUser()->getId())) {

            throw new StoreException('You aren\'t allowed to delete product');
        }

        $em = $this->getDoctrine()->getManager();

        $em->remove($product);
        $em->flush();

        $this->get('product.store_service')->updateProductDate($product->getStore()->getId());

        return ['result' => true];
    }

    /**
     * @param Store $store
     * @return User|\Symfony\Component\Form\Form
     *
     * @ApiDoc(
     *  section     = "Product",
     *  description = "Delete Many",
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
    public function deleteManyAction(Store $store)
    {
        $providerInfo = $this->get('product.store_service')->getStoreProvider($store->getId());
        if (!$providerInfo || ($providerInfo['id'] != $this->get('security.token_storage')->getToken()->getUser()->getId())) {

            throw new StoreException('You aren\'t allowed to delete products');
        }

        $em = $this->getDoctrine()->getManager();
        foreach ($store->getProducts() as $product) {

            $em->remove($product);
        }

        $em->flush();

        $this->get('product.store_service')->updateProductDate($store->getId());

        return ['result' => true];
    }

    /**
     * @param Product $product
     * @return User|\Symfony\Component\Form\Form
     *
     * @ApiDoc(
     *  section     = "Product",
     *  description = "Get",
     *  output={
     *    "class"="AppBundle\Entity\Product"
     *  },
     *  statusCodes = {
     *     200 = "Successful",
     *     401 = "Authentication error",
     *     404 = "Not found"
     *   }
     * )
     */
    public function indexAction(Product $product)
    {
        $providerInfo = $this->get('product.store_service')->getStoreProvider($product->getStore()->getId());
        if (!$providerInfo || ($providerInfo['id'] != $this->get('security.token_storage')->getToken()->getUser()->getId())) {

            throw new StoreException('You aren\'t allowed to view product');
        }

        return $product;
    }
}
