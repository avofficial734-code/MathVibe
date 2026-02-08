<x-app-layout>
    <div class="min-h-screen bg-slate-900 py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- Header -->
            <div class="px-4 sm:px-0 mb-8">
                <h2 class="text-3xl font-bold text-white mb-2">{{ __('Profile Settings') }}</h2>
                <p class="text-slate-400">Manage your account settings and preferences.</p>
            </div>

            <!-- Profile Information -->
            <div class="p-8 bg-slate-800/50 backdrop-blur-xl border border-white/5 rounded-3xl shadow-xl">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Update Password -->
            <div class="p-8 bg-slate-800/50 backdrop-blur-xl border border-white/5 rounded-3xl shadow-xl">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete Account -->
            <div class="p-8 bg-red-900/10 backdrop-blur-xl border border-red-500/10 rounded-3xl shadow-xl">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
