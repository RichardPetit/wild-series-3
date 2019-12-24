<?php


namespace App\DataFixtures;



use App\Entity\Actor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class ActorFixtures extends Fixture implements  DependentFixtureInterface
{
    const ACTORS =[
        'Andrew Lincoln' =>[
            'program' =>["program_0"]
        ],
        'Norman Reedus'=>[
            'program' =>["program_0"]
        ],
        'Steven Yeun'=>[
            'program' =>["program_0"]
        ],
        'Danai Gurira'=>[
            'program' =>["program_0"]
        ],
        'Melissa McBride'=>[
            'program' =>["program_0"]
        ],
        'Lauren Cohan'=>[
            'program' =>["program_0"]
        ],
        'Jeffrey Dean Morgan'=>[
            'program' =>["program_0"]
        ],
    ];
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('en_US');
        $i = 0;
        foreach (self::ACTORS as $actorName => $data){
            $actor = new Actor();
            $actor->setName($actorName);
            foreach ($data['program'] as $program){
                $actor->addProgram($this->getReference($program));
            }
            $this->addReference('actor_' . $i, $actor);
            $manager->persist($actor);
            $i++;
        }
        for ($i = 0; $i<50; $i++){
            $actor = new Actor();
            $actor->setName($faker->name);
            $number = rand(0,5);
            $actor->addProgram($this->getReference('program_'. $number));
            $manager->persist($actor);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [ProgramFixtures::class];

    }

}
