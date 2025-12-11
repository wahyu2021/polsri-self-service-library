@props(['name' => 'search', 'value' => '', 'placeholder' => 'Cari data...', 'suggestionsUrl' => null, 'targetContainer' => 'search-results-table'])

<div class="relative group" x-data="searchComponent('{{ $name }}', '{{ $suggestionsUrl }}', '{{ $targetContainer }}')">
    <input type="text" 
        x-ref="searchInput"
        name="{{ $name }}" 
        value="{{ $value }}" 
        placeholder="{{ $placeholder }}" 
        autocomplete="off"
        x-model="query"
        @input.debounce.300ms="fetchSuggestions"
        @keydown.enter.prevent="performSearch"
        @focus="fetchSuggestions"
        @blur="setTimeout(() => showSuggestions = false, 200)"
        {{ $attributes->merge(['class' => 'pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-polsri-primary/20 focus:border-polsri-primary w-full sm:w-64 transition-all shadow-sm group-hover:border-slate-300']) }}
    >
    <svg class="w-5 h-5 text-slate-400 absolute left-3 top-2.5 pointer-events-none group-hover:text-slate-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
    </svg>

    <!-- Suggestions Dropdown -->
    <template x-if="showSuggestions && suggestions.length > 0">
        <div class="absolute top-full left-0 right-0 mt-1 bg-white border border-slate-200 rounded-xl shadow-xl z-50 max-h-60 overflow-y-auto">
            <template x-for="item in suggestions" :key="item.id">
                <div @click="selectSuggestion(item)" class="px-4 py-2 hover:bg-slate-50 cursor-pointer border-b border-slate-50 last:border-none transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <!-- Helper to check if item has title (Book) or name (User) -->
                            <p class="text-sm font-bold text-slate-800 truncate" x-text="item.title || item.name"></p>
                            <p class="text-xs text-slate-500 font-mono" x-text="item.isbn || item.nim || item.email || ''"></p>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </template>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('searchComponent', (inputName, url, targetId) => ({
            query: '{{ $value }}',
            suggestions: [],
            showSuggestions: false,
            
            async fetchSuggestions() {
                // ... (Logic suggestion sama seperti sebelumnya) ...
                if (!url || this.query.length < 2) {
                    this.showSuggestions = false;
                    return;
                }
                try {
                    const separator = url.includes('?') ? '&' : '?';
                    const res = await fetch(`${url}${separator}q=${encodeURIComponent(this.query)}`, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    const data = await res.json();
                    if (data.success && data.data.length > 0) {
                        this.suggestions = data.data;
                        this.showSuggestions = true;
                    } else {
                        this.showSuggestions = false;
                    }
                } catch (e) {
                    console.error('Search error:', e);
                }
            },

            selectSuggestion(item) {
                let newValue = '';
                if (item.search_term) newValue = item.search_term;
                else if (item.title) newValue = item.title;
                else if (item.name) newValue = item.name;
                
                // 1. Set values immediately
                this.query = newValue;
                this.$refs.searchInput.value = newValue; // Force DOM update
                this.showSuggestions = false;
                
                // 2. Trigger Search immediately
                this.performSearch();
            },

            async performSearch() {
                const container = document.getElementById(targetId);
                if (!container) {
                    console.error('Search target container not found:', targetId);
                    this.$refs.searchInput.form.submit(); // Fallback
                    return;
                }

                // UI Feedback
                container.style.opacity = '0.5';
                container.style.pointerEvents = 'none';

                try {
                    const form = this.$refs.searchInput.closest('form');
                    const action = form ? form.action : window.location.href;
                    const url = new URL(action);
                    
                    if (form) {
                        new FormData(form).forEach((value, key) => {
                            url.searchParams.set(key, value);
                        });
                    }
                    
                    // Ensure the query param is set to the CURRENT selected value
                    url.searchParams.set(inputName, this.query);

                    const res = await fetch(url.toString(), {
                        headers: { 
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'text/html'
                        }
                    });

                    if (res.ok) {
                        const html = await res.text();
                        container.innerHTML = html;
                        window.history.pushState({}, '', url.toString());
                    } else {
                        window.location.href = url.toString(); 
                    }
                } catch (e) {
                    console.error('AJAX search failed:', e);
                } finally {
                    container.style.opacity = '1';
                    container.style.pointerEvents = 'auto';
                }
            }
        }));
    });
</script>
