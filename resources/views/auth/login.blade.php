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
            min-height: 520px;
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
            width: 300px;
            height: 60px;
            background: linear-gradient(180deg, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0.05) 100%);
            border-radius: 50% 50% 0 0;
            blur: 10px;
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

        .right-section {
            padding: 40px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: white;
            position: relative;
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
            margin-bottom: 28px;
        }

        .form-header h1 {
            font-size: 2.3rem;
            font-weight: 900;
            margin-bottom: 6px;
            letter-spacing: -0.5px;
            animation: slideDown 0.6s ease-out;
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

        .form-header .underline {
            width: 50px;
            height: 4px;
            background: #283B60;
            margin: 0 auto 10px;
            border-radius: 2px;
        }

        .form-header .subtitle {
            color: #4b5563;
            font-size: 0.85rem;
            line-height: 1.5;
            font-weight: 500;
            margin-bottom: 6px;
        }

        .form-header .tagline {
            color: #9ca3af;
            font-size: 0.7rem;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 18px;
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
            box-shadow: 0 0 0 4px rgba(147, 51, 234, 0.1);
            transform: translateY(-2px);
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

        .error-message {
            color: #ef4444;
            font-size: 0.8rem;
            margin-top: 6px;
            display: none;
        }

        .form-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 16px 0 22px 0;
            font-size: 0.8rem;
        }

        .remember-checkbox {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .remember-checkbox input[type='checkbox'] {
            width: 16px;
            height: 16px;
            border: 2px solid #e5e7eb;
            border-radius: 3px;
            cursor: pointer;
            accent-color: #283B60;
        }

        .remember-checkbox label {
            margin: 0;
            cursor: pointer;
            color: #374151;
            font-weight: 500;
            font-size: 0.8rem;
        }

        .forgot-password {
            color: #6b7280;
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .forgot-password:hover {
            color: #283B60;
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
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(147, 51, 234, 0.4);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .submit-btn svg {
            width: 16px;
            height: 16px;
            transition: transform 0.3s ease;
        }

        .submit-btn:hover svg {
            transform: translateX(4px);
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

        .signup-section {
            text-align: center;
            margin-top: 18px;
            font-size: 0.8rem;
            color: #6b7280;
            font-weight: 500;
        }

        .signup-link {
            color: #283B60;
            text-decoration: none;
            font-weight: 700;
            transition: all 0.3s ease;
        }

        .signup-link:hover {
            color: #283B60;
            text-decoration: underline;
        }

        .form-input.error {
            border-color: #ef4444;
            background-color: #fef2f2;
        }

        .features-section {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #f3f4f6;
        }

        .feature-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 6px;
            color: #4b5563;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.2px;
            padding: 10px 6px;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .feature-item:hover {
            background-color: #f0f9ff;
            color: #283B60;
        }

        .feature-item:hover .feature-icon {
            transform: scale(1.2) rotate(5deg);
        }

        .feature-icon {
            width: 20px;
            height: 20px;
            color: #283B60;
            transition: transform 0.3s ease;
        }

        @keyframes twinkle {
            0%, 100% { opacity: 0.6; }
            50% { opacity: 1; }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .illustration {
            animation: float 4s ease-in-out infinite;
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

        @media (max-width: 1024px) {
            .split-container {
                grid-template-columns: 1fr;
                min-height: auto;
            }

            .left-section {
                display: none;
            }

            .right-section {
                padding: 30px 25px;
            }

            .form-header h1 {
                font-size: 1.8rem;
            }
        }

        @media (max-width: 640px) {
            .right-section {
                padding: 25px 18px;
            }

            .form-header h1 {
                font-size: 1.5rem;
            }

            .form-input {
                font-size: 16px;
            }
        }
    </style>

    <div class="split-container">
        <!-- Left Section -->
        <div class="left-section">
            <div class="math-symbol" style="font-size: 8rem; top: 10%; left: 10%; animation-delay: 0s;">+</div>
            <div class="math-symbol" style="font-size: 6rem; top: 60%; left: 80%; animation-delay: 2s;">−</div>
            <div class="math-symbol" style="font-size: 7rem; top: 30%; right: 15%; animation-delay: 1s;">×</div>
            <div class="math-symbol" style="font-size: 5rem; bottom: 15%; left: 20%; animation-delay: 3s;">÷</div>
            <div class="math-symbol" style="font-size: 4rem; top: 40%; left: 50%; opacity: 0.05;">π</div>
            
            <div class="left-content">
                <div class="left-header">
                    <h2>MathVibe</h2>
                    <p>Unlock the secrets of numbers and master your mathematical journey.</p>
                </div>
                
                <div class="illustration" style="background: rgba(255,255,255,0.1); border-radius: 20px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center;">
                   <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="width: 100px; height: 100px; opacity: 0.9;">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Right Section -->
        <div class="right-section">
            <x-auth-session-status class="mb-6" :status="session('status')" />

            <div class="form-header">
                <h1>MathVibe</h1>
                <div class="underline"></div>
                <p class="subtitle">Welcome back, learner! Continue your mathematical journey with interactive challenges and real-time progress tracking.</p>
                <p class="tagline">Learn • Practice • Master</p>
            </div>

            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-group">
                        <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required
                            autofocus autocomplete="username" placeholder="you@mathgames.com" class="form-input"
                            oninput="validateEmail(this)" />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="error-message" />
                    <p id="emailError" class="error-message"></p>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="password-wrapper input-group">
                        <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <input id="password" type="password" name="password" required
                            autocomplete="current-password" placeholder="Enter your password" class="form-input"
                            oninput="validatePassword(this)" />
                        <button type="button" onclick="togglePasswordVisibility('password', 'eyeSlashIcon', 'eyeOpenIcon')" class="password-toggle"
                            title="Toggle password visibility">
                            <svg id="eyeSlashIcon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z"
                                    clip-rule="evenodd" />
                                <path
                                    d="M15.171 13.576l1.414 1.414a1 1 0 001.707-.707v-.757a1 1 0 00-.293-.707l-1.828-1.828" />
                            </svg>
                            <svg id="eyeOpenIcon" class="hidden" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                <path fill-rule="evenodd"
                                    d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="error-message" />
                    <p id="passwordError" class="error-message"></p>
                </div>

                <div class="form-footer">
                    <div class="remember-checkbox">
                        <input id="remember_me" type="checkbox" name="remember" />
                        <label for="remember_me">Remember me</label>
                    </div>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-password">Forgot your password?</a>
                    @endif
                </div>

                <button type="submit" class="submit-btn">
                    START LEARNING
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 10 10.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </form>

            <div class="signup-section">
                <span class="font-semibold">Sign Up Now</span>
                <a href="{{ route('register') }}" class="signup-link">Create account</a>
            </div>

            <div class="features-section">
                <div class="feature-item">
                    <svg class="feature-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v1h8v-1zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                    </svg>
                    <span>Adaptive Learning</span>
                </div>
                <div class="feature-item">
                    <svg class="feature-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 1 1 0 000 2H3a1 1 0 00-1 1v10a1 1 0 001 1h14a1 1 0 001-1V6a1 1 0 00-1-1h-3a1 1 0 000-2 2 2 0 00-2 2v2H8V5zm12 4H4v8h12V9z" clip-rule="evenodd"></path>
                    </svg>
                    <span>Smart Progress</span>
                </div>
                <div class="feature-item">
                    <svg class="feature-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z" />
                    </svg>
                    <span>Live Analytics</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            window.validateEmail = function (input) {
                const emailError = document.getElementById('emailError');
                const email = input.value.trim();

                input.classList.remove('error');
                emailError.style.display = 'none';

                if (!email) {
                    emailError.textContent = 'Email is required';
                    emailError.style.display = 'block';
                    input.classList.add('error');
                    return false;
                }

                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailPattern.test(email)) {
                    emailError.textContent = 'Please enter a valid email address';
                    emailError.style.display = 'block';
                    input.classList.add('error');
                    return false;
                }

                return true;
            };

            window.validatePassword = function (input) {
                const passwordError = document.getElementById('passwordError');
                const password = input.value;

                input.classList.remove('error');
                passwordError.style.display = 'none';

                if (!password) {
                    passwordError.textContent = 'Password is required';
                    passwordError.style.display = 'block';
                    input.classList.add('error');
                    return false;
                }

                if (password.length < 1) { // Basic check, backend handles stricter rules
                     passwordError.textContent = 'Password is required';
                     passwordError.style.display = 'block';
                     input.classList.add('error');
                     return false;
                }

                return true;
            };

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

            const loginForm = document.getElementById('loginForm');
            loginForm.addEventListener('submit', function (e) {
                const emailInput = document.getElementById('email');
                const passwordInput = document.getElementById('password');

                const isEmailValid = window.validateEmail(emailInput);
                const isPasswordValid = window.validatePassword(passwordInput);

                if (!isEmailValid || !isPasswordValid) {
                    e.preventDefault();
                } else {
                     // Add loading state
                    const submitBtn = this.querySelector('.submit-btn');
                    const originalText = submitBtn.innerText;
                    submitBtn.innerHTML = `
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        LOGGING IN...
                    `;
                    submitBtn.disabled = true;
                    submitBtn.style.opacity = '0.7';
                    submitBtn.style.cursor = 'not-allowed';
                }
            });
        });
    </script>
</x-guest-layout>
