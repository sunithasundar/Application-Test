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
    public function testBasicTest() //testBasicTest
    {
        $this->withoutExceptionHandling();
        $response = $this->get('/api/readProduct');

        $response->assertStatus(200);
    }

    // public function parameterProvider()
    // {
    //     return [
    //         [1, 'peanuts'],
    //         [2, 'MP'],
    //         [3, '43522'],
    //         [4, '2012'],
    //         [5, '102'],
    //         [6, 'PN34345']
    //     ];
    // }

    // public function testBasicTest()
    // {

        // $data = 'data'=> {'id'=>'9','name'=>'peanuts','state'=>'MP','zip'=>'43522','amount'=>'2012','qty'=>'102','item'=>'PN34345'};
        // $this->withoutExceptionHandling();
        // $response = $this->json('POST', '/api/createProduct', $data);
        // $response->assertStatus(200);

        // $this->withoutExceptionHandling();
        // $response = $this->postJson('/api/createProduct', ['id'=>'9','name'=>'peanuts','state'=>'MP','zip'=>'43522','amount'=>'2012','qty'=>'102','item'=>'PN34345']);

        // //$this->post('/api/createProduct')->json(["name"=>"peanuts","state"=>"MP","zip"=>"43522","amount"=>"2012","qty"=>"102","item"=>"PN34345"]);

        // //$response->assertStatus(200);
        // $response
        // ->assertStatus(200)
        // ->assertJson([
        //     'created' => true,
        // ]);

        // $response = $this->postJson('/api/user', [
        //     'name' => 'John Doe',
        //     'email' => 'johndoe@example.com',
        // ]);
    
        // $response->assertStatus(200)
        //          ->assertJson([
        //              'name' => 'John Doe',
        //              'email' => 'johndoe@example.com',
        //          ]);
    // }
}
