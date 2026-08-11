<!-- Chatbot Root Wrapper -->
<div id="tour raja-chatbot" class="chatbot-root" style="position: fixed; bottom: 20px; right: 20px; z-index: 99999; font-family: 'Poppins', sans-serif; display: flex; flex-direction: column; align-items: flex-end;">
    <!-- Scoped Styles -->
    <style>
        #tour raja-chatbot .chatbot-window {
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
        #tour raja-chatbot .chatbot-header {
            background: linear-gradient(135deg, #0052FF 0%, #1e3a8a 100%) !important;
            padding: 12px 16px !important;
            color: #ffffff !important;
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05) !important;
        }
        #tour raja-chatbot .chatbot-body {
            flex: 1 !important;
            overflow-y: auto !important;
            padding: 16px !important;
            background-color: #f8fafc !important;
            display: flex !important;
            flex-direction: column !important;
            gap: 12px !important;
        }
        #tour raja-chatbot .chatbot-footer {
            padding: 12px !important;
            border-top: 1px solid #f1f5f9 !important;
            background: #ffffff !important;
            display: flex !important;
            flex-direction: column !important;
            gap: 8px !important;
        }
        #tour raja-chatbot .chatbot-bubble-btn {
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
        #tour raja-chatbot .chatbot-bubble-btn:hover {
            transform: scale(1.05) !important;
        }
        #tour raja-chatbot .chatbot-msg-bot {
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
        #tour raja-chatbot .chatbot-msg-user {
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
        #tour raja-chatbot .chatbot-cat-btn {
            padding: 6px 10px !important;
            background-color: #f1f5f9 !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 8px !important;
            font-size: 10px !important;
            font-weight: 600 !important;
            color: #334155 !important;
            cursor: pointer !important;
            transition: all 0.15s ease !important;
        }
        #tour raja-chatbot .chatbot-cat-btn:hover {
            background-color: #eff6ff !important;
            color: #1d4ed8 !important;
            border-color: #bfdbfe !important;
        }
        #tour raja-chatbot .chatbot-qr-btn {
            padding: 5px 10px !important;
            background-color: #eff6ff !important;
            border: 1px solid #dbeafe !important;
            color: #1d4ed8 !important;
            border-radius: 9999px !important;
            font-size: 9px !important;
            font-weight: 700 !important;
            cursor: pointer !important;
            transition: all 0.15s ease !important;
        }
        #tour raja-chatbot .chatbot-qr-btn:hover {
            background-color: #0052FF !important;
            color: #ffffff !important;
        }
        #tour raja-chatbot .chatbot-body::-webkit-scrollbar {
            width: 4px !important;
        }
        #tour raja-chatbot .chatbot-body::-webkit-scrollbar-thumb {
            background-color: #cbd5e1 !important;
            border-radius: 2px !important;
        }
    </style>

    <!-- Chatbot Window Modal -->
    <div id="chatbot-window" class="chatbot-window">
        <!-- Header -->
        <div class="chatbot-header">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 32px; height: 32px; background: rgba(255,255,255,0.15); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 18px;">🤖</div>
                <div>
                    <h4 style="margin: 0; font-size: 13px; font-weight: 800; line-height: 1.2;">Tour Raja Agent Support</h4>
                    <p style="margin: 0; font-size: 9px; opacity: 0.8; font-weight: 500;">Always active online</p>
                </div>
            </div>
            <button id="chatbot-close-btn" style="background: none; border: none; color: #ffffff; cursor: pointer; padding: 4px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="display: block;"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>

        <!-- Chat Area -->
        <div class="chatbot-body" id="chatbot-body-area">
            <!-- Welcome message -->
            <div style="display: flex; gap: 8px; align-items: flex-start;">
                <div style="width: 24px; height: 24px; border-radius: 50%; background-color: #0052FF; color: white; display: flex; align-items: center; justify-content: center; font-size: 11px; flex-shrink: 0;">🤖</div>
                <div class="chatbot-msg-bot">
                    👋 Welcome to Tour Raja Agent Support!<br><br>
                    How can I help you today? Choose a topic below or type your query.
                </div>
            </div>
        </div>

        <!-- Panel Footer -->
        <div class="chatbot-footer">
            <!-- Input search -->
            <div style="position: relative; display: flex; width: 100%;">
                <input type="text" id="chatbot-search-input" placeholder="Search support topics..." style="width: 100%; padding: 8px 36px 8px 12px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 11px; outline: none; font-weight: 600;">
                <button id="chatbot-search-btn" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #94a3b8; cursor: pointer;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </button>
            </div>

            <!-- Categories Buttons wrapper -->
            <div id="chatbot-categories-area" style="display: flex; flex-wrap: wrap; gap: 6px; max-height: 90px; overflow-y: auto;">
                <!-- Filled dynamically -->
            </div>

            <!-- Active category sub-questions view -->
            <div id="chatbot-questions-area" style="display: none; flex-direction: column; gap: 6px;">
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f1f5f9; padding-bottom: 4px;">
                    <span id="chatbot-active-category-title" style="font-size: 9px; font-weight: 800; color: #0052FF; text-transform: uppercase; tracking: 0.05em;">Category</span>
                    <button id="chatbot-back-btn" style="background: none; border: none; color: #94a3b8; font-size: 9px; font-weight: 700; cursor: pointer;">&larr; Back</button>
                </div>
                <div id="chatbot-questions-list" style="display: flex; flex-direction: column; gap: 4px; max-height: 80px; overflow-y: auto;">
                    <!-- Filled dynamically -->
                </div>
            </div>

            <!-- Suggested Quick Reply Footer -->
            <div id="chatbot-quick-replies" style="display: flex; gap: 6px; overflow-x: auto; padding-top: 4px; border-top: 1px solid #f1f5f9; white-space: nowrap; -webkit-overflow-scrolling: touch;" class="scrollbar-none">
                <!-- Filled dynamically -->
            </div>
        </div>
    </div>

    <!-- Floating Chat Button -->
    <button id="chatbot-bubble-btn" class="chatbot-bubble-btn">
        <svg id="chatbot-icon-msg" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
        <svg id="chatbot-icon-close" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="display: none;"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
    </button>
</div>

<!-- Vanilla JS Controller -->
<script>
    (function() {
        // Chatbot database
        const quickReplies = [
            'Register My Agency', 'Membership Plans', 'List My Packages', 
            'View My Leads', 'Pricing', 'Payment Issue', 
            'Verification Status', 'Update Profile', 'Need Support', 'Talk to Admin'
        ];

        const categories = [
            {
                id: 'registration',
                name: 'Register Agency',
                icon: '📝',
                questions: [
                    {
                        q: 'How do I register as a travel agent?',
                        a: 'Click Register, fill in your business details, upload the required documents, and submit your application. Once verified, your profile will be published.'
                    },
                    {
                        q: 'What documents are required?',
                        a: 'Required documents for registration:<br>• Business Name<br>• Owner Name<br>• Mobile Number<br>• Email Address<br>• GST (Optional)<br>• Travel Agency Registration (if available)<br>• Office Address<br>• Company Logo'
                    },
                    {
                        q: 'How long does verification take?',
                        a: 'Verification is usually completed within 24–48 business hours.'
                    }
                ]
            },
            {
                id: 'membership',
                name: 'Membership Plans',
                icon: '💼',
                questions: [
                    {
                        q: 'Is there a free plan?',
                        a: 'Yes! Our Welcome Plan allows you to create your business profile and list your first package completely free.'
                    },
                    {
                        q: 'Which plan is best?',
                        a: '• Welcome plan &rarr; For new agencies<br>• Standard plan &rarr; For growing agencies<br>• Premium plan &rarr; For maximum visibility<br>• Custom plan &rarr; For large travel companies'
                    },
                    {
                        q: 'Can I upgrade later?',
                        a: 'Yes, you can upgrade or change your membership plan anytime directly from your dashboard settings.'
                    }
                ]
            },
            {
                id: 'packages',
                name: 'Package Listing',
                icon: '📦',
                questions: [
                    {
                        q: 'How do I add a package?',
                        a: 'Go to:<br><b>Dashboard &rarr; My Packages &rarr; Add Package &rarr; Publish</b>'
                    },
                    {
                        q: 'How many packages can I upload?',
                        a: 'The upload limit depends directly on your current active membership plan.'
                    },
                    {
                        q: 'Can I edit my package later?',
                        a: 'Yes, listed packages can be edited, updated, or removed at any time.'
                    }
                ]
            },
            {
                id: 'leads',
                name: 'Leads & Dashboard',
                icon: '📈',
                questions: [
                    {
                        q: 'How do I receive customer inquiries?',
                        a: 'When a traveler submits an inquiry on your package listing, it will automatically appear in your agent dashboard.'
                    },
                    {
                        q: 'Where can I see my leads?',
                        a: 'Navigate to:<br><b>Dashboard &rarr; Leads</b>'
                    },
                    {
                        q: 'Will I receive WhatsApp inquiries?',
                        a: 'If WhatsApp contact is enabled in your business profile settings, customers can message you directly via WhatsApp.'
                    }
                ]
            },
            {
                id: 'payment',
                name: 'Pricing & Payment',
                icon: '💰',
                questions: [
                    {
                        q: 'Which payment methods are accepted?',
                        a: 'We support:<br>• UPI<br>• Credit Cards<br>• Debit Cards<br>• Net Banking'
                    },
                    {
                        q: 'Will I get an invoice?',
                        a: 'Yes, invoices are automatically generated and emailed after every successful payment.'
                    }
                ]
            },
            {
                id: 'support',
                name: 'Contact Support',
                icon: '👨‍💻',
                questions: [
                    {
                        q: 'I need help / Contact us',
                        a: 'Get in touch:<br>📧 <b>support@tour raja.com</b><br>📞 <b>+91 XXXXX XXXXX</b><br>🕘 Mon–Sat (10 AM – 7 PM)'
                    },
                    {
                        q: 'My package is not showing.',
                        a: 'Possible reasons:<br>• Waiting for admin approval<br>• Membership plan has expired<br>• Listing is incomplete<br><br>If you still face issues, please contact our support team.'
                    },
                    {
                        q: 'I forgot my password.',
                        a: 'Click "Forgot Password" on the login page and follow the link instructions sent to your email.'
                    }
                ]
            }
        ];

        // State variables
        let isOpen = false;
        let activeCategory = null;

        // Elements
        const bubbleBtn = document.getElementById('chatbot-bubble-btn');
        const closeBtn = document.getElementById('chatbot-close-btn');
        const windowModal = document.getElementById('chatbot-window');
        const iconMsg = document.getElementById('chatbot-icon-msg');
        const iconClose = document.getElementById('chatbot-icon-close');
        
        const bodyArea = document.getElementById('chatbot-body-area');
        const searchInput = document.getElementById('chatbot-search-input');
        const searchBtn = document.getElementById('chatbot-search-btn');

        const categoriesArea = document.getElementById('chatbot-categories-area');
        const questionsArea = document.getElementById('chatbot-questions-area');
        const activeCategoryTitle = document.getElementById('chatbot-active-category-title');
        const questionsList = document.getElementById('chatbot-questions-list');
        const backBtn = document.getElementById('chatbot-back-btn');
        const quickRepliesArea = document.getElementById('chatbot-quick-replies');

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

        // Render functions
        function renderCategories() {
            categoriesArea.innerHTML = '';
            categories.forEach(cat => {
                const btn = document.createElement('button');
                btn.className = 'chatbot-cat-btn';
                btn.innerHTML = `${cat.icon} ${cat.name}`;
                btn.addEventListener('click', () => selectCategory(cat));
                categoriesArea.appendChild(btn);
            });
        }

        function selectCategory(cat) {
            activeCategory = cat;
            categoriesArea.style.display = 'none';
            questionsArea.style.display = 'flex';
            activeCategoryTitle.innerText = cat.name;

            questionsList.innerHTML = '';
            cat.questions.forEach(q => {
                const btn = document.createElement('button');
                btn.style.cssText = 'width: 100%; text-align: left; padding: 6px 8px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 10px; font-weight: 600; color: #334155; cursor: pointer; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;';
                btn.innerHTML = `❓ ${q.q}`;
                btn.addEventListener('click', () => {
                    addMessage('user', q.q);
                    setTimeout(() => addMessage('bot', q.a), 300);
                });
                questionsList.appendChild(btn);
            });
        }

        function resetToMain() {
            activeCategory = null;
            categoriesArea.style.display = 'flex';
            questionsArea.style.display = 'none';
        }

        backBtn.addEventListener('click', resetToMain);

        function renderQuickReplies() {
            quickRepliesArea.innerHTML = '';
            quickReplies.forEach(qr => {
                const btn = document.createElement('button');
                btn.className = 'chatbot-qr-btn';
                btn.innerText = qr;
                btn.addEventListener('click', () => triggerQuickReply(qr));
                quickRepliesArea.appendChild(btn);
            });
        }

        function triggerQuickReply(qr) {
            addMessage('user', qr);
            setTimeout(() => {
                const textLower = qr.toLowerCase();
                let response = null;

                // Match database
                for (let cat of categories) {
                    for (let q of cat.questions) {
                        if (q.q.toLowerCase().includes(textLower) || textLower.includes(q.q.toLowerCase())) {
                            response = q.a;
                            break;
                        }
                    }
                    if (response) break;
                }

                // Fallbacks
                if (!response) {
                    if (textLower.includes('plan')) {
                        response = 'We have Welcome, Standard, and Premium membership plans. Standard and Premium offer maximum visibility for listed packages.';
                    } else if (textLower.includes('lead')) {
                        response = 'Leads are shown in your Agent Dashboard. Customers can also reach out to you via WhatsApp if enabled.';
                    } else if (textLower.includes('register') || textLower.includes('agency')) {
                        response = 'Click Register to create an account. You will need your business name, mobile number, email, and GST/Registration details.';
                    } else if (textLower.includes('package')) {
                        response = 'Add packages via My Packages page in the dashboard. Make sure your active plan allows listings.';
                    } else if (textLower.includes('support') || textLower.includes('admin') || textLower.includes('need support')) {
                        response = 'Reach out to support at <b>support@tour raja.com</b> or phone at <b>+91 XXXXX XXXXX</b>.';
                    } else {
                        response = 'Thank you for reaching out! You can check details inside the category sections or contact support at support@tour raja.com.';
                    }
                }
                addMessage('bot', response);
            }, 300);
        }

        function handleSearch() {
            const query = searchInput.value.trim();
            if (!query) return;
            searchInput.value = '';
            addMessage('user', query);

            setTimeout(() => {
                const searchVal = query.toLowerCase();
                let bestMatch = null;
                let highestScore = 0;

                for (let cat of categories) {
                    for (let q of cat.questions) {
                        let score = 0;
                        if (q.q.toLowerCase().includes(searchVal)) score += 5;
                        if (q.a.toLowerCase().includes(searchVal)) score += 2;
                        if (score > highestScore) {
                            highestScore = score;
                            bestMatch = q;
                        }
                    }
                }

                if (bestMatch && highestScore > 0) {
                    addMessage('bot', `<b>${bestMatch.q}</b><br><br>${bestMatch.a}`);
                } else {
                    addMessage('bot', 'Sorry, I couldn\'t find an exact match. Try clicking one of our main support categories or email us at support@tour raja.com.');
                }
            }, 300);
        }

        searchBtn.addEventListener('click', handleSearch);
        searchInput.addEventListener('keyup', (e) => {
            if (e.key === 'Enter') handleSearch();
        });

        // Initialize view
        renderCategories();
        renderQuickReplies();
    })();
</script>
