@extends('layouts.app')

@section('content')
    <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('storage/images/hero1.jpg') }}" class="d-block w-100" alt="Hero 1">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('storage/images/hero2.jpg') }}" class="d-block w-100" alt="Hero 2">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('storage/images/hero3.jpg') }}" class="d-block w-100" alt="Hero 3">
            </div>
        </div>

        <!-- Tombol Navigasi -->
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Sebelumnya</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Berikutnya</span>
        </button>
    </div>
@endsection
