<?php

namespace Yanntyb\App\Model\Classes\Entity;

use Yanntyb\App\Model\Traits\GlobalEntityTrait;

class User
{
    use GlobalEntityTrait;
    private string $nom;
    private string $prenon;
    private string $mail;
    private string $pass;
    private int $admin;

    /**
     * @return string
     */
    public function getNom(): string
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     * @return User
     */
    public function setNom(string $nom): User
    {
        $this->nom = $nom;
        return $this;
    }

    /**
     * @return string
     */
    public function getPrenon(): string
    {
        return $this->prenon;
    }

    /**
     * @param string $prenon
     * @return User
     */
    public function setPrenon(string $prenon): User
    {
        $this->prenon = $prenon;
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