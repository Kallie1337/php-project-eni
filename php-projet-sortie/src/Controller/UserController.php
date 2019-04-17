<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/user")
 */
class UserController extends Controller
{

    /**
     * @Route("/", name="user")
     */
    public function user(UserPasswordEncoderInterface $passwordEncoder,Request $request,EntityManagerInterface $entityManager, UserRepository $us){
        $u = $this->getUser()->getId();
        $user = $us->find($u);
        //Save du path de l'image
        $imgPath = $user->getImage();
        //Reset img
        $user->setImage(null);

        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                ));
            /** @var UploadedFile $file*/
            $file = $user->getImage();
            if($file == null){
                $user->setImage($imgPath);
            }else{
                $fileName = md5(uniqid()) . ' . '.$file->guessExtension();
                $file->move(
                    $this->getParameter('brochures_directory'),
                    $fileName
                );
                $user->setImage($fileName);
            }
            $username = $form->get('username')->getData();
            $check = $us->findOneBy(['username' => $username]);
            if ($check == null) {
                $entityManager->flush();
                return $this->redirectToRoute('home');
            } else {
                if ($check->getId() != $u) {
                    $this->addFlash('error', 'This username is already used');
                    $form = $this->createForm(UserFormType::class, $user);
                    $form->handleRequest($request);
                    $form = $form->createView();
                    return $this->render('account/user.html.twig', compact('form'));
                } else {
                    $entityManager->flush();
                    return $this->redirectToRoute('home');
                }
            }
        }
        $form = $form->createView();
        return $this->render('account/user.html.twig',
            compact('form', 'user','imgPath'));
    }

    /**
     * @Route("/others/{id}", name="others",  requirements={"id":"\d+"})
     */
    public function others(User $u,EntityManagerInterface $entityManager){
        $user = $entityManager->getRepository(User::class);
        $u = $user->find($u);
        return $this->render('account/others.html.twig', compact('u'));
    }

}
