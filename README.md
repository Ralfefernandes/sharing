# Homepage Banner HTML Code

This is the HTML code for the homepage banner. The banner is a slideshow that displays different images and text.
```
<div>
    <div class="flexslider" >
        <ul class="slides">
            @if(isset($homepageBanner))
                @foreach($homepageBanner as $headerTitle)
            <li>
                <img src="{{('https://dashboard.solucao.be')}}/{{ $headerTitle['energia']['fullpath'] }}" alt="{{ $headerTitle['energia']['fullpath']}}">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <div class="slide_description_wrapper">
                                <div class="slide_description">
                                    <div class="intro-layer" data-animation="slideExpandUp">
                                        <div class="cornered-block">
                                            <p class="big grey raleway text-uppercase bold">
                                            {{$headerTitle['titleEnergia']}}
                                            </p>

                                            <p class="grey fontsize_16">
                                                {{$headerTitle['shortText']}}
                                            </p>
                                            <a href="{{ $headerTitle['buttonSobreNos']['path'] }}" class="theme_button color1 topmargin_30">{{$headerTitle['buttonSobreNos']['text']}}</a>
                                            <div class="bottom-corners"></div>
                                        </div>
                                    </div>
                                </div>
                                <!-- eof .slide_description -->
                            </div>
                            <!-- eof .slide_description_wrapper -->
                        </div>
                        <!-- eof .col-* -->
                    </div>
                    <!-- eof .row -->
                </div>
                <!-- eof .container -->
            </li>

                @endforeach
            @endif

        </ul>
    </div>
</div>
```
This code is located at: ``` resources/views/components/home-page-header.blade.php ```

## HomePageHeader.php
This is the PHP code for the HomePageHeader component. The component fetches data from the PIMCORE API and renders the homepage banner.
```
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
```
The PHP code is located at ```resources/views/components/HomePageHeader.php```
