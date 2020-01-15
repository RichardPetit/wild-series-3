<?php


namespace App\DataFixtures;


use App\Entity\Episode;
use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        $slugify = new Slugify();
        for ($i = 0; $i<50; $i++){
            $episode = new Episode();
            $episode->setTitle($faker->sentence);
            $episode->setNumber(rand(0,10));
            $episode->setSynopsis($faker->text);
            $slug = $slugify->generate($episode->getTitle());
            $episode->setSlug($slug);
            $episode->setSeason($this->getReference("season_" . rand(0, 9)));
            $manager->persist($episode);
            $this->addReference('episode_' .$i, $episode);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [SeasonFixtures::class];
    }

}
