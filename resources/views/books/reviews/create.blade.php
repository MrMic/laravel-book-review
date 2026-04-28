@extends('layouts.app')

@section('content')
  <h1 class="mb-10 text-2xl">Add Review for {{ $book->title }}</h1>

  <form method="POST" action="{{ route('books.reviews.store', $book) }}">
    @csrf

    <label for="review"
           class="block mb-2 text-sm font-medium text-gray-900">Review</label>
    <textarea id="review" name="review" required class="input mb-4"></textarea>

    <label for="rating"
           class="block mb-2 text-sm font-medium text-gray-900">Rating</label>
    <select id="rating" name="rating" class="input mb-4" required>
      <option value="">Select a rating</option>
      @for ($i = 1; $i <= 5; $i++)
        <option value="{{ $i }}">{{ $i }}</option>
      @endfor
    </select>

    <button type="submit" class="btn">Add Review</button>
  </form>
@endsection
