<?php

namespace Yanntyb\App\Model\Classes\Entity;

use Yanntyb\App\Model\Traits\GlobalEntityTrait;

class link
{
    use GlobalEntityTrait;
    private string $href;
    private string $title;
    private string $target;
    private string $name;
    private User $user;
    private int $used;

    /**
     * @return string
     */
    public function getHref(): string
    {
        return $this->href;
    }

    /**
     * @param string $href
     * @return link
     */
    public function setHref(string $href): link
    {
        $this->href = $href;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return link
     */
    public function setTitle(string $title): link
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getTarget(): string
    {
        return $this->target;
    }

    /**
     * @param string $target
     * @return link
     */
    public function setTarget(string $target): link
    {
        $this->target = $target;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return link
     */
    public function setName(string $name): link
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return link
     */
    public function setUser(User $user): link
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return int
     */
    public function getUsed(): int
    {
        return $this->used;
    }

    /**
     * @param int $used
     * @return link
     */
    public function setUsed(int $used): link
    {
        $this->used = $used;
        return $this;
    }
}