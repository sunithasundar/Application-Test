<?php

namespace Tests\Feature;

use League\Csv\Reader;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
// use Response;
use Tests\TestCase;

class ExampleTest extends TestCase
{

    public $filePath;

    public function setUp(): void
    {
        parent::setUp();

        $this->filePath = Reader::createFromPath("app/csv/data.csv");
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $this->withoutExceptionHandling();
        $response = $this->get('/api/readProduct');

        $response->assertStatus(200);
    }
}
