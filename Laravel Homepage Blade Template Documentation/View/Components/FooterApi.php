<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class FooterApi extends Component
{
 public  $informacaoGeral;
    public  $envioEntrega;
    public  $contacto;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {


        $language = app()->getLocale();


        $client = new Client([
            'base_uri' => 'https://dashboard.solucao.be/',
        ]);
        // Retrieve the API key from the .env file
        $apiKey = env('PIMCORE_FOOTER_API');
        try {
            $response = $client->request('POST', 'https://dashboard.solucao.be/pimcore-graphql-webservices/footer', [
                'json' => [
                    'query' => '{
                          informacaoGeral: getFooter(id: 12, defaultLanguage: "'.$language.'"){
                                id
                                informacaoGeral
                                informacao
                                vantagens
                                servicos
                                orcamento
                              },
                              envioEntrega: getFooter(id: 12, defaultLanguage: "'.$language.'") {
                                envioEntregas
                                EnvioEntrega
                                PoliticaDePrivacidade
                                ModosDePagamento
                              },
                              contacto: getFooter(id: 12, defaultLanguage: "'.$language.'") {
                                id
                                contacto
                                email
                                address
                              }
                }',
                    'variables' => null
                ],
                'headers' => [
                    'Content-Type' => 'application/json',
                    'X-API-Key' => $apiKey
                ]
            ]);
            // Parse the response JSON data into a PHP array
            $data = json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
            // extract the homepage data from the response
            $this->informacaoGeral = $data['data']['informacaoGeral'];
            $this->envioEntrega = $data['data']['envioEntrega'];
            $this->contacto = $data['data']['contacto'];


        } catch (GuzzleException $e) {
            $this->informacaoGeral= null;;
            $this->envioEntrega = null;
            $this->contacto = null;;
        } catch (\JsonException $e) {
        }

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.footer-api', [
            'informacaoGeral' => $this->informacaoGeral,
            'envioEntrega' =>$this->envioEntrega,
            'contacto' => $this->contacto

        ]);
    }
}
