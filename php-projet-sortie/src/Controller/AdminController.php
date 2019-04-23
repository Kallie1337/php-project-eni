<?php

namespace App\Controller;

use App\Entity\Location;
use App\Entity\User;
use App\Form\LocationType;
use App\Form\RegistrationFormType;
use App\Repository\LocationRepository;
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
     * @Route("/locations", name="location_index", methods={"GET"})
     */
    public function locationIndex(LocationRepository $locationRepository): Response
    {
        return $this->render('admin/location_index.html.twig', [
            'locations' => $locationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/location/{id}", name="location_show", methods={"GET"})
     */
    public function show(Location $location): Response
    {
        return $this->render('admin/location_show.html.twig', [
            'location' => $location,
        ]);
    }

    /**
     * @Route("/create", name="location_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $location = new Location();
        $form = $this->createForm(LocationType::class, $location);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($location);
            $entityManager->flush();

            return $this->redirectToRoute('location_index');
        }

        return $this->render('admin/location_new.html.twig', [
            'location' => $location,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/location/{id}/edit", name="location_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Location $location): Response
    {
        $form = $this->createForm(LocationType::class, $location);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('location_index', [
                'id' => $location->getId(),
            ]);
        }

        return $this->render('admin/location_edit.html.twig', [
            'location' => $location,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/location/{id}", name="location_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Location $location): Response
    {
        if ($this->isCsrfTokenValid('delete'.$location->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($location);
            $entityManager->flush();
        }

        return $this->redirectToRoute('location_index');
    }

    /**
     * @Route("/user/create", name="user_create")
     */
    public function userCreate(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
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
     * @Route("/user/update", name="user_update")
     */
    public function userUpdate(UserRepository $userRepository){
        $users = $userRepository->selectNonAdmin();

        return $this->render('admin/update.html.twig', compact('users'));
    }

    /**
     * @Route("/user/delete/{id}", name="user_delete", requirements={"id": "\d+"})
     */
    public function userDelete(User $us, EntityManagerInterface $entityManager, UserRepository $userRepository){
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
