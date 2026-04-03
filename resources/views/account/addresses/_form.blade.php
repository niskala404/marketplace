@php
    $isEdit = isset($address);
    $provinceValue = old('province', $address->province ?? '');
    $cityValue = old('city', $address->city ?? '');
    $districtValue = old('district', $address->district ?? '');
    $villageValue = old('village', $address->village ?? '');
    $latValue = old('latitude', $address->latitude ?? '-6.2000000');
    $lngValue = old('longitude', $address->longitude ?? '106.8166667');
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="font-semibold">Label</label>
        <input name="label" value="{{ old('label', $address->label ?? 'Rumah') }}" class="w-full rounded-xl border-slate-200" required>
        @error('label')<div class="text-rose-600 text-sm mt-1">{{ $message }}</div>@enderror
    </div>
    <div>
        <label class="font-semibold">Kode Pos</label>
        <input name="postal_code" value="{{ old('postal_code', $address->postal_code ?? '') }}" class="w-full rounded-xl border-slate-200" placeholder="Contoh: 40123">
        @error('postal_code')<div class="text-rose-600 text-sm mt-1">{{ $message }}</div>@enderror
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="font-semibold">Nama Penerima</label>
        <input name="recipient_name" value="{{ old('recipient_name', $address->recipient_name ?? '') }}" class="w-full rounded-xl border-slate-200" required>
        @error('recipient_name')<div class="text-rose-600 text-sm mt-1">{{ $message }}</div>@enderror
    </div>
    <div>
        <label class="font-semibold">No. HP</label>
        <input name="phone" value="{{ old('phone', $address->phone ?? '') }}" class="w-full rounded-xl border-slate-200" required>
        @error('phone')<div class="text-rose-600 text-sm mt-1">{{ $message }}</div>@enderror
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
    <div>
        <label class="font-semibold">Provinsi</label>
        <select id="provinceSelect" name="province" class="w-full rounded-xl border-slate-200" required>
            <option value="">Pilih Provinsi</option>
        </select>
        @error('province')<div class="text-rose-600 text-sm mt-1">{{ $message }}</div>@enderror
    </div>
    <div>
        <label class="font-semibold">Kota/Kabupaten</label>
        <select id="citySelect" name="city" class="w-full rounded-xl border-slate-200" required disabled>
            <option value="">Pilih Kota/Kabupaten</option>
        </select>
        @error('city')<div class="text-rose-600 text-sm mt-1">{{ $message }}</div>@enderror
    </div>
    <div>
        <label class="font-semibold">Kecamatan</label>
        <select id="districtSelect" name="district" class="w-full rounded-xl border-slate-200" required disabled>
            <option value="">Pilih Kecamatan</option>
        </select>
        @error('district')<div class="text-rose-600 text-sm mt-1">{{ $message }}</div>@enderror
    </div>
    <div>
        <label class="font-semibold">Kelurahan</label>
        <select id="villageSelect" name="village" class="w-full rounded-xl border-slate-200" required disabled>
            <option value="">Pilih Kelurahan</option>
        </select>
        @error('village')<div class="text-rose-600 text-sm mt-1">{{ $message }}</div>@enderror
    </div>
</div>

<div>
    <label class="font-semibold">Alamat Utama</label>
    <textarea name="full_address" rows="3" class="w-full rounded-xl border-slate-200" required placeholder="Nama jalan, nomor rumah, RT/RW">{{ old('full_address', $address->full_address ?? '') }}</textarea>
    @error('full_address')<div class="text-rose-600 text-sm mt-1">{{ $message }}</div>@enderror
</div>

<div>
    <label class="font-semibold">Detail Alamat (Patokan)</label>
    <textarea name="detail_address" rows="2" class="w-full rounded-xl border-slate-200" placeholder="Contoh: Rumah warna hijau, dekat masjid, gang kiri.">{{ old('detail_address', $address->detail_address ?? '') }}</textarea>
    @error('detail_address')<div class="text-rose-600 text-sm mt-1">{{ $message }}</div>@enderror
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="font-semibold">Latitude</label>
        <input id="latInput" name="latitude" value="{{ $latValue }}" class="w-full rounded-xl border-slate-200" required readonly>
        @error('latitude')<div class="text-rose-600 text-sm mt-1">{{ $message }}</div>@enderror
    </div>
    <div>
        <label class="font-semibold">Longitude</label>
        <input id="lngInput" name="longitude" value="{{ $lngValue }}" class="w-full rounded-xl border-slate-200" required readonly>
        @error('longitude')<div class="text-rose-600 text-sm mt-1">{{ $message }}</div>@enderror
    </div>
</div>

<div>
    <label class="font-semibold">Cari Lokasi</label>
    <div class="flex gap-2">
        <input id="mapSearchInput" class="w-full rounded-xl border-slate-200" placeholder="Cari alamat/lokasi...">
        <button type="button" id="mapSearchButton" class="px-4 py-2 rounded-xl bg-slate-900 text-white font-semibold">Cari</button>
    </div>
    <div class="text-xs text-slate-500 mt-1">Klik lokasi pada peta untuk memperbarui titik koordinat.</div>
</div>

<div id="addressMap" class="h-80 rounded-2xl border border-slate-200 overflow-hidden"></div>

<div>
    <label class="font-semibold">RajaOngkir City ID (opsional)</label>
    <input name="rajaongkir_city_id" value="{{ old('rajaongkir_city_id', $address->rajaongkir_city_id ?? '') }}" class="w-full rounded-xl border-slate-200" placeholder="contoh: 39">
    <div class="text-slate-500 text-sm mt-1">Biarkan kosong jika ongkir menggunakan skema demo shipping rate internal.</div>
    @error('rajaongkir_city_id')<div class="text-rose-600 text-sm mt-1">{{ $message }}</div>@enderror
</div>

<label class="inline-flex items-center gap-2">
    <input type="checkbox" name="is_default" value="1" {{ old('is_default', $address->is_default ?? false) ? 'checked' : '' }}>
    <span class="font-semibold">Jadikan default</span>
</label>

@once
    @push('scripts')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    @endpush
@endonce

@push('scripts')
<script>
(async function () {
    const regionApi = 'https://www.emsifa.com/api-wilayah-indonesia/api';
    const oldValues = {
        province: @json($provinceValue),
        city: @json($cityValue),
        district: @json($districtValue),
        village: @json($villageValue),
    };

    const provinceSelect = document.getElementById('provinceSelect');
    const citySelect = document.getElementById('citySelect');
    const districtSelect = document.getElementById('districtSelect');
    const villageSelect = document.getElementById('villageSelect');

    const mapSearchInput = document.getElementById('mapSearchInput');
    const mapSearchButton = document.getElementById('mapSearchButton');
    const latInput = document.getElementById('latInput');
    const lngInput = document.getElementById('lngInput');

    function fillOptions(select, options, placeholder) {
        select.innerHTML = `<option value="">${placeholder}</option>`;
        for (const item of options) {
            const option = document.createElement('option');
            option.value = item.name;
            option.textContent = item.name;
            option.dataset.id = item.id;
            select.appendChild(option);
        }
        select.disabled = false;
    }

    function getSelectedId(select) {
        return select.options[select.selectedIndex]?.dataset?.id || null;
    }

    function resetSelect(select, placeholder) {
        select.innerHTML = `<option value="">${placeholder}</option>`;
        select.disabled = true;
    }

    async function fetchRegions(path) {
        const response = await fetch(`${regionApi}/${path}`);
        if (!response.ok) throw new Error('Gagal memuat data wilayah');
        return response.json();
    }

    async function loadProvinces() {
        const provinces = await fetchRegions('provinces.json');
        fillOptions(provinceSelect, provinces, 'Pilih Provinsi');

        if (oldValues.province) {
            provinceSelect.value = oldValues.province;
            await loadCities();
        }
    }

    async function loadCities() {
        resetSelect(citySelect, 'Pilih Kota/Kabupaten');
        resetSelect(districtSelect, 'Pilih Kecamatan');
        resetSelect(villageSelect, 'Pilih Kelurahan');

        const provinceId = getSelectedId(provinceSelect);
        if (!provinceId) return;

        const cities = await fetchRegions(`regencies/${provinceId}.json`);
        fillOptions(citySelect, cities, 'Pilih Kota/Kabupaten');

        if (oldValues.city) {
            citySelect.value = oldValues.city;
            oldValues.city = null;
            await loadDistricts();
        }
    }

    async function loadDistricts() {
        resetSelect(districtSelect, 'Pilih Kecamatan');
        resetSelect(villageSelect, 'Pilih Kelurahan');

        const cityId = getSelectedId(citySelect);
        if (!cityId) return;

        const districts = await fetchRegions(`districts/${cityId}.json`);
        fillOptions(districtSelect, districts, 'Pilih Kecamatan');

        if (oldValues.district) {
            districtSelect.value = oldValues.district;
            oldValues.district = null;
            await loadVillages();
        }
    }

    async function loadVillages() {
        resetSelect(villageSelect, 'Pilih Kelurahan');

        const districtId = getSelectedId(districtSelect);
        if (!districtId) return;

        const villages = await fetchRegions(`villages/${districtId}.json`);
        fillOptions(villageSelect, villages, 'Pilih Kelurahan');

        if (oldValues.village) {
            villageSelect.value = oldValues.village;
            oldValues.village = null;
        }
    }

    provinceSelect.addEventListener('change', loadCities);
    citySelect.addEventListener('change', loadDistricts);
    districtSelect.addEventListener('change', loadVillages);

    try {
        await loadProvinces();
    } catch (e) {
        console.error(e);
    }

    const map = L.map('addressMap').setView([parseFloat(latInput.value), parseFloat(lngInput.value)], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    const marker = L.marker([parseFloat(latInput.value), parseFloat(lngInput.value)], { draggable: true }).addTo(map);

    function syncLatLng(lat, lng) {
        latInput.value = Number(lat).toFixed(7);
        lngInput.value = Number(lng).toFixed(7);
        marker.setLatLng([lat, lng]);
    }

    map.on('click', function (event) {
        syncLatLng(event.latlng.lat, event.latlng.lng);
    });

    marker.on('dragend', function (event) {
        const latlng = event.target.getLatLng();
        syncLatLng(latlng.lat, latlng.lng);
    });

    mapSearchButton.addEventListener('click', async function () {
        const query = mapSearchInput.value.trim();
        if (!query) return;

        const endpoint = new URL('https://nominatim.openstreetmap.org/search');
        endpoint.searchParams.set('format', 'jsonv2');
        endpoint.searchParams.set('q', query);
        endpoint.searchParams.set('countrycodes', 'id');
        endpoint.searchParams.set('limit', '1');

        const response = await fetch(endpoint.toString());
        const result = await response.json();
        if (!result.length) return;

        const found = result[0];
        const lat = parseFloat(found.lat);
        const lng = parseFloat(found.lon);

        map.setView([lat, lng], 16);
        syncLatLng(lat, lng);
    });
})();
</script>
@endpush
