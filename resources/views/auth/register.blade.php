<x-guest-layout>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .split-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0;
            max-width: 1000px;
            width: 100%;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.3);
        }

        .left-section {
            background: linear-gradient(135deg, #283B60 0%, #283B60 50%, #1A3263 100%);
            padding: 40px 30px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
            min-height: 580px; /* Slightly taller for register form */
        }

        .left-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 24px;
            position: relative;
            z-index: 5;
            text-align: center;
        }

        .left-header {
            color: white;
        }

        .left-header h2 {
            font-size: 1.8rem;
            font-weight: 800;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
            animation: slideDown 0.8s ease-out;
        }

        .left-header p {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.6;
            max-width: 280px;
            font-weight: 500;
        }

        .moon {
            position: absolute;
            width: 150px;
            height: 150px;
            background: radial-gradient(circle at 30% 30%, rgba(255, 255, 255, 1), rgba(255, 255, 255, 0.8));
            border-radius: 50%;
            top: 40px;
            right: 30px;
            box-shadow: 0 0 50px rgba(255, 255, 255, 0.3);
            animation: float 6s ease-in-out infinite;
        }

        .cloud {
            position: absolute;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50px;
            opacity: 0.8;
            backdrop-filter: blur(5px);
        }

        .cloud-1 {
            width: 120px;
            height: 40px;
            top: 20%;
            left: 5%;
            animation: drift 20s linear infinite;
        }

        .cloud-2 {
            width: 100px;
            height: 35px;
            top: 35%;
            right: 10%;
            animation: drift 25s linear infinite reverse;
        }

        .cloud-3 {
            width: 90px;
            height: 32px;
            bottom: 25%;
            left: 8%;
            animation: drift 30s linear infinite;
        }

        @keyframes drift {
            0%, 100% { transform: translateX(0px); }
            50% { transform: translateX(30px); }
        }

        .stars {
            position: absolute;
            width: 2px;
            height: 2px;
            background: white;
            border-radius: 50%;
            opacity: 0.6;
            box-shadow: 
                100px 50px 0 rgba(255, 255, 255, 0.8),
                200px 80px 0 rgba(255, 255, 255, 0.6),
                150px 150px 0 rgba(255, 255, 255, 0.7),
                250px 200px 0 rgba(255, 255, 255, 0.5);
        }

        .stars::before {
            content: '';
            position: absolute;
            width: 50%;
            height: 50%;
            top: -50%;
            left: -50%;
            background: inherit;
        }

        .illustration {
            position: relative;
            z-index: 10;
            width: 200px;
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: float 4s ease-in-out infinite;
        }

        .ground {
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 280px;
            height: 50px;
            background: linear-gradient(180deg, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0.05) 100%);
            border-radius: 50% 50% 0 0;
            filter: blur(2px);
        }

        .building {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            border: 2px solid rgba(255, 255, 255, 0.2);
            box-shadow: inset 0 0 20px rgba(255,255,255,0.1);
            transition: transform 0.3s ease;
        }

        .building::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 50%;
            top: 0;
            left: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.15) 0%, transparent 50%);
            border-radius: 8px 8px 0 0;
        }

        .building::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(255,255,255,0.1) 0%, rgba(0,0,0,0.05) 100%);
            border-radius: 8px;
            pointer-events: none;
        }

        .building-left {
            width: 70px;
            height: 120px;
            left: 15px;
            bottom: 0;
        }

        .building-right {
            width: 85px;
            height: 150px;
            right: 10px;
            bottom: 0;
        }

        .right-section {
            padding: 40px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: white;
            position: relative;
            overflow-y: auto;
            max-height: 580px;
        }

        .right-section::-webkit-scrollbar {
            width: 6px;
        }

        .right-section::-webkit-scrollbar-track {
            background: transparent;
        }

        .right-section::-webkit-scrollbar-thumb {
            background: #283B60;
            border-radius: 3px;
        }

        .right-section::before {
            content: '';
            position: absolute;
            top: -1px;
            right: -1px;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(147, 51, 234, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .form-header {
            text-align: center;
            margin-bottom: 24px;
        }

        .form-header h1 {
            font-size: 2rem;
            font-weight: 900;
            margin-bottom: 4px;
            letter-spacing: -0.5px;
            animation: slideDown 0.6s ease-out;
            color: #283B60;
            background: linear-gradient(135deg, #283B60 0%, #1A3263 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-header .subtitle {
            color: #4b5563;
            font-size: 0.8rem;
            line-height: 1.4;
            font-weight: 500;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
            letter-spacing: 0.2px;
        }

        .input-group {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            width: 20px;
            height: 20px;
            transition: color 0.3s ease;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px 12px 44px; /* Added left padding for icon */
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            background-color: #f9fafb;
            font-weight: 500;
        }

        .form-input:focus ~ .input-icon {
            color: #283B60;
        }

        .password-wrapper {
            position: relative;
        }

        .password-wrapper .form-input {
            padding-right: 44px;
        }

        .password-toggle {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #9ca3af;
            padding: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: #283B60;
        }

        .password-toggle svg {
            width: 20px;
            height: 20px;
        }

        .hidden {
            display: none;
        }

        .math-symbol {
            position: absolute;
            color: rgba(255, 255, 255, 0.1);
            font-weight: 900;
            user-select: none;
            animation: float 6s ease-in-out infinite;
        }


        .form-input::placeholder {
            color: #a1a5b0;
            font-weight: 400;
        }

        .form-input:focus {
            outline: none;
            border-color: #283B60;
            background-color: white;
            box-shadow: 0 0 0 3px rgba(147, 51, 234, 0.1);
            transform: translateY(-2px);
        }

        .error-message {
            color: #ef4444;
            font-size: 0.75rem;
            margin-top: 4px;
            display: block;
        }

        .submit-btn {
            width: 100%;
            padding: 12px 18px;
            background: linear-gradient(135deg, #283B60 0%, #283B60 100%);
            color: white;
            font-weight: 700;
            font-size: 0.9rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 15px rgba(147, 51, 234, 0.3);
            position: relative;
            margin-top: 8px;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(147, 51, 234, 0.4);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .submit-btn::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.3) 0%, transparent 50%, rgba(0,0,0,0.1) 100%);
            border-radius: 8px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .submit-btn:hover::before {
            opacity: 1;
        }

        .divider {
            text-align: center;
            margin: 16px 0;
            font-size: 0.8rem;
            color: #9ca3af;
            position: relative;
        }

        .divider::before,
        .divider::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 35%;
            height: 1px;
            background: #e5e7eb;
        }

        .divider::before {
            left: 0;
        }

        .divider::after {
            right: 0;
        }

        .login-link {
            text-align: center;
            margin-top: 14px;
            font-size: 0.8rem;
            color: #6b7280;
            font-weight: 500;
        }

        .login-link a {
            color: #283B60;
            text-decoration: none;
            font-weight: 700;
            transition: all 0.3s ease;
        }

        .login-link a:hover {
            color: #283B60;
            text-decoration: underline;
        }

        .form-input.error {
            border-color: #ef4444;
            background-color: #fef2f2;
        }

        .left-section::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(180deg, rgba(255,255,255,0.05) 0%, transparent 50%, rgba(0,0,0,0.1) 100%);
            pointer-events: none;
        }

        @keyframes twinkle {
            0%, 100% { opacity: 0.6; }
            50% { opacity: 1; }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        @media (max-width: 1024px) {
            .split-container {
                grid-template-columns: 1fr;
            }
            .left-section {
                display: none;
            }
            .right-section {
                padding: 30px 25px;
                max-height: none;
            }
            .form-header h1 {
                font-size: 1.6rem;
            }
        }

        @media (max-width: 640px) {
            .right-section {
                padding: 25px 18px;
            }
            .form-header h1 {
                font-size: 1.4rem;
            }
            .form-input {
                font-size: 16px;
            }
        }
    </style>

    <div class="split-container">
        <!-- Left Section -->
        <div class="left-section">
            <div class="math-symbol" style="font-size: 8rem; top: 15%; right: 10%; animation-delay: 0.5s;">∫</div>
            <div class="math-symbol" style="font-size: 6rem; bottom: 20%; left: 10%; animation-delay: 2.5s;">√</div>
            <div class="math-symbol" style="font-size: 7rem; top: 40%; left: 5%; animation-delay: 1.5s;">∑</div>
            <div class="math-symbol" style="font-size: 5rem; top: 10%; left: 20%; animation-delay: 3.5s;">∞</div>
            <div class="math-symbol" style="font-size: 4rem; bottom: 30%; right: 20%; opacity: 0.05;">%</div>

            <div class="left-content">
                <div class="left-header">
                    <h2 class="text-gray-600">Begin Your Journey</h2>
                    <p>Join thousands of students conquering math with confidence and achieving excellence.</p>
                </div>

                <div class="illustration" style="background: rgba(255,255,255,0.1); border-radius: 20px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="width: 100px; height: 100px; opacity: 0.9;">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Right Section -->
        <div class="right-section">
            <div class="form-header">
                <h1>MathVibe</h1>
                <p class="subtitle">Create your free account and start mastering math today</p>
            </div>

            <form method="POST" action="{{ route('register') }}" id="registerForm" novalidate>
                @csrf

                <!-- Name -->
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <div class="input-group">
                        <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="First and last name" class="form-input @error('name') error @enderror" />
                    </div>
                    @error('name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-group">
                        <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="you@example.com" class="form-input @error('email') error @enderror" />
                    </div>
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="password-wrapper input-group">
                        <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="Minimum 8 characters" class="form-input @error('password') error @enderror" />
                        <button type="button" onclick="togglePasswordVisibility('password', 'eyeSlashIcon', 'eyeOpenIcon')" class="password-toggle" title="Toggle password visibility">
                            <svg id="eyeSlashIcon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd" />
                                <path d="M15.171 13.576l1.414 1.414a1 1 0 001.707-.707v-.757a1 1 0 00-.293-.707l-1.828-1.828" />
                            </svg>
                            <svg id="eyeOpenIcon" class="hidden" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <div class="password-wrapper input-group">
                        <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm your password" class="form-input" />
                        <button type="button" onclick="togglePasswordVisibility('password_confirmation', 'eyeSlashIconConfirm', 'eyeOpenIconConfirm')" class="password-toggle" title="Toggle password visibility">
                            <svg id="eyeSlashIconConfirm" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd" />
                                <path d="M15.171 13.576l1.414 1.414a1 1 0 001.707-.707v-.757a1 1 0 00-.293-.707l-1.828-1.828" />
                            </svg>
                            <svg id="eyeOpenIconConfirm" class="hidden" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="submit-btn">
                    <span>Register</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </button>
            </form>

            <div class="divider">Already have an account?</div>

            <div class="login-link">
                <a href="{{ route('login') }}">Log in here</a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            window.togglePasswordVisibility = function (inputId, eyeSlashId, eyeOpenId) {
                const passwordInput = document.getElementById(inputId);
                const eyeSlashIcon = document.getElementById(eyeSlashId);
                const eyeOpenIcon = document.getElementById(eyeOpenId);

                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    eyeOpenIcon.classList.remove('hidden');
                    eyeSlashIcon.classList.add('hidden');
                } else {
                    passwordInput.type = 'password';
                    eyeOpenIcon.classList.add('hidden');
                    eyeSlashIcon.classList.remove('hidden');
                }
            };

            const registerForm = document.getElementById('registerForm');
            
            if (registerForm) {
                registerForm.addEventListener('submit', function (e) {
                    const nameInput = document.getElementById('name');
                    const emailInput = document.getElementById('email');
                    const passwordInput = document.getElementById('password');
                    const passwordConfirm = document.getElementById('password_confirmation');
                    
                    let isValid = true;
                    
                    // Reset previous errors
                    document.querySelectorAll('.error-message.client-error').forEach(el => el.remove());
                    document.querySelectorAll('.form-input').forEach(el => el.classList.remove('error'));

                    // Helper to show error
                    const showError = (input, message) => {
                        input.classList.add('error');
                        const errorSpan = document.createElement('span');
                        errorSpan.className = 'error-message client-error';
                        errorSpan.innerText = message;
                        // Append to form-group instead of input parent (which is now input-group)
                        input.closest('.form-group').appendChild(errorSpan);
                        isValid = false;
                    };

                    // Name Validation
                    if (!nameInput.value.trim()) {
                        showError(nameInput, 'Full name is required');
                    }

                    // Email Validation
                    if (!emailInput.value.trim()) {
                        showError(emailInput, 'Email address is required');
                    } else if (!emailInput.value.includes('@') || !emailInput.value.includes('.')) {
                        showError(emailInput, 'Please enter a valid email address');
                    }

                    // Password Validation
                    if (!passwordInput.value) {
                        showError(passwordInput, 'Password is required');
                    } else if (passwordInput.value.length < 8) {
                        showError(passwordInput, 'Password must be at least 8 characters');
                    }

                    // Confirm Password Validation
                    if (passwordInput.value !== passwordConfirm.value) {
                        showError(passwordConfirm, 'Passwords do not match');
                    }

                    if (!isValid) {
                        e.preventDefault();
                    }
                });
            }
        });
    </script>
</x-guest-layout>
