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

## Laravel Layout Blade HTML Code
File: resources/views/layouts/app.blade.php
```
<body>
    <div id="canvas">
        <div id="box_wrapper">
            <div class="container-fluid mudar-de-cores-home">
                <div class="row">
                    <div class="col-md  float-right home-page-social">
                        <div class="grey fontsize_12">
                            <div class="page_social_icons inline-block darklinks">
                                <a class="social-icon soc-facebook" href="{{$navSocial['facebook']['path']}}" title="Facebook" target="_blank"></a>
                                <a class="social-icon soc-linkedin" href="{{$navSocial['instagram']['path']}}" title="LinkedIn" target="_blank"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- template sections -->
            <section class="page_toplogo ds escuro-home table_section columns_margin_0" style="height:90px">
                <div class="container text-left meter-esquerda-nav encima-header">

                    <div class="row">
                        <div class="col text-left text-sm-left">
                            <a href="{{ url('/') }}" class="logo">
                                <img src="{{ asset('assets/images/logo.png') }}" class="logo-solucao" alt="solucao">
                            </a>
                        </div>
                    </div>
                </div>
            </section>
            <div class="page_header_wrapper affix-top-wrapper" style="height: 80px;">
                <header class="page_header header_gradient bordered_items affix-top">
                    <div class="container-fluid">
                        <div class="row tirar-margen-home">
                            <div class="col text-left">
                                <div class="row mainrow tirar-margem">
                                    <div class="col">
                                        <!-- main nav start -->
                                        <nav class="mainmenu_wrapper">
                                            <ul class="mainmenu nav sf-menu sf-js-enabled sf-arrows" style="touch-action: pan-y;">
                                                @foreach($generateNavMenu($navMenu) as $menuItem)
                                                    <li class="{{ $menuItem['active'] ? 'active' : '' }}">
                                                        <a href="{{ $menuItem['url'] }}">{{ $menuItem['text'] }}</a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </nav>
                                        <!-- eof main nav -->
                                        <!-- mobile -->
                                        <span class="toggle_menu text-left embaixo-toggle">
                                        </span>
                                        <!-- and mobile -->
                                    </div>
                                    <div class="col-auto text-right alinhagem">
                                        <ul class="inline-dropdown inline-block segunda-alinhagem">
                                            <li class="dropdown">
                                                <div class="search_form_trigger header-button ">
                                                    <i class="flaticon-magnifying-glass nav-branca"></i>
                                                </div>
                                            </li>
                                            <li class="dropdown">
                                                <a href="{{ url('contacto') }}" class="header-button">
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>
            </div>
        </div>
        {{$slot}}
        <footer class="page_footer theme_footer mais esquerda ds ms parallax section_padding_top_90 section_padding_bottom_50 smooth-goto" id="localizar">
            <x-footer-api > </x-footer-api>
        </footer>
    </div>
</body>
```
