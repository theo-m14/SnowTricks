<?php

namespace App\Tests;

use App\Entity\User;
use App\Entity\Tricks;
use App\Entity\TricksGroup;
use App\Entity\TricksImage;
use App\Entity\TricksVideo;
use PHPUnit\Framework\TestCase;

class TricksTest extends TestCase
{
    private User $user;
    private TricksGroup $group;
    private TricksVideo $video;
    private Tricks $trick;
    private TricksImage $image;

    public function setUp() : void
    {
        $this->trick = new Tricks();
        $this->trick->setName('Test');
        $this->trick->setDescription('DescriptionTest');
        $this->user = new User();
        $this->trick->setUser($this->user);
        $this->group = new TricksGroup();
        $this->trick->setTricksGroup($this->group);
        $this->video = new TricksVideo();
        $this->trick->addTricksVideo($this->video);
        $this->image = new TricksImage();
        $this->trick->addTricksImage($this->image);
    }

    public function testGetName() : void
    {
        $this->assertEquals('Test', $this->trick->getName());
    }

    public function testGetDescription() : void
    {
        $this->assertEquals('DescriptionTest', $this->trick->getDescription());
    }

    public function testGetUser() : void
    {
        $this->assertEquals($this->user, $this->trick->getUser());
    }

    public function testGetTricksGroup() : void
    {
        $this->assertEquals($this->group, $this->trick->getTricksGroup());
    }

    public function testGetVideo() : void
    {
        $this->assertTrue($this->trick->getTricksVideos()->contains($this->video));
    }

    public function testGetImage() : void
    {
        $this->assertTrue($this->trick->getTricksImages()->contains($this->image));
    }
}
