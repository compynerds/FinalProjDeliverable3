<?php
/**
 * File name: MysqlUserRepository.php
 * Project: project1
 * PHP version 5
 * @category  PHP
 * @package   Project1\Infrastructure
 * @author    donbstringham <donbstringham@gmail.com>
 * @copyright 2016 © donbstringham
 * @license   http://opensource.org/licenses/MIT MIT
 * @version   GIT: <git_id>
 * @link      http://donbstringham.us
 * $LastChangedDate$
 * $LastChangedBy$
 */

namespace Project1\Infrastructure;

use Project1\Domain\StringLiteral;
use Project1\Domain\User;
use Project1\Domain\UserRepository;

/**
 * Class MysqlUserRepository
 * @category  PHP
 * @package   Project1\Infrastructure
 * @author    donbstringham <donbstringham@gmail.com>
 * @link      http://donbstringham.us
 */
class MysqlUserRepository implements UserRepository
{
    /** @var \PDO */
    protected $driver;

    /**
     * MysqlUserRepository constructor
     * @param \PDO $driver
     */
    public function __construct(\PDO $driver)
    {
        $this->driver = $driver;
    }

    /**
     * @param \Project1\Domain\User $user
     * @return $this
     * @throws \PDOException
     */
    public function add(User $user)
    {
        $data =json_decode(json_encode($user));

        try {
            $this->driver->prepare(
                'INSERT INTO users VALUES (NULL,?,?,?,?)'
            )->execute(array($data));
        } catch (\PDOException $e) {
            if ($e->getCode() === 1062) {
                // Take some action if there is a key constraint violation, i.e. duplicate name
            } else {
                throw $e;
            }
        }

        return $this;
    }

    /**
     * @param \Project1\Domain\StringLiteral $id
     * @return $this
     */
    public function delete(StringLiteral $id)
    {
        $data = json_decode(json_encode($id));

        try {
            $this->driver->prepare(
                'DELETE FROM users WHERE id = $id'
            )->execute($id);
        } catch (\PDOException $e) {
            if ($e->getCode() === 1062) {
                // Take some action if there is a key constraint violation, i.e. duplicate name
            } else {
                throw $e;
            }
        }

        return $this;
    }

    /**
     * @return array
     */
    public function findAll()
    {
        try {
            $this->driver->prepare('SELECT * FROM users')->fetchAll(PDO::FETCH_UNIQUE);
        }catch(\PDOException $e)
        {
            if($e->getCode() === 1062)
            {

            }else{
                throw $e;
            }
        }
        return $this;
    }

    /**
     * @param StringLiteral $fragment
     * @return array
     */
    public function findByEmail(StringLiteral $fragment)
    {
        try {
            $this->driver->prepare('SELECT email FROM users WHERE email = $fragment')->fetchAll(PDO::FETCH_UNIQUE);
        }catch(\PDOException $e)
        {
            if($e->getCode() === 1062)
            {

            }else{
                throw $e;
            }
        }
        return $this;
    }

    /**
     * @param StringLiteral $id
     * @return \Project1\Domain\User
     */
    public function findById(StringLiteral $id)
    {
        try {
            $this->driver->prepare('SELECT email FROM users WHERE id = $id')->fetchAll(PDO::FETCH_UNIQUE);
        }catch(\PDOException $e)
        {
            if($e->getCode() === 1062)
            {

            }else{
                throw $e;
            }
        }
        return $this;
    }

    /**
     * @param StringLiteral $fragment
     * @return array
     */
    public function findByName(StringLiteral $fragment)
    {
        try {
            $this->driver->prepare('SELECT email FROM users WHERE name = $fragment')->fetchAll(PDO::FETCH_UNIQUE);
        }catch(\PDOException $e)
        {
            if($e->getCode() === 1062)
            {

            }else{
                throw $e;
            }
        }
        return $this;

    }

    /**
     * @param StringLiteral $username
     * @return array
     */
    public function findByUsername(StringLiteral $username)
    {
        try {
            $this->driver->prepare('SELECT email FROM users WHERE username = $username')->fetchAll(PDO::FETCH_UNIQUE);
        }catch(\PDOException $e)
        {
            if($e->getCode() === 1062)
            {

            }else{
                throw $e;
            }
        }
        return $this;

    }

    /**
     * @return bool
     */
    public function save()
    {
        return true;
    }

    /**
     * @param \Project1\Domain\User $user
     * @return $this
     */
    public function update(User $user)
    {
        $data = json_decode(json_encode($user));

        try {
            $this->driver->prepare(
                'UPDATE FROM users SET  WHERE email = ?, name = ?, username = ?, '
        )->execute($data);
        } catch (\PDOException $e) {
            if ($e->getCode() === 1062) {
                // Take some action if there is a key constraint violation, i.e. duplicate name
            } else {
                throw $e;
            }
        }

        return $this;
    }
}
