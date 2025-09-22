<div>
    <div class="modal fade" id="userPermissionsModal" tabindex="-1" aria-labelledby="userPermissionsModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userPermissionsModalLabel">صلاحيات المستخدم</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if($user)
                        <div class="mb-3">
                            <strong>{{ __('app.user') }}:</strong> {{ $user->getDisplayName() }}
                            <div class="text-muted small">{{ $user->email }}</div>
                        </div>

                        <div class="alert alert-info">
                            {{ __('app.user_permission_tip') }}
                        </div>

                        <div class="row g-3">
                            @php
                                $grouped = $permissions->groupBy(fn($p) => $p->table_name ?? __('app.general'));
                            @endphp
                            @foreach($grouped as $group => $list)
                                <div class="col-12">
                                    <div class="border rounded p-2">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="mb-0">{{ $group === __('app.general') ? __('app.general_permissions') : __('app.permissions_for_group', ['group' => $group]) }}</h6>
                                        </div>

                                        <div class="row g-2">
                                            @foreach($list as $permission)
                                                <div class="col-12 col-md-6">
                                                    <div class="d-flex align-items-center justify-content-between border rounded p-2">
                                                        <div>
                                                            <label class="form-label mb-0">{{ $permission->name }}</label>
                                                        </div>
                                                        <div class="btn-group btn-group-sm" role="group" aria-label="allow-deny">
                                                            <input type="radio" class="btn-check" name="perm-{{ $permission->id }}" id="perm-{{ $permission->id }}-none" autocomplete="off" value="none" wire:model.live="permEffects.{{ $permission->id }}">
                                                            <label class="btn btn-outline-secondary" for="perm-{{ $permission->id }}-none">{{ __('app.default') }}</label>

                                                            <input type="radio" class="btn-check" name="perm-{{ $permission->id }}" id="perm-{{ $permission->id }}-allow" autocomplete="off" value="allow" wire:model.live="permEffects.{{ $permission->id }}">
                                                            <label class="btn btn-outline-success" for="perm-{{ $permission->id }}-allow">{{ __('app.allow') }}</label>

                                                            <input type="radio" class="btn-check" name="perm-{{ $permission->id }}" id="perm-{{ $permission->id }}-deny" autocomplete="off" value="deny" wire:model.live="permEffects.{{ $permission->id }}">
                                                            <label class="btn btn-outline-danger" for="perm-{{ $permission->id }}-deny">{{ __('app.deny') }}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-muted">{{ __('app.select_user_to_manage_permissions') }}</div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                    <button type="button" class="btn btn-primary" wire:click="save">حفظ</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('livewire:init', () => {
            const modalEl = document.getElementById('userPermissionsModal');
            const modal = new bootstrap.Modal(modalEl);
            Livewire.on('openUserPermissionsModal', (e) => {
                modal.show();
            });
            Livewire.on('closeModalEvent', () => {
                modal.hide();
            });
        });
    </script>
</div>