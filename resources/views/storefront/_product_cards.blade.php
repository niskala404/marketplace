@foreach($products as $p)
  <x-storefront.product-card :p="$p" />
@endforeach
