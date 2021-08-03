<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Exception;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;


class SecurityController extends AbstractController
{

     /**
     * @var UserRepository
     */
    private $userRepository;

  /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $this->modelRepository = $userRepository;
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request, AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $lastUserName = $utils->getLastUsername();
       

        return $this->render('security/login2.html.twig',[
            'error' => $error,
            'last_username' => $lastUserName
        ]);
    }

    
     /**
     * @Route("/logout", name="logout")
     */
    public function logout(): void
    {
        throw new Exception('This should never be reached!');
    }

   

     /**
     * @Route("/register", name="security_registration")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param TokenStorageInterface $tokenStorage
     * @return RedirectResponse|Response
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder, TokenStorageInterface $tokenStorage)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
 
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
 
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $user->setRoles(['ROLE_USER']);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $token = new UsernamePasswordToken($user, $user->getPassword(), 'main', $user->getRoles());
            $tokenStorage->setToken($token);

           
            return $this->redirectToRoute('login');
        }
 
        return $this->render(
            'security/register2.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * @Route(name="api_login", path="/api/login_check")
     * @return JsonResponse
     */
    public function api_login(): JsonResponse
    {
        $user = $this->getUser();

        return new JsonResponse([
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
        ]);
    }

    /**
     * @Route(name="test", path="/test", options={"expose"=true})
     */
    public function test(Request $request, UserRepository $userRepository)
    {
     
        return $this->render(
            'security/login2.html.twig');
     
           

     
    }


}
