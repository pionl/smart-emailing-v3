<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Tests\Models;

use SmartEmailing\v3\Exceptions\PropertyRequiredException;
use SmartEmailing\v3\Models\MessageContents;
use SmartEmailing\v3\Tests\TestCase\BaseTestCase;

class MessageContentsTest extends BaseTestCase
{
    private MessageContents $model;

    protected function setUp(): void
    {
        $this->model = new MessageContents();
    }

    public function testShouldThrowExceptionWhenMissingAllData(): void
    {
        $this->expectException(PropertyRequiredException::class);
        $this->model->toArray();
    }

    public function testShouldThrowExceptionWhenMissingPartialData(): void
    {
        $this->model->setSubject('Email subject');
        $this->expectException(PropertyRequiredException::class);
        $this->model->toArray();
    }

    public function testShouldReturnArrayWithData(): void
    {
        $this->model->setSubject('Email_subject');
        $this->model->setHtmlBody('HTMLBODY');
        $this->model->setTextBody('TEXTBODY');

        $data = $this->model->toArray();

        self::assertSame([
            'subject' => 'Email_subject',
            'html_body' => 'HTMLBODY',
            'text_body' => 'TEXTBODY',
        ], $data);
    }

    public function testShouldReturnSameDataFromSerializer(): void
    {
        $this->model->setSubject('Email_subject');
        $this->model->setHtmlBody('HTMLBODY');
        $this->model->setTextBody('TEXTBODY');

        self::assertSame($this->model->toArray(), $this->model->jsonSerialize());
    }

    public function testShouldSetSettersAndReadGetters(): void
    {
        $this->model->setSubject('Email_subject');
        self::assertSame('Email_subject', $this->model->getSubject());

        $this->model->setHtmlBody('HTMLBODY');
        self::assertSame('HTMLBODY', $this->model->getHtmlBody());

        $this->model->setTextBody('TEXTBODY');
        self::assertSame('TEXTBODY', $this->model->getTextBody());
    }
}
