@props(['name'])

{!! function_exists('svg')
    ? svg('lucide-'.$name, $attributes->merge(['aria-hidden' => 'true'])->toArray())->toHtml()
    : '' !!}