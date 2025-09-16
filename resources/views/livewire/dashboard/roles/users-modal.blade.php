<div>
    <div class="modal-header">
        <h5 class="modal-title" id="usersModalLabel">المستخدمون في الدور: {{ $roleName }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
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
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
    </div>
</div>