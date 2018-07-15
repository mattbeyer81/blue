The routes for the API can be found in:
routes/api.php

The controller for the API can be found in:
app/Http/Controllers/MeterController.php

The api accepts these requests:

POST api/meter-read

e.g.
POST api/meter-read
{
    "customerId": "identifier123",
    "serialNumber": "27263927192",
    "mpan": "14582749",
    "read": [
        {"type": "ANYTIME", "registerId": "387373", "value": "2729"},
        {"type": "NIGHT", "registerId": "387373", "value": "2892"}
    ],
    "readDate": "2017-11-20T16:19:48+00:00Z"
}


GET api/meter-read

e.g.
GET api/meter-read meter-read?customerId=identifier123&mpan=14582749

{
    "status": "success",
    "data": [
        {
            "customerId": "identifier123",
            "serialNumber": "27263927192",
            "read": [
                {
                    "type": "ANYTIME",
                    "value": "2729",
                    "registerId": "387373"
                },
                {
                    "type": "NIGHT",
                    "value": "2892",
                    "registerId": "387373"
                }
            ],
            "readDate": "2017-11-20T16:19:48+00:00",
            "mpan": "14582749"
        }
    ]
}

MySQL schema is at schema.sql

PHPUnit test can be run using
vendor/bin/phpunit tests/Http/Controllers

MeterControllerTest contains these tests:

testAcceptReading
testAcceptReadingWithoutValidData
testAcceptReadingWithoutValidReadingArrayWillFail
testAcceptReadingWithoutReadingArrayWillFail
