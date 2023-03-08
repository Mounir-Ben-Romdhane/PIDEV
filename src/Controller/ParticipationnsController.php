<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\Participationns;
use App\Entity\PdfGeneratorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ParticipationnsRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Form\ParticipationnsType;
use App\Service\JWTService;
use Dompdf\Dompdf;
use Dompdf\Options;
use Swift_Message;
use Swift_Mailer;
use Swift_SmtpTransport;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;




class ParticipationnsController extends AbstractController
{


    #[Route('/participationns', name: 'app_participationns')]
    public function index(): Response
    {
        return $this->render('participationns/index.html.twig', [
            'controller_name' => 'ParticipationnsController',
        ]);
    }


    #[Route('/participationnAffichage', name: 'app_participationnAffichage')]
    public function affichage(ParticipationnsRepository $participationnsRepository)
    {
        $participationns= $participationnsRepository->findAll();
     

        return $this->render('participationns/affparticipationn.html.twig',array ('tableauParticipationns'=> $participationns));
    }




    #[Route('/ParticipationnsAdd/{id}', name: 'app_addParticipationns')]
    public function addParticipationns(Request $request , $id,ManagerRegistry$doctrine, ParticipationnsRepository $participationnsRepository):Response
    {
        $evenement=$doctrine->getRepository(Evenement::class)->find($id);
        $participationns = new Participationns();
        $form = $this->createForm(ParticipationnsType::class, $participationns);
        $form->handleRequest($request);
        $participationns->setEvenement($evenement);
        
        if ($form-> isSubmitted() && $form->isValid()) 
        {
            $em = $doctrine->getManager();
            $em->persist($participationns);
            $em->flush();




            $participationns = new participationns();
            $participationns = $participationnsRepository->find($id);
            // $reclamation->setEtat(1 );
            $em = $doctrine->getManager();
            $em->flush();
            $participationnsRepository->sms();
            $this->addFlash('danger', 'participationns envoyée avec succées');







            //return $this->redirectToRoute('app_participationnAffichage');
            $this->addFlash('Success','Participation ajoutée avec succès!');
            return $this->redirectToRoute('app_evenementAffichageFront');
        }
       return $this->render('Participationns/addParticipationns.html.twig',[
        "form" => $form->createView()
       ]
       
       
    );
}




#[Route('/ParticipationnsAddback/{id}', name: 'app_addParticipationnsback')]
    public function addParticipationnsback(Request $request , $id,ManagerRegistry$doctrine):Response
    {
        $evenement=$doctrine->getRepository(Evenement::class)->find($id);
        $participationns = new Participationns();
        $form = $this->createForm(ParticipationnsType::class, $participationns);
        $form->handleRequest($request);
        $participationns->setEvenement($evenement);
        
        if ($form-> isSubmitted() && $form->isValid()) 
        {
            $em = $doctrine->getManager();
            $em->persist($participationns);
            $em->flush();
            //return $this->redirectToRoute('app_participationnAffichage');
            $this->addFlash('Success','Participation ajoutée avec succès!');
            return $this->redirectToRoute('app_participationnAffichage');
        }
       return $this->render('Participationns/addParticipationnsback.html.twig',[
        "form" => $form->createView()
       ]
       
       
    );
}






#[Route('/ParticipationnsDelete/{id}', name: 'app_deleteParticipationns')]
    public function deleteEvenement(Participationns $participationns,$id,ManagerRegistry $doctrine):RedirectResponse
    {
        $em = $doctrine->getManager();
        $em->remove($participationns);
        $em->flush();

    return $this->redirectToRoute('app_participationnAffichage');
    }



    #[Route('/ParticipationnsDeleteFront/{id}', name: 'app_deleteParticipationnsFront')]
    public function deleteEvenementFront(Participationns $participationns,$id,ManagerRegistry $doctrine):RedirectResponse
    {
        $em = $doctrine->getManager();
        $em->remove($participationns);
        $em->flush();

    return $this->redirectToRoute('app_participationnAffichage');

    /*$entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($participationns);
        $entityManager->flush();

        $this->addFlash(
            'Success',
            'Client removed successfully!'
        );

        return $this->redirectToRoute('app_participationnAffichage');*/
    }



   
    #[Route('/ParticipationnsUpdate/{id}', name: 'app_updateParticipationns')]
    public function editParticipationns(Request $request, Participationns $participationns,$id, ManagerRegistry $doctrine):Response
    {
       
        $form = $this->createForm(ParticipationnsType::class, $participationns);


        $form->handleRequest($request);
        //*** */
        //$participationns=$this->getDoctrine()->getRepository(Participationns::class)->find($id);


        if ($form-> isSubmitted() && $form->isValid()) 
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($participationns);
            $em->flush();
            //return $this->redirectToRoute('app_participationnAffichage');
            $this->addFlash('Success','Participation modifie avec succes!');
            return $this->redirectToRoute('app_evenementAffichageFront');
        }

       return $this->render('Participationns/updateParticipationns.html.twig',[
        "form" => $form->createView()
       ]
    );
    }






    #[Route('/ParticipationnsUpdateBack/{id}', name: 'app_updateParticipationnsBack')]
    public function editParticipationnsBack(Request $request, Participationns $participationns,$id, ManagerRegistry $doctrine):Response
    {
       
        $form = $this->createForm(ParticipationnsType::class, $participationns);
        $form->handleRequest($request);
        
        if ($form-> isSubmitted() && $form->isValid()) 
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($participationns);
            $em->flush();
            //return $this->redirectToRoute('app_participationnAffichage');
            $this->addFlash('Success','Participation modifie avec succes!');
            return $this->redirectToRoute('app_participationnAffichage');
        }

       return $this->render('Participationns/updateParticipationnsBack.html.twig',[
        "form" => $form->createView()
       ]
    );
    }





















    #[Route('/participationnsdownload', name: 'app_participationns_download')]
    public function indexdownload(ParticipationnsRepository $participationnsRepository)
    {
        
        $participationns= $participationnsRepository->findAll();
        return $this->render('participationns/data.html.twig',array ('tableauParticipationns'=> $participationns));
    }


    #[Route('/participationns/data/download', name: 'app_participationns_data_download')]
    public function indexdownloaddata(ParticipationnsRepository $participationnsRepository)
    {
        $pdfOptions =new Options();
        //police
        $pdfOptions->set('defaultFont','Arial');

        $pdfOptions->setIsRemoteEnabled(true);

        $dompdf = new Dompdf($pdfOptions);

        $tableauParticipationns= $participationnsRepository->findAll();

        $context = stream_context_create([
            'ssl' =>[
                'verify_peer' =>FALSE,
                'verify_peer_name'=> False,
                'allow_self_signed' =>TRUE
            ]
            ]);
            $dompdf ->setHttpContext($context);

            $html = $this->renderView('participationns/download.html.twig', 
            ['tableauParticipationns'=> $tableauParticipationns]);

            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4','portrait');
            $dompdf->render();

            $fichier= 'participation-data-.pdf';

            $dompdf->stream($fichier,[
                'Attachement' => TRUE
            ]);

            return new Response();
           //return  $this->render('evenement/download.html.twig',['evenement'=> $evenement], new Response());
        
       
    }






    //PDF Wessim
    #[Route('/participationns/pdf', name: 'generator_service')]
    public function pdfService(): Response
    { 
        $participationns= $this->getDoctrine()
        ->getRepository(Participationns::class)
        ->findAll();

   

        $html =$this->renderView('pdf/index.html.twig', ['tableauParticipationns' => $participationns]);
        $pdfGeneratorService=new PdfGeneratorService;
        $pdf = $pdfGeneratorService->generatePdf($html);

        return new Response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="document.pdf"',
        ]);
       
    }


}
