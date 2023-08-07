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
File: ``` resources/views/components/layout.blade.php ```
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

## Layout.php
```
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

```
The file is located at ```View/Components/Layout.php``` and is responsible for the ```layout.blade.php``` view file.

## Laravel homepage Blade HTML Code
The file is located at ```resources/views/homepage.blade.php```
```
<x-layout>
    <section class="intro_section page_mainslider ds">
            <x-home-page-header homepage-banner="$homepage"></x-home-page-header>
    </section>
    <section class="homepage">
        <!-- Bootstrap Gallery nossos parceiros -->
        <section class="ls section_padding_110 columns_margin_0">
            <div class="container">
                <div class="row">
                    {{--   Voordelen section --}}
                    <div class="col-md-4 text-center text-md-left to_animate" data-animation="fadeInLeft">
                        <div class="cornered-heading bottommargin_60">
                            <h2 class="text-uppercase">{{ $nossasVantagens['vantagenTitle'] }}</h2>
                            <span class="text-uppercase">{{ $nossasVantagens['shortVarias'] }}</span>
                        </div>
                        <p>{{ $nossasVantagens['pagragraphPodemos'] }}</p>
                    </div>
                    {{--   End voordelen section --}}

                    @php
                        $data = $vantagensCarousel[0] ?? [];
                        $totalItems = count($data);
                        $itemsLeft = min(3, $totalItems);
                        $itemsRight = $totalItems - $itemsLeft;
                    @endphp
                    {{--  The 6 items --}}
                    @foreach([0 => $itemsLeft, $itemsLeft => $itemsRight] as $start => $count)
                        <div class="col-md-4 col-sm-6 {{ $start == $itemsLeft ? 'ml-auto' : '' }}">
                            @for($i = $start; $i < $start + $count; $i++)
                                @if(isset($data[$i]))
                                    <div class="teaser media inline-block topmargin_50 features-teaser to_animate" data-animation="pullDown">
                                        <div class="media-left media-middle">
                                            <div class="teaser_icon fontsize_36 grey with_background amarelinha-home">
                                                @if(isset($data[$i]['vantagensIcon']['path']))
                                                    <i class="{{ $data[$i]['vantagensIcon']['path'] }}"></i>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="media-body media-middle text-left">
                                            <h5 class="text-uppercase">
                                                @if(isset($data[$i]['titleLink']['path']) && isset($data[$i]['title']))
                                                    <a href="{{ $data[$i]['titleLink']['path'] }}">{{ $data[$i]['title'] }}</a>
                                                @endif
                                            </h5>
                                            @if(isset($data[$i]['shortTitle']))
                                                <p>{{ $data[$i]['shortTitle'] }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endfor
                        </div>
                    @endforeach
                    {{--  End the 6 items --}}

                </div>
            </div>
        </section>

                    {{--  Onze diensten  section--}}
        <section class="section_padding_110 fotos-de-servicos">
            <div class="container">
                <div class="row home-em-cima">
                    <div class="col-sm-6 col-md-5 col-lg-6 transparente espaco-esquerdo-home">
                        <div class="cornered-heading bottommargin_60 home-em-baixo">
                            <h2 class="text-uppercase amplo-aspecto-home">{{ $nossosProdutos['titleServicos'] }}</h2>
                            <p class="text-uppercase amplo-aspecto-home">{{ $nossosProdutos['shortTest'] }}</p>
                        </div>
                        <p class="bottommargin_0 embaixo-home text-branco">
                            {{ $nossosProdutos['paragraph'] }}
                        </p>
                        <div class="row columns_margin_0 service-teasers-row icones-homepage-grande">
                            @foreach($servicosIcons['servicosIcons'] as $servicoIcon)
                                <div class="col-sm-4 col-xs-6" data-animation="pullUp">
                                    <div class="service-teaser transparente with_corners hover_corners small_corners topmargin_30">
                                        <div class="teaser_content">
                                            <div class="teaser developer">
                                                <div class="1teaser_icon grey size_normal">
                                                    <img src="https://dashboard.solucao.be/{{ $servicoIcon['icon']['thumbnail'] }}" class="" alt="">
                                                </div>
                                                <h6 class="text-uppercase cores-brancas raleway bold darklinks bottommargin_0 texto-branco">
                                                    <a class="texto-branco" href="{{ $servicoIcon['iconsLink']['path'] }}">{{ $servicoIcon['title'] }}</a>
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
                {{-- End onze dienst section  --}}
    </section>
</x-layout>
```
The file is located at ```View/Components/Layout.php``` and is responsible for the ```layout.blade.php``` view file.

## Laravel HomePageController Code
The file is located at ``` Http/Controllers/HomepageController.php```
```
<?php

namespace App\Http\Controllers;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use Exception;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends Controller
{
    /**
     * @throws GuzzleException
     * @Route("/joao")
     * @throws Exception
     */
    public function index(Request $request)
    {
        $client = new Client([
            'base_uri' => 'https://dashboard.solucao.be/',
        ]);

        $language = app()->getLocale();

        $homepageApi = env('PIMCORE_HOMEPAGE_API');
        try {
            $responseHomepage = $client->request('POST', 'https://dashboard.solucao.be/pimcore-graphql-webservices/homepageheader', [
                'json' => [
                    'query' => '{

                        nossosProdutos: getHomepage(id: 3, defaultLanguage: "'.$language.'"){
                        id
                        nossosProdutos
                        shortTextNossosProdutos
                        somosParte
                            titleServicos
                            shortTest
                            paragraph

                      },
                    homePaineis: getHomepage(id: 3, defaultLanguage: "'.$language.'"){
                        bgTeaser {
                          ... on fieldcollection_BgTeaser {
                            bgTeaserIcon {
                              path
                            }
                            saibaMais {
                              path
                              __typename
                            }
                            title
                            shortText
                          }
                        }
                      },
                    nossoBrand: getHomepage(id: 3, defaultLanguage: "'.$language.'"){
                    titleMarcas
                    propocionamos
                    trabalhamos
                        verMais    {
                        path
                                    }
                              owlCarousel{
                                image{
    			                fullpath
    			                filename
                                }
                }
                    }
                   nossasVantagens: getHomepage(id: 3, defaultLanguage: "'.$language.'") {
                      vantagenTitle
                    shortVarias
                    pagragraphPodemos
                   }

                   vantagensCarousel: getHomepage(id: 3, defaultLanguage: "'.$language.'") {
                            vantagens{
                              title

                              shortTitle
                                  vantagensIcon{
                                    text
                                    path
                                    __typename
                                  },
                                  titleLink{
                                    path
                                    text
                                  }
                            }
                   }
                     servicosIcons: getHomepage(id: 3, defaultLanguage: "'.$language.'"){
                        servicosIcons{
                          iconsLink{
                            path
                        }
                          icon{
                          thumbnail: fullpath(thumbnail: "homepageheader", format: "webp")
                            fullpath
                          }
                          title
                        }
                        title
                        shortFaca

                      },


                   footerRodape: getHomepage(id: 3, defaultLanguage: "'.$language.'"){
                        rodapeSlider{
                            image{
                                id
                                filename
                                thumbnail: fullpath(thumbnail: "homepageheader", format: "webp")
                            }
                        }
                   }

            }',
                    'variables' => null
                ],
                'headers' => [
                    'Content-Type' => 'application/json',
                    'X-API-Key' => $homepageApi
                ]

            ]);

            // Parse the response JSON data into a PHP array
            $data = json_decode($responseHomepage->getBody(), true, 512, JSON_THROW_ON_ERROR);
            $nossosProdutos = $data['data']['nossosProdutos'];
            $homePaineis = $data['data']['homePaineis'];
            $nossoBrand = $data['data']['nossoBrand'];
            $owlCarousel = $nossoBrand['owlCarousel'];
            $nossasVantagens = $data['data']['nossasVantagens'];
            $vantagensCarousel = $data['data']['vantagensCarousel'];
            $servicosIcons = $data['data']['servicosIcons'];
            $footerRodape = $data['data']['footerRodape'];

            // Extract the homepage data from the response

            //Footer data

            $filted =[];
            foreach ($vantagensCarousel as $vantagemItem){
                $filted[] = $vantagemItem;


            }
            return view('homepage', [
                'nossosProdutos' => $nossosProdutos,
                'homePaineis' => $homePaineis,
                'nossoBrand' => $nossoBrand,
                'owlCarousel' => $owlCarousel,
                'nossasVantagens' =>$nossasVantagens,
                'vantagensCarousel' => $filted,
                'servicosIcons' => $servicosIcons,
                'footerRodape' => $footerRodape
            ]);



        } catch (Exception $e) {
            // Handle exception
        }
    }
}
```


