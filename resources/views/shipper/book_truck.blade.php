@section('css-section')
   
   <link rel="stylesheet" href="{{asset('public/css/parsley.css')}}">
    
@endsection

@extends('shipper.common.master')
@section('main-content')

<main>
        <!--? slider Area Start-->
        <div class="slider-area ">
            <div class="single-slider hero-overly slider-height2 d-flex align-items-center" data-background="{{asset('public/img/hero/about.jpg')}}">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="hero-cap">
                                <h2>Book Truck</h2>
                              <!--   <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                                        <li class="breadcrumb-item"><a href="#">Contact</a></li> 
                                    </ol>
                                </nav> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- slider Area End-->
        <!-- ================ contact section start ================= -->
        <section id="booktruck" class="contact-form-area section-bg  pt-115 pb-120 fix" data-background="{{asset('public/img/gallery/section_bg02.jpg')}}">
        <div class="container">
            <div class="row justify-content-end">
                <!-- Contact wrapper -->
                <div class="col-xl-8 col-lg-9">
                    <div class="contact-form-wrapper">
                        <!-- From tittle -->
                        <div class="row">
                            <div class="col-lg-12">
                                <!-- Section Tittle -->
                                <div class="section-tittle mb-50">
                                    <!-- <span>Get a Qote For Free</span> -->
                                    <h2>Book New Shipment</h2>
                                    <!-- <p>Brook presents your services with flexible, convenient and cdpose layouts. You can select your favorite layouts & elements for.</p> -->
                                </div>
                            </div>
                        </div>
                        <!-- form -->
                        <form action="{{route('shipperShowEnterBookDetails')}}" class="contact-form" data-parsley-validate="parsley"> 
                            <div class="row ">
                                <div class="col-lg-6 col-md-6">
                                    <div class="input-form">
                                        <label class="main-lable">Enter Pick-up Location</label>
                                        <input type="text" name="pickup" data-id="0" id="address_0" placeholder="Example: Dallas,Texs" class="pickup address" data-parsley-required="true">
                                            <input type="hidden" id="lat_0" name="lat_0">
                                            <input type="hidden" id="lng_0" name="lng_0">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="input-form">
                                        <label class="main-lable">Enter Drop Location</label>
                                        <input type="text" name="drop" id="address_1" class="address" data-id="1" placeholder="Example: New-your,NYC" data-parsley-required="true">
                                            <input type="hidden" id="lat_1" name="lat_1">
                                            <input type="hidden" id="lng_1" name="lng_1">
                                    </div>
                                </div>
                                <div class="map-info" id="map-info" style="height: 400px;border: 1px gray;">
                                            <!-- <img src="{{asset('public/images/map.png')}}"> -->
                                        <!-- <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2957.61495542656!2d-71.11884837482297!3d42.37699974237274!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89e377427d7f0199%3A0x5937c65cee2427f0!2sHarvard%20University!5e0!3m2!1sen!2sin!4v1597051632747!5m2!1sen!2sin" width="100%" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe> -->
                                    </div>
                                <div class="select-truck">
                                    <div class="radio-wrapper mb-30 mt-15">
                                        <label>Choose Truck:</label>
                                    </div>

                                    @foreach($truck as $truck)
                                    <div class="truck-{{$truck->id}} truck-sec">
                                        <input type="radio" class="custom-control-input" id="customCheck{{$truck->id}}"  name="vehicle_id" value="{{$truck->id}}">
                                        <label class="custom-control-label-truck-img" for="customCheck{{$truck->id}}">
                                            <img class="truck-selc-img" src="{{$truck->truck_img}}">
                                        </label>
                                        <label>{{$truck->truck_name}}</label>
                                    </div>
                                    @endforeach

                                </div>
                                <!-- Button -->
                                <div class="col-lg-12">
                                    <a href="javascript:void(0);" class="request-truck-find submit-btn" id="btnBook">Request a Truck</a>
                                </div>
                            </div>
                        </form> 
                    </div>
                </div>
            </div>
        </div>
    </section>
        <!-- ================ contact section end ================= -->
    </main>
@endsection


@section('js-section')

    <script src="{{asset('public/js/parsley.js')}}"></script>

    <script src="https://maps.googleapis.com/maps/api/js?regions=dz&key=AIzaSyDlGeSHx-FT_W9EK7FZsxsxeZDtgF3q8XU&type=address&libraries=places&callback=initMap"
        async defer>
    </script>

<script type="text/javascript">
  $(document).ready(function(){ 

    $('.contact-form').parsley(); 
     
    $("#btnBook").click(function(e){
        
        var form =  $('.contact-form');

        e.preventDefault();
        
        if(form.parsley().validate())
        {  

            if ($("input[name='vehicle_id']:checked").val()) {
               
                $('.contact-form').submit();
            }
            else {
                alert("Please Select Truck First");
            }
        }

    });
    
});


     var station_row = '0';
        
        $(document).on('keyup','.address', function(event){
            event.preventDefault();
            station_row = $(this).data('id');
            initMap();
        });

        function initMap() {
            var location = document.getElementById('address_'+station_row);
              var lat_lng = {lat: 42.3770, lng: 71.1167};  

            var opts = {
                componentRestrictions: {country: "dz"}
            };
            
            var autocomplete = new google.maps.places.Autocomplete(location, opts);

            // Listener for whenever input value changes            
            autocomplete.addListener('place_changed', function() {

              // Get place info
              var place = autocomplete.getPlace();

              // Do whatever with the value!
              $('#lat_'+station_row).val(place.geometry.location.lat());
              $('#lng_'+station_row).val(place.geometry.location.lng());
            });


            var directionsService = new google.maps.DirectionsService;    
            var directionsDisplay = new google.maps.DirectionsRenderer;    
            var map = new google.maps.Map(document.getElementById('map-info'), {    
            zoom: 4,    
            center: lat_lng    
            });    
            directionsDisplay.setMap(map);    

            var onChangeHandler = function() {    
            calculateAndDisplayRoute(directionsService, directionsDisplay);    
            };    
            document.getElementById('address_0').addEventListener('focusout', onChangeHandler);    
            document.getElementById('address_1').addEventListener('focusout', onChangeHandler);    
        }

        function calculateAndDisplayRoute(directionsService, directionsDisplay) {    
              directionsService.route({    
                origin: document.getElementById('address_0').value,    
                destination: document.getElementById('address_1').value,    
                travelMode: google.maps.TravelMode.DRIVING    
              }, function(response, status) {    
                if (status === google.maps.DirectionsStatus.OK) {    
                  directionsDisplay.setDirections(response);  

                } else {    
                  // window.alert('Request for getting direction is failed due to ' + status);    
                }    
              });    
            }    

  

// end ready function
</script>
@endsection
