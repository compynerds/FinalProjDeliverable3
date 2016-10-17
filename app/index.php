<?php
/**
 * File name: index.php
 * Project: project1
 * PHP version 5
 * @category  PHP
 * @author    mark Richardson <compynerds@gmail.com>
 * @modifier  Mark Richardson 8/6/2016
 * @copyright 2016 © donbstringham
 * @license   http://opensource.org/licenses/MIT MIT
 * @version   GIT: <git_id>
 * $LastChangedDate$ 8/6/2016
 * $LastChangedBy$   Mark Richardson
 */

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Pimple\Container;
use cs3360\src\Infrastructure\InMemoryUserRepository;
use cs3360\src\Infrastructure\MysqlUserRepository;
use cs3360\src\Domain\StringLiteral;
use cs3360\src\Domain\User;

require_once __DIR__ . '/../vendor/autoload.php';

$dic = bootstrap();

$app = $dic['app'];

//$app->before(function (Request $request) {
//    $password = $request->getPassword();
//    $username = $request->getUser();
//
//    if ($username !== 'professor') {
//        $response = new Response();
//        $response->setStatusCode(401);
//
//        return $response;
//    }
//
//    if ($password !== '1234pass') {
//        $response = new Response();
//        $response->setStatusCode(401);
//
//        return $response;
//    }
//});

$app->get('/', function () {
    return '<h1>Welcome to the Final Project</h1>';
});

$app->get('/ping', function() use ($dic) {
   $response = new Response();

    $driver = $dic['db-driver'];
    if (!$driver instanceof \PDO) {
        $response->setStatusCode(500);
        $msg = ['msg' => 'could not connect to the database'];
        $response->setContent(json_encode($msg));

        return $response;
    }

    $repo = $dic['repo-mysql'];
    if (!$repo instanceof \cs3360\src\Domain\UserRepository) {
        $response->setStatusCode(500);
        $msg = ['msg' => 'repository problem'];
        $response->setContent(json_encode($msg));

        return $response;
    }

    $response->setStatusCode(200);
    $msg = ['msg' => 'pong'];
    $response->setContent(json_encode($msg));

    return $response;

});

$app->get('/login', function() use ($dic){
    $response = new Response();
    $response->setStatusCode(200);
    $response->setContent("<h1>This will be the login page</h1>");
    return $response;
});

$app->get('/volunteers', function () use ($dic) {
    $response = new Response();
//    $repo = $dic['repo-mem'];
    $response->setStatusCode(200);
//    $response->setContent(json_encode($repo->findAll()));

    return $response;
});



$app->get('/volunteers/{id}', function ($id) use ($dic) {
    $response = new Response();
//    $repo = $dic['repo-mem'];
//    $user = $repo->findById(new StringLiteral($id));
//    if ($user === null) {
//        $response->setStatusCode(404);
//
//        return $response;
//    }

    $response->setStatusCode(200);
    $response->setContent(json_encode($user));

    return $response;
});

$app->post('/volunteers', function (Request $request) use ($dic) {
    $content = $request->getContent();

    if($content === '')
    {
        $response = new Response();
        $response->setStatusCode(204);
        return $response;
    }
    else if($content != '')
    {
        $jsonArray = json_decode($content);
        $email = $jsonArray['email'];
        $firstname = $jsonArray['firstname'];
        $lastname = $jsonArray['lastname'];
        $organization = $jsonArray['organization'];
        $groupnumber = $jsonArray['groupnumber'];
        $datetime = $jsonArray['datetime'];
        $department = $jsonArray['department'];

        $user = new user(new StringLiteral($email), new StringLiteral($firstname),new StringLiteral($lastname),
            new StringLiteral($organization),
            new StringLiteral($groupnumber),
            new DateTime($datetime),
            new StringLiteral($department));

        $dic['redis-client']->add($user);
        $dic['db-driver']->add($user);

        $response = new Response();
        $response->setStatusCode(201);
        return $response;
    }

    $response = new Response();
    $response->setStatusCode(501);

    return $response;
});

//TODO: not sure we need to have an update function for this application
$app->put('/volunteers/{id}', function ($id, Request $request) use ($dic) {
    $response = new Response();
    $response->setStatusCode(501);

    return $response;
});

$app->run();


function bootstrap()
{
    $dic = new Container();

    $dic['app'] = function() {
        return new Silex\Application();
    };

    $dic['db-driver'] = function() {
        $host = 'localhost';
        $db   = 'RoadHome';
        $user = 'root';
        $pass = 'one';
        $charset = 'utf8';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        return new PDO($dsn, $user, $pass, $opt);
    };

    $pdo = $dic['db-driver'];
    $dic['repo-mysql'] = function() use ($pdo) {
        return new MysqlUserRepository($pdo);
    };

    $dic['redis-client'] = function() {
        return new Predis\Client([
            'scheme' => 'tcp',
            'host'   => 'redisserver',
            'port'   => 6379,
        ]);
    };

    $dic['repo-mem'] = function() {
        $bill = new User(
            new StringLiteral('bill@email.com'),
            new StringLiteral('harris'),
            new StringLiteral('bharris'),
            new StringLiteral('blah'),
            new StringLiteral('blah'),
            new DateTime(),
            new StringLiteral('blah')
        );
        $bill->setId(new StringLiteral('1'));

        $charlie = new User(
            new StringLiteral('charlie@email.com'),
            new StringLiteral('fuller'),
            new StringLiteral('cfuller'),
            new StringLiteral('blah'),
            new StringLiteral('blah'),
            new DateTime(),
            new StringLiteral('blah')
        );
        $charlie->setId(new StringLiteral('2'));

        $dawn = new User(
            new StringLiteral('dawn@email.com'),
            new StringLiteral('brown'),
            new StringLiteral('dbrown'),
            new StringLiteral('blah'),
            new StringLiteral('blah'),
            new DateTime(),
            new StringLiteral('blah')
        );
        $dawn->setId(new StringLiteral('3'));

        $repoMem = new InMemoryUserRepository();
        $repoMem->add($bill)->add($charlie)->add($dawn);

        return $repoMem;
    };

    return $dic;
}
