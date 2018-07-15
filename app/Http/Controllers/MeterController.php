<?php

namespace App\Http\Controllers;

use Illuminate\Http\{
    Request,
    Response
};
use App\Reading;
use Carbon\Carbon;
use Exception;

class MeterController extends Controller
{

    /*
    *   Validate the reading data
    *   @param  array $data
    *
    *   return array Any errors
    */

    private function validateRead(Array $data) : Array
    {
        $errors = [];
        if (!isset($data['customerId'])) {
            $errors[] = 'customerId is missing';
        }
        if (!isset($data['serialNumber'])) {
            $errors[] = 'serialNumber is missing';
        }
        if (!isset($data['mpan']) && !isset($data['mprn'])) {
            $errors[] = 'Meter point number is missing';
        }
        if (isset($data['mpan']) && isset($data['mprn'])) {
            $errors[] = 'Only gas or electric meter point number allowed, not both';
        }
        if (!isset($data['readDate'])) {
            $errors[] = 'customerId is missing';
        }
        if (!isset($data['read'])) {
            $errors[] = 'readings are missing';
        }

        return $errors;
    }


    /*
    *   Validate the reading
    *   @param  array $reads    The readings
    *
    *   return array Any errors
    */

    private function validateReading(Array $read) : Array
    {
        $errors = [];
        if (!isset($read['type'])) {
            $errors[] = 'Reading type is missing';
        }

        if (!isset($read['registerId'])) {
            $errors[] = 'Reading registerId is missing';
        }

        if (!isset($read['value'])) {
            $errors[] = 'Reading value is missing';
        }

        if (isset($read['value']) && !ctype_digit($read['value'])) {
            $errors[] = 'Reading value is not integers only';
        }

        return $errors;

    }


    /*
    *   Validate the readings
    *   @param  array $reads    The readings
    *
    *   return array Any errors
    */


    public function validateReadings(Array $reads) : Array
    {
        $errors = [];
        foreach ($reads as $read) {
            $readingErrors = $this->validateReading($read);
            if (count($readingErrors) > 0) {
                $read['errors'] = $readingErrors;
                $errors[] = $read;
            }
        }

        return $errors;

    }

    /*
    *   Get the reading from request query data
    *   @parm   Request $request
    *
    *   @return Response readings results
    */

    public function getReadings(Request $request) : Response
    {
        $readingsQuery = Reading::query();

        if ($request->get('customerId')) {
            $readingsQuery->where('customer_id', $request->get('customerId'));
        }

        if ($request->get('mpan')) {
            $readingsQuery->where('power_type', 'MPAN');
            $readingsQuery->where('mpxn', $request->get('mpan'));
        }
        if ($request->get('mprn')) {
            $readingsQuery->where('power_type', 'MPRN');
            $readingsQuery->where('mpxn', $request->get('mprn'));
        }

        $readings = $readingsQuery->get();

        $results = [];

        foreach ($readings as $reading) {
            $result = [
                'customerId' => $reading->customer_id,
                'serialNumber' => $reading->serial_number,
                'read' => $reading->read,
                'readDate' => date('c', strtotime($reading->read_date))
            ];
            if ($reading->power_type == 'MPAN') {
                $result['mpan'] = $reading->mpxn;
            } else {
                $result['mprn'] = $reading->mpxn;
            }
            $results[] = $result;
        }

        return response([
            'status' => 'success',
            'data' => $results
        ], 200);


    }

    /*
    *   Store the reading from request query data
    *   @parm   Request $request
    *
    *   @return Response readings stored results
    */

    public function storeReadings(Request $request) : Response
    {

        try {

            $data = $request->all();
            $read = $data['read'];

            $errors = [];

            if (is_array($data)) {
                $errors['data_errors'] = $this->validateRead($data);
            } else {
                $errors['data_error'] = 'Data is not valid';
            }

            if (isset($data['read']) && is_array($data['read'])) {
                $errors['reading_errors'] = $this->validateReadings($data['read']);
            } else {
                $errors['data_reading_error'] = 'Reading is not valid';
            }

            try {
                $datetime = Carbon::createFromFormat('Y-m-d\TH:i:s+', $data['readDate'] );
            } catch (Exception $e) {
                $errors['data_errors'][] = 'Invalid reading date';
            }

            if (count($errors['data_errors']) > 0 || count($errors['reading_errors'])) {
                throw new Exception;
            }

            $newRead = [
                'customer_id' => $data['customerId'],
                'serial_number' => $data['serialNumber'],
                'power_type' => isset($data['mpan']) ? 'mpan' : 'mprn',
                'mpxn' => isset($data['mpan']) ? $data['mpan'] : $data['mprn'],
                'read' => $data['read'],
                'read_date' => $datetime
            ];
            $result = Reading::create($newRead);

            return response([
                'status' => 'success',
                'data' => $result
            ], 200);

        } catch (Exception $e) {
            return response([
                'status' => 'failed',
                'errors' => $errors
            ], 400);
        }
    }
}
