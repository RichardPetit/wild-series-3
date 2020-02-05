<?php


namespace App\Controller;


use App\Entity\Category;
use App\Form\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/category", name="category_")
 */
class CategoryController extends AbstractController
{

    /**
     * @Route("/", name="index")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\Response
     * @IsGranted("ROLE_ADMIN")
     */
public function add(Request $request, EntityManagerInterface $em): Response
{
    $category = new Category();

    $form = $this->createForm(
        CategoryType::class,
        $category
    );

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $category = $form->getData();
        $em->persist($category);
        $em->flush();

        $this->addFlash('success', 'La catégorie été ajoutée');
        return $this->render('Category/add.html.twig', [
            'category' => $category
            ]);
    }
    return $this->render('Category/index.html.twig', [
        'form' => $form->createView(),
    ]);
}

}
