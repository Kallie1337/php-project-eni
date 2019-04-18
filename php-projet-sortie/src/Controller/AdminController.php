<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\Authenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/admin")
 */
class AdminController extends Controller
{
    /**
     * @Route("/", name="admin")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig');
    }

    /**
     * @Route("/create", name="create")
     */
    public function create(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
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
            $this->addFlash('success', 'Successful registration');
            return $this->redirectToRoute('admin');
        }

        return $this->render('account/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/update", name="update")
     */
    public function update(UserRepository $userRepository){
        $users = $userRepository->selectNonAdmin();

        return $this->render('admin/update.html.twig', compact('users'));
    }

    /**
     * @Route("/delete/{id}", name="delete", requirements={"id": "\d+"})
     */
    public function delete(User $us, EntityManagerInterface $entityManager, UserRepository $userRepository){
        $user = $userRepository->find($us);
        $entityManager->remove($user);
        $entityManager->flush();
        return $this->redirectToRoute("update");
    }

    /**
     * @Route("/unactive/{id}", name="unactive", requirements={"id": "\d+"})
     */
    public function unactive(User $us, EntityManagerInterface $entityManager, UserRepository $userRepository){
        $user = $userRepository->find($us);
        $user->setActive(false);
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->redirectToRoute("update");
    }

    /**
     * @Route("/active/{id}", name="active", requirements={"id": "\d+"})
     */
    public function active(User $us, EntityManagerInterface $entityManager, UserRepository $userRepository){
        $user = $userRepository->find($us);
        $user->setActive(true);
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->redirectToRoute("update");
    }
}
