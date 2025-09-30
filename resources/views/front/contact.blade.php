  @extends('layouts.master')
  @section('title', 'Contact')
  @section('content')
      <x-nav-property :about="$about" />
      <div class="site-section">
          <div class="container">
              <div class="row">

                  <div class="col-md-12 col-lg-8 mb-5">



                      <form method="POST" action="{{ route('front.customer.store') }}" class="p-5 bg-white border form-contact-agent">
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

       <!-- Modal HTML -->
    <div id="successModal" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <div class="modal-body">
                <div class="modal-icon">
                    <svg width="50" height="50" viewBox="0 0 60 60" fill="none">
                        <circle cx="30" cy="30" r="30" fill="#10B981" />
                        <path d="M16 30L26 40L44 22" stroke="white" stroke-width="4" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </div>
                <h3 class="modal-title">Berhasil!</h3>
                <p class="modal-message">Pesan berhasil dikirim, kami akan menghubungi kembali untuk konfirmasi</p>
                <button id="modalOkBtn" class="modal-btn">OK</button>
            </div>
        </div>
    </div>

    <!-- CSS untuk Modal -->
    <style>
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.3s ease;
        }

        .modal-content {
            background: white;
            border-radius: 12px;
            padding: 0;
            max-width: 400px;
            width: 90%;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            animation: slideUp 0.3s ease;
        }

        .modal-body {
            padding: 30px 25px;
            text-align: center;
        }

        .modal-icon {
            margin: 0 auto 15px;
            width: 50px;
            height: 50px;
        }

        .modal-icon svg {
            width: 50px;
            height: 50px;
        }

        .modal-title {
            font-size: 20px;
            font-weight: 700;
            color: #1f2937;
            margin: 0 0 10px 0;
        }

        .modal-message {
            font-size: 14px;
            color: #6b7280;
            line-height: 1.5;
            margin: 0 0 20px 0;
        }

        .modal-btn {
            background-color: #2563eb;
            color: white;
            border: none;
            padding: 10px 35px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%;
            max-width: 180px;
        }

        .modal-btn:hover {
            background-color: #1d4ed8;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                transform: translateY(30px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('.form-contact-agent');
            const modal = document.getElementById('successModal');
            const okBtn = document.getElementById('modalOkBtn');

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                // Ambil data form
                const formData = new FormData(form);

                // Kirim data menggunakan fetch API
                fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Tampilkan modal
                            modal.style.display = 'flex';
                        } else {
                            // Handle error jika diperlukan
                            alert('Terjadi kesalahan. Silakan coba lagi.');
                        }
                    })
                    .catch(error => {
                        // Jika terjadi error, tetap tampilkan modal (opsional)
                        modal.style.display = 'flex';
                    });
            });

            // Event listener untuk tombol OK
            okBtn.addEventListener('click', function() {
                // Refresh halaman
                window.location.reload();
            });

            // Tutup modal jika klik di luar modal content
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    window.location.reload();
                }
            });
        });
    </script>
  @endsection
