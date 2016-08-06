<?php
/**
 * File name: match-by-email.spec.php
 * Project: project1
 * PHP version 5
 * @category  PHP
 * @author    donbstringham <donbstringham@gmail.com>
 * @copyright 2016 © donbstringham
 * @license   http://opensource.org/licenses/MIT MIT
 * @version   GIT: <git_id>
 * @link      http://donbstringham.us
 * $LastChangedDate$
 * $LastChangedBy$
 */

use Project1\Domain\StringLiteral;
use Project1\Infrastructure\InMemoryUserRepository;
use Project1\Domain\User;
use Project1\Services\MatchByEmail;


describe('Project1\Services\MatchByName', function () {
    beforeEach(function () {
        $this->repo = new InMemoryUserRepository();
        $user = new User(
            new StringLiteral('bill@email.com'),
            new StringLiteral('bill'),
            new StringLiteral('billharris')
        );
        $user->setId(new StringLiteral('1a'));
        $this->repo->add($user);
    });//End before each setup

    describe('->__construct()', function () {
        it('should return a MatchByName object', function () {
            $repo = new InMemoryUserRepository();
            $user = new User(
                new StringLiteral('bill@email.com'),
                new StringLiteral('bill'),
                new StringLiteral('billharris')
            );
            $user->setId(new StringLiteral('1a'));
            $repo->add($user);
            $matchTest = new MatchByEmail($repo);
            expect($matchTest)->to->be->instanceof(
                'Project1\Services\MatchByName'
            );
        });
    });//End construct Unit test

    describe('->match(StringLiteral value)', function(){
        it('should return the name', function()
        {
            $repo = new InMemoryUserRepository();
            $user = new User(
                new StringLiteral('bill@email.com'),
                new StringLiteral('bill'),
                new StringLiteral('billharris')
            );
            $user->setId(new StringLiteral('1a'));
            $repo->add($user);
            $matchTest = new MatchByEmail($repo);

            expect($matchTest->match(new StringLiteral('bill')))->equal($user);//InMemory should return the user not just the email
        });
    });//End match function unit test

});//end initial describe/function block

