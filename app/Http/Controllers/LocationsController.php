<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Swagger\Annotations as SWG;
require (base_path('vendor/autoload.php'));
use Guzzle\Http\Client;

/**
 * @SWG\Resource(
 *      swaggerVersion="2.0",
 *      apiVersion="1.0",
 *      resourcePath="/locations",
 *      basePath="/"
 *  )
 *
 */

class LocationsController extends Controller
{

    /**
     * @SWG\Api(
     *  path="/locations",
     *  description="Returns the current location of user based on IP address of the request that is sent.",
     *  @SWG\Operation(
     *      method="GET",
     *      summary="Gets current location information of user",
     *      type="Locations",
     *      @SWG\ResponseMessage(code=200, message="OK")
     *  )
     * )
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $clientIp = $request->getClientIp();
        $guzzle = new Client();

        $response = $guzzle->get("http://freegeoip.net/json/$clientIp")->send();
        $data = json_encode($response->json(), JSON_PRETTY_PRINT);

        return response($data, 200)->header('Content-Type', 'application/json');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }


     /**
      * @SWG\Api(
      *      path="/locations/{query}",
      *      description="Displays a list of all the locations returned by the API called",
      *      @SWG\Operation(
      *          method="GET",
      *          summary="GETs a listing of all locations",
      *          type="Locations",
      *          @SWG\Parameter(
      *              name="query",
      *              description="Name of location to query",
      *              paramType="path",
      *              required=true,
      *              allowMultiple=false,
      *              type="string",
      *          ),
      *          @SWG\ResponseMessage(code=200, message="OK")
      *      )
      *  )
      *
      * @param  string  $query
      * @return Response
      */
    public function show($query)
    {
        // These code snippets use an open-source library.
        $KEY = env('API_KEY');
        $client = new Client();

        //Checks if query String has spaces and parses it to correct format i.e 'string1+string2'
        $query = $this->parseQuery($query);

        $region = 'jm';
        $response = $client
                    ->get("https://maps.googleapis.com/maps/api/geocode/json?address=$query&key=$KEY&region=$region&components=country:JM")
                    ->send();
        $status = $response->getStatusCode();
        if ($status === 200)
        {
            $data = json_encode($response->json(), JSON_PRETTY_PRINT);
            return response($data, 200)->header('Content-Type', 'application/json');
        }
        else
        {
            $data = json_encode(['message' => 'RouteJA encountered errors searching for that location.']);
            return response($data, 400)->header('Content-Type', 'application/json');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }


    //Checks if query String has spaces and replaces spaces with '+'
    public function  parseQuery($query)
    {
        if(strpos($query, ' '))
        {
            $queryArr = explode(' ', $query); //creates an array of the strings seperated by spaces
            $count = count($queryArr);
            $query = ""; //unsets the string to remake it with
            for($i=0;$i<$count;$i++)
            {
                $query = $query.$queryArr[$i];
                //bruteforce way of avoiding extra + at end of string i.e "Half+Way+Tree+"
                if($i==$count - 1)
                {
                    break;
                }
                $query = $query.'+';
            }
        }
        return $query;
    }
}
