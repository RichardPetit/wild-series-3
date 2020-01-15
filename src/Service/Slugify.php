<?php


namespace App\Service;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Slugify extends AbstractController
{

    public function generate(string $input) : string
    {

        $input = preg_replace('~[^\pL\d]+~u', '-', $input);
        $input = iconv('utf-8', 'us-ascii//TRANSLIT', $input);
        $input = preg_replace('~[^-\w]+~', '', $input);
        $input = trim($input, '-');
        $input = preg_replace('~-+~', '-', $input);
        $input = strtolower($input);
        if (empty($input)) {
            return 'No slug has been sent to find a program in program\'s table.';
        }
        return $input;
    }

}
