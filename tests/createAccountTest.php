<?php
use App\Entity\User;
use App\UserStory\CreateAccount;
use Doctrine\ORM\EntityManager;

require_once __DIR__. "/../vendor/autoload.php";
$entityManager = require __DIR__ . "/../config/bootstrap.php";
$user = new createAccount($entityManager);

try{
    $user->execute("test","test","test@test.com","mdptaze9Ae");

}catch (\Exception $e){
    echo $e->getMessage();
}