@extends('layouts.app')
@section('title','Page Not Found — Ghorer Bazar')
@section('content')
<div class="min-h-[60vh] flex flex-col items-center justify-center text-center px-4 py-16">
    <div class="text-[120px] md:text-[160px] font-extrabold text-ink404 leading-none">404</div>
    <h2 class="text-2xl font-bold text-ink mt-4">OPPS! Page Not Found</h2>
    <p class="text-text mt-2 max-w-md">The page you are looking for doesn't exist or has been moved.</p>
    <a href="{{ route('home') }}" class="btn-primary mt-6">← Back To Home</a>
</div>
@endsection
