<?php

// src/CustomerBundle/Controller/SecurityController.php
namespace CustomerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

use FOS\RestBundle\Controller\FOSRestController;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use JMS\Serializer\SerializationContext;

use AppBundle\Entity\Role;

use CustomerBundle\Exception\InvalidCredentialsException;


/**
 * Class SecurityController
 * @package Customer\Controller
 */
class SecurityController extends FOSRestController
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @ApiDoc(
     *  section     = "Customer",
     *  description = "User sign in",
     *  parameters={
     *      {"name"="email",    "dataType"="string", "format"="example@example.com", "required"=true, "description"="Email field"},
     *      {"name"="password", "dataType"="string", "required"=true, "description" = "Password field, at least 6 symbols"}
     *  },
     *  output={
     *    "class"="AppBundle\Entity\Token"
     *  },
     *  statusCodes = {
     *     200 = "Successful",
     *     400 = "Email was not confirmed. Please confirm your email by the link send to your email | Email and password combination is incorrect"
     *   }
     * )
     */
    public function loginAction(Request $request)
    {
        $this->logoutAction($request);

        $data = json_decode($request->getContent(), true);
        if (empty($data['email']) || empty($data['password'])) {

            throw new InvalidCredentialsException('Email or password can\'t be empty');
        }

        $em   = $this->getDoctrine()->getManager();

        $user = $em->getRepository('AppBundle:User')->findOneBy(['username' => $data['email']]);
        if (!$user)
            throw new InvalidCredentialsException('You put bad credentials');

        $isValid = $this->get('security.password_encoder')->isPasswordValid($user, $data['password'], $user->getSalt());
        if (!$isValid)
            throw new InvalidCredentialsException('You put bad credentials');

        if (!$user->getRoleCollection()->contains($em->getRepository('AppBundle:Role')->findOneBy(['role' => Role::ROLE_CUSTOMER])))
            throw new InvalidCredentialsException('You put bad credentials');

        $this->get('customer.token_manager')->populateUser($user);

        $this->get("security.token_storage")->setToken(new UsernamePasswordToken($user, null, "public", $user->getRoles()));

        return $user->getTokens()->first();
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @ApiDoc(
     *  section     = "Customer",
     *  description = "Logout",
     *  output={
     *    "class"="AppBundle\Doc\OutputTrue"
     *  },
     *  statusCodes = {
     *     200 = "Successful"
     *   }
     * )
     */
    public function logoutAction(Request $request)
    {
        $this->get('security.token_storage')->setToken(null);

        return ['result' => true];
    }
}