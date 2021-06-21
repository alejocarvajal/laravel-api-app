<?php

namespace App\Http\Controllers\Search;

use App\Exports\SearchExport;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class SearchController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function find(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'porcentaje' => 'required|string'
        ]);

        $api = "api/stradataAPI/findName";

        $params = [
            'nombre' => $request->get('nombre'),
            'porcentaje' => $request->get('porcentaje')
        ];

        $token = $this->getToken();

        if ($token['error'] == true) {
            return response()
                ->json([
                    'type' => 'error',
                    'message' => 'No se pudo obtener el token'
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        $headers = [
            'Authorization' => 'Bears ' . $token['token']
        ];

        $comparacion = $this->callStradataAPI($api, $params, $headers);

        if (strtolower($comparacion->code) != strtolower(Response::HTTP_OK)) {
            return response()
                ->json([
                    'type' => 'error',
                    'message' => 'No se pudo conectar al servicio'
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        $respuesta = ['result' => $comparacion->result];
        return $respuesta;
    }

    private function getToken()
    {
        $api = "api/login";
        $AuthData = Auth::user();
        $respuesta = [
            'error' => false,
        ];

        $params = [
            'email' => $AuthData->email,
            'password' => '12345678'
        ];
        $token = $this->callStradataAPI($api, $params);
        if (strtolower($token->code) != strtolower(Response::HTTP_OK)) { //En caso de que el servicio no responda
            $error['error'] = true;
            return $error;
        }
        $respuesta['token'] = $token->result->token;
        return $respuesta;
    }

    private function callStradataAPI($api, $params, $headers = null)
    {


        try {
            $client = new Client([
                'base_uri' => '//localhost:8001',
                'curl' => [
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_SSL_VERIFYHOST => false
                ],
                'verify' => false
            ]);

            $this->request = $client->request('POST', $api, [
                'headers' => $headers,
                'json' => $params,
                'timeout' => 10
            ]);

            //Construimos la respuesta
            $this->response = [
                'code' => $this->request->getStatusCode(), //Obtenemos el codigo de la peticion 200
                'result' => json_decode($this->request->getBody()->getContents()) //Deserializamos el contenido JSON del response
            ];
        } catch (ClientException | ServerException $ex) {
            $this->response = [
                'code' => $ex->getResponse()->getStatusCode(),  //Obtenemos el codigo de la peticion 400 / 500
                'message' => json_decode($ex->getResponse()->getBody()->getContents()) //Deserializamos el contenido JSON del response
            ];
        } catch (ConnectException | \Exception $ex) {
            $this->response = [
                'code' => 500
            ];
        }
        return (object)$this->response;
    }

    public function is_palindrome()
    {
        $cadena = 'Allí por la tropa portado, traído a ese paraje de maniobras, una tipa como capitán usar boina me dejara, pese a odiar toda tropa por tal ropilla';
        $retorno = false;
        //convierte toda la frase a minuscula incluyendo caraceteres especiales
        $cadena = mb_strtolower($cadena, 'UTF-8');

        //reemplaza los acentos por caracteres normales
        $cadena = $this->removeAccents($cadena);

        //reemplaza caracteres de puntuación
        $patron = '/[¿!¡;,:\.\?#@()"]/';
        $reemplazo = '';
        $cadena = preg_replace($patron, $reemplazo, trim($cadena));

        //reemplaza los espacios
        $cadena = str_replace(" ", "", $cadena);
        $separar = explode(" ", $cadena);


        if ($cadena == strrev($cadena)) {
            $retorno = true;
        }
        return $retorno;
    }

    private function removeAccents($word)
    {
        $conv = [
            'á' => 'a',
            'é' => 'e',
            'í' => 'i',
            'ó' => 'o',
            'ú' => 'u',
        ];
        return strtr($word, $conv);
    }

    public function exportReport(Request $request)
    {
        $writeType = \Maatwebsite\Excel\Excel::XLSX;
        if($request->get('formato') == 'pdf'){
            $writeType = \Maatwebsite\Excel\Excel::DOMPDF;
        }
        $data = $this->find($request);

        if(!empty($data->errors) && isset($data->errors) ) {
            return response()
                ->json([
                    'type' => 'error',
                    'message' => $data->message
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        if(!is_array($data) && $data->getData()->type =='error') {
            return response()
                ->json([
                    'type' => 'error',
                    'message' => $data->getData()->message
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }


        $data = $data['result']->resultados;
        return Excel::download(new SearchExport($data), 'palibdromeReport.pdf',$writeType);
    }

}
