@extends('layouts.admin')

@section('title', 'Tambah Panduan Topik')
@section('header-subtitle', 'Konten Topik Bayi & kategori lain')
@section('header-title', 'Tambah Panduan Topik')

@section('content')
    <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 mb-6">
        Hanya admin yang dapat menambah atau mengubah konten halaman topik (mis. Bayi & Flu).
    </div>

    <div class="rounded-3xl bg-white shadow-lg border border-gray-100 p-6 space-y-6">
        @include('admin.topic-guides._form', [
            'action' => route('admin.topic-guides.store'),
            'method' => 'POST',
            'guide' => $guide,
            'categories' => $categories,
        ])
    </div>
@endsection
