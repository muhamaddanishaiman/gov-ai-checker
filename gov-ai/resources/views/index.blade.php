<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Gov AI Checker</title>
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
        
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body {
                font-family: 'Inter', sans-serif;
                background-color: #EFECE5;
                /* Plus grid background */
                background-image: url("data:image/svg+xml,%3Csvg width='80' height='80' viewBox='0 0 80 80' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M40 36V44M36 40H44' stroke='%23D1CCC1' stroke-width='1' stroke-linecap='round' fill='none'/%3E%3C/svg%3E");
            }
            .font-serif {
                font-family: 'Playfair Display', serif;
            }
            .text-brand-green {
                color: #123524;
            }
            .bg-brand-green {
                background-color: #123524;
            }
            .bg-brand-green:hover {
                background-color: #0E291B;
            }
            .card-bg {
                background-color: #F8F6F1;
            }
            .nav-bg {
                background-color: rgba(248, 246, 241, 0.95);
            }
            /* Hide default file input */
            input[type="file"] {
                display: none;
            }
        </style>
    </head>
    <body class="antialiased min-h-screen flex flex-col items-center pt-6 px-4 md:px-8 text-gray-800 relative selection:bg-[#123524] selection:text-white overflow-x-hidden">
        
        <!-- Navbar -->
        <nav class="w-full max-w-6xl nav-bg backdrop-blur-md rounded-full px-6 py-4 flex justify-between items-center shadow-[0_2px_10px_rgb(0,0,0,0.03)] border border-black/5 z-10">
            <div class="flex items-center gap-3">
                <svg viewBox="0 0 24 24" fill="currentColor" class="h-7 w-7 text-brand-green">
                  <path d="M2 6C2 4.89543 2.89543 4 4 4H10.5C11.3284 4 12 4.67157 12 5.5V19.5C12 18.6716 11.3284 18 10.5 18H4C2.89543 18 2 17.1046 2 16V6ZM13.5 4H20C21.1046 4 22 4.89543 22 6V16C22 17.1046 21.1046 18 20 18H13.5C12.6716 18 12 18.6716 12 19.5V5.5C12 4.67157 12.6716 4 13.5 4Z"/>
                </svg>
                <span class="font-bold text-xl tracking-tight text-brand-green">Gov AI Checker</span>
            </div>
            <div class="hidden md:flex gap-8 text-sm font-semibold text-gray-500">
                <a href="#" class="text-brand-green">Home</a>
                <a href="#" class="hover:text-brand-green transition-colors">Learn</a>
                <a href="#" class="hover:text-brand-green transition-colors">About</a>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="w-full max-w-4xl flex flex-col items-center mt-20 mb-16 text-center z-10">
            <h1 class="text-5xl md:text-[4.5rem] font-serif font-semibold tracking-tight mb-6 leading-[1.05] text-brand-green">
                Validate Your<br/> Bantuan Documents
            </h1>
            <p class="text-lg md:text-xl text-gray-600 mb-12 max-w-2xl leading-relaxed">
                Upload your IC or Salary Slip for a free, instant AI analysis of potentially problematic information.
            </p>

            <!-- Main Card -->
            <div class="card-bg p-8 md:p-12 rounded-[2rem] shadow-[0_8px_40px_rgb(0,0,0,0.06)] border border-black/5 w-full max-w-[42rem] relative">
                
                <!-- Checklist Tracker -->
                <div class="mb-10 p-6 bg-white rounded-2xl shadow-[0_2px_10px_rgb(0,0,0,0.02)] border border-black/5 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 text-left">
                    <div>
                        <h3 class="font-bold text-gray-900 text-lg mb-1">Application Status</h3>
                        <p class="text-sm text-gray-500">Ensure all required documents are valid</p>
                    </div>
                    <div class="flex flex-col gap-3 w-full md:w-auto bg-gray-50 p-3 rounded-xl border border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="w-6 h-6 flex items-center justify-center bg-gray-200 rounded-full text-xs shadow-sm" id="ic-status">❌</div>
                            <span class="font-medium text-gray-700 text-sm">Identity Card (IC)</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-6 h-6 flex items-center justify-center bg-gray-200 rounded-full text-xs shadow-sm" id="salary-status">❌</div>
                            <span class="font-medium text-gray-700 text-sm">Salary Slip</span>
                        </div>
                    </div>
                </div>

                <!-- Upload Section -->
                <label for="fileInput" id="dropzone" class="border-2 border-dashed border-[#D1CCC1] rounded-2xl p-10 mb-8 flex flex-col items-center justify-center bg-transparent transition-colors hover:bg-black/[0.02] cursor-pointer group">
                    <div class="bg-white p-3 rounded-full shadow-sm mb-4 group-hover:scale-110 transition-transform duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                    </div>
                    <p class="text-gray-800 font-medium mb-1 text-lg" id="fileLabelText">
                        Drop your document PDF/Image <span class="font-normal text-gray-500">or click to browse</span>
                    </p>
                    <p class="text-sm text-gray-400">Files up to 15 MB</p>
                </label>
                
                <input type="file" id="fileInput" onchange="updateFileName()">

                <button id="analyzeBtn" onclick="uploadFile()" class="bg-brand-green w-full py-4 rounded-full text-white text-lg font-medium transition-transform active:scale-[0.98] flex justify-center items-center gap-2 hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                    <span id="btnText">Analyze Document</span>
                    <svg id="btnIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>

                <p class="text-xs text-gray-400 mt-6 font-medium tracking-wide">
                    Free · No account required · Secure processing · <a href="#" class="underline hover:text-gray-600">Privacy</a>
                </p>

                <!-- Result Display Area -->
                <div id="result" class="mt-8 text-left hidden p-6 bg-white rounded-2xl shadow-sm border border-black/5"></div>
            </div>
        </main>

        <script>
        function updateFileName() {
            const input = document.getElementById('fileInput');
            const label = document.getElementById('fileLabelText');
            if (input.files && input.files.length > 0) {
                label.innerHTML = `Selected: <span class="font-semibold text-brand-green">${input.files[0].name}</span>`;
            } else {
                label.innerHTML = `Drop your document PDF/Image <span class="font-normal text-gray-500">or click to browse</span>`;
            }
        }

        async function uploadFile() {
            const fileInput = document.getElementById('fileInput');
            if (!fileInput.files || fileInput.files.length === 0) {
                alert('Please select a file first.');
                return;
            }

            const file = fileInput.files[0];
            const formData = new FormData();
            formData.append('document', file);

            const btn = document.getElementById('analyzeBtn');
            const btnText = document.getElementById('btnText');
            const btnIcon = document.getElementById('btnIcon');
            const resultDiv = document.getElementById('result');

            // Set loading state
            btn.disabled = true;
            btnText.innerText = 'Analyzing...';
            btnIcon.classList.add('hidden');
            resultDiv.classList.add('hidden');

            try {
                let res = await fetch('/check', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                let data = await res.json();
                
                let issuesHtml = data.issues && data.issues.length ? `<ul class="list-disc pl-5 mt-1 text-red-600">` + data.issues.map(i => `<li>${i}</li>`).join('') + `</ul>` : '<p class="text-gray-500 italic mt-1">None</p>';
                let suggestionsHtml = data.suggestions && data.suggestions.length ? `<ul class="list-disc pl-5 mt-1 text-amber-600">` + data.suggestions.map(s => `<li>${s}</li>`).join('') + `</ul>` : '<p class="text-gray-500 italic mt-1">None</p>';
                let validityColor = data.valid ? 'text-green-600' : 'text-red-600';

                // Update Checklist UI
                if (data.valid) {
                    let docType = (data.document_type || '').toLowerCase();
                    if (docType.includes('ic') || docType.includes('identity')) {
                        const icStatus = document.getElementById('ic-status');
                        icStatus.innerText = '✅';
                        icStatus.classList.replace('bg-gray-200', 'bg-green-100');
                    } else if (docType.includes('salary')) {
                        const salaryStatus = document.getElementById('salary-status');
                        salaryStatus.innerText = '✅';
                        salaryStatus.classList.replace('bg-gray-200', 'bg-green-100');
                    }
                }

                resultDiv.innerHTML = `
                    <h3 class="text-xl font-bold text-gray-900 mb-4 border-b pb-2">Analysis Result</h3>
                    <div class="space-y-4 text-sm">
                        <div>
                            <span class="font-semibold text-gray-700">Detected Document:</span> 
                            <span class="ml-2 font-medium bg-gray-100 px-2 py-1 rounded">${data.document_type || 'Unknown'}</span>
                        </div>
                        <div>
                            <span class="font-semibold text-gray-700">Validity:</span> 
                            <span class="ml-2 font-bold ${validityColor}">${data.valid ? 'Valid' : 'Invalid'}</span>
                        </div>
                        <div class="bg-red-50 p-3 rounded-lg border border-red-100">
                            <span class="font-semibold text-red-800">Issues:</span>
                            ${issuesHtml}
                        </div>
                        <div class="bg-amber-50 p-3 rounded-lg border border-amber-100">
                            <span class="font-semibold text-amber-800">Suggestions:</span>
                            ${suggestionsHtml}
                        </div>
                    </div>
                `;
                resultDiv.classList.remove('hidden');
            } catch (error) {
                console.error(error);
                resultDiv.innerHTML = `<p class="text-red-600 font-medium p-4 bg-red-50 rounded-lg">An error occurred during analysis. Please try again.</p>`;
                resultDiv.classList.remove('hidden');
            } finally {
                // Reset button
                btn.disabled = false;
                btnText.innerText = 'Analyze Document';
                btnIcon.classList.remove('hidden');
            }
        }
        </script>
    </body>
</html>
