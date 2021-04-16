<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Model;
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

class SecurityController extends AbstractController
{

     /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->modelRepository = $userRepository;
     
    }
    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request, AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $lastUserName = $utils->getLastUsername();
        

        return $this->render('security/login.html.twig',[
            'error' => $error,
            'last_username' => $lastUserName
        ]);
    }

    
     /**
     * @Route("/logout", name="logout")
     * @throws Exception
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

            $this->addFlash('success', 'You have been successfully registered! Congratulations');
            return $this->redirectToRoute('index');
        }
 
        return $this->render(
            'security/register.html.twig',
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
     * @Route("/test", name="test")
     */
    public function test(UserRepository $userRepository)
    {
     
        
        return $this->render('security/register.html.twig');
     
    }


}
