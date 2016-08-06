<?php
/**
 * Created by PhpStorm.
 * User: Mark
 * Date: 8/5/2016
 * Time: 10:55 PM
 */

use Project1\Domain\StringLiteral;
use Project1\Domain\User;
use Project1\Services\RedisUserRepository;

describe('Project1\Infrastructure\MysqlUserRepository', function () {
    beforeEach(function () {
        $this->repo = new RedisUserRepository();
        $user = new User(
            new StringLiteral('bill@email.com'),
            new StringLiteral('bill'),
            new StringLiteral('billharris')
        );
        $user->setId(new StringLiteral('1a'));
        $this->repo->add($user);
    });
    describe('->__construct()', function () {
        it('should return a RedisUserRepository object', function () {
            expect($this->repo)->to->be->instanceof(
                'Project1\Infrastructure\RedisUserRepository'
            );
        });
    });
    describe('->add()', function () {
        it('should add a valid User object', function () {
            $users = $this->repo->add(new StringLiteral('bill@email.com'));
            expect($users)->to->be->an('array');
            expect(1 === count($users))->to->be->true();
        });
    });
    describe('->delete("1a")', function () {
        it('should return a $this pointer after deleting the user', function () {
            $actual = $this->repo->delete(new StringLiteral('1a'));
            expect($actual)->to->be->instanceof(
                'Project1\Infrastructure\RedisUserRepository'
            );
            expect($this->repo->count())->to->equal(0);
        });
    });
    describe('->delete("")', function () {
        it('should return a $this pointer after deleting the user', function () {
            $actual = $this->repo->delete(new StringLiteral(''));
            expect($actual)->to->be->instanceof(
                'Project1\Infrastructure\RedisUserRepository'
            );
            expect($this->repo->count())->to->equal(1);
        });
    });
    describe('->findAll()', function () {
        it('should return a valid User object', function () {
            /** @var \Project1\Domain\User $user */
            $users = $this->repo->findAll();
            expect($users)->to->be->an('array');
            expect(1 === count($users))->to->be->true();
        });
    });

    describe('->findByEmail("bill@email.com")', function () {
        it('should return a valid User object', function () {
            $users = $this->repo->findByEmail(new StringLiteral('bill@email.com'));
            expect($users)->to->be->an('array');
            expect(1 === count($users))->to->be->true();
        });
    });
    describe('->findById("1a")', function () {
        it('should return a valid User object', function () {
            /** @var \Project1\Domain\User $user */
            $user = $this->repo->findById(new StringLiteral('1a'));
            expect($user)->to->be->instanceof('Project1\Domain\User');
            expect($user->getEmail()->equal(new StringLiteral('bill@email.com')))
                ->to->be->true();
        });
    });
    describe('->findById("2b")', function () {
        it('should return a null', function () {
            /** @var \Project1\Domain\User $user */
            $user = $this->repo->findById(new StringLiteral('2b'));
            expect($user)->to->be->a('NULL');
        });
    });
    describe('->findByName("bill")', function () {
        it('should return a valid User object', function () {
            /** @var \Project1\Domain\User $user */
            $user = $this->repo->findByName(new StringLiteral('1a'));
            expect($user)->to->be->instanceof('Project1\Domain\User');
            expect($user->getEmail()->equal(new StringLiteral('billm')))
                ->to->be->true();
        });
    });
    describe('->findByUsername("billharris")', function () {
        it('should return a valid User object', function () {
            /** @var \Project1\Domain\User $user */
            $user = $this->repo->findByUsername(new StringLiteral('1a'));
            expect($user)->to->be->instanceof('Project1\Domain\User');
            expect($user->getEmail()->equal(new StringLiteral('billharris')))
                ->to->be->true();
        });
    });
    describe('->findAll()', function () {
        it('should return a valid User object', function () {
            /** @var \Project1\Domain\User $user */
            $users = $this->repo->findAll();
            expect($users)->to->be->an('array');
            expect(1 === count($users))->to->be->true();
        });
    });
    describe('->save()', function () {
        it('should return a null', function () {
            /** @var \Project1\Domain\User $user */
            $user = $this->repo->save();
            expect($user)->to->be->a(true);
        });
    });
    describe('->update()', function () {
        it('should return a null', function () {
            /** @var \Project1\Domain\User $user */
            $user = $this->repo->update(new User( new StringLiteral("Bill"),  new StringLiteral("Billharris"), new StringLiteral("Bill@email.com")));
            expect($user->getEmail()->equal(new StringLiteral('Billharris')))
                ->to->be->true();
        });
    });
});
