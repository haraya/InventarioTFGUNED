@props([
    'id' => 'exampleModal',
    'title' => 'Modal title',
    'content' => '...',
    'buttonText' => 'Launch demo modal',
    'buttonClass' => 'btn btn-primary',
    'size' => 'lg'
])

<!-- Button trigger modal -->
{{--<button type="button" class="{{ $buttonClass }}" data-bs-toggle="modal" data-bs-target="#{{ $id }}">
    {{ $buttonText }}
</button>--}}

<!-- Modal -->
<div class="modal fade show d-block" style="background: rgba(0,0,0,0.5);">
    <div class="modal-dialog modal-{{ $size }}">
        <div class="modal-content">
            <div class="modal-header">
                {{ $header }}
                
            </div>
            {{ $contenido}}
        </div>
    </div>
</div>