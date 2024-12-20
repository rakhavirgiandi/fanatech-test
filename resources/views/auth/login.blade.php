<x-guest-layout>
    <!-- Session Status -->
    <div class="container vh-100 d-flex justify-content-center">
        <x-auth-session-status class="mb-4" :status="session('status')" />
        <div class="d-block auth-box align-self-center">
            @error('credentials')
            <x-alert variant="danger" message={{$message}}/>
            @enderror
            <div class="card" style="height: min-content">
                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                
                        <!-- Email Address -->
                        <div>
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                
                        <!-- Password -->
                        <div class="mt-4">
                            <x-input-label for="password" :value="__('Password')" />
                
                            <x-text-input id="password" class="block mt-1 w-full"
                                            type="password"
                                            name="password"
                                            required autocomplete="current-password" />
                
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>
                
                        <!-- Remember Me -->
                        <div class="form-check mt-3">
                            <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                            <label for="remember_me" class="inline-flex items-center">
                                {{ __('Remember me') }}
                            </label>
                        </div>
                
                        <div class="flex items-center justify-end mt-4">
                            <x-button class="btn-primary w-100">
                                {{ __('Log in') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
