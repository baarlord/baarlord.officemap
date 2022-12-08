<?php

use Doctrine\ORM\Mapping as ORM;

/** @Annotation */
final class Office
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="string")
     */
    public $name;

    /**
     * @ORM\Column(type="string")
     */
    public $code;

    /**
     * @ORM\Column(type="string")
     */
    public $floor;

    /**
     * @ORM\Column(type="integer")
     */
    public $img;
}
