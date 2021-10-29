<?php

namespace Yanntyb\App\Model\Classes\Entity;

use Yanntyb\App\Model\Traits\GlobalEntityTrait;

class User
{
    use GlobalEntityTrait;
    private string $mail;
    private string $pass;
    private int $admin;
    private string $apikey;
    private string $apisecret;

    /**
     * @return string
     */
    public function getApikey(): string
    {
        return $this->apikey;
    }

    /**
     * @param string $apikey
     * @return User
     */
    public function setApikey(string $apikey): User
    {
        $this->apikey = $apikey;
        return $this;
    }

    /**
     * @return string
     */
    public function getApisecret(): string
    {
        return $this->apisecret;
    }

    /**
     * @param string $apisecret
     * @return User
     */
    public function setApisecret(string $apisecret): User
    {
        $this->apisecret = $apisecret;
        return $this;
    }

    /**
     * @return string
     */
    public function getMail(): string
    {
        return $this->mail;
    }

    /**
     * @param string $mail
     * @return User
     */
    public function setMail(string $mail): User
    {
        $this->mail = $mail;
        return $this;
    }

    /**
     * @return string
     */
    public function getPass(): string
    {
        return $this->pass;
    }

    /**
     * @param string $pass
     * @return User
     */
    public function setPass(string $pass): User
    {
        $this->pass = $pass;
        return $this;
    }

    /**
     * @return int
     */
    public function getAdmin(): int
    {
        return $this->admin;
    }

    /**
     * @param int $admin
     * @return User
     */
    public function setAdmin(int $admin): User
    {
        $this->admin = $admin;
        return $this;
    }




}