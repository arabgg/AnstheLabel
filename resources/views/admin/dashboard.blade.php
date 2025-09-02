@extends('admin.layouts.app')

@section('content')
<div class="p-5 bg-white rounded-lg">
    <!-- Top small boxes -->
    <div class="grid grid-cols-4 gap-4 mb-4">
        <div class="bg-gray-300 h-24 rounded-lg"></div>
        <div class="bg-gray-300 h-24 rounded-lg"></div>
        <div class="bg-gray-300 h-24 rounded-lg"></div>
        <div class="bg-gray-300 h-24 rounded-lg"></div>
    </div>

    <!-- Large main content area with side box -->
    <div class="grid grid-cols-4 gap-4 mb-4">
        <div class="col-span-3 bg-gray-300 h-64 rounded-lg"></div>
        <div class="bg-gray-300 h-64 rounded-lg"></div>
    </div>

    <!-- Bottom smaller boxes -->
    <div class="grid grid-cols-4 gap-4">
        <div class="col-span-2 bg-gray-300 h-48 rounded-lg"></div>
        <div class="bg-gray-300 h-48 rounded-lg"></div>
        <div class="bg-gray-300 h-48 rounded-lg"></div>
    </div>
</div>
@endsection

@push('scripts')
@endpush
