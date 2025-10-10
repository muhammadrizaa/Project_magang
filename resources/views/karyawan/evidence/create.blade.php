<x-karyawan-layout>
    <head>
        {{-- Pastikan Anda sudah memuat file CSS dan JS Dropzone di layout utama Anda --}}
        <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
        <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    </head>

    <style>
        .card { background-color: #fff; padding: 24px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .card-header { font-size: 1.25rem; font-weight: 600; color: #1f2937; border-bottom: 1px solid #e5e7eb; padding-bottom: 16px; margin-bottom: 24px; }
        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 500; color: #374151; }
        .form-group input, .form-group textarea { width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box; }
        .btn-submit { display: block; width: 100%; padding: 0.875rem; border: none; border-radius: 8px; background-color: #dc2626; color: white; font-weight: 600; cursor: pointer; transition: background-color 0.2s; }
        .btn-submit:disabled { background-color: #fca5a5; cursor: not-allowed; }
        .alert { padding: 1rem; border-radius: 8px; font-weight: 500; margin-bottom: 1.5rem; }
        .alert-danger { background-color: #fee2e2; color: #991b1b; border: 1px solid #f87171; white-space: pre-line; }
        .alert-success { background-color: #dcfce7; color: #166534; border: 1px solid #86efac; }
        
        /* Dropzone Styling */
        .dropzone { border: 2px dashed #dc2626; border-radius: 12px; background: #fee2e2; padding: 15px; transition: all 0.3s; min-height: 150px; display: flex; flex-wrap: wrap; justify-content: center; align-items: center; }
        .dropzone .dz-message { color: #b91c1c; }
        .dropzone .dz-preview { background: #fff; border-radius: 14px; border: 1px solid #e5e7eb; padding: 12px; margin: 12px; width: 220px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); display: flex; flex-direction: column; align-items: center; position: relative; }
        .dropzone .dz-preview .dz-image { width: 100%; height: 140px; margin-bottom: 10px; }
        .dropzone .dz-preview .dz-image img { width: 100%; height: 100%; object-fit: cover; border-radius: 12px; }
        .caption-input { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 8px; background: #f9fafb; text-align: center; font-size: 0.85rem; color: #1f2937; margin-bottom: 10px; }
        .remove-btn { position: absolute; top: -10px; right: -10px; background: #ef4444; color: white; border: none; border-radius: 50%; width: 28px; height: 28px; font-weight: bold; cursor: pointer; }
    </style>

    <div class="card">
        <h2 class="card-header">Upload Evidence</h2>
        
        <div id="notification-area" style="display: none;"></div>

        <form 
            action="{{ route('karyawan.evidence.store') }}" 
            id="evidence-form" 
            method="POST" 
            enctype="multipart/form-data">
            
            @csrf

            <div class="form-group">
                <label for="lokasi">Masukkan Lokasi Anda</label>
                <input type="text" id="lokasi" name="lokasi" placeholder="Contoh: Telkom STO Banjarmasin" required>
            </div>

            <div class="form-group">
                <label for="deskripsi">Deskripsi Umum (Opsional)</label>
                <textarea id="deskripsi" name="deskripsi" rows="3" placeholder="Deskripsi umum atau catatan tambahan..."></textarea>
            </div>

            <div class="form-group">
                <label>File Evidence</label>
                <div id="evidence-dropzone" class="dropzone">
                    <div class="dz-message" data-dz-message><span>Seret foto ke sini atau klik untuk memilih</span></div>
                </div>
            </div>

            <button type="button" id="submit-button" class="btn-submit" style="margin-top: 1rem;">Upload Evidence</button>
        </form>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (document.querySelector("#evidence-dropzone").dropzone) {
                Dropzone.forElement("#evidence-dropzone").destroy();
            }

            const previewTemplate = `
                <div class="dz-preview dz-file-preview">
                    <div class="dz-image"><img data-dz-thumbnail /></div>
                    <input type="text" name="caption[]" class="caption-input" placeholder="Deskripsi foto (opsional)...">
                    <button type="button" data-dz-remove class="remove-btn">X</button>
                    <div class="dz-error-message"><span data-dz-errormessage></span></div>
                </div>
            `;

            Dropzone.autoDiscover = false;
            let myDropzone = new Dropzone("#evidence-dropzone", { 
                url: "{{ route('karyawan.evidence.store') }}",
                paramName: "file",
                autoProcessQueue: false,
                uploadMultiple: true,
                parallelUploads: 10,
                maxFiles: 10,
                acceptedFiles: 'image/*',
                addRemoveLinks: false,
                previewTemplate: previewTemplate,
                
                init: function() {
                    const self = this;
                    const form = document.querySelector("#evidence-form");
                    const submitButton = document.querySelector("#submit-button");
                    const notificationArea = document.querySelector("#notification-area");

                    submitButton.addEventListener("click", function(e) {
                        e.preventDefault();
                        e.stopPropagation();

                        if (document.querySelector("#lokasi").value.trim() === "") {
                            notificationArea.innerHTML = `<div class="alert alert-danger">Lokasi wajib diisi!</div>`;
                            notificationArea.style.display = 'block';
                            return;
                        }

                        if (self.getQueuedFiles().length > 0) {
                            submitButton.disabled = true;
                            submitButton.innerText = 'Mengupload...';
                            self.processQueue();
                        } else {
                            notificationArea.innerHTML = `<div class="alert alert-danger">Mohon pilih minimal satu file gambar!</div>`;
                            notificationArea.style.display = 'block';
                        }
                    });

                    this.on("sendingmultiple", function(files, xhr, formData) {
                        formData.append("_token", form.querySelector('input[name="_token"]').value);
                        formData.append("lokasi", form.querySelector('#lokasi').value);
                        formData.append("deskripsi", form.querySelector('#deskripsi').value);

                        document.querySelectorAll('.dz-preview .caption-input').forEach(input => {
                            formData.append("caption[]", input.value);
                        });
                        
                        notificationArea.style.display = 'none';
                    });

                    this.on("successmultiple", function(files, response) {
                        notificationArea.innerHTML = `<div class="alert alert-success">Evidence berhasil di-upload!</div>`;
                        notificationArea.style.display = 'block';

                        form.querySelector('#lokasi').value = '';
                        form.querySelector('#deskripsi').value = '';
                        self.removeAllFiles(true);

                        submitButton.disabled = false;
                        submitButton.innerText = 'Upload Evidence';

                        setTimeout(() => {
                            notificationArea.style.display = 'none';
                        }, 3000);
                    });

                    this.on("errormultiple", function(files, response) {
                        let errorMessage = "Terjadi kesalahan:\n";
                        if (response.errors) {
                            for (let field in response.errors) {
                                errorMessage += `- ${response.errors[field].join(', ')}\n`;
                            }
                        } else {
                            errorMessage = response.message || "Gagal mengupload file. Ukuran file mungkin terlalu besar.";
                        }
                        
                        notificationArea.innerHTML = `<div class="alert alert-danger">${errorMessage.replace(/\n/g, '<br>')}</div>`;
                        notificationArea.style.display = 'block';
                        
                        submitButton.disabled = false;
                        submitButton.innerText = 'Upload Evidence';
                    });
                }
            });
        });
    </script>
    @endpush
</x-karyawan-layout>