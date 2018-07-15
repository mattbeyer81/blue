<?php

class MeterControllerTest extends TestCase
{

    public function testAcceptReadingWithoutReadingArrayWillFail(){

        $response = $this->call('post', 'api/meter-read', [
            "customerId" => "identifier123",
            "serialNumber" => "27263927192",
            "mpxn" => "14582749",
            "read" => 'foobaa',
            "readDate" => "2017-11-20T16:19:48+00:00Z"
        ]);

        Log::info(__METHOD__ . ' - response : '. json_encode($response->getContent()) . '  ');

        $this->assertTrue($response->status() == 400);

    }


    public function testAcceptReadingWithoutValidReadingArrayWillFail(){

        $response = $this->call('post', 'api/meter-read', [
            "customerId" => "identifier123",
            "serialNumber" => "27263927192",
            "mpan" => "14582749",
            "read" => [
                ["type" => "ANYTIME", "registerId" => "387373"],
                ["type" => "NIGHT", "registerId" => "387373", "value" => "2892"]
            ],
            "readDate" => "2017-11-20T16:19:48+00:00Z"
        ]);

        Log::info(__METHOD__ . ' - response : '. json_encode($response->getContent()) . '  ');
        $this->assertTrue($response->status() == 400);

    }


    public function testAcceptReadingWithoutValidData()
    {

        $response = $this->call('post', 'api/meter-read', [
            "serialNumber" => "27263927192",
            "mpxn" => "14582749",
            "read" => [
                ["type" => "ANYTIME", "registerId" => "387373", "value" => "2729"],
                ["type" => "NIGHT", "registerId" => "387373", "value" => "2892"]
            ],
            "readDate" => "TEST2017-11-20T16:19:48+00:00Z"
        ]);

        Log::info(__METHOD__ . ' - response code: '. json_encode($response->status()));
        $this->assertTrue($response->status() == 400);


    }

    public function testAcceptReading()
    {
        $response = $this->call('post', 'api/meter-read', [
            "customerId" => "identifier123",
            "serialNumber" => "27263927192",
            "mpan" => "14582749",
            "read" => [
                ["type" => "ANYTIME", "registerId" => "387373", "value" => "2729"],
                ["type" => "NIGHT", "registerId" => "387373", "value" => "2892"]
            ],
            "readDate" => "2017-11-20T16:19:48+00:00Z"
        ]);

        Log::info(__METHOD__ . ' - response code: '. json_encode($response->status()));
        $this->assertTrue($response->status() == 200);
    }
}
