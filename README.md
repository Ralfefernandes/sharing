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
