@props(['text', 'href' => null])

<li>
  @if ($href)
    <a href="{{ $href }}">{{ $text }}</a>
  @else
    {{ $text }}
  @endif
</li>
