@extends('agent.layouts.app')

@section('title', 'Feedback - Tour Raja Agent')

@section('content')

    <div x-data="feedbackManager()">
        <!-- Search Bar -->
        <div class="bg-white p-2 rounded-2xl shadow-sm border border-gray-100 flex items-center mb-12">
            <div class="flex-grow flex items-center px-4">
                <i class="fas fa-search text-gray-300 mr-3"></i>
                <input type="text" id="feedbackSearchInput" oninput="filterFeedbacks()" placeholder="Search Feedback" class="w-full bg-transparent border-none outline-none text-sm text-gray-600 placeholder:text-gray-300">
            </div>
            <button class="bg-primary text-white w-10 h-10 rounded-xl flex items-center justify-center shadow-lg shadow-orange-100">
                <i class="fas fa-search"></i>
            </button>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-10">
            <h3 class="text-lg font-bold text-gray-800 tracking-tight">Customer Feedback</h3>
            <button @click="openModal()" class="bg-primary text-white px-6 py-2 rounded-xl text-[10px] font-bold uppercase tracking-widest shadow-lg shadow-orange-100 hover:scale-105 transition-all w-fit">
                + Add Feedback
            </button>
        </div>

        <!-- Feedback Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
            @forelse($feedbacks as $feedback)
            <div class="feedback-item bg-white p-8 rounded-[32px] shadow-sm border border-gray-100 hover:shadow-xl hover:shadow-gray-200/50 transition-all group">
                <div class="flex items-center mb-6">
                    <div class="relative flex-shrink-0">
                        @if($feedback->image_path)
                            <img src="{{ asset($feedback->image_path) }}" class="w-14 h-14 rounded-2xl object-cover border-2 border-orange-50">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($feedback->customer_name) }}&background=random" class="w-14 h-14 rounded-2xl object-cover border-2 border-orange-50">
                        @endif
                        <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-2 border-white rounded-full"></div>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-sm font-bold text-gray-800">{{ $feedback->customer_name }}</h4>
                        <div class="flex text-yellow-400 text-[8px] mt-1">
                            @for($i = 0; $i < $feedback->rating; $i++)
                                <i class="fas fa-star"></i>
                            @endfor
                            @for($i = $feedback->rating; $i < 5; $i++)
                                <i class="far fa-star"></i>
                            @endfor
                        </div>
                    </div>
                </div>
                <p class="text-[10px] text-gray-400 font-medium leading-relaxed mb-8">{{ $feedback->message }}</p>
                
                <div class="flex space-x-3">
                    <button @click="editModal({{ $feedback->id }}, '{{ addslashes($feedback->customer_name) }}', {{ $feedback->rating }}, '{{ addslashes($feedback->message) }}')" class="bg-primary text-white px-4 py-1.5 rounded-lg text-[10px] font-bold flex items-center"><i class="fas fa-edit mr-2"></i> Edit</button>
                    <a href="{{ route('agent.feedback.delete', $feedback->id) }}" onclick="confirmDelete(event, this.href)" class="bg-gray-100 text-gray-400 p-1.5 rounded-lg hover:text-red-500 transition-colors"><i class="far fa-trash-alt"></i></a>
                </div>
            </div>
            @empty
            <div class="col-span-full py-12 text-center text-gray-400">
                <p class="text-sm">No feedback found. Add some customer feedback!</p>
            </div>
            @endforelse
        </div>

        <!-- Add/Edit Modal -->
        <div x-show="isModalOpen" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
            <div class="bg-white rounded-[32px] w-full max-w-lg p-8 shadow-2xl relative" @click.away="closeModal()">
                <button @click="closeModal()" class="absolute top-6 right-6 text-gray-400 hover:text-red-500">
                    <i class="fas fa-times"></i>
                </button>
                <h3 class="text-xl font-black text-gray-800 mb-6" x-text="isEditing ? 'Edit Feedback' : 'Add Feedback'"></h3>
                
                <form :action="isEditing ? '{{ route('agent.feedback.store') }}'.replace('store', 'update/' + editId) : '{{ route('agent.feedback.store') }}'" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Customer Name</label>
                            <input type="text" name="customer_name" x-model="form.customer_name" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:border-primary" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Rating (1-5)</label>
                            <input type="number" name="rating" x-model="form.rating" min="1" max="5" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:border-primary" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Message</label>
                            <textarea name="message" x-model="form.message" rows="4" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:border-primary" required></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Image (Optional)</label>
                            <input type="file" name="image" accept="image/*" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:border-primary">
                        </div>
                    </div>
                    <div class="mt-8 flex justify-end gap-3">
                        <button type="button" @click="closeModal()" class="px-6 py-2 rounded-xl text-xs font-bold text-gray-500 hover:bg-gray-100 transition-colors">Cancel</button>
                        <button type="submit" class="bg-primary text-white px-6 py-2 rounded-xl text-xs font-bold shadow-lg shadow-orange-100 hover:scale-105 transition-all">
                            Save Feedback
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function feedbackManager() {
            return {
                isModalOpen: false,
                isEditing: false,
                editId: null,
                form: {
                    customer_name: '',
                    rating: 5,
                    message: ''
                },
                openModal() {
                    this.isEditing = false;
                    this.editId = null;
                    this.form = {
                        customer_name: '',
                        rating: 5,
                        message: ''
                    };
                    this.isModalOpen = true;
                },
                editModal(id, name, rating, message) {
                    this.isEditing = true;
                    this.editId = id;
                    this.form = {
                        customer_name: name,
                        rating: rating,
                        message: message
                    };
                    this.isModalOpen = true;
                },
                closeModal() {
                    this.isModalOpen = false;
                }
            }
        }
    </script>
@endsection

@push('scripts')
    <!-- AlpineJS & SweetAlert2 -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(event, url) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#F0642F',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                customClass: {
                    popup: 'rounded-3xl'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        }

        function filterFeedbacks() {
            const input = document.getElementById('feedbackSearchInput');
            const filter = input.value.toLowerCase();
            const items = document.querySelectorAll('.feedback-item');

            items.forEach(item => {
                const text = item.textContent || item.innerText;
                if (text.toLowerCase().indexOf(filter) > -1) {
                    item.style.display = "";
                } else {
                    item.style.display = "none";
                }
            });
        }
    </script>
@endpush
