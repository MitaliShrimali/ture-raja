<!-- Admin Chatbot Root Wrapper -->
<div id="tourraja-admin-chatbot" class="chatbot-root" style="position: fixed; bottom: 20px; right: 20px; z-index: 99999; font-family: 'Poppins', sans-serif; display: flex; flex-direction: column; align-items: flex-end;">
    <!-- Scoped Styles -->
    <style>
        #tourraja-admin-chatbot .chatbot-window {
            width: 360px !important;
            height: 480px !important;
            background: #ffffff !important;
            border-radius: 20px !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
            border: 1px solid #f0f0f0 !important;
            display: none; /* Hidden by default */
            flex-direction: column !important;
            margin-bottom: 12px !important;
            overflow: hidden !important;
        }
        #tourraja-admin-chatbot .chatbot-header {
            background: linear-gradient(135deg, #0052FF 0%, #1e3a8a 100%) !important;
            padding: 12px 16px !important;
            color: #ffffff !important;
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05) !important;
        }
        #tourraja-admin-chatbot .chatbot-body {
            flex: 1 !important;
            overflow-y: auto !important;
            padding: 16px !important;
            background-color: #f8fafc !important;
            display: flex !important;
            flex-direction: column !important;
            gap: 12px !important;
        }
        #tourraja-admin-chatbot .chatbot-footer {
            padding: 12px !important;
            border-top: 1px solid #f1f5f9 !important;
            background: #ffffff !important;
            display: flex !important;
            flex-direction: column !important;
            gap: 8px !important;
        }
        #tourraja-admin-chatbot .chatbot-bubble-btn {
            width: 56px !important;
            height: 56px !important;
            background-color: #0052FF !important;
            color: #ffffff !important;
            border-radius: 50% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            box-shadow: 0 4px 16px rgba(0, 82, 255, 0.3) !important;
            border: none !important;
            cursor: pointer !important;
            transition: all 0.2s ease !important;
        }
        #tourraja-admin-chatbot .chatbot-bubble-btn:hover {
            transform: scale(1.05) !important;
        }
        #tourraja-admin-chatbot .chatbot-msg-bot {
            background-color: #ffffff !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 16px !important;
            border-top-left-radius: 2px !important;
            padding: 10px 12px !important;
            font-size: 12px !important;
            line-height: 1.5 !important;
            color: #1e293b !important;
            max-width: 85% !important;
        }
        #tourraja-admin-chatbot .chatbot-msg-user {
            background-color: #0052FF !important;
            color: #ffffff !important;
            border-radius: 16px !important;
            border-top-right-radius: 2px !important;
            padding: 10px 12px !important;
            font-size: 12px !important;
            line-height: 1.5 !important;
            max-width: 85% !important;
            margin-left: auto !important;
        }
        #tourraja-admin-chatbot .chatbot-cat-btn {
            padding: 6px 10px !important;
            background-color: #f1f5f9 !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 8px !important;
            font-size: 10px !important;
            font-weight: 600 !important;
            color: #334155 !important;
            cursor: pointer !important;
            transition: all 0.15s ease !important;
            text-align: left !important;
            width: 100% !important;
        }
        #tourraja-admin-chatbot .chatbot-cat-btn:hover {
            background-color: #eff6ff !important;
            color: #1d4ed8 !important;
            border-color: #bfdbfe !important;
        }
        #tourraja-admin-chatbot .chatbot-body::-webkit-scrollbar {
            width: 4px !important;
        }
        #tourraja-admin-chatbot .chatbot-body::-webkit-scrollbar-thumb {
            background-color: #cbd5e1 !important;
            border-radius: 2px !important;
        }
    </style>

    <!-- Chatbot Window Modal -->
    <div id="admin-chatbot-window" class="chatbot-window">
        <!-- Header -->
        <div class="chatbot-header">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 32px; height: 32px; background: rgba(255,255,255,0.15); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 18px;">🤖</div>
                <div>
                    <h4 style="margin: 0; font-size: 13px; font-weight: 800; line-height: 1.2;">Tour Raja Admin Support</h4>
                    <p style="margin: 0; font-size: 9px; opacity: 0.8; font-weight: 500;">Always active online</p>
                </div>
            </div>
            <button id="admin-chatbot-close-btn" style="background: none; border: none; color: #ffffff; cursor: pointer; padding: 4px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="display: block;"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>

        <!-- Chat Area -->
        <div class="chatbot-body" id="admin-chatbot-body-area">
            <!-- Welcome message -->
            <div style="display: flex; gap: 8px; align-items: flex-start;">
                <div style="width: 24px; height: 24px; border-radius: 50%; background-color: #0052FF; color: white; display: flex; align-items: center; justify-content: center; font-size: 11px; flex-shrink: 0;">🤖</div>
                <div class="chatbot-msg-bot">
                    👋 Welcome to Tour Raja Admin Support!<br><br>
                    How can I help you today? Choose a question below or type your query.
                </div>
            </div>
        </div>

        <!-- Panel Footer -->
        <div class="chatbot-footer">
            <!-- Input search -->
            <div style="position: relative; display: flex; width: 100%;">
                <input type="text" id="admin-chatbot-search-input" placeholder="Type a command or query..." style="width: 100%; padding: 8px 36px 8px 12px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 11px; outline: none; font-weight: 600;">
                <button id="admin-chatbot-search-btn" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #94a3b8; cursor: pointer;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </button>
            </div>

            <!-- Questions list wrapper -->
            <div id="admin-chatbot-questions-area" style="display: flex; flex-direction: column; gap: 4px; max-height: 120px; overflow-y: auto;">
                <!-- Filled dynamically -->
            </div>
        </div>
    </div>

    <!-- Floating Chat Button -->
    <button id="admin-chatbot-bubble-btn" class="chatbot-bubble-btn">
        <svg id="admin-chatbot-icon-msg" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
        <svg id="admin-chatbot-icon-close" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="display: none;"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
    </button>
</div>

<!-- Vanilla JS Controller -->
<script>
    (function() {
        const commands = [
            {
                q: 'I want more leads',
                a: 'Upgrade to Premium for higher visibility and priority listing.'
            },
            {
                q: "My agency isn't verified",
                a: 'Please share your registered email or agency name so we can check your verification status.'
            },
            {
                q: 'How do I upload packages?',
                a: 'Go to Dashboard &rarr; My Packages &rarr; Add Package.'
            },
            {
                q: 'Can I change my plan?',
                a: 'Yes, you can upgrade anytime from the Membership section.'
            },
            {
                q: 'How do customers contact me?',
                a: 'Customers can contact you through WhatsApp, phone, or the inquiry form on your profile.'
            },
            {
                q: 'I want to advertise my agency',
                a: 'Premium and Custom plans include promotional opportunities. Contact our sales team for more details.'
            },
            {
                q: 'I have a payment issue',
                a: 'Please share your transaction ID or registered email so we can assist you.'
            }
        ];

        // State variables
        let isOpen = false;

        // Elements
        const bubbleBtn = document.getElementById('admin-chatbot-bubble-btn');
        const closeBtn = document.getElementById('admin-chatbot-close-btn');
        const windowModal = document.getElementById('admin-chatbot-window');
        const iconMsg = document.getElementById('admin-chatbot-icon-msg');
        const iconClose = document.getElementById('admin-chatbot-icon-close');
        
        const bodyArea = document.getElementById('admin-chatbot-body-area');
        const searchInput = document.getElementById('admin-chatbot-search-input');
        const searchBtn = document.getElementById('admin-chatbot-search-btn');
        const questionsArea = document.getElementById('admin-chatbot-questions-area');

        // Toggle logic
        function toggleChat() {
            isOpen = !isOpen;
            if (isOpen) {
                windowModal.style.setProperty('display', 'flex', 'important');
                iconMsg.style.display = 'none';
                iconClose.style.display = 'block';
                scrollToBottom();
            } else {
                windowModal.style.setProperty('display', 'none', 'important');
                iconMsg.style.display = 'block';
                iconClose.style.display = 'none';
            }
        }

        bubbleBtn.addEventListener('click', toggleChat);
        closeBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            isOpen = false;
            windowModal.style.setProperty('display', 'none', 'important');
            iconMsg.style.display = 'block';
            iconClose.style.display = 'none';
        });

        // Messages helpers
        function scrollToBottom() {
            setTimeout(() => {
                bodyArea.scrollTop = bodyArea.scrollHeight;
            }, 50);
        }

        function addMessage(sender, text) {
            const wrapper = document.createElement('div');
            if (sender === 'user') {
                wrapper.style.cssText = 'display: flex; justify-content: flex-end; width: 100%;';
                wrapper.innerHTML = `<div class="chatbot-msg-user">${text}</div>`;
            } else {
                wrapper.style.cssText = 'display: flex; gap: 8px; align-items: flex-start;';
                wrapper.innerHTML = `
                    <div style="width: 24px; height: 24px; border-radius: 50%; background-color: #0052FF; color: white; display: flex; align-items: center; justify-content: center; font-size: 11px; flex-shrink: 0;">🤖</div>
                    <div class="chatbot-msg-bot">${text}</div>
                `;
            }
            bodyArea.appendChild(wrapper);
            scrollToBottom();
        }

        // Render questions
        function renderQuestions() {
            questionsArea.innerHTML = '';
            commands.forEach(cmd => {
                const btn = document.createElement('button');
                btn.className = 'chatbot-cat-btn';
                btn.innerHTML = `⚡ ${cmd.q}`;
                btn.addEventListener('click', () => {
                    addMessage('user', cmd.q);
                    setTimeout(() => addMessage('bot', cmd.a), 300);
                });
                questionsArea.appendChild(btn);
            });
        }

        // Search match logic
        function handleSearch() {
            const query = searchInput.value.trim();
            if (!query) return;
            searchInput.value = '';
            addMessage('user', query);

            setTimeout(() => {
                const searchVal = query.toLowerCase();
                let bestMatch = null;
                let highestScore = 0;

                for (let cmd of commands) {
                    let score = 0;
                    if (cmd.q.toLowerCase().includes(searchVal)) score += 5;
                    if (cmd.a.toLowerCase().includes(searchVal)) score += 2;
                    if (score > highestScore) {
                        highestScore = score;
                        bestMatch = cmd;
                    }
                }

                if (bestMatch && highestScore > 0) {
                    addMessage('bot', `<b>${bestMatch.q}</b><br><br>${bestMatch.a}`);
                } else {
                    addMessage('bot', 'Sorry, I couldn\'t find an exact match. Try clicking one of our suggested questions or email us at support@tour raja.com.');
                }
            }, 300);
        }

        searchBtn.addEventListener('click', handleSearch);
        searchInput.addEventListener('keyup', (e) => {
            if (e.key === 'Enter') handleSearch();
        });

        // Initialize view
        renderQuestions();
    })();
</script>
