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
