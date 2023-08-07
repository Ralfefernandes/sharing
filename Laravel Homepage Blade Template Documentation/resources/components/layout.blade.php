<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

    <script type="text/javascript">
        (function(c,l,a,r,i,t,y){
            c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
            t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
            y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
        })(window, document, "clarity", "script", "gz7c8pmyjg");
    </script>

    <title>{{('SOLUCAO - Gedreven door de natuur')}}</title>
    <meta charset="utf-8">

    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('https://solucao.ao/webshop/pub/media/favicon/stores/1/favicon.png') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}" id="color-switcher-link">
    <link rel="stylesheet" href="{{ asset('assets/css/solucao.css') }}" id="">
    <link rel="stylesheet" href="{{ asset('assets/css/sobre-nos.css') }}" id="">
    <link rel="stylesheet" href="{{ asset('assets/css/vantagem.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/servicos.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/orcamento.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/contacto.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/media.css') }}" id="">
    <link rel="stylesheet" href="{{ asset('assets/css/animations.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome-pro/css/all.css') }}">
    <script src="{{ asset('assets/js/vendor/modernizr-2.6.2.min.js') }}"></script>

    <meta name="robots" content="index, follow">
    <meta name="MSNBot-Media" content="follow">
</head>

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
</html>
