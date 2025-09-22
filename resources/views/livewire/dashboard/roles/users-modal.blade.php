<div>
    <div class="modal-body">
        @if (count($users) > 0)
            <ul class="list-group">
                @foreach ($users as $user)
                    <li class="list-group-item">{{ $user->name }} ({{ $user->email }})</li>
                @endforeach
            </ul>
        @else
            <p>{{ __('app.no_users_in_role') }}</p>
        @endif
    </div>
</div>