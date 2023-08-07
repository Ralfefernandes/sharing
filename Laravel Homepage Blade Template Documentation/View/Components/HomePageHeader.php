<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;


class HomePageHeader extends Component
{
    /**
     * @var array
     */
    public $homepageBanner;
    public $fullUrl;



    /**
     * HomePageHeader constructor.
     */
    public function __construct()
    {

        $language = \App::getLocale();
        $client = new Client([
            'base_uri' => 'https://dashboard.solucao.be/',
        ]);
        // Retrieve the API key from the .env file
        $apiKey = env('PIMCORE_API_KEY');
        try {
            $response = $client->request('POST', 'https://dashboard.solucao.be/pimcore-graphql-webservices/homepageheader', [
                'json' => [
                    'query' => '{
                        getHomepage(id: 3, defaultLanguage: "'.$language.'") {
                            headerBlock{
                                energia{
                                    id
                                    fullpath
                                },
                                titleEnergia
                                shortText
                                buttonSobreNos{
                                    __typename
                                    path
                                    text
                                }
                            }
                        },
                        nossosProdutos: getHomepage(id: 3, defaultLanguage: "'.$language.'"){
                            id
                            nossosProdutos
                            shortTextNossosProdutos
                            somosParte
                        },
                        servicosIcons: getHomepage(id: 3)
                        {
                                servicosIcons{
                                iconsLink{
                                    path
                                }
                            }
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
            // Extract the homepage data from the response
            $homepageBanner = $data['data']['getHomepage']['headerBlock'];
            $linkIcon = $data['data']['servicosIcons'];

            // concatenate the API base URL with the relative path to the image file

            if (isset($homepageBanner['energia'])) {
                $imageUrl = $homepageBanner['energia'][0]['fullpath'];

                $apiUrl = 'https://dashboard.solucao.be';
                $fullUrl = $apiUrl . $imageUrl;
                $homepageBanner['energia'][0]['fullpath'] = $fullUrl;
                $linkIcon['servicosIcons'][0]['path'];
              }
            // Add the full image Url to the homepageBanner array
            $this->homepageBanner = $homepageBanner;


        } catch (GuzzleException $e) {
            $this->homepageBanner = null;

        }
    }
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.home-page-header', [
            'homepageBanner' => $this->homepageBanner,
        ]);
    }
}
