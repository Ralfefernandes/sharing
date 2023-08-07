<?php

namespace App\View\Components;

use Closure;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Layout extends Component
{
    public $navSocial;
    public $navMenu;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        // Add the processArray() function here
        function processArray($input)
        {
            $output = [];
            foreach ($input as $key => $value) {
                if (is_array($value)) {
                    $output[$key] = processArray($value);
                } else {
                    $output[$key] = htmlspecialchars($value);
                }
            }
            return $output;
        }
        $language = app()->getLocale();


        $client = new Client([
            'base_uri' => 'https://dashboard.solucao.be',
        ]);
        $layoutApi = env('PIMCORE_LAYOUT_API');
        $seoApi = env('PIMCORE_SEO');
        try {
            $responseNav = $client->request('POST', 'https://dashboard.solucao.be/pimcore-graphql-webservices/nav-menu', [
                'json' => [
                    'query' => '{
                     navSocial:	getNavigation(id:5, defaultLanguage: "'.$language.'"){
                                  id
                                    facebook{
                                      path
                                    }
                                    instagram{
                                      path
                                      text
                                    }
                                    },
                               getNavigation(id:5){
                                navMenu{
                                ... on fieldcollection_NavMenu{
                                  inicio
                                  over_ons
                                  vantagens
                                  servicos
                                  orcamento
                                  contacto

                                }
                              }
                              },

                }',
                    'variables' => null
                ],
                'headers' => [
                    'Content-Type' => 'application/json',
                    'X-API-Key' => $layoutApi,
                ]
            ]);
            // Parse the response JSON data into a PHP array
            $data = json_decode($responseNav->getBody(), true, 512, JSON_THROW_ON_ERROR);
            // extract the Navdata data from the response
            $this->navSocial = processArray($data['data']['navSocial']);
            $this->navMenu = processArray($data['data']['getNavigation']);

            $responseMeta = $client->request('POST', 'https://dashboard.solucao.be/pimcore-graphql-webservices/meta-description', [
                'json' => [
                  'query' => '{
                    getSeo(id: 30) {
                      metaTitle
                      metaDescription
                    }
                  }',
                  'variables' => null,
                ],
                'headers' => [
                  'Content-Type' => 'application/json',
                  'X-API-Key' =>    $seoApi,
                ],
            ]);
            // Parse the response JSON data into a PHP array
            $dataMeta = json_decode($responseMeta->getBody(), true, 512, JSON_THROW_ON_ERROR);
            // extract the meta description from the response
            $metaData = $dataMeta['data']['getSeo']['metaDescription'];

          } catch (GuzzleException $e) {
            $this->navSocial= null;
            $this->navMenu = null;
          } catch (\JsonException $e) {
            $this->navSocial= null;
            $this->navMenu = null;
          }

    }


    /**
     * to check whic NavLink is active
     */
    public function generateNavMenu($navMenu)
    {
        $currentPath = request()->path();

        $menuItems = [];
        foreach ($navMenu['navMenu'][0] as $navAddress => $navItem) {
            $url = strtolower($navItem); // Convert the text to lowercase and use it as the URL

            $isActive = $currentPath == $url;
            $menuItems[] = [
                'url' => $url,
                'text' => $navItem,
                'active' => $isActive,
            ];
        }

        return $menuItems;
    }



    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.layout', [
  'navSocial' => $this->navSocial,
  'navMenu' => $this->navMenu,
  'generateNavMenu' => fn($navMenu) => $this->generateNavMenu($navMenu),
]);
    }

}
