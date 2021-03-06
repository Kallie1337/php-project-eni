<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\PasswordFormType;
use App\Security\Authenticator;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Swift_Mailer;
use Swift_SmtpTransport;

/**
 * @Route("/account")
 */
class AccountController extends Controller
{
    /**
     * @Route("/", name="account")
     */
    public function index()
    {
        return $this->render('account/ndex.html.twig', [
            'controller_name' => 'AccountController',
        ]);
    }

    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('account/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @IsGranted("[ROLE_ADMIN]")
     * @Route("/register", name="register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, Authenticator $authenticator): Response
    {
        $user = new User();

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('account/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout(){
        session_destroy();
        session_abort();
       return $this->redirectToRoute('home');
    }

    /**
     * @Route("/forgetPassword", name="forgetPassword")
     */
    public function forgetPassword(UserPasswordEncoderInterface $passwordEncoder,Request $request,EntityManagerInterface $entityManager)
    {

        $form = $this->createForm(PasswordFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $email = $form->get('email')->getData();
            $check = $form->get('password')->getData();
            $u = $entityManager
                ->getRepository(User::class)
                ->findOneBy(['email' => $email]);
            if($u != null && $check == 1234)
            {
                $u->setPassword(
                    $passwordEncoder->encodePassword(
                        $u,
                        $form->get('plainPassword')->getData()
                    ));
                $entityManager->flush();
                return $this->redirectToRoute('home');
            }
        }

        return $this->render('account/forgetPassword.html.twig',
            ['passwordForm' => $form->createView()]);
    }


    /**
     * @param \Swift_Mailer $mailer
     * @return Response
     * @Route("/test", name="test")
     */

    public function indexAction()
    {
        // Create the Transport
        $transport = (new Swift_SmtpTransport('smtp.gmail.com', 465))
            ->setUsername('sortieprojet@gmail.com')
            ->setPassword('Azertyuiop$');

// Create the Mailer using your created Transport
        $mailer = new Swift_Mailer($transport);


        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('sortieprojet@gmail.com')
            ->setTo('thomas.lebricquir@hotmail.com')
            ->setBody(
                $this->renderView(
                // app/Resources/views/Emails/registration.html.twig
                    'account/email.html.twig'
                ),
                'text/html'
            )
        ;


        // or, you can also fetch the mailer service this way
        // $this->get('mailer')->send($message);

        $mailer->send($message);

        return $this->render("account/index.html.twig");
    }

}
