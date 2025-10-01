<?php

if (!function_exists('linkify_mentions')) {
    /**
     * تحويل @username إلى رابط للملف الشخصي
     *
     * @param string $text النص المراد معالجته
     * @return string النص مع الروابط
     */
    function linkify_mentions($text)
    {
        // البحث عن @username وتحويله إلى رابط
        $pattern = '/@([a-zA-Z0-9_]+)/';
        $replacement = '<a href="' . url('/') . '/profile/$1" class="text-primary text-decoration-none fw-bold">@$1</a>';
        
        return preg_replace($pattern, $replacement, $text);
    }
}

if (!function_exists('format_username')) {
    /**
     * تنسيق اسم المستخدم مع رابط للملف الشخصي
     *
     * @param string $username اسم المستخدم
     * @param bool $withAt إضافة @ قبل الاسم
     * @return string HTML للرابط
     */
    function format_username($username, $withAt = true)
    {
        $displayName = $withAt ? '@' . $username : $username;
        $url = route('profile.show', $username);
        
        return '<a href="' . $url . '" class="text-primary text-decoration-none fw-bold">' . $username . '</a>';
    }
}