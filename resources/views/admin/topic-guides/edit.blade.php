@extends('layouts.admin')

@section('title', 'Edit Panduan Topik')
@section('header-subtitle', 'Konten Topik Bayi & kategori lain')
@section('header-title', 'Edit Panduan Topik')

@section('content')
    <div class="rounded-3xl bg-white shadow-lg border border-gray-100 p-6 space-y-6">
        @include('admin.topic-guides._form', [
            'action' => route('admin.topic-guides.update', $guide),
            'method' => 'PUT',
            'guide' => $guide,
            'categories' => $categories,
        ])
    </div>
@endsection
