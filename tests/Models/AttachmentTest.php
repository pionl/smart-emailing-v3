<?php

declare(strict_types=1);

namespace SmartEmailing\v3\Tests\Models;

use SmartEmailing\v3\Exceptions\PropertyRequiredException;
use SmartEmailing\v3\Models\Attachment;
use SmartEmailing\v3\Tests\TestCase\BaseTestCase;

class AttachmentTest extends BaseTestCase
{
    private Attachment $model;

    protected function setUp(): void
    {
        $this->model = new Attachment();
    }

    public function testShouldThrowExceptionWhenMissingAllData(): void
    {
        $this->expectException(PropertyRequiredException::class);
        $this->model->toArray();
    }

    public function testShouldThrowExceptionWhenMissingPartialData(): void
    {
        $this->model->setFileName('car.png');
        $this->expectException(PropertyRequiredException::class);
        $this->model->toArray();
    }

    public function testShouldReturnArrayWithData(): void
    {
        $this->model->setFileName('car.png');
        $this->model->setContentType('image/png');
        $this->model->setDataBase64('data');

        $data = $this->model->toArray();

        self::assertSame([
            'file_name' => 'car.png',
            'content_type' => 'image/png',
            'data_base64' => 'data',
        ], $data);
    }

    public function testShouldReturnSameDataFromSerializer(): void
    {
        $this->model->setFileName('car.png');
        $this->model->setContentType('image/png');
        $this->model->setDataBase64('data');

        self::assertSame($this->model->toArray(), $this->model->jsonSerialize());
    }

    public function testShouldSetSettersAndReadGetters(): void
    {
        $this->model->setFileName('car.png');
        self::assertSame('car.png', $this->model->getFileName());

        $this->model->setContentType('image/png');
        self::assertSame('image/png', $this->model->getContentType());

        $this->model->setDataBase64('data');
        self::assertSame('data', $this->model->getDataBase64());
    }
}
