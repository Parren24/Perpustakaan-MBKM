@php
    $showDivider = isset($showDivider) ? $showDivider : true;
@endphp

<div class="container">
    <div class="divider-dark-lg p-0" style="display: {{ $showDivider ? 'block' : 'none' }}"></div>
</div>
