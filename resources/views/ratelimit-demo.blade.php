<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Rate Limiting Demo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .glass-card {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="bg-slate-900 text-white min-h-screen flex flex-col items-center justify-center p-6">

    <div class="text-center mb-12 animate-fade-in-down">
        <h1 class="text-5xl font-bold mb-4 bg-clip-text text-transparent bg-gradient-to-r from-purple-400 to-pink-600">
            <i class="fa-solid fa-rocket mr-3 text-purple-400"></i>Laravel Rate Limiting
        </h1>
        <p class="text-slate-400 text-lg">Comprehensive demonstration of rate limiting strategies</p>
    </div>

    <!-- Auth & API Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full max-w-7xl mb-12">
        
        <!-- Token Generator -->
        <div class="glass-card rounded-2xl p-8">
            <div class="flex items-center mb-6">
                <i class="fa-solid fa-key text-3xl text-yellow-500 mr-4"></i>
                <h2 class="text-2xl font-bold">Generate API Token</h2>
            </div>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-slate-400 text-sm mb-2">Email</label>
                    <input type="email" id="email" value="test@example.com" class="w-full bg-slate-800 border border-slate-700 rounded-lg p-3 text-white focus:border-purple-500 focus:outline-none transition">
                </div>
                <div>
                    <label class="block text-slate-400 text-sm mb-2">Password</label>
                    <input type="password" id="password" value="password" class="w-full bg-slate-800 border border-slate-700 rounded-lg p-3 text-white focus:border-purple-500 focus:outline-none transition">
                </div>
                <button onclick="getToken()" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-lg transition duration-200 shadow-lg shadow-indigo-500/30">
                    GENERATE TOKEN
                </button>
            </div>

            <div id="token-result" class="mt-6 hidden">
                <div class="bg-green-900/40 border border-green-500/50 rounded-lg p-4">
                    <p class="text-green-400 mb-2"><i class="fa-solid fa-check-circle mr-2"></i>Token generated!</p>
                    <div class="bg-slate-900/80 p-3 rounded text-xs text-green-300 font-mono break-all" id="token-display"></div>
                    <p class="text-xs text-slate-500 mt-2">User: <span id="token-user"></span></p>
                </div>
            </div>
        </div>

        <!-- API Test -->
        <div class="glass-card rounded-2xl p-8">
            <div class="flex items-center mb-6">
                <i class="fa-solid fa-satellite-dish text-3xl text-slate-300 mr-4"></i>
                <h2 class="text-2xl font-bold">Test API Endpoints</h2>
            </div>
            
            <p class="text-slate-400 mb-6">10 requests per minute (Authenticated)</p>

            <div class="space-y-4 mb-6">
                <button onclick="testApi('public')" class="w-full bg-gradient-to-r from-teal-400 to-emerald-500 hover:from-teal-500 hover:to-emerald-600 text-white font-bold py-3 px-6 rounded-lg transition duration-200 shadow-lg shadow-emerald-500/30">
                    PUBLIC API
                </button>
                <button onclick="testApi('protected')" class="w-full bg-gradient-to-r from-orange-400 to-red-500 hover:from-orange-500 hover:to-red-600 text-white font-bold py-3 px-6 rounded-lg transition duration-200 shadow-lg shadow-red-500/30">
                    PROTECTED API
                </button>
            </div>

            <div id="api-result" class="w-full min-h-[120px] rounded-lg p-4 text-sm hidden border"></div>
            
            <div class="mt-4 flex justify-between text-xs text-slate-500">
                <span id="api-limit">Limit: --</span>
                <span id="api-remaining">Remaining: --</span>
            </div>
        </div>
    </div>
        
        <!-- Basic Limit Card -->
        <div class="glass-card rounded-2xl p-6 flex flex-col items-center hover:transform hover:scale-105 transition duration-300">
            <div class="text-4xl text-yellow-400 mb-4"><i class="fa-solid fa-bolt"></i></div>
            <h2 class="text-2xl font-bold mb-2">Basic Rate Limit</h2>
            <p class="text-slate-400 text-sm mb-6 text-center">10 requests per minute</p>
            
            <button onclick="testLimit('basic')" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 shadow-lg shadow-indigo-500/30 mb-4">
                TEST BASIC LIMIT
            </button>

            <div id="basic-result" class="w-full min-h-[80px] rounded-lg p-3 text-sm hidden"></div>
            <div class="mt-auto w-full pt-4 border-t border-slate-700 flex justify-between text-xs text-slate-500">
                <span id="basic-limit">Limit: --</span>
                <span id="basic-remaining">Remaining: --</span>
            </div>
        </div>

        <!-- Strict Limit Card -->
        <div class="glass-card rounded-2xl p-6 flex flex-col items-center hover:transform hover:scale-105 transition duration-300">
            <div class="text-4xl text-red-500 mb-4"><i class="fa-solid fa-fire"></i></div>
            <h2 class="text-2xl font-bold mb-2">Strict Rate Limit</h2>
            <p class="text-slate-400 text-sm mb-6 text-center">3 requests per minute (quick to test!)</p>
            
            <button onclick="testLimit('strict')" class="w-full bg-pink-600 hover:bg-pink-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 shadow-lg shadow-pink-500/30 mb-4">
                TEST STRICT LIMIT
            </button>

            <div id="strict-result" class="w-full min-h-[80px] rounded-lg p-3 text-sm hidden"></div>
            <div class="mt-auto w-full pt-4 border-t border-slate-700 flex justify-between text-xs text-slate-500">
                <span id="strict-limit">Limit: --</span>
                <span id="strict-remaining">Remaining: --</span>
            </div>
        </div>

        <!-- Custom Limit Card -->
        <div class="glass-card rounded-2xl p-6 flex flex-col items-center hover:transform hover:scale-105 transition duration-300">
            <div class="text-4xl text-cyan-400 mb-4"><i class="fa-solid fa-gear"></i></div>
            <h2 class="text-2xl font-bold mb-2">Custom Rate Limit</h2>
            <p class="text-slate-400 text-sm mb-6 text-center">5 req/min with custom response</p>
            
            <button onclick="testLimit('custom')" class="w-full bg-cyan-500 hover:bg-cyan-600 text-white font-bold py-3 px-6 rounded-lg transition duration-200 shadow-lg shadow-cyan-500/30 mb-4">
                TEST CUSTOM LIMIT
            </button>

            <div id="custom-result" class="w-full min-h-[80px] rounded-lg p-3 text-sm hidden"></div>
            <div class="mt-auto w-full pt-4 border-t border-slate-700 flex justify-between text-xs text-slate-500">
                <span id="custom-limit">Limit: --</span>
                <span id="custom-remaining">Remaining: --</span>
            </div>
        </div>

        <!-- No Limit Card -->
        <div class="glass-card rounded-2xl p-6 flex flex-col items-center hover:transform hover:scale-105 transition duration-300">
            <div class="text-4xl text-purple-300 mb-4"><i class="fa-solid fa-infinity"></i></div>
            <h2 class="text-2xl font-bold mb-2">No Rate Limit</h2>
            <p class="text-slate-400 text-sm mb-6 text-center">Unlimited requests for comparison</p>
            
            <button onclick="testLimit('no-limit')" class="w-full bg-slate-600 hover:bg-slate-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 shadow-lg shadow-slate-500/30 mb-4">
                TEST NO LIMIT
            </button>

            <div id="no-limit-result" class="w-full min-h-[80px] rounded-lg p-3 text-sm hidden"></div>
            <div class="mt-auto w-full pt-4 border-t border-slate-700 flex justify-between text-xs text-slate-500">
                <span id="no-limit-limit">Limit: ∞</span>
                <span id="no-limit-remaining">Remaining: ∞</span>
            </div>
        </div>

    </div>

    <script>
        async function testLimit(type) {
            const resultDiv = document.getElementById(`${type}-result`);
            const limitSpan = document.getElementById(`${type}-limit`);
            const remainingSpan = document.getElementById(`${type}-remaining`);
            
            // Clear previous result
            resultDiv.className = 'w-full min-h-[80px] rounded-lg p-3 text-sm hidden';
            resultDiv.innerHTML = '';

            try {
                let url = `/api/${type}-limit`;
                if (type === 'no-limit') {
                    url = '/api/no-limit';
                }

                const response = await fetch(url, {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await response.json();
                
                // Get headers
                const limit = response.headers.get('X-RateLimit-Limit');
                const remaining = response.headers.get('X-RateLimit-Remaining');

                if (limit) limitSpan.innerText = `Limit: ${limit} req/min`;
                if (remaining) remainingSpan.innerText = `Remaining: ${remaining}`;

                resultDiv.classList.remove('hidden');
                
                if (response.ok) {
                    resultDiv.className = 'w-full min-h-[80px] rounded-lg p-3 text-sm bg-green-900/50 border border-green-500 text-green-200';
                    resultDiv.innerHTML = `<p><i class="fa-solid fa-check-circle mr-2"></i>Request successful!</p>
                                         <p class="text-xs mt-1 text-green-400">${data.message}</p>
                                         <p class="text-xs mt-1 text-slate-400">Time: ${new Date().toLocaleTimeString()}</p>`;
                } else if (response.status === 429) {
                    resultDiv.className = 'w-full min-h-[80px] rounded-lg p-3 text-sm bg-red-900/50 border border-red-500 text-red-200';
                    resultDiv.innerHTML = `<p><i class="fa-solid fa-circle-xmark mr-2"></i>Too Many Requests!</p>
                                         <p class="text-xs mt-1 text-red-300">${data.message || 'Rate limit exceeded.'}</p>`;
                } else {
                    resultDiv.className = 'w-full min-h-[80px] rounded-lg p-3 text-sm bg-orange-900/50 border border-orange-500 text-orange-200';
                    resultDiv.innerHTML = `<p>Error: ${response.status}</p>`;
                }

            } catch (error) {
                console.error(error);
                resultDiv.classList.remove('hidden');
                resultDiv.className = 'w-full min-h-[80px] rounded-lg p-3 text-sm bg-red-900/50 border border-red-500 text-red-200';
                resultDiv.innerHTML = `<p>Fetch Error</p>`;
            }
        }
        let authToken = '';

        async function getToken() {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            try {
                const response = await fetch('/api/login', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ email, password })
                });
                const data = await response.json();
                
                if(response.ok) {
                    authToken = data.token;
                    document.getElementById('token-result').classList.remove('hidden');
                    document.getElementById('token-display').textContent = authToken;
                    document.getElementById('token-user').textContent = data.user;
                }
            } catch(e) {
                alert('Login failed');
            }
        }

        async function testApi(type) {
            const resultDiv = document.getElementById('api-result');
            const limitSpan = document.getElementById('api-limit');
            const remainingSpan = document.getElementById('api-remaining');
            
            let url = type === 'public' ? '/api/no-limit' : '/api/private';
            let headers = { 'Accept': 'application/json' };
            
            if(type === 'protected') {
                if(!authToken) {
                    resultDiv.classList.remove('hidden');
                    resultDiv.className = 'w-full min-h-[120px] rounded-lg p-4 text-sm border bg-red-900/50 border-red-500 text-red-200';
                    resultDiv.innerHTML = 'Error: Please generate a token first!';
                    return;
                }
                headers['Authorization'] = `Bearer ${authToken}`;
            }

            try {
                const response = await fetch(url, { headers });
                const data = await response.json();
                
                const limit = response.headers.get('X-RateLimit-Limit');
                const remaining = response.headers.get('X-RateLimit-Remaining');

                if (limit) limitSpan.innerText = `Limit: ${limit} req/min`;
                if (remaining) remainingSpan.innerText = `Remaining: ${remaining}`;

                resultDiv.classList.remove('hidden');
                
                if (response.ok) {
                    resultDiv.className = 'w-full min-h-[120px] rounded-lg p-4 text-sm border bg-green-900/50 border-green-500 text-green-200 font-mono';
                    resultDiv.innerHTML = `<div class="mb-2"><i class="fa-solid fa-check-circle mr-2"></i>Request Successful</div>
                                         <pre class="text-xs overflow-x-auto">${JSON.stringify(data, null, 2)}</pre>`;
                } else {
                    resultDiv.className = 'w-full min-h-[120px] rounded-lg p-4 text-sm border bg-red-900/50 border-red-500 text-red-200';
                    resultDiv.innerHTML = `Error ${response.status}: ${data.message || 'Request failed'}`;
                }
            } catch (error) {
                resultDiv.classList.remove('hidden');
                resultDiv.innerHTML = 'Fetch Error';
            }
        }
    </script>
</body>
</html>
