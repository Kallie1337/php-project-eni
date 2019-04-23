<?php

namespace App\Controller;

use App\Entity\Historic;
use App\Form\HistoricType;
use App\Repository\HistoricRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/historic")
 */
class HistoricController extends Controller
{
    /**
     * @Route("/", name="historic_index", methods={"GET"})
     */
    public function index(HistoricRepository $historicRepository): Response
    {
        return $this->render('historic/index.html.twig', [
            'historics' => $historicRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="historic_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $historic = new Historic();
        $form = $this->createForm(HistoricType::class, $historic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($historic);
            $entityManager->flush();

            return $this->redirectToRoute('historic_index');
        }

        return $this->render('historic/new.html.twig', [
            'historic' => $historic,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="historic_show", methods={"GET"})
     */
    public function show(Historic $historic): Response
    {
        return $this->render('historic/show.html.twig', [
            'historic' => $historic,
        ]);
    }


}
