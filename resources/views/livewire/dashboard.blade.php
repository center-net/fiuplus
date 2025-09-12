<div>
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-3">لوحة التحكم</h1>
            <div class="row">
                <div class="col-sm-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col mt-0">
                                    <h5 class="card-title">المستخدمين</h5>
                                </div>
                                <div class="col-auto">
                                    <div class="stat text-primary">
                                        <i class="fas fa-users fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                            <h1 class="mt-1 mb-3">{{ $stats['users'] }}</h1>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col mt-0">
                                    <h5 class="card-title">الدول</h5>
                                </div>
                                <div class="col-auto">
                                    <div class="stat text-success">
                                        <i class="fas fa-globe fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                            <h1 class="mt-1 mb-3">{{ $stats['countries'] }}</h1>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col mt-0">
                                    <h5 class="card-title">المدن</h5>
                                </div>
                                <div class="col-auto">
                                    <div class="stat text-info">
                                        <i class="fas fa-city fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                            <h1 class="mt-1 mb-3">{{ $stats['cities'] }}</h1>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col mt-0">
                                    <h5 class="card-title">القرى</h5>
                                </div>
                                <div class="col-auto">
                                    <div class="stat text-warning">
                                        <i class="fas fa-map-marker-alt fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                            <h1 class="mt-1 mb-3">{{ $stats['villages'] }}</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">آخر النشاطات</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle fa-2x"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h4 class="alert-heading">مرحباً بك في لوحة التحكم!</h4>
                                <p>هذه النسخة التجريبية من نظام فيوبلس. يمكنك استكشاف الميزات المختلفة من خلال القائمة الجانبية.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">معلومات النظام</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6>إصدار النظام</h6>
                        <p class="text-muted">1.0.0</p>
                    </div>
                    <div class="mb-3">
                        <h6>آخر تحديث</h6>
                        <p class="text-muted">{{ now()->format('Y-m-d') }}</p>
                    </div>
                    <div>
                        <h6>حالة النظام</h6>
                        <span class="badge bg-success">يعمل</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
