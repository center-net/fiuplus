<div>
    @if($showModal)
    <!-- Bootstrap Modal -->
    <div class="modal fade show" id="completeProfileModal" tabindex="-1" aria-labelledby="completeProfileModalLabel" 
         style="display: block; background-color: rgba(0,0,0,0.5);" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 15px; overflow: hidden; direction: rtl;">
                <!-- Header with gradient -->
                <div class="modal-header text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                    <h5 class="modal-title w-100 text-center fw-bold" id="completeProfileModalLabel">
                        أكمل ملفك الشخصي
                    </h5>
                </div>
                
                <div class="modal-body p-4">
                    <p class="text-center text-muted mb-4">
                        يرجى إكمال بياناتك الأساسية للمتابعة
                    </p>

                    <!-- Referrer Info (if exists) -->
                    @if($referrer_id)
                    <div class="alert alert-success d-flex align-items-center mb-4" role="alert" style="background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%); border: 1px solid #b1dfbb; border-radius: 10px;">
                        <svg class="ms-2" style="width: 24px; height: 24px; color: #155724;" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <div class="flex-grow-1">
                            <strong>تم التسجيل عبر دعوة من: {{ $referrer_name }}</strong>
                        </div>
                    </div>
                    @endif

                    <!-- Form -->
                    <form wire:submit.prevent="saveAndFinish">
                        <!-- Personal Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">
                                الاسم الشخصي <span class="text-danger">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="name"
                                wire:model.defer="name"
                                class="form-control @error('name') is-invalid @enderror"
                                placeholder="أدخل اسمك الكامل"
                                required
                                style="border-radius: 8px; padding: 10px 15px;"
                            >
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">
                                البريد الإلكتروني <span class="text-danger">*</span>
                            </label>
                            <input 
                                type="email" 
                                id="email"
                                wire:model.defer="email"
                                class="form-control @error('email') is-invalid @enderror"
                                placeholder="example@email.com"
                                required
                                style="border-radius: 8px; padding: 10px 15px;"
                            >
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Send Friend Request (if referrer exists) -->
                        @if($referrer_id)
                        <div class="mb-4">
                            <div class="form-check">
                                <input 
                                    type="checkbox" 
                                    wire:model.defer="send_friend_request"
                                    class="form-check-input"
                                    id="sendFriendRequest"
                                    style="cursor: pointer;"
                                >
                                <label class="form-check-label" for="sendFriendRequest" style="cursor: pointer;">
                                    إرسال طلب صداقة إلى <span class="fw-bold text-primary">{{ $referrer_name }}</span>
                                </label>
                            </div>
                        </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="d-grid gap-2">
                            <!-- Save and Finish -->
                            <button 
                                type="submit"
                                wire:loading.attr="disabled"
                                class="btn btn-lg text-white fw-bold"
                                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 8px; padding: 12px;"
                            >
                                <span wire:loading.remove wire:target="saveAndFinish">
                                    <i class="fas fa-check-circle me-2"></i>حفظ وإنهاء
                                </span>
                                <span wire:loading wire:target="saveAndFinish">
                                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                    جاري الحفظ...
                                </span>
                            </button>

                            <!-- Continue to Profile -->
                            <button 
                                type="button"
                                wire:click="continueToProfile"
                                wire:loading.attr="disabled"
                                class="btn btn-lg btn-primary fw-bold"
                                style="border-radius: 8px; padding: 12px;"
                            >
                                <span wire:loading.remove wire:target="continueToProfile">
                                    <i class="fas fa-user-edit me-2"></i>متابعة وإكمال الملف الشخصي
                                </span>
                                <span wire:loading wire:target="continueToProfile">
                                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                    جاري التحميل...
                                </span>
                            </button>
                        </div>
                    </form>

                    <!-- Info Note -->
                    <div class="alert alert-info mt-4 text-center" role="alert" style="background-color: #e7f3ff; border: 1px solid #b3d9ff; border-radius: 8px;">
                        <i class="fas fa-info-circle me-1"></i>
                        <small>يمكنك إكمال باقي معلومات ملفك الشخصي لاحقاً من صفحة الملف الشخصي</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif


<style>
    /* Custom styles for RTL modal */
    #completeProfileModal .modal-content {
        text-align: right;
    }
    
    #completeProfileModal .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
    }
    
    #completeProfileModal .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        transition: all 0.3s ease;
    }
    
    #completeProfileModal .btn:active {
        transform: translateY(0);
    }
    
    /* Prevent body scroll when modal is open */
    body.modal-open {
        overflow: hidden;
    }
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Prevent modal from closing when clicking outside or pressing ESC
        const modalElement = document.getElementById('completeProfileModal');
        if (modalElement) {
            // Prevent closing on backdrop click
            modalElement.addEventListener('click', function(e) {
                if (e.target === modalElement) {
                    e.stopPropagation();
                }
            });
            
            // Prevent closing on ESC key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && modalElement.style.display === 'block') {
                    e.preventDefault();
                    e.stopPropagation();
                }
            });
            
            // Add modal-open class to body
            document.body.classList.add('modal-open');
        }
    });
    
    // Listen for Livewire events to close modal
    document.addEventListener('livewire:init', () => {
        Livewire.on('profileCompleted', () => {
            const modalElement = document.getElementById('completeProfileModal');
            if (modalElement) {
                modalElement.style.display = 'none';
                document.body.classList.remove('modal-open');
            }
        });
    });
</script>
@endpush
</div>