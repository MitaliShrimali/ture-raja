(function() {
    const originalConfirm = window.confirm;
    const originalPrompt = window.prompt;
    
    window.confirm = function(message) {
        // Find the event that triggered this
        const e = window.event;
        let target = null;
        
        if (e && e.target) {
            target = e.target.closest('a, button, form');
            if (e.type === 'submit') {
                target = e.target;
            }
            // Try to stop default action if possible
            if (e.preventDefault) e.preventDefault();
            if (e.stopImmediatePropagation) e.stopImmediatePropagation();
        }

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: '<h2 style="font-size: 1.5rem; font-weight: 700; color: #3d3d3d; margin-bottom: 8px;">Are you sure to proceed?</h2>',
                html: `<p style="font-size: 0.875rem; color: #888;">${message || 'This action cannot be undone.'}</p>`,
                showCancelButton: true,
                confirmButtonText: 'Confirm',
                cancelButtonText: 'Cancel',
                buttonsStyling: false,
                customClass: {
                    popup: 'rounded-[24px] shadow-2xl p-6 border-0',
                    confirmButton: 'bg-[#e85d26] hover:bg-[#d45020] text-white px-8 py-3 rounded-lg font-bold mx-2 transition-colors',
                    cancelButton: 'bg-white border-2 border-[#e85d26] text-[#e85d26] hover:bg-orange-50 px-8 py-3 rounded-lg font-bold mx-2 transition-colors',
                    actions: 'mt-6'
                },
                width: '400px',
            }).then((result) => {
                if (result.isConfirmed) {
                    if (target) {
                        // Handle links
                        if (target.tagName === 'A' && target.href && target.href !== 'javascript:void(0);') {
                            window.location.href = target.href;
                        } 
                        // Handle forms and submit buttons
                        else if (target.tagName === 'FORM' || (target.tagName === 'BUTTON' && target.type === 'submit')) {
                            let form = target.tagName === 'FORM' ? target : target.closest('form');
                            if (form) {
                                // Remove onsubmit to prevent loop if it uses confirm
                                form.removeAttribute('onsubmit');
                                form.submit();
                            }
                        } else {
                            // If it's a generic button with an onclick, we can't easily resume execution.
                            // Fallback to original confirm in edge cases? We already suppressed it.
                            console.warn('Unhandled target for confirm:', target);
                        }
                    }
                }
            });
        } else {
            // Fallback if Swal not loaded
            return originalConfirm(message);
        }
        
        // Return false to prevent the default synchronous execution (e.g., link navigation or form submission)
        return false;
    };

    window.customPrompt = function(message) {
        return new Promise((resolve) => {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: '<h2 style="font-size: 1.5rem; font-weight: 700; color: #3d3d3d; margin-bottom: 8px;">Input Required</h2>',
                    html: `<p style="font-size: 0.875rem; color: #888;">${message}</p>`,
                    input: 'text',
                    showCancelButton: true,
                    confirmButtonText: 'Confirm',
                    cancelButtonText: 'Cancel',
                    buttonsStyling: false,
                    customClass: {
                        popup: 'rounded-[24px] shadow-2xl p-6 border-0',
                        confirmButton: 'bg-[#e85d26] hover:bg-[#d45020] text-white px-8 py-3 rounded-lg font-bold mx-2 transition-colors',
                        cancelButton: 'bg-white border-2 border-[#e85d26] text-[#e85d26] hover:bg-orange-50 px-8 py-3 rounded-lg font-bold mx-2 transition-colors',
                        actions: 'mt-6',
                        input: 'w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-[#e85d26]/25 transition-all font-bold text-gray-800 text-sm mt-4'
                    },
                    width: '400px',
                }).then((result) => {
                    if (result.isConfirmed) {
                        resolve(result.value);
                    } else {
                        resolve(null);
                    }
                });
            } else {
                resolve(originalPrompt(message));
            }
        });
    };

})();
