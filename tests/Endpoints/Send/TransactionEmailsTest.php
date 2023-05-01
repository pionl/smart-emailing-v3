<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Tests\Endpoints\Send;

use SmartEmailing\v3\Endpoints\Send\TransactionalEmails\TransactionalEmailsRequest;
use SmartEmailing\v3\Models\Attachment;
use SmartEmailing\v3\Models\MessageContents;
use SmartEmailing\v3\Models\Recipient;
use SmartEmailing\v3\Models\Replace;
use SmartEmailing\v3\Models\SenderCredentials;
use SmartEmailing\v3\Models\Task;
use SmartEmailing\v3\Models\TemplateVariable;
use SmartEmailing\v3\Tests\TestCase\ApiStubTestCase;

class TransactionEmailsTest extends ApiStubTestCase
{
    private TransactionalEmailsRequest $transactionEmails;

    protected function setUp(): void
    {
        parent::setUp();

        $this->transactionEmails = new TransactionalEmailsRequest($this->apiStub);
    }

    public function testShouldReturnSameDataFromSerializer()
    {
        self::assertSame($this->transactionEmails->toArray(), $this->transactionEmails->jsonSerialize());
    }

    public function testEndpoint()
    {
        $this->createEndpointTest(
            $this->transactionEmails,
            'send/transactional-emails-bulk',
            'POST',
            self::arrayHasKey('json')
        );
    }

    public function testShouldSetSettersAndReadGetters()
    {
        $this->transactionEmails->setTag('tag');
        self::assertSame('tag', $this->transactionEmails->getTag());

        $this->transactionEmails->setEmailId(5);
        self::assertSame(5, $this->transactionEmails->getEmailId());

        $messageContents = new MessageContents();
        $this->transactionEmails->setMessageContents($messageContents);
        self::assertNotEmpty($this->transactionEmails->getMessageContents());

        self::assertSame([], $this->transactionEmails->getTasks());

        $task = new Task();
        $this->transactionEmails->addTask($task);
        self::assertCount(1, $this->transactionEmails->getTasks());
    }

    public function testShouldSeeAllKeysInOutputArray()
    {
        $credentials = new SenderCredentials();
        $credentials->setFrom('from@example.com');
        $credentials->setReplyTo('to@example.com');
        $credentials->setSenderName('Jean-Luc Picard');

        $recipient = new Recipient();
        $recipient->setEmailAddress('kirk@example.com');

        $replace1 = new Replace();
        $replace1->setKey('key1');
        $replace1->setContent('content1');

        $replace2 = new Replace();
        $replace2->setKey('key2');
        $replace2->setContent('content2');

        $templateVariable = new TemplateVariable();
        $templateVariable->setCustomData([
            'foo' => 'bar',
            'products' => [
                [
                    'name' => 'bbb',
                    'desc' => 'lll',
                ],
                [
                    'name' => 'ddd',
                    'desc' => 'kkk',
                ],
            ],
        ]);

        $attachment1 = new Attachment();
        $attachment1->setContentType('image/png');
        $attachment1->setFileName('picture.png');
        $attachment1->setDataBase64('data1');

        $attachment2 = new Attachment();
        $attachment2->setContentType('image/gif');
        $attachment2->setFileName('sun.gif');
        $attachment2->setDataBase64('data2');

        $task = new Task();
        $task->setRecipient($recipient);
        $task->addReplace($replace1);
        $task->addReplace($replace2);
        $task->setTemplateVariables($templateVariable);
        $task->addAttachment($attachment1);
        $task->addAttachment($attachment2);

        $messageContents = new MessageContents();
        $messageContents->setTextBody('text_body');
        $messageContents->setHtmlBody('html_body');
        $messageContents->setSubject('subject');

        $this->transactionEmails->setTag('tag_tag');
        $this->transactionEmails->setEmailId(5);
        $this->transactionEmails->setSenderCredentials($credentials);
        $this->transactionEmails->addTask($task);
        $this->transactionEmails->setMessageContents($messageContents);

        $data = $this->transactionEmails->toArray();
        self::assertArrayHasKey('sender_credentials', $data);
        self::assertArrayHasKey('tag', $data);
        self::assertArrayHasKey('email_id', $data);
        self::assertArrayHasKey('message_contents', $data);
        self::assertArrayHasKey('tasks', $data);
    }
}
