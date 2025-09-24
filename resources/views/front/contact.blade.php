  @extends('layouts.master')
  @section('title', 'Contact')
  @section('content')
      <div id="notification-container" class="notification-container">
          @if (session('success'))
              <div class="alert alert-success notification-alert" id="success-notification">
                  <i class="fas fa-check-circle me-2"></i>
                  {{ session('success') }}
              </div>
          @endif
          @if (session('error'))
              <div class="alert alert-danger notification-alert" id="error-notification">
                  <i class="fas fa-exclamation-circle me-2"></i>
                  {{ session('error') }}
              </div>
          @endif
      </div>
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
                                      placeholder="Email Address*" required>
                              </div>
                          </div>
                          <div class="row form-group">
                              <div class="col-md-12">
                                  <select name="property_id" id="select-city" class="form-control d-block rounded-0"
                                      required>
                                      <option value="" hidden disabled selected>Select property *</option>
                                      @foreach ($propertie as $properties)
                                          <option value="{{ $properties->id }}">{{ $properties->name }}</option>
                                      @endforeach
                                  </select>

                              </div>
                          </div>


                          <div class="row form-group">
                              <div class="col-md-12">

                                  <textarea name="message" id="message" cols="30" rows="5" class="form-control" placeholder="Message*"
                                      required></textarea>
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
      <x-footer-property :about="$about" />

      <style>
          .notification-container {
              position: fixed;
              top: 20px;
              right: 20px;
              z-index: 9999;
              width: 350px;
          }

          .notification-alert {
              margin-bottom: 10px;
              border-radius: 8px;
              box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
              border: none;
              padding: 12px 16px;
              font-size: 14px;
              animation: slideInRight 0.3s ease-out;
              position: relative;
              overflow: hidden;
          }

          .notification-alert.alert-success {
              background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
              color: #155724;
              border-left: 4px solid #28a745;
          }

          .notification-alert.alert-danger {
              background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
              color: #721c24;
              border-left: 4px solid #dc3545;
          }

          .notification-alert::before {
              content: '';
              position: absolute;
              bottom: 0;
              left: 0;
              height: 3px;
              background: #28a745;
              animation: progressBar 3s linear;
          }

          .notification-alert.alert-danger::before {
              background: #dc3545;
          }

          @keyframes slideInRight {
              from {
                  transform: translateX(100%);
                  opacity: 0;
              }

              to {
                  transform: translateX(0);
                  opacity: 1;
              }
          }

          @keyframes slideOutRight {
              from {
                  transform: translateX(0);
                  opacity: 1;
              }

              to {
                  transform: translateX(100%);
                  opacity: 0;
              }
          }

          @keyframes progressBar {
              from {
                  width: 100%;
              }

              to {
                  width: 0%;
              }
          }

          .notification-fade-out {
              animation: slideOutRight 0.3s ease-in forwards;
          }

          /* Responsive untuk mobile */
          @media (max-width: 768px) {
              .notification-container {
                  width: calc(100% - 40px);
                  right: 20px;
                  left: 20px;
              }

              .notification-alert {
                  font-size: 13px;
                  padding: 10px 14px;
              }
          }
      </style>

      <script>
          document.addEventListener('DOMContentLoaded', function() {
              // Auto hide notifications after 3 seconds
              const notifications = document.querySelectorAll('.notification-alert');

              notifications.forEach(function(notification) {
                  // Add click to close functionality
                  notification.style.cursor = 'pointer';
                  notification.addEventListener('click', function() {
                      hideNotification(this);
                  });

                  // Auto hide after 3 seconds
                  setTimeout(function() {
                      hideNotification(notification);
                  }, 3000);
              });

              function hideNotification(element) {
                  element.classList.add('notification-fade-out');
                  setTimeout(function() {
                      if (element && element.parentNode) {
                          element.parentNode.removeChild(element);
                      }
                  }, 300);
              }
          });
      </script>
  @endsection
