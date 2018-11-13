<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Photo;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PhotoTest extends TestCase
{
    private $file;
    /** @var UploadedFile */
    private $image;
    /** @var Photo */
    private $photo;


    public function setUp()
    {
        $this->file = tempnam(sys_get_temp_dir(), 'upl');
        imagepng(imagecreatetruecolor(10, 10), $this->file);
        $this->image = new UploadedFile(
            $this->file,
            'new_image.png'
        );
        $this->photo = new Photo();
    }

    public function testSettingFile()
    {
        $this->photo->setFile($this->image);
        $this->assertInstanceOf(UploadedFile::class, $this->photo->getFile());
    }

    public function testSettingFileName()
    {
        $this->photo->setFileName($filename = 'File name');
        $this->assertEquals($filename, $this->photo->getFileName());
    }

    public function tearDown()
    {
        unlink($this->file);
    }
}