@extends('layouts.app')
@section('content')
    <app-post-index :tags="{{ $tags }}" :categories="{{ $categories }}"></app-post-index>
@endsection