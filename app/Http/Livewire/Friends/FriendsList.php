<?php

namespace App\Http\Livewire\Friends;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FriendsList extends Component
{
    use WithPagination;
    
    public $activeTab = 'friends'; // friends, requests, sent, suggestions
    public $search = '';
    
    protected $listeners = [
        'friendRequestAccepted' => 'refreshData',
        'friendRequestDeclined' => 'refreshData',
        'friendRemoved' => 'refreshData',
        'friendRequestSent' => 'refreshData',
        'friendRequestCancelled' => 'refreshData'
    ];
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }
    
    public function refreshData()
    {
        // تحديث البيانات
        $this->resetPage();
    }
    
    public function getFriendsProperty()
    {
        if (!Auth::check()) {
            return collect();
        }
        
        $currentUser = Auth::user();
        
        // الحصول على الأصدقاء مع بيانات الصداقة
        $sentFriends = $currentUser->sentFriendRequests()
            ->where('status', 'accepted')
            ->with('receiver')
            ->get()
            ->map(function($friendship) {
                $user = $friendship->receiver;
                $user->pivot = (object) [
                    'created_at' => $friendship->created_at,
                    'accepted_at' => $friendship->accepted_at,
                    'status' => $friendship->status
                ];
                return $user;
            });

        $receivedFriends = $currentUser->receivedFriendRequests()
            ->where('status', 'accepted')
            ->with('sender')
            ->get()
            ->map(function($friendship) {
                $user = $friendship->sender;
                $user->pivot = (object) [
                    'created_at' => $friendship->created_at,
                    'accepted_at' => $friendship->accepted_at,
                    'status' => $friendship->status
                ];
                return $user;
            });

        $friends = $sentFriends->merge($receivedFriends);
        
        if ($this->search) {
            $friends = $friends->filter(function($friend) {
                return stripos($friend->name, $this->search) !== false ||
                       stripos($friend->username, $this->search) !== false ||
                       stripos($friend->email, $this->search) !== false;
            });
        }
        
        // تحويل Collection إلى LengthAwarePaginator للترقيم
        $perPage = 12;
        $currentPage = request()->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        $items = $friends->slice($offset, $perPage);
        
        return new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $friends->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url()]
        );
    }
    
    public function getFriendRequestsProperty()
    {
        if (!Auth::check()) {
            return collect();
        }
        
        $query = Auth::user()->pendingFriendRequests();
        
        if ($this->search) {
            $query->whereHas('sender', function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('username', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }
        
        return $query->with('sender')->paginate(12)->through(function($friendship) {
            $user = $friendship->sender;
            // إضافة بيانات الصداقة كـ pivot
            $user->pivot = (object) [
                'created_at' => $friendship->created_at,
                'accepted_at' => $friendship->accepted_at,
                'status' => $friendship->status
            ];
            return $user;
        });
    }
    
    public function getSentRequestsProperty()
    {
        if (!Auth::check()) {
            return collect();
        }
        
        $query = Auth::user()->sentPendingRequests();
        
        if ($this->search) {
            $query->whereHas('receiver', function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('username', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }
        
        return $query->with('receiver')->paginate(12)->through(function($friendship) {
            $user = $friendship->receiver;
            // إضافة بيانات الصداقة كـ pivot
            $user->pivot = (object) [
                'created_at' => $friendship->created_at,
                'accepted_at' => $friendship->accepted_at,
                'status' => $friendship->status
            ];
            return $user;
        });
    }
    
    public function getSuggestionsProperty()
    {
        if (!Auth::check()) {
            return collect();
        }
        
        $currentUser = Auth::user();
        
        // اقتراح المستخدمين الذين ليسوا أصدقاء ولم يتم إرسال طلبات لهم
        $friendIds = $currentUser->getFriends()->pluck('id');
        $sentRequestIds = $currentUser->sentFriendRequests()->pluck('receiver_id');
        $receivedRequestIds = $currentUser->receivedFriendRequests()->pluck('sender_id');
        
        $excludeIds = $friendIds->merge($sentRequestIds)
                                ->merge($receivedRequestIds)
                                ->push($currentUser->id)
                                ->unique();
        
        $query = User::whereNotIn('id', $excludeIds);
        
        if ($this->search) {
            // البحث عن الاسم أو username أو email
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('username', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        } else {
            // عرض المستخدمين بشكل عشوائي فقط عند عدم البحث
            $query->inRandomOrder();
        }
        
        return $query->paginate(15); // عرض 15 اقتراح
    }
    
    public function render()
    {
        $data = [];
        
        switch ($this->activeTab) {
            case 'friends':
                $data['users'] = $this->friends;
                break;
            case 'requests':
                $data['users'] = $this->friendRequests;
                break;
            case 'sent':
                $data['users'] = $this->sentRequests;
                break;
            case 'suggestions':
                $data['users'] = $this->suggestions;
                break;
        }
        
        return view('livewire.friends.friends-list', $data);
    }
}