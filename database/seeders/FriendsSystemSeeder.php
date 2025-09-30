<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Friendship;
use App\Models\Notification;
use Illuminate\Support\Facades\Hash;

class FriendsSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء مستخدمين تجريبيين
        $users = [];
        
        // الحصول على دور المستخدم العادي
        $userRole = \App\Models\Role::where('key', 'user')->first();
        
        // المستخدم الأول
        $user1 = User::firstOrCreate([
            'email' => 'ahmed@example.com'
        ], [
            'username' => 'ahmed_mohamed',
            'phone' => '+966501234567',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $user1->translateOrNew('ar')->name = 'أحمد محمد';
        $user1->translateOrNew('en')->name = 'Ahmed Mohamed';
        $user1->save();
        if ($userRole && !$user1->roles()->where('role_id', $userRole->id)->exists()) {
            $user1->roles()->attach($userRole->id);
        }
        $users[] = $user1;
        
        // المستخدم الثاني
        $user2 = User::firstOrCreate([
            'email' => 'sara@example.com'
        ], [
            'username' => 'sara_ahmed',
            'phone' => '+966501234568',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $user2->translateOrNew('ar')->name = 'سارة أحمد';
        $user2->translateOrNew('en')->name = 'Sara Ahmed';
        $user2->save();
        if ($userRole && !$user2->roles()->where('role_id', $userRole->id)->exists()) {
            $user2->roles()->attach($userRole->id);
        }
        $users[] = $user2;
        
        // المستخدم الثالث
        $user3 = User::firstOrCreate([
            'email' => 'omar@example.com'
        ], [
            'username' => 'omar_khaled',
            'phone' => '+966501234569',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $user3->translateOrNew('ar')->name = 'عمر خالد';
        $user3->translateOrNew('en')->name = 'Omar Khaled';
        $user3->save();
        if ($userRole && !$user3->roles()->where('role_id', $userRole->id)->exists()) {
            $user3->roles()->attach($userRole->id);
        }
        $users[] = $user3;
        
        // المستخدم الرابع
        $user4 = User::firstOrCreate([
            'email' => 'fatima@example.com'
        ], [
            'username' => 'fatima_ali',
            'phone' => '+966501234570',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $user4->translateOrNew('ar')->name = 'فاطمة علي';
        $user4->translateOrNew('en')->name = 'Fatima Ali';
        $user4->save();
        if ($userRole && !$user4->roles()->where('role_id', $userRole->id)->exists()) {
            $user4->roles()->attach($userRole->id);
        }
        $users[] = $user4;
        
        // المستخدم الخامس
        $user5 = User::firstOrCreate([
            'email' => 'hassan@example.com'
        ], [
            'username' => 'hassan_mahmoud',
            'phone' => '+966501234571',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $user5->translateOrNew('ar')->name = 'حسن محمود';
        $user5->translateOrNew('en')->name = 'Hassan Mahmoud';
        $user5->save();
        if ($userRole && !$user5->roles()->where('role_id', $userRole->id)->exists()) {
            $user5->roles()->attach($userRole->id);
        }
        $users[] = $user5;
        
        // إنشاء علاقات صداقة تجريبية
        
        // أحمد وسارة أصدقاء
        if (!Friendship::where([
            ['sender_id', $users[0]->id],
            ['receiver_id', $users[1]->id]
        ])->orWhere([
            ['sender_id', $users[1]->id],
            ['receiver_id', $users[0]->id]
        ])->exists()) {
            Friendship::create([
                'sender_id' => $users[0]->id,
                'receiver_id' => $users[1]->id,
                'status' => 'accepted',
                'accepted_at' => now()->subDays(5),
                'created_at' => now()->subDays(5),
            ]);
        }
        
        // عمر أرسل طلب صداقة لأحمد (معلق)
        if (!Friendship::where([
            ['sender_id', $users[2]->id],
            ['receiver_id', $users[0]->id]
        ])->orWhere([
            ['sender_id', $users[0]->id],
            ['receiver_id', $users[2]->id]
        ])->exists()) {
            $friendship = Friendship::create([
                'sender_id' => $users[2]->id,
                'receiver_id' => $users[0]->id,
                'status' => 'pending',
                'created_at' => now()->subDays(2),
            ]);
            
            // إنشاء إشعار طلب الصداقة
            Notification::create([
                'user_id' => $users[0]->id,
                'from_user_id' => $users[2]->id,
                'type' => 'friend_request',
                'title' => 'طلب صداقة جديد',
                'message' => 'أرسل لك ' . $users[2]->name . ' طلب صداقة',
                'data' => json_encode([
                    'friendship_id' => $friendship->id,
                    'sender_name' => $users[2]->name,
                    'sender_avatar' => $users[2]->avatar
                ]),
                'created_at' => now()->subDays(2),
            ]);
        }
        
        // فاطمة أرسلت طلب صداقة لسارة (معلق)
        if (!Friendship::where([
            ['sender_id', $users[3]->id],
            ['receiver_id', $users[1]->id]
        ])->orWhere([
            ['sender_id', $users[1]->id],
            ['receiver_id', $users[3]->id]
        ])->exists()) {
            $friendship = Friendship::create([
                'sender_id' => $users[3]->id,
                'receiver_id' => $users[1]->id,
                'status' => 'pending',
                'created_at' => now()->subDays(1),
            ]);
            
            // إنشاء إشعار طلب الصداقة
            Notification::create([
                'user_id' => $users[1]->id,
                'from_user_id' => $users[3]->id,
                'type' => 'friend_request',
                'title' => 'طلب صداقة جديد',
                'message' => 'أرسلت لك ' . $users[3]->name . ' طلب صداقة',
                'data' => json_encode([
                    'friendship_id' => $friendship->id,
                    'sender_name' => $users[3]->name,
                    'sender_avatar' => $users[3]->avatar
                ]),
                'created_at' => now()->subDays(1),
            ]);
        }
        
        // حسن وعمر أصدقاء
        if (!Friendship::where([
            ['sender_id', $users[4]->id],
            ['receiver_id', $users[2]->id]
        ])->orWhere([
            ['sender_id', $users[2]->id],
            ['receiver_id', $users[4]->id]
        ])->exists()) {
            Friendship::create([
                'sender_id' => $users[4]->id,
                'receiver_id' => $users[2]->id,
                'status' => 'accepted',
                'accepted_at' => now()->subDays(3),
                'created_at' => now()->subDays(3),
            ]);
        }
        
        // إنشاء بعض الإشعارات الإضافية
        
        // إشعار قبول صداقة لحسن من عمر
        Notification::firstOrCreate([
            'user_id' => $users[4]->id,
            'from_user_id' => $users[2]->id,
            'type' => 'friend_accepted',
        ], [
            'title' => 'تم قبول طلب الصداقة',
            'message' => 'قبل ' . $users[2]->name . ' طلب صداقتك',
            'data' => json_encode([
                'accepter_name' => $users[2]->name,
                'accepter_avatar' => $users[2]->avatar
            ]),
            'read_at' => now()->subDays(2), // مقروء
            'created_at' => now()->subDays(3),
        ]);
        
        $this->command->info('تم إنشاء البيانات التجريبية لنظام الأصدقاء بنجاح!');
        $this->command->info('المستخدمين المنشأين:');
        foreach ($users as $user) {
            $this->command->info("- {$user->name} ({$user->email})");
        }
    }
}