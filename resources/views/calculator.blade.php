<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Date Matcher</title>
     
            @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
                @vite(['resources/css/app.css', 'resources/js/app.js'])
            @else
                <script src="https://cdn.tailwindcss.com"></script>
                <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
            @endif
   
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 font-sans antialiased">

    <div class="max-w-4xl mx-auto px-4 py-12" x-data="dateApp()">
        
        <header class="text-center mb-12">
            <h1 class="text-4xl font-bold text-blue-900 tracking-tight mb-4">
                Date Pattern Matcher
            </h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
             Welcome to our app , here you can find every occurrence of a specific day and date combination across a range of years. 
            </p>
        </header>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-8">
            <form @submit.prevent="fetchResults" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Day of Week</label>
                    <select  x-model="formData.day" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
                        <template x-for="d in days">
                            <option :value="d" x-text="d"></option>
                        </template>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Day of Month</label>
                    <select x-model="formData.date" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
                        <template  x-for="i in 32">
                            <option :value="i" x-text="i"></option>
                        </template>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Start Year</label>
                    <select x-model="formData.start_year" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
                        <template x-for="year in yearRange">
                            <option :value="year" x-text="year"></option>
                        </template>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">End Year</label>
                    <select x-model="formData.end_year" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
                        <template x-for="year in yearRange">
                            <option :value="year" x-text="year"></option>
                        </template>
                    </select>
                </div>

                <div class="md:col-span-2 lg:col-span-4 mt-4">
                    <button type="submit" 
                            :disabled="loading"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition-all shadow-lg shadow-blue-200 flex items-center justify-center space-x-2">
                        <span x-show="!loading">Calculate Matches</span>
                        <span x-show="loading" class="animate-spin border-2 border-white border-t-transparent rounded-full h-5 w-5"></span>
                        <span x-show="loading">Processing...</span>
                    </button>
                </div>
            </form>
        </div>

        <div x-show="results.length > 0" x-cloak x-transition class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-8 py-5 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                <h2 class="text-xl font-bold text-gray-800">Found <span x-text="results.length" class="text-blue-600"></span> Occurrences</h2>
                <button @click="copyResults" class="text-sm text-blue-600 font-semibold hover:text-blue-800 transition">Copy to Clipboard</button>
            </div>
            
            <div class="p-8 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                <template x-for="date in results">
                    <div class="bg-blue-50 text-blue-700 font-mono text-sm py-2 px-3 rounded-lg text-center border border-blue-100">
                        <span x-text="date"></span>
                    </div>
                </template>
            </div>
        </div>

        <div x-show="searched && results.length === 0 && !loading" x-cloak class="text-center py-12">
            <div class="text-gray-400 mb-4 text-5xl">📅</div>
            <h3 class="text-lg font-medium text-gray-900">No matches found</h3>
            <p class="text-gray-500">Try adjusting your year range or day/date combination.</p>
        </div>
    </div>

    <script>
        function dateApp() {
            return {
                loading: false,
                searched: false,
                results: [],
                days: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
                yearRange: Array.from({length: 101}, (_, i) => 2000 + i), 
                formData: {
                    day: 'Monday',
                    date: 1,
                    start_year: 2000,
                    end_year: 2000
                },
                async fetchResults() {
                    this.loading = true;
                    this.searched = true;
                    try {
                        const params = new URLSearchParams(this.formData);
                        const response = await fetch(`/api/calculate-dates?${params}`,{
                                                            headers: {
                                                            'Accept': 'application/json', 
                                                        }
                                                    });
                        if(response.status===422){alert('make sure the start year is  before the end year ');this.results = [];return}                                                    
                        console.log(response);
                        const json = await response.json();
                        this.results = json.data || [];
                    } catch (error) {
                        alert('Error fetching data. Check your Laravel server.');
                    } finally {
                        this.loading = false;
                    }
                },
                copyResults() {
                    navigator.clipboard.writeText(this.results.join(', '));
                    alert('Copied to clipboard!');
                }
            }
        }
    </script>
</body>
</html>