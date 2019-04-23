<?php

namespace App\Controller;

use App\Entity\Historic;
use App\Entity\Location;
use App\Entity\Trip;
use App\Entity\TripUserLove;
use App\Form\HistoricType;
use App\Form\TripType;
use App\Repository\LocationRepository;
use App\Repository\TripRepository;
use App\Repository\TripUserLoveRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\Exception\AccessException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/trip")
 */
class TripController extends Controller
{
    /**
     * @Route("/", name="trip_index", methods={"GET"})
     */
    public function index(TripRepository $tripRepository): Response
    {
        return $this->render('trip/index.html.twig', [
            'trips' => $tripRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="trip_new", methods={"GET","POST"})
     */
    //Créer une nouvelle sortie
    public function new(Request $request, LocationRepository $locationRepository): Response
    {
        $locations = $locationRepository->findAll();

        $choix = $request->request->get('decision');

        $trip = new Trip();
        $trip->setIsArchived(false);
        $form = $this->createForm(TripType::class, $trip);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $entityManager = $this->getDoctrine()->getManager();

            $trip->setCurrentRegistration(0);
            //Placement de la note dans la page
            $love = new TripUserLove();
            $love->setTrip($trip);
            $love->setUser($this->getUser());
            $trip->addLove($love);

            $trip->setAuthor($this->getUser());

            $currentLocation = $locationRepository->findOneBy([
                'name' => $choix
            ]);

            $trip->setLocation($currentLocation);

            $entityManager->persist($trip);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('trip/new.html.twig', [
            'trip' => $trip,
            'locations' => $locations,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="trip_show", methods={"GET"})
     */
    public function show(Trip $trip): Response
    {
        $users = $trip->getUsers();
        $user = $this->getUser();
        //si l'utilisateur est inscrit ( isParticipant ) à la sortie, il peut se désister
        $isParticipant = false;

        if ($trip->getUsers()->contains($user)) {

            $isParticipant = true;

        } else {
            $isParticipant = false;
        }

        return $this->render('trip/show.html.twig', [
            'trip' => $trip,
            'users' => $users,
            'isParticipant' => $isParticipant,
        ]);
    }

    /**
     * @Route("/{id}/love", name="trip_love", methods={"GET"})
     */

    //Cette fonction permet de mettre une note à la sortie
    public function love(Request $request, Trip $trip, TripUserLoveRepository $tripUserLoveRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $lovefind = $tripUserLoveRepository->findOneBy([
            'trip' => $trip,
            'user' => $this->getUser()
        ]);

        if ($lovefind == null) {
            $love = new TripUserLove();
            $love->setTrip($trip);
            $love->setUser($this->getUser());
            $trip->addLove($love);

            $entityManager->persist($trip);
            $entityManager->flush();

        }

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/{id}/register", name="trip_register", methods={"GET"})
     */
    //La fonction regiter permet de s'inscrire à une sortie
    public function register(Request $request, Trip $trip): Response
    {
        $user = $this->getUser();
        $entityManager = $this->getDoctrine()->getManager();

        if ($trip->getUsers()->contains($user)) {

            $trip->removeUser($user);

        } else {

            if ($trip->getUsers()->count() < $trip->getMaxRegistration() && new \DateTime('now') < $trip->getRegistrationEndDate()) {

                $trip->addUser($user);
            }
        }
        $entityManager->persist($trip);
        $entityManager->flush();
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/{id}/edit", name="trip_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Trip $trip, LocationRepository $locationRepository): Response
    {
        $locations = $locationRepository->findAll();
        $form = $this->createForm(TripType::class, $trip);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('trip_index', [
                'id' => $trip->getId(),
            ]);
        }

        return $this->render('trip/edit.html.twig', [
            'trip' => $trip,
            'form' => $form->createView(),
            'locations' => $locations,
        ]);
    }

    /**
     * @Route("/{id}/archive", name="trip_archive", methods={"GET","POST"})
     */
    public function archive(Request $request, Trip $trip): Response
    {
        $historic = new Historic();
        $historic->setTrip($trip);
        $form = $this->createForm(HistoricType::class, $historic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();

           $trip->setIsArchived(true);

            $entityManager->persist($historic);
            $entityManager->persist($trip);
            $entityManager->flush();

            return $this->redirectToRoute('historic_index');
        }

        return $this->render('historic/new.html.twig', [
            'historic' => $historic,
            'form' => $form->createView(),
        ]);
    }
}
