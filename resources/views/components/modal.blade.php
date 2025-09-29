{{-- Component for reusable modals --}}
{{-- $id: Modal ID --}}
{{-- $size: Modal size class, e.g., 'modal-lg' --}}
{{-- $title: Modal title --}}
@props(['id', 'size' => 'modal-lg', 'title' => 'Modal Title', 'formId' => null])

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}Label" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog {{ $size }}">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $id }}Label">{{ $title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{ $slot }}
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('app.cancel') }}</button>
                <button type="submit" @if($formId) form="{{ $formId }}" @endif class="btn btn-primary">{{ __('app.save') }}</button>
            </div>
        </div>
    </div>
</div>