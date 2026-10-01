<!-- Floating Live Chat Widget for Sport Store -->
<div id="sport-chat-widget">
    <!-- Floating Trigger Button -->
    <button id="chat-widget-btn" class="btn btn-primary rounded-circle shadow-lg d-flex align-items-center justify-content-center position-fixed" 
            style="bottom: 25px; right: 25px; width: 60px; height: 60px; z-index: 1050; transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);"
            title="Chat với Sport Store">
        <i id="chat-icon-open" class="fa-solid fa-comments fs-4"></i>
        <i id="chat-icon-close" class="fa-solid fa-xmark fs-4 d-none"></i>
        <span id="chat-unread-badge" class="position-absolute top-0 start-100 translate-middle p-2 bg-danger border border-light rounded-circle d-none">
            <span class="visually-hidden">Tin nhắn mới</span>
        </span>
    </button>

    <!-- Chat Window Container -->
    <div id="chat-widget-box" class="card border-0 shadow-lg rounded-4 position-fixed d-none overflow-hidden" 
         style="bottom: 95px; right: 25px; width: 370px; max-width: calc(100vw - 40px); height: 500px; max-height: calc(100vh - 120px); z-index: 1050; display: flex; flex-direction: column;">
        
        <!-- Header -->
        <div class="card-header bg-gradient text-white p-3 d-flex justify-content-between align-items-center" 
             style="background: linear-gradient(135deg, #ff5722 0%, #ff784e 100%);">
            <div class="d-flex align-items-center gap-2">
                <div class="position-relative">
                    <div class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px;">
                        <i class="fa-solid fa-headset fs-5"></i>
                    </div>
                    <span class="position-absolute bottom-0 end-0 bg-success border border-white rounded-circle" style="width: 11px; height: 11px;"></span>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold">CSKH Sport Store</h6>
                    <small class="text-white-50" style="font-size: 0.75rem;">
                        <i class="fa-solid fa-bolt me-1"></i> Trực tuyến phản hồi ngay
                    </small>
                </div>
            </div>
            <button id="chat-close-btn" class="btn btn-sm btn-link text-white text-decoration-none p-1">
                <i class="fa-solid fa-minus fs-5"></i>
            </button>
        </div>

        <!-- Body / Message List -->
        <div id="chat-messages-body" class="card-body p-3 overflow-y-auto bg-light flex-grow-1" style="scroll-behavior: smooth;">
            <!-- Welcome message -->
            <div class="d-flex align-items-start gap-2 mb-3">
                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width: 32px; height: 32px; font-size: 0.8rem;">
                    <i class="fa-solid fa-store"></i>
                </div>
                <div>
                    <div class="bg-white p-3 rounded-4 shadow-sm text-dark small" style="border-top-left-radius: 4px !important; max-width: 270px;">
                        Xin chào @auth {{ auth()->user()->name }} @endauth! 👋<br>
                        Sport Store có thể hỗ trợ gì cho bạn về dụng cụ thể thao, chọn size hoặc đơn vận chuyển hôm nay?
                    </div>
                    <small class="text-muted ms-1" style="font-size: 0.7rem;">Hôm nay</small>
                </div>
            </div>

            <!-- Dynamic Messages will render here -->
            <div id="chat-messages-list"></div>
        </div>

        <!-- Footer / Input Form -->
        <div class="card-footer bg-white p-2 border-top">
            <form id="chat-widget-form" class="d-flex align-items-center gap-2 m-0">
                <input type="text" id="chat-input-message" class="form-control form-control-sm rounded-pill border-0 bg-light px-3 py-2" 
                       placeholder="Nhập tin nhắn..." autocomplete="off" maxlength="1000" required>
                <button type="submit" id="chat-submit-btn" class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" 
                        style="width: 38px; height: 38px;">
                    <i class="fa-solid fa-paper-plane" style="font-size: 0.9rem;"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<style>
    #chat-widget-btn:hover {
        transform: scale(1.08);
    }
    .chat-bubble-user {
        background: #ff5722;
        color: #ffffff;
        border-top-right-radius: 4px !important;
        max-width: 260px;
    }
    .chat-bubble-admin {
        background: #ffffff;
        color: #1e293b;
        border-top-left-radius: 4px !important;
        border: 1px solid #e2e8f0;
        max-width: 260px;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const chatBtn = document.getElementById('chat-widget-btn');
    const chatBox = document.getElementById('chat-widget-box');
    const chatCloseBtn = document.getElementById('chat-close-btn');
    const chatIconOpen = document.getElementById('chat-icon-open');
    const chatIconClose = document.getElementById('chat-icon-close');
    const messagesBody = document.getElementById('chat-messages-body');
    const messagesList = document.getElementById('chat-messages-list');
    const chatForm = document.getElementById('chat-widget-form');
    const chatInput = document.getElementById('chat-input-message');
    const chatBadge = document.getElementById('chat-unread-badge');

    let isOpen = false;
    let pollInterval = null;
    let renderedMsgIds = new Set();

    function scrollToBottom() {
        messagesBody.scrollTop = messagesBody.scrollHeight;
    }

    function toggleChat() {
        isOpen = !isOpen;
        if (isOpen) {
            chatBox.classList.remove('d-none');
            chatIconOpen.classList.add('d-none');
            chatIconClose.classList.remove('d-none');
            chatBadge.classList.add('d-none');
            chatInput.focus();
            fetchMessages();
            // Start polling every 3.5s
            if (!pollInterval) {
                pollInterval = setInterval(fetchMessages, 3500);
            }
        } else {
            chatBox.classList.add('d-none');
            chatIconOpen.classList.remove('d-none');
            chatIconClose.classList.add('d-none');
            if (pollInterval) {
                clearInterval(pollInterval);
                pollInterval = null;
            }
        }
    }

    chatBtn.addEventListener('click', toggleChat);
    chatCloseBtn.addEventListener('click', toggleChat);

    function renderMessage(msg) {
        if (renderedMsgIds.has(msg.id)) return;
        renderedMsgIds.add(msg.id);

        const isUser = (msg.sender_type === 'user');
        const wrapper = document.createElement('div');
        wrapper.className = isUser ? 'd-flex justify-content-end mb-3' : 'd-flex align-items-start gap-2 mb-3';

        if (isUser) {
            wrapper.innerHTML = `
                <div class="text-end">
                    <div class="p-3 rounded-4 shadow-sm small chat-bubble-user text-start">
                        ${msg.message}
                    </div>
                    <small class="text-muted me-1" style="font-size: 0.7rem;">${msg.time}</small>
                </div>
            `;
        } else {
            wrapper.innerHTML = `
                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width: 32px; height: 32px; font-size: 0.8rem;">
                    <i class="fa-solid fa-headset"></i>
                </div>
                <div>
                    <div class="p-3 rounded-4 shadow-sm small chat-bubble-admin">
                        ${msg.message}
                    </div>
                    <small class="text-muted ms-1" style="font-size: 0.7rem;">${msg.time}</small>
                </div>
            `;
        }

        messagesList.appendChild(wrapper);
        scrollToBottom();
    }

    function fetchMessages() {
        fetch("{{ route('chat.messages') }}", {
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success && data.messages) {
                data.messages.forEach(msg => renderMessage(msg));
            }
        })
        .catch(err => console.error('Lỗi tải tin nhắn:', err));
    }

    chatForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const text = chatInput.value.trim();
        if (!text) return;

        chatInput.value = '';
        chatInput.disabled = true;

        fetch("{{ route('chat.send') }}", {
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
            chatInput.focus();
            if (data.success && data.message) {
                renderMessage(data.message);
            }
        })
        .catch(err => {
            chatInput.disabled = false;
            console.error('Lỗi gửi tin nhắn:', err);
        });
    });
});
</script>
