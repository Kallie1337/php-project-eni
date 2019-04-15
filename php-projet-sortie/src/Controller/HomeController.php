<?php

namespace App\Controller;

use App\Repository\TripRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function index(TripRepository $tripRepository)
    {
        $trips = $tripRepository->findAll();

        return $this->render('home/index.html.twig', compact('trips'));
    }


}
