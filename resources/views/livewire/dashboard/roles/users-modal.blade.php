<div>
    <div class="modal-body">
        @if (count($users) > 0)
            <ul class="list-group">
                @foreach ($users as $user)
                    <li class="list-group-item">{{ $user->name }} ({{ $user->email }})</li>
                @endforeach
            </ul>
        @else
            <p>لا يوجد مستخدمون في هذا الدور.</p>
        @endif
    </div>
</div>