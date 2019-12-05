<?php
// src/Controller/WildController.php
namespace App\Controller;

use App\Entity\Actor;
use App\Entity\Episode;
use App\Entity\Season;
use App\Entity\Category;
use App\Entity\Program;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class WildController extends AbstractController
{
    /**
     * @Route("/wild", name="wild_index")
     * @return Response A response instance
     */
    public function index()
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();
        if (!$programs) {
            throw $this->createNotFoundException(
                'No program found in program\'s table.'
            );
        }
        return $this->render(
            'wild/index.html.twig', [
                'programs' => $programs
        ]);
    }

    /**
     * @param Actor $actor
     * @return Response
     * @Route("/actor/{name}", name="wild_actor")
     */
    public function showActors(Actor $actor)
    {
        $series = $actor->getPrograms();
        return $this->render('actor/show.html.twig', [
            'actor' => $actor,
            'series' => $series,
        ]);

    }

    /**
     * @Route("/episode/{id}", name="show_episode")
     */
    public function showEpisode(Episode $episode): Response
    {
        $season = $episode->getSeason();
        $program = $season->getProgram();
        return $this->render('wild/showEpisode.html.twig', [
            'episode' => $episode,
            'season' => $season,
            'program' => $program,
        ]);
    }

    /**
     * @Route("/show/{slug<^[ a-zA-Z0-9-é]+$>}", defaults={"slug" = null}, name="wild_show")
     * @param string $slug
     * @return Response
     */
    public function showByProgram(string $slug)
    {
        if (!$slug) {
            throw $this->createNotFoundException('No slug has been sent to find a program in program\'s table.');
        }
        $slug = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($slug)), "-")
        );
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy([
                'title' => mb_strtolower($slug)
            ]);
        if (!$program) {
            throw $this->createNotFoundException('No program with '.$slug.' title, found in program\'s table.');
        }
        $seasons = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findBy([
                'program' => $program,
            ]);

        return $this->render('wild/show.html.twig', [
            'program' => $program,
            'slug'    => $slug,
            'seasons' => $seasons,
        ]);
    }


    /**
     * @Route("/wild/category/{categoryName}", requirements={"categoryName"="[a-z0-9-]+"},
     *     defaults={"categoryName"=null},
     *     name="show_category")
     * @param string|null $categoryName
     * @return Response
     */
    public function showByCategory(?string $categoryName)
    {
        if (!$categoryName) {
            throw $this
                ->createNotFoundException('No category has been sent to find a category in program\'s table.');
        }
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(['name' => $categoryName]);

        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findBy(
                ['category' => $category],
                ['id' => 'DESC'],
                3, null);
        return $this->render(
            'wild/category.html.twig', [
            'programs' => $programs
        ]);
    }

    /**
     * @Route("/season/{id}", name="wild_season")
     * @param int $id
     * @return Response
     */
    public function showBySeason(int $id)
    {
        $seasons = $this->getDoctrine()
            ->getRepository(Season::class)
            ->find($id);

        $programs = $seasons->getProgram();
        $episodes = $seasons->getEpisodes();

        return $this->render('wild/showBySeason.html.twig', [
            'seasons' => $seasons,
            'programs' => $programs,
            'episodes' => $episodes,
    ]);
    }



}
