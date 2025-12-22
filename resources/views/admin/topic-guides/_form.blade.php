@php
    $lineify = fn ($arr) => implode("\n", is_array($arr) ? $arr : []);
@endphp

<form method="POST" action="{{ $action }}" class="space-y-6">
    @csrf
    @if (!empty($method) && $method !== 'POST')
        @method($method)
    @endif

    <div class="grid md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Kategori</label>
            <select name="category_slug" class="w-full rounded-2xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500" required>
                <option value="" disabled {{ old('category_slug', $guide->category_slug) ? '' : 'selected' }}>Pilih kategori</option>
                @foreach ($categories as $slug => $label)
                    <option value="{{ $slug }}" {{ old('category_slug', $guide->category_slug) === $slug ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            @error('category_slug')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Slug Topik</label>
            <input type="text" name="topic_slug" value="{{ old('topic_slug', $guide->topic_slug) }}" placeholder="mis. flu, demam, ruam-popok" class="w-full rounded-2xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500" required>
            <p class="mt-1 text-xs text-gray-500">Slug akan disimpan huruf kecil (otomatis dislugify).</p>
            @error('topic_slug')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Judul</label>
            <input type="text" name="title" value="{{ old('title', $guide->title) }}" class="w-full rounded-2xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500" required>
            @error('title')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Warna latar (opsional)</label>
            <input type="text" name="palette" value="{{ old('palette', $guide->palette) }}" placeholder="mis. from-emerald-50 via-white to-emerald-100" class="w-full rounded-2xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500">
            @error('palette')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">Ringkasan</label>
        <textarea name="summary" rows="3" class="w-full rounded-2xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500">{{ old('summary', $guide->summary) }}</textarea>
        @error('summary')
            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Gejala (satu per baris)</label>
            <textarea name="symptoms" rows="5" class="w-full rounded-2xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500" placeholder="Gejala 1&#10;Gejala 2">{{ old('symptoms', $lineify($guide->symptoms)) }}</textarea>
            @error('symptoms')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Perawatan singkat (satu per baris)</label>
            <textarea name="care" rows="5" class="w-full rounded-2xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500" placeholder="Langkah 1&#10;Langkah 2">{{ old('care', $lineify($guide->care)) }}</textarea>
            @error('care')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Pencegahan (satu per baris)</label>
            <textarea name="prevention" rows="5" class="w-full rounded-2xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500" placeholder="Pencegahan 1&#10;Pencegahan 2">{{ old('prevention', $lineify($guide->prevention)) }}</textarea>
            @error('prevention')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Tanda bahaya (satu per baris)</label>
            <textarea name="danger_signs" rows="5" class="w-full rounded-2xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500" placeholder="Tanda 1&#10;Tanda 2">{{ old('danger_signs', $lineify($guide->danger_signs)) }}</textarea>
            @error('danger_signs')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="flex items-center gap-3">
        <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-emerald-600 px-5 py-2 text-sm font-semibold text-white shadow-lg shadow-emerald-500/30 hover:bg-emerald-700 transition">
            Simpan panduan
        </button>
        <a href="{{ route('admin.topic-guides.index') }}" class="text-sm font-semibold text-gray-500 hover:text-gray-700">Batal</a>
    </div>
</form>
