@props(['label', 'value', 'meta' => null, 'tone' => 'blue'])

<div class="stat {{ $tone }}">
    <span>{{ $label }}</span>
    <strong>{{ $value }}</strong>
    @if ($meta)
        <small>{{ $meta }}</small>
    @endif
</div>
