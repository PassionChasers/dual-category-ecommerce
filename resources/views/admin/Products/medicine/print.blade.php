{{-- A very simple print-friendly template that reuses show content --}}
@extends('admin.products.medicine.show') {{-- extends the same markup but hide controls via print CSS --}}
@section('title', 'Print - Medicine')
@push('styles')
    <style>
        .no-print { display: none !important; }
    </style>
@endpush
