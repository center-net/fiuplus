<div class="friends-container">
    <!-- رأس الصفحة -->
    <div class="friends-header">
        <h2 class="page-title">
            <i class="fas fa-users"></i> الأصدقاء
        </h2>
        
        <!-- شريط البحث -->
        <div class="search-container">
            <div class="input-group">
                <span class="input-group-text">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" 
                       wire:model.debounce.300ms="search" 
                       class="form-control" 
                       placeholder="البحث بالاسم أو اسم المستخدم أو البريد...">
                @if($search)
                    <button class="btn btn-outline-secondary" wire:click="$set('search', '')" type="button">
                        <i class="fas fa-times"></i>
                    </button>
                @endif
            </div>
        </div>
    </div>
    
    <!-- تبويبات الأصدقاء -->
    <div class="friends-tabs">
        <nav class="nav nav-pills nav-fill">
            <button class="nav-link {{ $activeTab === 'friends' ? 'active' : '' }}" 
                    wire:click="setActiveTab('friends')">
                <i class="fas fa-user-friends"></i>
                الأصدقاء
                @if(Auth::check())
                    <span class="badge bg-primary ms-1">{{ Auth::user()->friends()->count() }}</span>
                @endif
            </button>
            
            <button class="nav-link {{ $activeTab === 'requests' ? 'active' : '' }}" 
                    wire:click="setActiveTab('requests')">
                <i class="fas fa-user-clock"></i>
                طلبات الصداقة
                @if(Auth::check())
                    @php $requestsCount = Auth::user()->pendingFriendRequests()->count(); @endphp
                    @if($requestsCount > 0)
                        <span class="badge bg-danger ms-1">{{ $requestsCount }}</span>
                    @endif
                @endif
            </button>
            
            <button class="nav-link {{ $activeTab === 'sent' ? 'active' : '' }}" 
                    wire:click="setActiveTab('sent')">
                <i class="fas fa-paper-plane"></i>
                الطلبات المرسلة
                @if(Auth::check())
                    <span class="badge bg-secondary ms-1">{{ Auth::user()->sentPendingRequests()->count() }}</span>
                @endif
            </button>
            
            <button class="nav-link {{ $activeTab === 'suggestions' ? 'active' : '' }}" 
                    wire:click="setActiveTab('suggestions')">
                <i class="fas fa-user-plus"></i>
                اقتراحات
            </button>
        </nav>
    </div>
    
    <!-- محتوى التبويبات -->
    <div class="friends-content">
        @if($users->count() > 0)
            <div class="users-grid">
                @foreach($users as $user)
                    <div class="user-card">
                        <!-- صورة المستخدم -->
                        <div class="user-avatar">
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" 
                                     alt="{{ $user->name }}"
                                     class="rounded-circle">
                            @else
                                <div class="avatar-placeholder">
                                    <i class="fas fa-user"></i>
                                </div>
                            @endif
                        </div>
                        
                        <!-- معلومات المستخدم -->
                        <div class="user-info">
                            <h5 class="user-name">{{ $user->name }}</h5>
                            @if($user->username)
                                <p class="user-username">
                                    <i class="fas fa-at"></i> {{ $user->username }}
                                </p>
                            @endif
                            @if($user->email && $search)
                                <p class="user-email">{{ $user->email }}</p>
                            @endif
                            
                            <!-- معلومات إضافية حسب التبويب -->
                            @if($activeTab === 'friends')
                                <p class="user-meta">
                                    <i class="fas fa-calendar-alt"></i>
                                    أصدقاء منذ {{ ($user->pivot && $user->pivot->accepted_at) ? $user->pivot->accepted_at->diffForHumans() : 'غير محدد' }}
                                </p>
                            @elseif($activeTab === 'requests')
                                <p class="user-meta">
                                    <i class="fas fa-clock"></i>
                                    طلب منذ {{ ($user->pivot && $user->pivot->created_at) ? $user->pivot->created_at->diffForHumans() : 'غير محدد' }}
                                </p>
                            @elseif($activeTab === 'sent')
                                <p class="user-meta">
                                    <i class="fas fa-paper-plane"></i>
                                    مرسل منذ {{ ($user->pivot && $user->pivot->created_at) ? $user->pivot->created_at->diffForHumans() : 'غير محدد' }}
                                </p>
                            @endif
                        </div>
                        
                        <!-- أزرار الإجراءات -->
                        <div class="user-actions">
                            @livewire('friends.friend-button', ['userId' => $user->id], key($user->id))
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- ترقيم الصفحات -->
            <div class="pagination-container">
                {{ $users->links() }}
            </div>
        @else
            <!-- حالة فارغة -->
            <div class="empty-state">
                @if($activeTab === 'friends')
                    <i class="fas fa-user-friends"></i>
                    <h4>لا توجد أصدقاء بعد</h4>
                    <p>ابدأ بإضافة أصدقاء جدد من خلال الاقتراحات</p>
                    <button wire:click="setActiveTab('suggestions')" class="btn btn-primary">
                        <i class="fas fa-user-plus"></i> عرض الاقتراحات
                    </button>
                @elseif($activeTab === 'requests')
                    <i class="fas fa-user-clock"></i>
                    <h4>لا توجد طلبات صداقة</h4>
                    <p>لم تتلق أي طلبات صداقة جديدة</p>
                @elseif($activeTab === 'sent')
                    <i class="fas fa-paper-plane"></i>
                    <h4>لم ترسل أي طلبات</h4>
                    <p>لم ترسل أي طلبات صداقة بعد</p>
                @elseif($activeTab === 'suggestions')
                    <i class="fas fa-user-plus"></i>
                    <h4>لا توجد اقتراحات</h4>
                    <p>لا توجد اقتراحات أصدقاء متاحة حالياً</p>
                @endif
            </div>
        @endif
    </div>


<style>
/* حاوي الأصدقاء الرئيسي */
.friends-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem 1rem;
}

/* رأس الصفحة */
.friends-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.page-title {
    color: #495057;
    font-weight: 600;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.search-container {
    flex: 1;
    max-width: 400px;
}

.search-container .input-group-text {
    background-color: #f8f9fa;
    border-color: #dee2e6;
    color: #6c757d;
}

.search-container .form-control {
    border-color: #dee2e6;
}

.search-container .form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

/* تبويبات الأصدقاء */
.friends-tabs {
    margin-bottom: 2rem;
}

.friends-tabs .nav-link {
    color: #6c757d;
    border: 1px solid #dee2e6;
    background-color: #fff;
    border-radius: 0.5rem;
    margin: 0 0.25rem;
    padding: 0.75rem 1rem;
    font-weight: 500;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.friends-tabs .nav-link:hover {
    background-color: #f8f9fa;
    color: #495057;
    transform: translateY(-1px);
}

.friends-tabs .nav-link.active {
    background-color: #007bff;
    border-color: #007bff;
    color: #fff;
}

.friends-tabs .badge {
    font-size: 0.75rem;
}

/* شبكة المستخدمين */
.users-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

/* بطاقة المستخدم */
.user-card {
    background: #fff;
    border: 1px solid #dee2e6;
    border-radius: 0.75rem;
    padding: 1.5rem;
    text-align: center;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.user-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    border-color: #007bff;
}

/* صورة المستخدم */
.user-avatar {
    width: 80px;
    height: 80px;
    margin: 0 auto 1rem;
    position: relative;
}

.user-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 2rem;
}

/* معلومات المستخدم */
.user-info {
    margin-bottom: 1rem;
}

.user-name {
    font-size: 1.1rem;
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
}

.user-username {
    color: #007bff;
    font-size: 0.85rem;
    margin-bottom: 0.25rem;
    font-weight: 500;
}

.user-email {
    color: #6c757d;
    font-size: 0.85rem;
    margin-bottom: 0.5rem;
}

.user-meta {
    color: #adb5bd;
    font-size: 0.85rem;
    margin: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.25rem;
}

/* أزرار الإجراءات */
.user-actions {
    margin-top: 1rem;
}

/* حالة فارغة */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: #6c757d;
}

.empty-state i {
    font-size: 4rem;
    margin-bottom: 1rem;
    color: #dee2e6;
}

.empty-state h4 {
    color: #495057;
    margin-bottom: 0.5rem;
}

.empty-state p {
    margin-bottom: 1.5rem;
}

/* ترقيم الصفحات */
.pagination-container {
    display: flex;
    justify-content: center;
    margin-top: 2rem;
}

/* تحسينات للشاشات الصغيرة */
@media (max-width: 768px) {
    .friends-header {
        flex-direction: column;
        align-items: stretch;
    }
    
    .search-container {
        max-width: none;
    }
    
    .friends-tabs .nav {
        flex-direction: column;
    }
    
    .friends-tabs .nav-link {
        margin: 0.25rem 0;
    }
    
    .users-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1rem;
    }
    
    .user-card {
        padding: 1rem;
    }
    
    .user-avatar {
        width: 60px;
        height: 60px;
    }
    
    .avatar-placeholder {
        font-size: 1.5rem;
    }
}

@media (max-width: 576px) {
    .friends-container {
        padding: 1rem 0.5rem;
    }
    
    .users-grid {
        grid-template-columns: 1fr;
    }
    
    .empty-state {
        padding: 2rem 1rem;
    }
    
    .empty-state i {
        font-size: 3rem;
    }
}

/* تحسينات إضافية */
.user-card .friend-button-container {
    width: 100%;
}

.user-card .friend-button-container .btn {
    width: 100%;
    justify-content: center;
}

.user-card .friend-button-container .btn-group {
    width: 100%;
}

.user-card .friend-button-container .btn-group .btn {
    flex: 1;
}
</style>
</div>