<?php

namespace App\Controller;

use App\Entity\Location;
use App\Entity\Trip;
use App\Entity\User;
use App\Repository\LocationRepository;
use App\Repository\TripRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function index(TripRepository $tripRepository, LocationRepository $locationRepository)
    {
        $trips = $tripRepository->findAll();
        $locations = $locationRepository->findAll();;

        return $this->render('home/index.html.twig', compact('trips', 'locations'));
    }

    /**
     * @Route("/lister", name="lister")
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function lister(Request $request, TripRepository $tripRepository, LocationRepository $locationRepository): Response
    {

        $filter = $request->get('filter', 'all');
        $locations = $locationRepository->findAll();

        $champ = $request->get('champ');
        $date_begin = $request->get('date-begin');

        $trips = $tripRepository->recherche($champ, $date_begin, ($filter === "all") ? null : $this->getUser());



        return $this->render('trip/lister.html.twig', [
            'trips' => $trips,
            'locations' => $locations,

        ]);
    }

    /**
     * @Route("/listerVille", name="listerVille")
     * @param Request $request
     * @param TripRepository $tripRepository
     * @param LocationRepository $locationRepository
     * @return Response
     */
    public function listerVille(Request $request, TripRepository $tripRepository, LocationRepository $locationRepository): Response
    {

        $filter = $request->get('filter', 'all');
        $choix = $request->request->get('location');
        $date_begin = $request->get('date-begin');
        $champ = $request->get('champ');

        $trips = $tripRepository->recherche($champ, $date_begin, ($filter === "all") ? null : $this->getUser());
        $locations = $locationRepository->rechercheVille($choix, ($filter === "all") ? null : $this->getUser());


        return $this->render('trip/listerVille.html.twig', [
            'trips' => $trips,
            'locations' => $locations,

        ]);
    }
}
