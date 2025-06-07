@extends('layouts.admin')

@section('title', 'Buat Proyek Baru')
@section('page_title', 'Buat Proyek Channel Baru')

@push('styles')
<style>
    .ai-tooltip-popup {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 500px;
        background-color: white;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 1050;
        padding: 20px;
    }
    .ai-tooltip-popup .form-group {
        margin-bottom: 1rem;
    }
    .ai-tooltip-popup-backdrop {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
        z-index: 1040;
    }
    .ai-btn-container {
        position: relative;
    }
    .btn-ai-generate {
        position: absolute;
        top: 0;
        right: 0;
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    textarea.form-control {
        padding-right: 120px; /* Ruang untuk tombol AI */
    }
</style>
@endpush

@section('content')
<div class="card" id="project-form-card">
    <div class="card-header">
        <h3 class="card-title">Detail Proyek Channel</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('projects.store') }}" method="POST">
            @csrf
            
            {{-- BAGIAN 1: IDENTITAS CHANNEL --}}
            <p class="h3">BAGIAN 1: IDENTITAS CHANNEL</p>
            <hr class="mt-0 mb-4">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="channel_name_final" class="form-label required">Nama Channel Terpilih</label>
                    <input type="text" name="channel_name_final" id="channel_name_final" class="form-control" placeholder="Nama Channel Final Pilihan Anda" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="business_email" class="form-label">Email Kontak Bisnis Channel</label>
                    <input type="email" name="business_email" id="business_email" class="form-control" placeholder="Alamat Email Bisnis Anda">
                </div>
                <div class="col-12 mb-3">
                    <label class="form-label">Link Channel YouTube</label>
                    <input type="url" name="youtube_channel_link" class="form-control" placeholder="URL Channel (setelah dibuat)">
                </div>
                
                <div class="col-md-4 mb-3">
                    <label class="form-label">Handle X/Twitter</label>
                    <input type="text" name="social_handle_twitter" class="form-control" placeholder="@handle_x">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Handle Threads</label>
                    <input type="text" name="social_handle_threads" class="form-control" placeholder="@handle_threads">
                </div>
            </div>

            {{-- BAGIAN 2: FONDASI & VISI --}}
            <p class="h3 mt-4">BAGIAN 2: FONDASI & VISI</p>
            <hr class="mt-0 mb-4">
            <div class="row">
                <div class="col-12 mb-3 ai-btn-container">
                    <label for="channel_description" class="form-label">Deskripsi Singkat Channel (Elevator Pitch)</label>
                    <textarea name="channel_description" id="channel_description" class="form-control" rows="3"></textarea>
                    <button type="button" class="btn btn-sm btn-outline-primary btn-ai-generate" data-target="channel_description" data-prompt="Buatkan deskripsi singkat (elevator pitch) 1-2 kalimat untuk channel YouTube.">Generate with AI</button>
                </div>
                <div class="col-12 mb-3 ai-btn-container">
                    <label for="long_term_vision" class="form-label">Visi Jangka Panjang Channel</label>
                    <textarea name="long_term_vision" id="long_term_vision" class="form-control" rows="3"></textarea>
                    <button type="button" class="btn btn-sm btn-outline-primary btn-ai-generate" data-target="long_term_vision" data-prompt="Buatkan visi jangka panjang (3-5 tahun) untuk channel YouTube.">Generate with AI</button>
                </div>
                <div class="col-12 mb-3 ai-btn-container">
                    <label for="channel_mission" class="form-label">Misi Channel</label>
                    <textarea name="channel_mission" id="channel_mission" class="form-control" rows="3" placeholder="Contoh: 1. Melakukan... 2. Menyediakan..."></textarea>
                    <button type="button" class="btn btn-sm btn-outline-primary btn-ai-generate" data-target="channel_mission" data-prompt="Buatkan 3 poin misi untuk mencapai visi channel.">Generate with AI</button>
                </div>
            </div>

            {{-- BAGIAN 3: TARGET AUDIENS --}}
            <p class="h3 mt-4">BAGIAN 3: TARGET AUDIENS</p>
            <hr class="mt-0 mb-4">
            <div class="row">
                <div class="col-md-6 mb-3 ai-btn-container">
                    <label for="primary_audience_persona" class="form-label">Persona Audiens Utama</label>
                    <textarea name="primary_audience_persona" id="primary_audience_persona" class="form-control" rows="6" placeholder="Nama Persona:&#10;Usia:&#10;Pekerjaan:&#10;Masalah:&#10;Tujuan:"></textarea>
                    <button type="button" class="btn btn-sm btn-outline-primary btn-ai-generate" data-target="primary_audience_persona" data-prompt="Buatkan profil persona audiens utama untuk channel YouTube bertema produktivitas AI.">Generate with AI</button>
                </div>
                <div class="col-md-6 mb-3 ai-btn-container">
                    <label for="secondary_audience_persona" class="form-label">Persona Audiens Sekunder</label>
                    <textarea name="secondary_audience_persona" id="secondary_audience_persona" class="form-control" rows="6" placeholder="Nama Persona:&#10;Usia:&#10;Pekerjaan:&#10;Masalah:&#10;Tujuan:"></textarea>
                    <button type="button" class="btn btn-sm btn-outline-primary btn-ai-generate" data-target="secondary_audience_persona" data-prompt="Buatkan profil persona audiens sekunder (misal: mahasiswa) untuk channel YouTube bertema produktivitas AI.">Generate with AI</button>
                </div>
            </div>

            {{-- BAGIAN 4: ANALISIS NICHE & USP --}}
            <p class="h3 mt-4">BAGIAN 4: ANALISIS NICHE & USP</p>
            <hr class="mt-0 mb-4">
            <div class="row">
                <div class="col-md-6 mb-3 ai-btn-container">
                    <label for="main_niche" class="form-label">Niche Utama & Sub-Niche</label>
                    <textarea name="main_niche" id="main_niche" class="form-control" rows="4" placeholder="Niche Utama:&#10;Sub-Niche 1:&#10;Sub-Niche 2:"></textarea>
                    <button type="button" class="btn btn-sm btn-outline-primary btn-ai-generate" data-target="main_niche" data-prompt="Tentukan niche utama dan beberapa sub-niche spesifik untuk channel ini.">Generate with AI</button>
                </div>
                <div class="col-md-6 mb-3 ai-btn-container">
                    <label for="unique_selling_proposition" class="form-label">Unique Selling Proposition (USP)</label>
                    <textarea name="unique_selling_proposition" id="unique_selling_proposition" class="form-control" rows="4" placeholder="Apa yang membuat channel ini berbeda?&#10;- Perspektif Praktisi...&#10;- Fokus pada Eksperimen..."></textarea>
                    <button type="button" class="btn btn-sm btn-outline-primary btn-ai-generate" data-target="unique_selling_proposition" data-prompt="Buatkan 3-4 poin Unique Selling Proposition (USP) untuk channel ini.">Generate with AI</button>
                </div>
            </div>

            {{-- BAGIAN 5: STRATEGI KONTEN AWAL --}}
            <p class="h3 mt-4">BAGIAN 5: STRATEGI KONTEN AWAL</p>
            <hr class="mt-0 mb-4">
            <div class="row">
                <div class="col-md-6 mb-3 ai-btn-container">
                    <label for="content_pillars" class="form-label">Pilar Konten</label>
                    <textarea name="content_pillars" id="content_pillars" class="form-control" rows="5" placeholder="Pilar 1:&#10;Pilar 2:&#10;Pilar 3:"></textarea>
                    <button type="button" class="btn btn-sm btn-outline-primary btn-ai-generate" data-target="content_pillars" data-prompt="Buatkan 3-4 pilar konten utama untuk channel ini.">Generate with AI</button>
                </div>
                <div class="col-md-6 mb-3 ai-btn-container">
                    <label for="initial_video_ideas" class="form-label">Ide Judul Video Awal</label>
                    <textarea name="initial_video_ideas" id="initial_video_ideas" class="form-control" rows="5" placeholder="- Saya mencoba...&#10;- 5 Tools AI...&#10;- Bisakah AI mengalahkan saya..."></textarea>
                    <button type="button" class="btn btn-sm btn-outline-primary btn-ai-generate" data-target="initial_video_ideas" data-prompt="Berikan 5 ide judul video yang clickbait tapi tetap relevan.">Generate with AI</button>
                </div>
            </div>

            {{-- BAGIAN 6: BRANDING VISUAL & AUDIO --}}
            <p class="h3 mt-4">BAGIAN 6: BRANDING VISUAL & AUDIO</p>
            <hr class="mt-0 mb-4">
            <div class="row">
                <div class="col-md-6 mb-3 ai-btn-container">
                    <label for="logo_concept" class="form-label">Konsep Logo & Banner</label>
                    <textarea name="logo_concept" id="logo_concept" class="form-control" rows="4" placeholder="Logo: Simbol otak dengan sirkuit...&#10;Banner: Menampilkan nama channel, tagline..."></textarea>
                    <button type="button" class="btn btn-sm btn-outline-primary btn-ai-generate" data-target="logo_concept" data-prompt="Berikan ide konsep untuk logo dan banner channel.">Generate with AI</button>
                </div>
                 <div class="col-md-6 mb-3 ai-btn-container">
                    <label for="color_palette" class="form-label">Palet Warna & Font</label>
                    <textarea name="color_palette" id="color_palette" class="form-control" rows="4" placeholder="Warna Primer: #...&#10;Warna Sekunder: #...&#10;Font: Montserrat"></textarea>
                    <button type="button" class="btn btn-sm btn-outline-primary btn-ai-generate" data-target="color_palette" data-prompt="Sarankan palet warna (dengan kode HEX) dan 1-2 jenis font yang modern dan mudah dibaca.">Generate with AI</button>
                </div>
            </div>

            {{-- BAGIAN 7: MONETISASI & TUJUAN AWAL --}}
            <p class="h3 mt-4">BAGIAN 7: MONETISASI & TUJUAN AWAL</p>
            <hr class="mt-0 mb-4">
            <div class="row">
                <div class="col-md-6 mb-3 ai-btn-container">
                    <label for="monetization_strategy" class="form-label">Strategi Monetisasi</label>
                    <textarea name="monetization_strategy" id="monetization_strategy" class="form-control" rows="4" placeholder="- Penjualan Produk Digital&#10;- Pemasaran Afiliasi&#10;- YouTube AdSense"></textarea>
                    <button type="button" class="btn btn-sm btn-outline-primary btn-ai-generate" data-target="monetization_strategy" data-prompt="Sebutkan 3 strategi monetisasi yang paling cocok untuk channel ini, urutkan berdasarkan prioritas.">Generate with AI</button>
                </div>
                <div class="col-md-6 mb-3 ai-btn-container">
                    <label for="kpi_targets_3_months" class="form-label">Target KPIs (3 Bulan Pertama)</label>
                    <textarea name="kpi_targets_3_months" id="kpi_targets_3_months" class="form-control" rows="4" placeholder="Subscriber: 100&#10;Total Views: 5.000&#10;Pendapatan Awal: Rp 500.000"></textarea>
                    <button type="button" class="btn btn-sm btn-outline-primary btn-ai-generate" data-target="kpi_targets_3_months" data-prompt="Buatkan target KPI yang realistis untuk 3 bulan pertama (Subscribers, Views, Watch Time).">Generate with AI</button>
                </div>
            </div>

            {{-- BAGIAN 8: ALUR KERJA PRODUKSI AI --}}
            <p class="h3 mt-4">BAGIAN 8: ALUR KERJA PRODUKSI AI</p>
            <hr class="mt-0 mb-4">
            <div class="row">
                <div class="col-12 mb-3 ai-btn-container">
                    <label for="ai_production_workflow" class="form-label">Gambaran Umum Alur Kerja Produksi</label>
                    <textarea name="ai_production_workflow" id="ai_production_workflow" class="form-control" rows="6" placeholder="Ideasi & Riset: Menggunakan ChatGPT...&#10;Penulisan Naskah: Draft awal dari Claude..."></textarea>
                    <button type="button" class="btn btn-sm btn-outline-primary btn-ai-generate" data-target="ai_production_workflow" data-prompt="Jelaskan alur kerja produksi konten dari ideasi hingga optimasi, sebutkan tools AI yang mungkin digunakan di setiap tahap.">Generate with AI</button>
                </div>
            </div>

            <div class="form-footer text-end mt-4">
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Proyek</button>
            </div>
        </form>
    </div>
</div>

{{-- AI Popup --}}
<div class="ai-tooltip-popup-backdrop" id="aiBackdrop"></div>
<div class="ai-tooltip-popup" id="aiPopup">
    <h4 id="aiPopupTitle">Generate Content with AI</h4>
    <div class="form-group">
        <label for="aiPrompt" class="form-label">Prompt</label>
        <textarea id="aiPrompt" class="form-control" rows="5"></textarea>
    </div>
    
    <div class="row">
        <div class="col-md-8">
            <div class="form-group">
                <label for="aiCreativity" class="form-label">Tingkat Kreativitas</label>
                <select id="aiCreativity" class="form-select">
                    <option value="0.2">Tepat & Konsisten</option>
                    <option value="0.7" selected>Seimbang</option>
                    <option value="1.0">Sangat Kreatif</option>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="aiSeed" class="form-label">Seed</label>
                <input type="number" id="aiSeed" class="form-control" placeholder="Acak">
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end mt-3">
        <button type="button" class="btn btn-secondary me-2" id="aiCancelBtn">Batal</button>
        <button type="button" class="btn btn-primary" id="aiSubmitBtn">
            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true" id="aiSpinner"></span>
            Generate
        </button>
    </div>
</div>
@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const aiPopup = document.getElementById('aiPopup');
    const aiBackdrop = document.getElementById('aiBackdrop');
    const aiPromptTextarea = document.getElementById('aiPrompt');
    const aiSubmitBtn = document.getElementById('aiSubmitBtn');
    const aiCancelBtn = document.getElementById('aiCancelBtn');
    const aiSpinner = document.getElementById('aiSpinner');
    const mainForm = document.getElementById('project-form-card');
    
    // Pengaturan AI
    const aiCreativitySelect = document.getElementById('aiCreativity');
    const aiSeedInput = document.getElementById('aiSeed');
    
    let currentTargetId = null;

    // Muat pengaturan AI dari localStorage saat halaman dimuat
    function loadAiSettings() {
        aiCreativitySelect.value = localStorage.getItem('ai_creativity') || '0.7';
        aiSeedInput.value = localStorage.getItem('ai_seed') || '';
    }

    // Simpan pengaturan AI ke localStorage
    function saveAiSettings() {
        localStorage.setItem('ai_creativity', aiCreativitySelect.value);
        localStorage.setItem('ai_seed', aiSeedInput.value);
    }

    loadAiSettings();

    document.querySelectorAll('.btn-ai-generate').forEach(button => {
        button.addEventListener('click', function () {
            currentTargetId = this.dataset.target;
            const defaultPrompt = this.dataset.prompt;
            aiPromptTextarea.value = defaultPrompt;
            aiPopup.style.display = 'block';
            aiBackdrop.style.display = 'block';
        });
    });

    function closeAiPopup() {
        aiPopup.style.display = 'none';
        aiBackdrop.style.display = 'none';
        aiSpinner.classList.add('d-none');
        aiSubmitBtn.disabled = false;
    }

    aiCancelBtn.addEventListener('click', closeAiPopup);
    aiBackdrop.addEventListener('click', closeAiPopup);

    aiSubmitBtn.addEventListener('click', function() {
        if (!currentTargetId) return;

        saveAiSettings(); // Simpan pengaturan setiap kali generate

        aiSpinner.classList.remove('d-none');
        this.disabled = true;

        // Kumpulkan semua field yang sudah diisi sebagai konteks
        let context = "Berikut adalah konteks dari proyek yang sedang dikerjakan:\n";
        const formElements = mainForm.querySelectorAll('input[type="text"], input[type="email"], textarea');
        let fieldFound = false;
        
        formElements.forEach(el => {
            if (el.value.trim() !== '' && el.id) {
                const label = document.querySelector(`label[for='${el.id}']`);
                if (label) {
                    context += `- ${label.textContent.trim()}: ${el.value.trim()}\n`;
                    fieldFound = true;
                }
            }
        });
        
        if (!fieldFound) {
            context = "Belum ada konteks yang diisi. Mulai dari awal.";
        }

        fetch('{{ route("ai.generate") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                prompt: aiPromptTextarea.value,
                context: context,
                creativity: aiCreativitySelect.value,
                seed: aiSeedInput.value
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById(currentTargetId).value = data.text;
                closeAiPopup();
            } else {
                alert('Error: ' + (data.message || 'Unknown error'));
                closeAiPopup();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An unexpected error occurred.');
            closeAiPopup();
        });
    });
});
</script>
@endpush
