@extends('layouts.admin')

@section('title', 'Trung Tâm Live Chat Khách Hàng - Quản Trị Sport Store')

@section('admin_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fa-solid fa-comments me-2 text-primary"></i> Live Chat Khách Hàng</h4>
        <p class="text-muted small mb-0">Hỗ trợ và tư vấn trực tiếp với khách mua hàng trên website</p>
    </div>
    <div>
        <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2">
            <i class="fa-solid fa-circle-dot me-1"></i> Trực tuyến sẵn sàng hỗ trợ
        </span>
    </div>
</div>

<div class="row g-4">
    <!-- Conversation List Sidebar (Left Column) -->
    <div class="col-lg-4 col-md-5">
        <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden" style="min-height: 600px;">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <span class="fw-bold mb-0">Hội thoại gần đây ({{ $conversations->count() }})</span>
                <button class="btn btn-sm btn-light rounded-circle" onclick="window.location.reload()" title="Tải lại danh sách">
                    <i class="fa-solid fa-rotate-right"></i>
                </button>
            </div>
            
            <div class="list-group list-group-flush overflow-y-auto" style="max-height: 580px;">
                @forelse($conversations as $conv)
                    @php
                        $isActive = ($conv->session_id === $activeSessionId);
                        $senderName = $conv->user ? $conv->user->name : 'Khách vãng lai #' . substr($conv->session_id, 0, 6);
                    @endphp
                    <a href="{{ route('admin.chats.index', ['session_id' => $conv->session_id]) }}" 
                       class="list-group-item list-group-item-action p-3 border-bottom {{ $isActive ? 'bg-primary-subtle border-primary-subtle' : '' }}">
                        <div class="d-flex align-items-start gap-3">
                            <div class="position-relative">
                                @if($conv->user && $conv->user->avatar)
                                    <img src="{{ asset($conv->user->avatar) }}" alt="{{ $senderName }}" class="rounded-circle" style="width: 44px; height: 44px; object-fit: cover;">
                                @else
                                    <div class="rounded-circle {{ $conv->user ? 'bg-primary text-white' : 'bg-secondary text-white' }} fw-bold d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                        {{ $conv->user ? strtoupper(substr($conv->user->name, 0, 1)) : 'K' }}
                                    </div>
                                @endif
                                @if($conv->user)
                                    <span class="position-absolute bottom-0 end-0 bg-success border border-white rounded-circle" style="width: 10px; height: 10px;" title="Thành viên đã đăng ký"></span>
                                @endif
                            </div>

                            <div class="flex-grow-1 min-w-0">
                                <div class="d-flex justify-content-between align-items-baseline mb-1">
                                    <h6 class="mb-0 fw-semibold text-truncate {{ $isActive ? 'text-primary' : 'text-dark' }}" style="max-width: 140px;">
                                        {{ $senderName }}
                                    </h6>
                                    <small class="text-muted" style="font-size: 0.75rem;">
                                        {{ $conv->last_time ? $conv->last_time->format('H:i') : '' }}
                                    </small>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <p class="mb-0 text-muted small text-truncate" style="max-width: 180px;">
                                        @if($conv->last_sender === 'admin')
                                            <span class="text-primary fw-semibold">Bạn: </span>
                                        @endif
                                        {{ $conv->last_message }}
                                    </p>
                                    @if($conv->unread_count > 0)
                                        <span class="badge bg-danger rounded-pill px-2">{{ $conv->unread_count }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="text-center py-5 text-muted">
                        <i class="fa-regular fa-comment-dots fs-1 mb-2"></i>
                        <p class="mb-0">Chưa có cuộc hội thoại nào.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Active Conversation View (Right Column) -->
    <div class="col-lg-8 col-md-7">
        <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden d-flex flex-column" style="min-height: 600px;">
            @if($activeSessionId)
                @php
                    $currentConv = $conversations->firstWhere('session_id', $activeSessionId);
                    $customerName = $currentConv && $currentConv->user ? $currentConv->user->name : 'Khách vãng lai #' . substr($activeSessionId, 0, 8);
                @endphp
                <!-- Chat Window Header -->
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary fw-bold d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold">{{ $customerName }}</h6>
                            <div class="small text-muted d-flex align-items-center gap-2">
                                @if($currentConv && $currentConv->user)
                                    <span><i class="fa-regular fa-envelope me-1"></i>{{ $currentConv->user->email }}</span>
                                    @if($currentConv->user->phone)
                                        <span>• <i class="fa-solid fa-phone me-1"></i>{{ $currentConv->user->phone }}</span>
                                    @endif
                                @else
                                    <span>Mã phiên: <code class="text-primary">{{ $activeSessionId }}</code></span>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($currentConv && $currentConv->user)
                        <a href="{{ route('admin.customers.show', $currentConv->user->id) }}" target="_blank" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                            <i class="fa-solid fa-user-gear me-1"></i> Xem hồ sơ khách
                        </a>
                    @endif
                </div>

                <!-- Chat Messages Body -->
                <div id="admin-chat-box" class="card-body p-4 overflow-y-auto bg-light flex-grow-1" style="max-height: 430px; scroll-behavior: smooth;">
                    <div id="admin-chat-messages" class="d-flex flex-column gap-3">
                        <!-- Loaded via AJAX -->
                        <div class="text-center py-4 text-muted">
                            <div class="spinner-border spinner-border-sm text-primary me-2"></div> Đang tải tin nhắn...
                        </div>
                    </div>
                </div>

                <!-- Quick Replies -->
                <div class="px-4 py-2 bg-white border-top border-light d-flex gap-2 overflow-x-auto">
                    <span class="small text-muted fw-semibold align-self-center flex-shrink-0">Mẫu trả lời:</span>
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill py-1 px-3 small text-nowrap js-quick-reply">
                        Dạ Sport Store xin chào bạn! Cần shop hỗ trợ gì ạ?
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill py-1 px-3 small text-nowrap js-quick-reply">
                        Sản phẩm thể thao này hiện đang sẵn hàng tại kho ạ!
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill py-1 px-3 small text-nowrap js-quick-reply">
                        Đơn hàng của bạn đã được bàn giao cho Giao Hàng Nhanh (GHN) rồi nhé!
                    </button>
                </div>

                <!-- Chat Input Footer -->
                <div class="card-footer bg-white p-3 border-top">
                    <form id="admin-chat-form" class="d-flex align-items-center gap-2 m-0">
                        <textarea id="admin-chat-input" class="form-control rounded-4 bg-light border-0 px-3 py-2" rows="1" 
                                  placeholder="Nhập tin nhắn phản hồi khách hàng... (Enter để gửi, Shift+Enter xuống dòng)" 
                                  style="resize: none;" required></textarea>
                        <button type="submit" id="admin-chat-submit" class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;">
                            <i class="fa-solid fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            @else
                <!-- No Active Session Selected -->
                <div class="d-flex flex-column align-items-center justify-content-center h-100 py-5 text-muted">
                    <div class="rounded-circle bg-light p-4 mb-3">
                        <i class="fa-regular fa-comments fs-1 text-secondary"></i>
                    </div>
                    <h5>Chưa chọn cuộc hội thoại nào</h5>
                    <p class="small text-muted">Chọn một khách hàng ở danh sách bên trái để bắt đầu trò chuyện trực tuyến.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .chat-bubble-admin-own {
        background: #0d6efd;
        color: #ffffff;
        border-top-right-radius: 4px !important;
        max-width: 480px;
    }
    .chat-bubble-customer {
        background: #ffffff;
        color: #1e293b;
        border: 1px solid #e2e8f0;
        border-top-left-radius: 4px !important;
        max-width: 480px;
    }
</style>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const activeSessionId = "{{ $activeSessionId }}";
    if (!activeSessionId) return;

    const chatBox = document.getElementById('admin-chat-box');
    const messagesContainer = document.getElementById('admin-chat-messages');
    const chatForm = document.getElementById('admin-chat-form');
    const chatInput = document.getElementById('admin-chat-input');
    const chatSubmit = document.getElementById('admin-chat-submit');

    let renderedIds = new Set();
    let pollTimer = null;

    function scrollToBottom() {
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    function renderMessage(msg) {
        if (renderedIds.has(msg.id)) return;
        renderedIds.add(msg.id);

        const isMe = (msg.sender_type === 'admin');
        const wrapper = document.createElement('div');
        wrapper.className = isMe ? 'd-flex justify-content-end mb-2' : 'd-flex align-items-start gap-2 mb-2';

        if (isMe) {
            wrapper.innerHTML = `
                <div class="text-end">
                    <div class="p-3 rounded-4 shadow-sm small chat-bubble-admin-own text-start">
                        ${msg.message}
                    </div>
                    <small class="text-muted me-1" style="font-size: 0.7rem;">${msg.time} • Bạn (Admin)</small>
                </div>
            `;
        } else {
            wrapper.innerHTML = `
                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width: 34px; height: 34px; font-size: 0.8rem;">
                    <i class="fa-solid fa-user"></i>
                </div>
                <div>
                    <div class="p-3 rounded-4 shadow-sm small chat-bubble-customer">
                        ${msg.message}
                    </div>
                    <small class="text-muted ms-1" style="font-size: 0.7rem;">${msg.time} • ${msg.sender_name}</small>
                </div>
            `;
        }

        messagesContainer.appendChild(wrapper);
        scrollToBottom();
    }

    function loadMessages() {
        fetch(`/admin/chats/${activeSessionId}/messages`, {
            headers: { 'Accept': 'application/json' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                if (renderedIds.size === 0 && data.messages.length === 0) {
                    messagesContainer.innerHTML = `
                        <div class="text-center py-4 text-muted small">
                            Chưa có tin nhắn nào trong phiên này.
                        </div>
                    `;
                } else if (renderedIds.size === 0 && data.messages.length > 0) {
                    messagesContainer.innerHTML = '';
                }

                data.messages.forEach(msg => renderMessage(msg));
            }
        })
        .catch(err => console.error('Lỗi tải tin nhắn admin:', err));
    }

    // Initial load
    loadMessages();

    // Polling every 3.5s
    pollTimer = setInterval(loadMessages, 3500);

    // Send Reply
    chatForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const text = chatInput.value.trim();
        if (!text) return;

        chatInput.value = '';
        chatInput.disabled = true;
        chatSubmit.disabled = true;

        fetch(`/admin/chats/${activeSessionId}/reply`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ message: text })
        })
        .then(res => res.json())
        .then(data => {
            chatInput.disabled = false;
            chatSubmit.disabled = false;
            chatInput.focus();
            if (data.success && data.message) {
                renderMessage(data.message);
            }
        })
        .catch(err => {
            chatInput.disabled = false;
            chatSubmit.disabled = false;
            console.error('Lỗi gửi tin nhắn admin:', err);
        });
    });

    // Enter to send, Shift+Enter to newline
    chatInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            chatForm.dispatchEvent(new Event('submit'));
        }
    });

    // Quick replies click
    document.querySelectorAll('.js-quick-reply').forEach(btn => {
        btn.addEventListener('click', function () {
            chatInput.value = this.textContent.trim();
            chatInput.focus();
        });
    });
});
</script>
@endpush
