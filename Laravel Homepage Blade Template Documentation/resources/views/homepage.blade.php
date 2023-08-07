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






