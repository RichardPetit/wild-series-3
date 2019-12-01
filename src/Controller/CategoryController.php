<?php


namespace App\Controller;


use App\Entity\Category;
use App\Form\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     */
public function add(Request $request, EntityManagerInterface $em)
{
    $category = new Category();

    $form = $this->createForm(
        CategoryType::class,
        $category
    );

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // $form->getData() holds the submitted values
        // but, the original `$task` variable has also been updated
        $category = $form->getData();

        // ... perform some action, such as saving the task to the database
        // for example, if Task is a Doctrine entity, save it!
        $em->persist($category);
        $em->flush();

        return $this->render('Category/add.html.twig', [
            'category' => $category
            ]);
    }
    return $this->render('Category/index.html.twig', [
        'form' => $form->createView(),
    ]);
}

}