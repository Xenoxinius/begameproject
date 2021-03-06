<?php

namespace App\Controller;

use App\Entity\Team;
use App\Entity\User;
use App\Form\JoinTeamType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    /**
     * @Route("/homepage", name="homepage")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {

        if (!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }
        if (in_array("ROLE_COACH", $this->getUser()->getRoles())) {
            return $this->redirectToRoute("admin_overview");
        }

        if (in_array("ROLE_ADVANCED", $this->getUser()->getRoles())) {
            return $this->redirectToRoute("profile_page");
        }
        /*
         *  @var Team $team
         */
        $user = $this->getUser();

        $allTeams = $this->getDoctrine()->getRepository(Team::class)->findBy(['isLocked' => false]);
        $teams = [];
        foreach ($allTeams as $team) {
            $teams[] = [
                'form' => $this->createForm(JoinTeamType::class, ['team' => $team]),
                'teamName' => $team->getTeamName(),
            ];
        }


        /* @var User $user
         * @var Team $clickedTeam
         */
        if (!empty($teams)) {


            $teams[0]['form']->handleRequest($request);
            if ($teams[0]['form']->isSubmitted()) {

                $clickedTeamName = $teams[0]['form']->get('team')->getData();
                $clickedTeam = $this->getDoctrine()->getRepository(Team::class)->findOneBy(['teamName' => $clickedTeamName]);

                $user->setTeam($clickedTeam);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                return $this->redirectToRoute('profile_page');
            }
        }
        return $this->render('homepage/index.html.twig', [
            'teams' => $teams,
            'user' => $user,
        ]);
    }


}
