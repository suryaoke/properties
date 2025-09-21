  @extends('layouts.master')
  @section('title', 'Contact')
  @section('content')

      <x-nav-property :about="$about" />
      <div class="site-section">
          <div class="container">
              <div class="row">

                  <div class="col-md-12 col-lg-8 mb-5">



                      <form method="POST" action="{{ route('front.customer.store') }}" class="p-5 bg-white border">
                          @csrf
                          <input type="hidden" value="{{ $about->phone }}" name="agen_phone">
                          <div class="row form-group">
                              <div class="col-md-12 mb-3 mb-md-0">

                                  <input type="text" id="fullname" name="name" class="form-control"
                                      placeholder="Full Name *" required>
                              </div>
                          </div>
                          <div class="row form-group">
                              <div class="col-md-12 mb-3 mb-md-0">

                                  <input type="text" id="phone" name="phone" class="form-control"
                                      placeholder="Your Phone *" required>
                              </div>
                          </div>
                          <div class="row form-group">
                              <div class="col-md-12">

                                  <input type="email" id="email" name="email" class="form-control"
                                      placeholder="Email Address">
                              </div>
                          </div>
                          <div class="row form-group">
                              <div class="col-md-12">
                                  <select name="property_id" id="select-city" class="form-control d-block rounded-0" required>
                                      <option value="" hidden disabled selected>Select property *</option>
                                      @foreach ($propertie as $properties)
                                          <option value="{{ $properties->id }}">{{ $properties->name }}</option>
                                      @endforeach
                                  </select>

                              </div>
                          </div>


                          <div class="row form-group">
                              <div class="col-md-12">

                                  <textarea name="message" id="message" cols="30" rows="5" class="form-control" placeholder="Messsage"></textarea>
                              </div>
                          </div>

                          <div class="row form-group">
                              <div class="col-md-12">
                                  <input type="submit" value="Send Message" class="btn btn-primary  py-2 px-4 rounded-0">
                              </div>
                          </div>


                      </form>
                  </div>

                  <div class="col-lg-4">
                      <div class="p-4 mb-3 bg-white">
                          <h3 class="h6 text-black mb-3 text-uppercase">Contact Perusahaan</h3>
                          <p class="mb-0 font-weight-bold">Address</p>
                          <p class="mb-4">{{ $about->address }}</p>

                          <p class="mb-0 font-weight-bold">Phone</p>
                          <p class="mb-4"><a href="#">{{ $about->phone }}</a></p>

                          <p class="mb-0 font-weight-bold">Email Address</p>
                          <p class="mb-0"><a href="#"> {{ $about->email }} </a></p>

                      </div>

                  </div>
              </div>
          </div>
      </div>

      <div class="site-section bg-light">
          <div class="container">
              <div class="row mb-5 justify-content-center">
                  <div class="col-md-7">
                      <div class="site-section-title text-center">
                          <h2>Our Agents</h2>
                          <p>{{ $about->deskripsi_agen }}</p>
                      </div>
                  </div>
              </div>
              <div class="row">


                  @forelse ($agen as $agens)
                      <div class="col-md-6 col-lg-4 mb-5 mb-lg-5">
                          <div class="team-member">

                              <img src="{{ Storage::url($agens->photo ?? '') }}" alt="Image"
                                  class="img-fluid rounded mb-4">

                              <div class="text">

                                  <h2 class="mb-2 font-weight-light text-black h4">{{ $agens->name }}</h2>
                                  <span class="d-block mb-3 text-white-opacity-05"> {{ $agens->phone }} </span>
                                  <p>{{ $agens->visi }}</p>
                                  <p>
                                      <a href="{{ $agens->fb }}" class="text-black p-2"><span
                                              class="icon-facebook"></span></a>
                                      <a href="{{ $agens->twitter }}" class="text-black p-2"><span
                                              class="icon-twitter"></span></a>
                                      <a href="{{ $agens->linkedin }}" class="text-black p-2"><span
                                              class="icon-linkedin"></span></a>
                                  </p>
                              </div>

                          </div>
                      </div>
                  @empty
                      <p>No agen.</p>
                  @endforelse



              </div>
          </div>
      </div>
      <x-footer-property :about="$about" />


  @endsection
