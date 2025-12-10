<x-layouts.app title="Notifikasi">
    <div class="py-6 lg:py-12 min-h-[calc(100vh-5rem)] bg-slate-50">
        <div class="max-w-3xl mx-auto px-6 lg:px-8">
            
            <!-- Header -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">Pemberitahuan</h1>
                    <p class="text-slate-500 text-sm mt-1">Update terkini mengenai aktivitas perpustakaan Anda.</p>
                </div>
                <!-- Mark All Read Action (Optional Implementation) -->
                @if($notifications->count() > 0)
                <form action="{{ route('student.notifications.readAll') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-xs font-bold text-blue-600 hover:underline">Tandai Semua Dibaca</button>
                </form>
                @endif
            </div>

            <div class="space-y-4">
                @forelse($notifications as $notification)
                    @php
                        $data = $notification->data;
                        $color = $data['color'] ?? 'blue';
                        $icon = $data['icon'] ?? 'ℹ️';
                        
                        $colors = [
                            'rose' => ['bg' => 'bg-rose-50', 'border' => 'border-rose-100', 'text' => 'text-rose-600', 'bar' => 'bg-rose-500'],
                            'amber' => ['bg' => 'bg-amber-50', 'border' => 'border-amber-100', 'text' => 'text-amber-600', 'bar' => 'bg-amber-400'],
                            'blue' => ['bg' => 'bg-blue-50', 'border' => 'border-blue-100', 'text' => 'text-blue-600', 'bar' => 'bg-blue-500'],
                        ];
                        $c = $colors[$color] ?? $colors['blue'];
                    @endphp

                    <div class="bg-white p-4 rounded-2xl border {{ $c['border'] }} shadow-sm flex gap-4 items-start relative overflow-hidden group">
                        <!-- Color Bar -->
                        <div class="absolute left-0 top-0 bottom-0 w-1 {{ $c['bar'] }}"></div>
                        
                        <!-- Icon -->
                        <div class="w-10 h-10 {{ $c['bg'] }} rounded-full flex items-center justify-center {{ $c['text'] }} shrink-0 text-xl">
                            {{ $icon }}
                        </div>
                        
                        <div class="flex-1">
                            <h3 class="font-bold text-slate-900 text-sm">{{ $data['title'] }}</h3>
                            <p class="text-slate-500 text-xs mt-1">{{ $data['message'] }}</p>
                            <div class="mt-2 flex items-center justify-between">
                                <span class="text-[10px] text-slate-400">{{ $notification->created_at->diffForHumans() }}</span>
                                <a href="{{ route('student.notifications.read', $notification->id) }}" class="text-[10px] font-bold text-slate-400 hover:text-slate-600">Tandai Dibaca</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-4xl grayscale opacity-50">
                            🎉
                        </div>
                        <h3 class="font-bold text-slate-900">Semua Beres!</h3>
                        <p class="text-slate-500 text-sm mt-1">Tidak ada notifikasi baru.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts.app>