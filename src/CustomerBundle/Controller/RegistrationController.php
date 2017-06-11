<?php

namespace CustomerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use JMS\Serializer\SerializationContext;

use AppBundle\Entity\User;
use AppBundle\Entity\Role;

use CustomerBundle\Form\CustomerType;

/**
 * Class RegistrationController
 * @package CustomerBundle\Controller
 */
class RegistrationController extends FOSRestController
{
    /**
     * @param Request $request
     * @return User|\Symfony\Component\Form\Form
     *
     * @ApiDoc(
     *  section     = "Customer",
     *  description = "Registration",
     *  parameters={
     *      {"name"="firstname", "dataType"="string", "required"=true, "description"="Firstname field, at least 2 symbols"},
     *      {"name"="surname",   "dataType"="string", "required"=true, "description"="Surname field, at least 2 symbols"},
     *      {"name"="email",     "dataType"="string", "format"="example@example.com", "required"=true, "description"="Email field"},
     *      {"name"="password",  "dataType"="string", "required"=true, "description" = "Password field, at least 6 symbols"}
     *  },
     *  output={
     *    "class"="AppBundle\Entity\User"
     *  },
     *  statusCodes = {
     *     200 = "Successful",
     *     400 = "Form validation error"
     *   }
     * )
     */
    public function registerAction(Request $request)
    {
        // handle the submit (will only happen on POST)
        $data = json_decode($request->getContent(), true);
        $data['plainPassword']['first'] = $data['plainPassword']['second'] = $data['password'];

        // build the form
        $user = new User();
        $form = $this->createForm(CustomerType::class, $user, array('csrf_protection' => false));

        $form->submit($data);
        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            // encode the password (you could also do this via Doctrine listener)
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $user->addRole($em->getRepository('AppBundle:Role')->findOneBy(['role' => Role::ROLE_CUSTOMER]));

            // save the User!
            $em->persist($user);
            $em->flush();

            return $user;
        }

        return new Response($form->getErrors(), Response::HTTP_BAD_REQUEST, ['Content-Type' => 'application/json']);
    }
}
