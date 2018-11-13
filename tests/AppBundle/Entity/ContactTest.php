<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Contact;
use PHPUnit\Framework\TestCase;

class ContactTest extends TestCase
{
    /** @var Contact */
    private $contact;

    public function setUp()
    {
        $this->contact = new Contact();
    }

    public function testCreate()
    {
        $this->assertInstanceOf(Contact::class, $this->contact);
        $this->assertInstanceOf(\DateTime::class, $this->contact->getCreatedAt());
        $this->assertTrue($this->contact->isNew());
        $this->assertFalse($this->contact->isOld());
    }

    public function testSettingName()
    {
        $this->contact->setName($contactName = 'Contact name');
        $this->assertEquals($contactName, $this->contact->getName());
    }

    public function testSettingEmail()
    {
        $this->contact->setEmail($email = 'email@email.ru');
        $this->assertEquals($email, $this->contact->getEmail());
    }

    public function testSettingSubject()
    {
        $this->contact->setSubject($subject = 'Subject');
        $this->assertEquals($subject, $this->contact->getSubject());
    }

    public function testSettingMessage()
    {
        $this->contact->setMessage($message = 'Message');
        $this->assertEquals($message, $this->contact->getMessage());
    }

    public function testSettingOldStatus()
    {
        $this->contact->setOldStatus();
        $this->assertTrue($this->contact->isOld());
        $this->assertFalse($this->contact->isNew());
    }
}