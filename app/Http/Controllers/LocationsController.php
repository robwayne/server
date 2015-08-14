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
     *      path="/locations/{location_name}",
     *      description="Displays a list of all the locations returned by the API called",
     *      @SWG\Operation(
     *          method="GET",
     *          summary="GETs a listing of all locations",
     *          type="Locations",
     *          @SWG\Parameter(
     *              name="location_name",
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
     * @return Response
     */
    public function index($query)
    {
        // These code snippets use an open-source library.
        $KEY = env('API_KEY');
        $client = new Client();

        $region = 'jm';
        $response = $client
                    ->get("https://maps.googleapis.com/maps/api/geocode/json?address=$query&key=$KEY&region=$region&components=country:JM")
                    ->send();
        $status = $response->getStatusCode();
        if ($status === 200)
            $data = $response->json();
        else
            $data = ['message' => 'RouteJA encountered errors searching for that location.'];
        return $data;
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
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
}
