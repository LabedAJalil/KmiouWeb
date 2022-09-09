@extends('transporter.common.master')
@section('main-content')

 <main>
        <!--? slider Area Start-->
        <div class="slider-area ">
            <div class="single-slider hero-overly slider-height2 d-flex align-items-center" data-background="{{asset('public/img/hero/about.jpg')}}">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="hero-cap">
                                <h2>Profile</h2>
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
        <section class="contact-section">
            <div class="container">
                <div class="main-body">
                        <div class="page-wrapper">
                            <!-- [ Main Content ] start -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="page-header-title">
                                        <h5 class="m-b-10">Personal Info</h5>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="card Recent-Users">
                                        <div class="card-block px-0 py-3">
                                            <div class="card-info-shipp">
                                                <form method="POST" id="signup_form" action="{{ route('transporterUpdateProfile') }}" enctype="multipart/form-data">
                                                     @csrf
                                                
                                                 <!-- Alert Message -->  
                                                    <div class="flash-message">
                                                      @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                                                        @if(Session::has('alert-' . $msg))
                                                        <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
                                                        {{ Session::forget('alert-' . $msg) }}
                                                         {{ Session::save() }}
                                                        @endif
                                                      @endforeach
                                                    </div>
                                                  <!-- Alert Message -->

                                                <div class="user-img">
                                                    <label class="det-label">Profile Pic: </label>
                                                    
                                                    <img class="profile-logo profile_pic" 
                                                    @if($user->profile_pic != null && $user->profile_pic != '')
                                                    src="{{$user->profile_pic}}"
                                                    @else
                                                    src="{{asset('public/images/user.png')}}"
                                                    @endif
                                                    >

                                                    <input type="file" class="profile_pic_input" name="profile_pic" value="{{$user->profile_pic}}" placeholder="choose document" style="padding-left: 40px;" accept=".jpg,.jpeg,.png">
                                                </div>
                                                <!-- <div class="user-name">
                                                    <p>Alex Christano</p>
                                                </div> -->
                                                <div class="pickup-info edit-info">
                                                    <label class="det-label">First Name: </label>
                                                    <input type="text" name="first_name" value="{{$user->first_name}}"  class="form-control edit-pro" placeholder="Enter here">
                                                </div>
                                                <div class="pickup-info edit-info">
                                                    <label class="det-label">Last Name: </label>
                                                    <input type="text" name="last_name" value="{{$user->last_name}}"  class="form-control edit-pro" placeholder="Enter here">
                                                </div>
                                                <div class="pickup-info edit-info">
                                                    <label class="det-label">Mobile No: </label>
                                                    <input type="text" name="mobile_no" value="{{$user->mobile_no}}"  class="form-control edit-pro" placeholder="Enter here">
                                                </div>
                                                <div class="pickup-info edit-info">
                                                    <label class="det-label">Email: </label>
                                                    <input type="email" value="{{$user->email}}"  class="form-control edit-pro" placeholder="Enter here" readonly="" disabled="">
                                                </div>
                                                
                                                <div class="pickup-info edit-info">
                                                    <label class="det-label">Push Notification: </label>
                                                <select class="form-control noti-sel" id="exampleFormControlSelect1" name="push_notification" required>
                                                    @if($user->push_notification == '1')
                                                    <option value="1" selected>Yes</option>
                                                    <option value="0">No</option>
                                                    @else
                                                    <option value="1">Yes</option>
                                                    <option value="0" selected>No</option>
                                                    @endif
                                                </select>
                                                </div>
                                                <!-- <div class="view-more">
                                                    <a href="view">View More</a>
                                                </div> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            
                                <!-- Modal -->
                                    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" >
                                      <div class="modal-dialog modal-dialog-centered doc-modal" role="document">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                              <span aria-hidden="true">&times;</span>
                                            </button>
                                          </div>
                                          <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-12 driv-block">
                                                    <img class="upload-doc-modl" src="{{asset('public/img/doc.jpg')}}" id="ModalImage">
                                                    <!-- <p class="user-mob">+1 273 122 12</p> -->
                                                </div>
                                            </div>
                                          </div>
                                          <!-- <div class="modal-footer text-center">
                                            <button type="button" class="btn btn-primary">Select</button>
                                          </div> -->
                                        </div>
                                      </div>
                                    </div>
                                
                                <!-- modal end -->

                                <div class="col-md-12">
                                    <div class="page-header-title">
                                        <h5 class="m-b-10">Company Info</h5>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="card Recent-Users">
                                        <div class="card-block px-0 py-3">
                                            <div class="card-info-shipp">
                                                <!-- <div class="user-name">
                                                    <p>Alex Christano</p>
                                                </div> -->
                                                <div class="pickup-info edit-info">
                                                    <label class="det-label">Company Name: </label>
                                                    <input type="text" name="company_name" value="{{$user->company_name}}" class="form-control edit-pro" placeholder="Enter here">
                                                </div>
                                                <div class="pickup-info edit-info">
                                                    <label class="det-label">Carrier number: </label>
                                                    <input type="text" name="carrier_number" value="{{$user->carrier_number}}"  class="form-control edit-pro" placeholder="Enter here">
                                                </div>

                                                <div class="pickup-info edit-info">
                                                    <label class="det-label">Address: </label>
                                                    <input type="text" name="address"  value="{{$user->address}}"  class="form-control edit-pro" placeholder="Enter here">
                                                </div>
                                                <div class="pickup-info edit-info">
                                                    <label class="det-label">Commercial Registration: </label>
                                                    @foreach($doc as $doc)
                                                        <a href="javascript:void(0);"><img class="upload-doc vehicle_document" src="{{$doc}}"></a>
                                                    @endforeach
                                                </div>
                                                <div class="pickup-info edit-info">
                                                    <label class="det-label">Owner ID: </label>
                                                    <input type="file" name="doc[]" class="form-control edit-pro" value="{{$user->doc}}" multiple="" accept=".jpg,.jpeg,.png">
                                                </div>
                                                </div>
                                                <!-- <div class="view-more">
                                                    <a href="view">View More</a>
                                                </div> -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="full-width text-center">
                                            <button class="btn btn-primary mb-4">Save</button>
                                        </div>
                                    </div>
                            </div>
                            <!-- [ Main Content ] end -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- ================ contact section end ================= -->
    </main>

@endsection

@section('js-section')

<script type="text/javascript">
    

        $(document).on("click",".vehicle_document",function(){
            
            var img = $(this).attr('src');
            
            $('#ModalImage').attr("src",img);

            $('#exampleModalCenter').modal('show');
        });


    function readURL(input) {

        if (input.files && input.files[0]) {
        var reader = new FileReader();

            reader.onload = function(e) {
            $('.profile_pic').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $(".profile_pic_input").change(function() {
        readURL(this);
    });

</script>

@endsection