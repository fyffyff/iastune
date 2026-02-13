<?php
include "auth.php";
?>

<?php
session_start();
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}
?>

<!doctype html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SimuLab — Simulations Library</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'dark-bg': '#f8fafc',
                        'glass': 'rgba(99, 102, 241, 0.08)',
                        'glass-border': 'rgba(99, 102, 241, 0.2)'
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-out',
                        'slide-in': 'slideIn 0.4s ease-out',
                        'bounce': 'bounce 0.6s ease-out',
                        'pulse-glow': 'pulseGlow 2s ease-in-out infinite',
                        'float': 'float 3s ease-in-out infinite',
                        'shimmer': 'shimmer 2s linear infinite',
                        'scale-in': 'scaleIn 0.4s ease-out',
                        'slide-up': 'slideUp 0.5s ease-out'
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0', transform: 'translateY(20px) scale(0.95)' },
                            '100%': { opacity: '1', transform: 'translateY(0) scale(1)' }
                        },
                        slideIn: {
                            '0%': { opacity: '0', transform: 'translateX(-30px)' },
                            '100%': { opacity: '1', transform: 'translateX(0)' }
                        },
                        bounce: {
                            '0%, 20%, 53%, 80%, 100%': { transform: 'translateY(0)' },
                            '40%, 43%': { transform: 'translateY(-10px)' },
                            '70%': { transform: 'translateY(-5px)' }
                        },
                        pulseGlow: {
                            '0%, 100%': { boxShadow: '0 0 20px rgba(99, 102, 241, 0.3)' },
                            '50%': { boxShadow: '0 0 30px rgba(99, 102, 241, 0.6), 0 0 40px rgba(34, 197, 94, 0.3)' }
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-5px)' }
                        },
                        shimmer: {
                            '0%': { backgroundPosition: '-200% 0' },
                            '100%': { backgroundPosition: '200% 0' }
                        },
                        scaleIn: {
                            '0%': { opacity: '0', transform: 'scale(0.8)' },
                            '100%': { opacity: '1', transform: 'scale(1)' }
                        },
                        slideUp: {
                            '0%': { opacity: '0', transform: 'translateY(30px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        }
                    }
                }
            }
        }
    </script>
  <style>
        body {
            box-sizing: border-box;
        }
        .glass-morphism {
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }
        
        .card-hover {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .card-hover::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.6s;
            z-index: 1;
        }
        
        .card-hover:hover::before {
            left: 100%;
        }
        
        .card-hover:hover {
            transform: perspective(1000px) rotateX(3deg) rotateY(-3deg) scale(1.05);
            box-shadow: 0 20px 40px rgba(99, 102, 241, 0.3), 0 0 30px rgba(34, 197, 94, 0.2);
        }
        
        .neon-border {
            box-shadow: 0 0 15px rgba(99, 102, 241, 0.6), 0 0 30px rgba(99, 102, 241, 0.4);
            animation: pulse-glow 2s ease-in-out infinite;
        }
        
        .search-highlight {
            background: linear-gradient(90deg, rgba(99, 102, 241, 0.3), rgba(59, 130, 246, 0.3));
            padding: 2px 6px;
            border-radius: 6px;
            color: #4f46e5;
            font-weight: 600;
            animation: pulse-glow 1.5s ease-in-out infinite;
        }
        
        .dark .search-highlight {
            background: linear-gradient(90deg, rgba(99, 102, 241, 0.5), rgba(34, 197, 94, 0.5));
            color: #a5b4fc;
        }
        
        .floating-element {
            animation: float 3s ease-in-out infinite;
        }
        
        .shimmer-text {
            background: linear-gradient(90deg, #4f46e5, #2563eb, #7c3aed, #4f46e5);
            background-size: 200% 100%;
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: shimmer 3s linear infinite;
        }
        
        .dark .shimmer-text {
            background: linear-gradient(90deg, #60a5fa, #34d399, #a78bfa, #60a5fa);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .stagger-animation {
            opacity: 0;
            animation: fadeIn 0.6s ease-out forwards;
        }
    </style>
  <style>@view-transition { navigation: auto; }</style>
  <script src="/_sdk/data_sdk.js" type="text/javascript"></script>
  <script src="/_sdk/element_sdk.js" type="text/javascript"></script>
 </head>
 <body class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 text-slate-900 min-h-screen font-sans"><!-- Top Bar -->
  <header class="fixed top-0 left-0 right-0 z-50 bg-white/80 glass-morphism border-b border-indigo-200/40 shadow-sm">
   <div class="flex items-center justify-between px-6 py-4">
    <h1 class="text-2xl font-bold bg-gradient-to-r from-indigo-600 via-purple-600 to-blue-600 bg-clip-text text-transparent floating-element">IAStune Mind Maps</h1>
    <div class="flex items-center gap-4 flex-1 max-w-2xl mx-8">
     <div class="relative flex-1">
      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
       <svg class="h-5 w-5 text-indigo-400" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
       </svg>
      </div><input type="text" id="searchInput" placeholder="Search Mind Maps... (Press / to focus)" class="w-full pl-10 pr-10 py-3 bg-white/80 border border-indigo-200/40 rounded-full text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 shadow-sm"> <button id="clearSearch" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-700 transition-colors duration-200" style="display: none;">
       <svg class="h-5 w-5" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
       </svg></button>
     </div>
    </div><button id="themeToggle" class="p-2 rounded-full bg-indigo-100 border border-indigo-300 hover:bg-indigo-200 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 shadow-sm"> <span class="text-2xl">🌙</span> </button>
   </div>
  </header>
  <div class="flex pt-20"><!-- Sidebar -->
   <aside class="fixed left-0 top-20 bottom-0 w-80 bg-white/60 glass-morphism border-r border-indigo-200/40 p-6 overflow-y-auto shadow-sm">
    <nav>
     <ul class="space-y-3" id="subjectList"><!-- Subjects will be populated by JavaScript -->
     </ul>
    </nav>
    <div class="mt-8 pt-6 border-t border-indigo-200/40"><button id="shortcutsBtn" class="flex items-center gap-2 text-slate-500 hover:text-indigo-600 transition-colors duration-200 text-sm font-medium"> <span>❓</span> <span>Keyboard Shortcuts</span> </button>
    </div>
   </aside><!-- Main Content -->
   <main class="flex-1 ml-80 p-6">
    <div class="mb-8">
     <div class="mb-4">
      <h2 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 via-purple-600 to-blue-600 bg-clip-text text-transparent mb-2 floating-element">✨ IAStune</h2>
      <p class="text-slate-600 text-lg">Experience our interactive learning mind maps across different subjects</p>
     </div>
     <div id="activeSubjectChip" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-indigo-100 to-blue-100 border border-indigo-300/60 rounded-full text-indigo-700 font-medium floating-element shadow-sm"><span id="activeSubjectIcon" class="text-xl">🚜</span> <span id="activeSubjectName">Agriculture</span>
     </div>
    </div>
    <div id="simulationsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6"><!-- Simulation cards will be populated by JavaScript -->
    </div>
    <div id="emptyState" class="text-center py-16 hidden animate-fade-in">
     <div class="text-6xl mb-4 floating-element">
          
     </div>
     <h3 class="text-xl font-semibold text-gray-300 mb-2 animate-slide-up">No results found</h3>
     <p class="text-gray-400 mb-6 animate-slide-up" style="animation-delay: 0.1s">Try adjusting your search terms or browse different subjects</p><button id="clearSearchBtn" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 hover:scale-105 rounded-full font-medium transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 animate-scale-in" style="animation-delay: 0.2s"> Clear Search </button>
    </div>
   </main>
  </div><!-- Modal -->
  <div id="modal" class="fixed inset-0 z-50 hidden">
   <div class="fixed inset-0 bg-black/30 glass-morphism backdrop-blur-sm" id="modalOverlay"></div>
   <div class="fixed inset-0 flex items-center justify-center p-4">
    <div class="bg-white border border-indigo-200/40 rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto glass-morphism shadow-2xl">
     <div class="p-6">
      <div class="flex items-start justify-between mb-4">
       <div>
        <h2 id="modalTitle" class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-blue-600 bg-clip-text text-transparent mb-2"></h2>
        <p id="modalRole" class="text-slate-600"></p>
       </div><button id="closeModal" class="p-2 hover:bg-slate-100 rounded-full transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-slate-500">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg></button>
      </div>
      <div id="modalBadges" class="flex flex-wrap gap-2 mb-6"><!-- Badges will be populated by JavaScript -->
      </div>
      <div class="mb-6">
       <h3 class="text-lg font-semibold text-slate-800 mb-3">Brief</h3>
       <p id="modalBrief" class="text-slate-600 leading-relaxed"></p>
      </div>
      <div class="mb-8">
       <h3 class="text-lg font-semibold text-slate-800 mb-3">Learning Points</h3>
       <ul id="modalLearningPoints" class="space-y-2 text-slate-600"><!-- Learning points will be populated by JavaScript -->
       </ul>
      </div>
      <div class="flex gap-3"><button id="launchSimulation" class="flex-1 px-6 py-3 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white rounded-xl font-semibold transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 transform hover:scale-105 shadow-lg"> 🚀 Launch Simulation </button> <button id="closeModalBtn" class="px-6 py-3 bg-slate-100 border border-slate-300 hover:bg-slate-200 text-slate-800 rounded-xl font-medium transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-slate-400"> Close </button>
      </div>
     </div>
    </div>
   </div>
  </div><!-- Shortcuts Modal -->
  <div id="shortcutsModal" class="fixed inset-0 z-50 hidden">
   <div class="fixed inset-0 bg-black/30 glass-morphism backdrop-blur-sm" id="shortcutsOverlay"></div>
   <div class="fixed inset-0 flex items-center justify-center p-4">
    <div class="bg-white border border-indigo-200/40 rounded-2xl max-w-md w-full glass-morphism shadow-2xl">
     <div class="p-6">
      <div class="flex items-center justify-between mb-4">
       <h2 class="text-xl font-bold text-slate-800">Keyboard Shortcuts</h2><button id="closeShortcuts" class="p-2 hover:bg-slate-100 rounded-full transition-colors duration-200 text-slate-500">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg></button>
      </div>
      <div class="space-y-3 text-sm">
       <div class="flex justify-between"><span class="text-slate-600">Focus search</span> <kbd class="px-2 py-1 bg-indigo-100 border border-indigo-300 rounded text-xs text-indigo-700 font-medium">/</kbd>
       </div>
       <div class="flex justify-between"><span class="text-slate-600">Clear search</span> <kbd class="px-2 py-1 bg-indigo-100 border border-indigo-300 rounded text-xs text-indigo-700 font-medium">Esc</kbd>
       </div>
       <div class="flex justify-between"><span class="text-slate-600">Previous subject</span> <kbd class="px-2 py-1 bg-indigo-100 border border-indigo-300 rounded text-xs text-indigo-700 font-medium">[</kbd>
       </div>
       <div class="flex justify-between"><span class="text-slate-600">Next subject</span> <kbd class="px-2 py-1 bg-indigo-100 border border-indigo-300 rounded text-xs text-indigo-700 font-medium">]</kbd>
       </div>
       <div class="flex justify-between"><span class="text-slate-600">Toggle theme</span> <kbd class="px-2 py-1 bg-indigo-100 border border-indigo-300 rounded text-xs text-indigo-700 font-medium">L</kbd>
       </div>
      </div>
     </div>
    </div>
   </div>
  </div>
  <script>
        // Sample simulations data
        const simulations = [
            // Agriculture
            {
                id: "land-reforms",
                title: "Land Reforms",
                role: "Study agricultural land reform policies",
                difficulty: "Intermediate",
                duration: "14 min",
                xp: 140,
                url: "https://www.iastune.in/mindmaps/agriculture/Land%20Reforms.html",
                subjects: ["Agriculture"],
                brief: "Understand land reform policies and their impact on agriculture.",
                learningPoints: ["Land distribution", "Agricultural policies", "Farmer welfare"]
            },
            // Economics
            {
                id: "introduction",
                title: "Introduction",
                role: "Learn economic fundamentals",
                difficulty: "Basic",
                duration: "10 min",
                xp: 100,
                url: "https://www.iastune.in/mindmaps/economics/1.-Introduction.html",
                subjects: ["Economics"],
                brief: "Introduction to economics and fundamental concepts.",
                learningPoints: ["Economic basics", "Fundamental principles"]
            },
            {
                id: "progress-growth",
                title: "Progress, Growth & Development",
                role: "Analyze economic indicators and development",
                difficulty: "Intermediate",
                duration: "13 min",
                xp: 130,
                url: "https://www.iastune.in/mindmaps/economics/2.-Progress%2CGrowth%2CDevelopment.html",
                subjects: ["Economics"],
                brief: "Understand economic progress, growth, and development metrics.",
                learningPoints: ["Economic indicators", "Development theories"]
            },
            {
                id: "indian-economy-evolution",
                title: "Indian Economy - Evolution",
                role: "Understand India's economic history",
                difficulty: "Intermediate",
                duration: "16 min",
                xp: 160,
                url: "https://www.iastune.in/mindmaps/economics/3.-Indian%20Economy-Evolution.html",
                subjects: ["Economics"],
                brief: "Trace the evolution and development of the Indian economy.",
                learningPoints: ["Economic history", "Development phases"]
            },
            {
                id: "economic-planning",
                title: "Economic Planning",
                role: "Formulate economic plans",
                difficulty: "Advanced",
                duration: "18 min",
                xp: 180,
                url: "https://www.iastune.in/mindmaps/economics/4.-Economic%20Planning.html",
                subjects: ["Economics"],
                brief: "Learn economic planning principles and methodologies.",
                learningPoints: ["Planning strategies", "Resource allocation"]
            },
            {
                id: "planning-india",
                title: "Planning in India",
                role: "Analyze India's five-year plans",
                difficulty: "Intermediate",
                duration: "14 min",
                xp: 140,
                url: "https://www.iastune.in/mindmaps/economics/5.-Planning%20in%20India.html",
                subjects: ["Economics"],
                brief: "Explore India's five-year plans and planning models.",
                learningPoints: ["Five-year plans", "Policy implementation"]
            },
            {
                id: "economic-reforms",
                title: "Economic Reforms",
                role: "Navigate economic policy reforms",
                difficulty: "Intermediate",
                duration: "15 min",
                xp: 150,
                url: "https://www.iastune.in/mindmaps/economics/6.-Economic%20Reforms.html",
                subjects: ["Economics"],
                brief: "Understand economic reforms and their implications.",
                learningPoints: ["Reform policies", "Economic transformation"]
            },
            {
                id: "inflation-deflation",
                title: "Inflation & Deflation",
                role: "Analyze price level changes",
                difficulty: "Advanced",
                duration: "16 min",
                xp: 160,
                url: "https://www.iastune.in/mindmaps/economics/7.-Inflation%20n%20Deflation.html",
                subjects: ["Economics"],
                brief: "Explore inflation, deflation, and price stability mechanisms.",
                learningPoints: ["Inflation-unemployment trade-offs", "Monetary policy"]
            },
            {
                id: "business-cycle",
                title: "Business Cycle",
                role: "Understand economic cycles",
                difficulty: "Intermediate",
                duration: "14 min",
                xp: 140,
                url: "https://www.iastune.in/mindmaps/economics/8.-Business%20Cycle.html",
                subjects: ["Economics"],
                brief: "Learn about business cycles and economic fluctuations.",
                learningPoints: ["Cyclical patterns", "Economic phases"]
            },
            {
                id: "industry",
                title: "Industry",
                role: "Explore industrial sectors and development",
                difficulty: "Intermediate",
                duration: "12 min",
                xp: 120,
                url: "https://www.iastune.in/mindmaps/economics/10.-Industry.html",
                subjects: ["Economics"],
                brief: "Understanding industrial sectors and their role in economic development.",
                learningPoints: ["Industrial sectors", "Economic development"]
            },
            {
                id: "financial-market",
                title: "Financial Market",
                role: "Navigate financial markets and instruments",
                difficulty: "Advanced",
                duration: "15 min",
                xp: 150,
                url: "https://www.iastune.in/mindmaps/economics/13.-Financial%20Market.html",
                subjects: ["Economics"],
                brief: "Explore financial markets, instruments, and trading mechanisms.",
                learningPoints: ["Financial instruments", "Market operations"]
            },
            {
                id: "intl-econ-orgs",
                title: "International Economic Organisations",
                role: "Understand global economic institutions",
                difficulty: "Intermediate",
                duration: "14 min",
                xp: 140,
                url: "https://www.iastune.in/mindmaps/economics/18.-International%20Economic%20Organisations%20India.html",
                subjects: ["Economics"],
                brief: "Learn about international economic organizations and their roles.",
                learningPoints: ["Global institutions", "International trade"]
            },
            {
                id: "public-finance",
                title: "Public Finance",
                role: "Manage government financial resources",
                difficulty: "Intermediate",
                duration: "14 min",
                xp: 140,
                url: "https://www.iastune.in/mindmaps/economics/20.-Public%20Finance.html",
                subjects: ["Economics"],
                brief: "Manage government revenue receipts and optimize tax collection strategies.",
                learningPoints: ["Government revenue", "Tax policy", "Fiscal planning"]
            },
            {
                id: "inclusive-growth",
                title: "Inclusive Growth",
                role: "Design equitable growth policies",
                difficulty: "Intermediate",
                duration: "15 min",
                xp: 150,
                url: "https://www.iastune.in/mindmaps/economics/22.%20INCLUSIVE%20GROWTH.html",
                subjects: ["Economics"],
                brief: "Explore strategies for inclusive economic growth.",
                learningPoints: ["Equity in growth", "Social welfare"]
            },
            {
                id: "investment-models",
                title: "Investment Models",
                role: "Analyze investment strategies",
                difficulty: "Advanced",
                duration: "17 min",
                xp: 170,
                url: "https://www.iastune.in/mindmaps/economics/Investment%20Models.html",
                subjects: ["Economics"],
                brief: "Explore various investment models and strategies.",
                learningPoints: ["Investment theory", "Portfolio management"]
            },
            {
                id: "ecosystem",
                title: "Ecosystem",
                role: "Study ecosystem structure and function",
                difficulty: "Basic",
                duration: "12 min",
                xp: 120,
                url: "https://www.iastune.in/mindmaps/environment-&-ecology/2.-Ecosystem.html",
                subjects: ["Environment & Ecology"],
                brief: "Understand ecosystem components and functioning.",
                learningPoints: ["Ecosystem structure", "Energy flow"]
            },
            {
                id: "natural-ecosystem",
                title: "Natural Ecosystem",
                role: "Explore natural ecosystems",
                difficulty: "Intermediate",
                duration: "14 min",
                xp: 140,
                url: "https://www.iastune.in/mindmaps/environment-&-ecology/3.-Natural-Ecosystem.html",
                subjects: ["Environment & Ecology"],
                brief: "Examine different types of natural ecosystems.",
                learningPoints: ["Ecosystem types", "Biodiversity"]
            },
            {
                id: "env-pollution",
                title: "Environmental Pollution",
                role: "Analyze pollution and mitigation",
                difficulty: "Intermediate",
                duration: "15 min",
                xp: 150,
                url: "https://www.iastune.in/mindmaps/environment-&-ecology/6.-Environmental%20Pollution.html",
                subjects: ["Environment & Ecology"],
                brief: "Study environmental pollution and solutions.",
                learningPoints: ["Pollution types", "Environmental protection"]
            },
            {
                id: "indian-biodiversity",
                title: "Indian Biodiversity",
                role: "Explore India's biological diversity",
                difficulty: "Intermediate",
                duration: "13 min",
                xp: 130,
                url: "https://www.iastune.in/mindmaps/environment-&-ecology/Indian%20Biodiversity.html",
                subjects: ["Environment & Ecology"],
                brief: "Understand India's unique biodiversity and conservation.",
                learningPoints: ["Endemic species", "Conservation efforts"]
            },
            {
                id: "marine-organisms",
                title: "Marine Organisms",
                role: "Study marine life and ecosystems",
                difficulty: "Intermediate",
                duration: "14 min",
                xp: 140,
                url: "https://www.iastune.in/mindmaps/environment-&-ecology/Marine%20organisms.html",
                subjects: ["Environment & Ecology"],
                brief: "Explore marine organisms and ocean ecosystems.",
                learningPoints: ["Marine life", "Ocean conservation"]
            },
            {
                id: "scheduled-animals-wpa",
                title: "Scheduled Animals of WPA 1972",
                role: "Understand wildlife protection laws",
                difficulty: "Basic",
                duration: "11 min",
                xp: 110,
                url: "https://www.iastune.in/mindmaps/environment-&-ecology/Scheduled%20animals%20of%20WPA%201972.html",
                subjects: ["Environment & Ecology"],
                brief: "Learn about scheduled animals under Wildlife Protection Act 1972.",
                learningPoints: ["Wildlife protection", "Conservation law"]
            },
            {
                id: "ethics-human-interface",
                title: "Ethics Human Interface",
                role: "Explore ethics in human interactions",
                difficulty: "Intermediate",
                duration: "13 min",
                xp: 130,
                url: "https://www.iastune.in/mindmaps/ethics/1.%20Ethics%20Human%20Interface.html",
                subjects: ["Ethics"],
                brief: "Understand ethics in human relationships and interactions.",
                learningPoints: ["Ethical principles", "Human relations"]
            },
            {
                id: "attitude",
                title: "Attitude",
                role: "Study attitude formation and change",
                difficulty: "Intermediate",
                duration: "12 min",
                xp: 120,
                url: "https://www.iastune.in/mindmaps/ethics/2.%20Attitude.html",
                subjects: ["Ethics"],
                brief: "Explore attitude, values, and behavioral ethics.",
                learningPoints: ["Attitude development", "Value systems"]
            },
            {
                id: "emotional-intelligence",
                title: "Emotional Intelligence",
                role: "Develop emotional intelligence skills",
                difficulty: "Intermediate",
                duration: "14 min",
                xp: 140,
                url: "https://www.iastune.in/mindmaps/ethics/4.%20Emotional%20Intelligence.html",
                subjects: ["Ethics"],
                brief: "Learn about emotional intelligence and self-awareness.",
                learningPoints: ["Emotional awareness", "Interpersonal skills"]
            },
            {
                id: "moral-thinkers",
                title: "Moral Thinkers & Philosophers",
                role: "Study ethical philosophies",
                difficulty: "Advanced",
                duration: "16 min",
                xp: 160,
                url: "https://www.iastune.in/mindmaps/ethics/5.%20Moral%20Thinkers%20Philosophers.html",
                subjects: ["Ethics"],
                brief: "Explore moral philosophers and ethical theories.",
                learningPoints: ["Philosophical schools", "Ethical theories"]
            },
            {
                id: "case-studies-ethics",
                title: "Case Studies",
                role: "Analyze ethical case studies",
                difficulty: "Intermediate",
                duration: "15 min",
                xp: 150,
                url: "https://www.iastune.in/mindmaps/ethics/8.%20Case%20Studies%20Mock%20Questions.html",
                subjects: ["Ethics"],
                brief: "Study real-world ethical dilemmas and case studies.",
                learningPoints: ["Case analysis", "Ethical reasoning"]
            },
            {
                id: "interior-earth",
                title: "Interior Earth",
                role: "Understand Earth's internal structure",
                difficulty: "Basic",
                duration: "11 min",
                xp: 110,
                url: "https://www.iastune.in/mindmaps/geography/1.%20Interior%20Earth.html",
                subjects: ["Geography"],
                brief: "Explore Earth's layers, crust, and interior composition.",
                learningPoints: ["Earth layers", "Plate tectonics"]
            },
            {
                id: "physical-features",
                title: "Physical Features",
                role: "Study Earth's physical geography",
                difficulty: "Intermediate",
                duration: "13 min",
                xp: 130,
                url: "https://www.iastune.in/mindmaps/geography/1.%20Physical%20Features.html",
                subjects: ["Geography"],
                brief: "Understand major physical features of Earth.",
                learningPoints: ["Landforms", "Physical processes"]
            },
            {
                id: "drainage-system",
                title: "Drainage System",
                role: "Analyze water flow and drainage patterns",
                difficulty: "Intermediate",
                duration: "12 min",
                xp: 120,
                url: "https://www.iastune.in/mindmaps/geography/2.%20Drainage%20system.html",
                subjects: ["Geography"],
                brief: "Study river systems and drainage patterns.",
                learningPoints: ["River systems", "Hydrological cycles"]
            },
            {
                id: "dynamic-surface",
                title: "Dynamic Surface of Earth",
                role: "Explore earth surface changes",
                difficulty: "Intermediate",
                duration: "14 min",
                xp: 140,
                url: "https://www.iastune.in/mindmaps/geography/2.%20Dynamic%20Surface%20of%20Earth.html",
                subjects: ["Geography"],
                brief: "Understand dynamic processes shaping Earth's surface.",
                learningPoints: ["Erosion", "Sedimentation"]
            },
            {
                id: "endogenetic-exogenetic",
                title: "Endogenetic and Exogenetic Forces",
                role: "Study Earth-shaping forces",
                difficulty: "Intermediate",
                duration: "13 min",
                xp: 130,
                url: "https://www.iastune.in/mindmaps/geography/3.%20Endogenetic%20and%20Exogenetic%20forces.html",
                subjects: ["Geography"],
                brief: "Learn about forces that shape Earth's surface.",
                learningPoints: ["Internal forces", "External forces"]
            },
            {
                id: "landforms",
                title: "Landforms",
                role: "Classify and study landforms",
                difficulty: "Intermediate",
                duration: "14 min",
                xp: 140,
                url: "https://www.iastune.in/mindmaps/geography/3.%20Landforms%20Evolution%20due%20to%20Internal%20Forces.html",
                subjects: ["Geography"],
                brief: "Explore various landforms and their formation.",
                learningPoints: ["Landform types", "Formation processes"]
            },
            {
                id: "rocks",
                title: "Rocks",
                role: "Study rock types and formation",
                difficulty: "Basic",
                duration: "12 min",
                xp: 120,
                url: "https://www.iastune.in/mindmaps/geography/Rocks.html",
                subjects: ["Geography"],
                brief: "Understand igneous, sedimentary, and metamorphic rocks.",
                learningPoints: ["Rock types", "Rock cycle"]
            },
            {
                id: "soil-types",
                title: "Soil Types and Distribution",
                role: "Analyze soil characteristics",
                difficulty: "Intermediate",
                duration: "13 min",
                xp: 130,
                url: "https://www.iastune.in/mindmaps/geography/Soil%20types%20and%20their%20distributions.html",
                subjects: ["Geography"],
                brief: "Study soil types, formation, and distribution.",
                learningPoints: ["Soil profiles", "Soil classification"]
            },
            {
                id: "atmosphere",
                title: "Atmosphere",
                role: "Explore atmospheric structure",
                difficulty: "Basic",
                duration: "11 min",
                xp: 110,
                url: "https://www.iastune.in/mindmaps/geography/8.%20Atmosphere.html",
                subjects: ["Geography"],
                brief: "Understand atmospheric layers and composition.",
                learningPoints: ["Atmospheric layers", "Air composition"]
            },
            {
                id: "pressure-winds",
                title: "Pressure and Winds",
                role: "Study atmospheric pressure systems",
                difficulty: "Intermediate",
                duration: "14 min",
                xp: 140,
                url: "https://www.iastune.in/mindmaps/geography/10.%20Pressure%20and%20Winds.html",
                subjects: ["Geography"],
                brief: "Analyze pressure systems and wind patterns.",
                learningPoints: ["Pressure gradients", "Wind systems"]
            },
            {
                id: "humidity-precipitation",
                title: "Humidity and Precipitation",
                role: "Understand water in atmosphere",
                difficulty: "Intermediate",
                duration: "13 min",
                xp: 130,
                url: "https://www.iastune.in/mindmaps/geography/11.%20Humidity%20Precipitation.html",
                subjects: ["Geography"],
                brief: "Study humidity, condensation, and precipitation.",
                learningPoints: ["Water cycle", "Precipitation types"]
            },
            {
                id: "jet-streams",
                title: "Jet Streams",
                role: "Explore high-altitude wind systems",
                difficulty: "Advanced",
                duration: "15 min",
                xp: 150,
                url: "https://www.iastune.in/mindmaps/geography/7.%20Jet%20streams.html",
                subjects: ["Geography"],
                brief: "Understand jet streams and their effects.",
                learningPoints: ["Upper atmospheric winds", "Weather patterns"]
            },
            {
                id: "climate-change",
                title: "Climate Change",
                role: "Analyze global climate shifts",
                difficulty: "Advanced",
                duration: "16 min",
                xp: 160,
                url: "https://www.iastune.in/mindmaps/geography/16.%20Climate%20Change.html",
                subjects: ["Geography"],
                brief: "Study climate change causes and impacts.",
                learningPoints: ["Climate trends", "Global warming"]
            },
            {
                id: "hydrological-cycle",
                title: "Hydrological Cycle",
                role: "Understand water cycle processes",
                difficulty: "Intermediate",
                duration: "12 min",
                xp: 120,
                url: "https://www.iastune.in/mindmaps/geography/15.%20Hydrological%20Cycle%20with%20diagram.html",
                subjects: ["Geography"],
                brief: "Explore the water cycle and hydrological processes.",
                learningPoints: ["Water movement", "Evaporation transpiration"]
            },
            {
                id: "ocean-deposits",
                title: "Ocean Deposits",
                role: "Study marine sediments",
                difficulty: "Intermediate",
                duration: "13 min",
                xp: 130,
                url: "https://www.iastune.in/mindmaps/geography/4.%20Ocean%20deposits.html",
                subjects: ["Geography"],
                brief: "Understand ocean floor deposits and composition.",
                learningPoints: ["Marine sediments", "Ocean resources"]
            },
            {
                id: "oceans-relief",
                title: "Oceans (Relief and Circulation)",
                role: "Explore ocean structure and currents",
                difficulty: "Intermediate",
                duration: "14 min",
                xp: 140,
                url: "https://www.iastune.in/mindmaps/geography/7.%20Oceans%20(Relief%20and%20circulation).html",
                subjects: ["Geography"],
                brief: "Study ocean relief features and circulation patterns.",
                learningPoints: ["Ocean currents", "Ocean floor topography"]
            },
            {
                id: "coral-reefs",
                title: "Coral Reefs",
                role: "Study coral ecosystems",
                difficulty: "Intermediate",
                duration: "12 min",
                xp: 120,
                url: "https://www.iastune.in/mindmaps/geography/7.%20Coral%20reefs.html",
                subjects: ["Geography"],
                brief: "Understand coral reef formation and biodiversity.",
                learningPoints: ["Coral formation", "Reef ecosystems"]
            },
            {
                id: "coral-bleaching",
                title: "Coral Bleaching",
                role: "Analyze coral degradation",
                difficulty: "Intermediate",
                duration: "13 min",
                xp: 130,
                url: "https://www.iastune.in/mindmaps/geography/7.%20Coral%20bleaching.html",
                subjects: ["Geography"],
                brief: "Study coral bleaching and its environmental impacts.",
                learningPoints: ["Coral health", "Climate impacts"]
            },
            {
                id: "glaciers-waves",
                title: "Glaciers, Wind & Sea Waves",
                role: "Study erosional agents",
                difficulty: "Intermediate",
                duration: "14 min",
                xp: 140,
                url: "https://www.iastune.in/mindmaps/geography/5.%20Glacier%2C%20Wind%2C%20Sea%20waves.html",
                subjects: ["Geography"],
                brief: "Explore glaciers, wind, and wave erosion.",
                learningPoints: ["Glaciation", "Wave action"]
            },
            {
                id: "natural-ecosys-geo",
                title: "Natural Ecosystems",
                role: "Study geographic ecosystems",
                difficulty: "Intermediate",
                duration: "14 min",
                xp: 140,
                url: "https://www.iastune.in/mindmaps/geography/Natural%20Ecosystems.html",
                subjects: ["Geography"],
                brief: "Understand natural ecosystems by geography.",
                learningPoints: ["Ecosystem distribution", "Biomes"]
            },
            {
                id: "crops-cropping",
                title: "Crops and Cropping Patterns",
                role: "Analyze agricultural geography",
                difficulty: "Intermediate",
                duration: "13 min",
                xp: 130,
                url: "https://www.iastune.in/mindmaps/geography/10.%20Crops%20and%20Cropping%20Patterns.html",
                subjects: ["Geography"],
                brief: "Study global cropping patterns and agriculture.",
                learningPoints: ["Agricultural zones", "Crop distribution"]
            },
            {
                id: "demographic-attributes",
                title: "Demographic Attributes",
                role: "Study population characteristics",
                difficulty: "Intermediate",
                duration: "12 min",
                xp: 120,
                url: "https://www.iastune.in/mindmaps/geography/Demographic%20attributes.html",
                subjects: ["Geography"],
                brief: "Understand demographic data and population attributes.",
                learningPoints: ["Demographic data", "Population structure"]
            },
            {
                id: "population-growth",
                title: "Growth and Distribution of World Population",
                role: "Analyze global population patterns",
                difficulty: "Intermediate",
                duration: "14 min",
                xp: 140,
                url: "https://www.iastune.in/mindmaps/geography/Growth%20and%20Distribution%20of%20World%20Population.html",
                subjects: ["Geography"],
                brief: "Study world population growth and distribution.",
                learningPoints: ["Population dynamics", "Distribution patterns"]
            },
            {
                id: "population-development",
                title: "Population and Development",
                role: "Connect population to development",
                difficulty: "Intermediate",
                duration: "13 min",
                xp: 130,
                url: "https://www.iastune.in/mindmaps/geography/Population%20and%20Development.html",
                subjects: ["Geography"],
                brief: "Understand relationship between population and development.",
                learningPoints: ["Development indicators", "Population policies"]
            },
            {
                id: "climate-regions",
                title: "World Climatic Regions",
                role: "Classify climate zones",
                difficulty: "Intermediate",
                duration: "14 min",
                xp: 140,
                url: "https://www.iastune.in/mindmaps/geography/WORLD%20CLIMATIC%20REGIONS.html",
                subjects: ["Geography"],
                brief: "Study world climate zones and classification.",
                learningPoints: ["Climate zones", "Climate determinants"]
            },
            {
                id: "world-resources",
                title: "World Resources and Distribution",
                role: "Analyze global resource distribution",
                difficulty: "Intermediate",
                duration: "15 min",
                xp: 150,
                url: "https://www.iastune.in/mindmaps/geography/World%20Resources%20and%20their%20Distribution.html",
                subjects: ["Geography"],
                brief: "Understand global resource distribution and management.",
                learningPoints: ["Resource types", "Distribution patterns"]
            },
            {
                id: "world-trade",
                title: "World Trade Pattern",
                role: "Explore international trade geography",
                difficulty: "Intermediate",
                duration: "14 min",
                xp: 140,
                url: "https://www.iastune.in/mindmaps/geography/Pattern%20of%20World%20Trade.html",
                subjects: ["Geography"],
                brief: "Study world trade patterns and routes.",
                learningPoints: ["Trade networks", "Economic geography"]
            },
            {
                id: "latitudes-longitudes",
                title: "Latitudes, Longitudes & Standard Time",
                role: "Understand geographic coordinates",
                difficulty: "Basic",
                duration: "11 min",
                xp: 110,
                url: "https://www.iastune.in/mindmaps/geography/Latitudes%2C%20Longitudes%2C%20Standard%20Time.html",
                subjects: ["Geography"],
                brief: "Learn about coordinate systems and time zones.",
                learningPoints: ["Coordinates", "Time zones"]
            },
            {
                id: "motions-earth",
                title: "Motions of the Earth",
                role: "Study planetary motions",
                difficulty: "Basic",
                duration: "12 min",
                xp: 120,
                url: "https://www.iastune.in/mindmaps/geography/Motions%20of%20the%20earth.html",
                subjects: ["Geography"],
                brief: "Understand Earth's rotation and revolution.",
                learningPoints: ["Rotation", "Revolution", "Seasons"]
            },
            {
                id: "origin-universe",
                title: "Origin and Evolution of Universe",
                role: "Explore cosmic origins",
                difficulty: "Advanced",
                duration: "16 min",
                xp: 160,
                url: "https://www.iastune.in/mindmaps/geography/Origin%20and%20evolution%20of%20universe.html",
                subjects: ["Geography"],
                brief: "Study Big Bang theory and universe evolution.",
                learningPoints: ["Cosmology", "Universe formation"]
            },
            {
                id: "oceans-countries",
                title: "Oceans and Bordering Countries",
                role: "Study ocean geography",
                difficulty: "Basic",
                duration: "11 min",
                xp: 110,
                url: "https://www.iastune.in/mindmaps/geography/Oceans%20and%20Bordering%20Countries.html",
                subjects: ["Geography"],
                brief: "Learn about oceans and their bordering nations.",
                learningPoints: ["Ocean geography", "Coastal states"]
            },
            {
                id: "location-factors",
                title: "Location Factors",
                role: "Analyze geographic location effects",
                difficulty: "Intermediate",
                duration: "12 min",
                xp: 120,
                url: "https://www.iastune.in/mindmaps/geography/Location%20Factors.html",
                subjects: ["Geography"],
                brief: "Understand how location affects development.",
                learningPoints: ["Geographic advantage", "Location impact"]
            },
            {
                id: "preamble",
                title: "Preamble",
                role: "Study constitutional preamble",
                difficulty: "Basic",
                duration: "11 min",
                xp: 110,
                url: "https://www.iastune.in/mindmaps/polity/Preamble.html",
                subjects: ["Polity"],
                brief: "Understand the Preamble of Indian Constitution.",
                learningPoints: ["Constitutional values", "Objectives"]
            },
            {
                id: "rights",
                title: "Rights",
                role: "Explore fundamental rights",
                difficulty: "Intermediate",
                duration: "13 min",
                xp: 130,
                url: "https://www.iastune.in/mindmaps/polity/Rights.html",
                subjects: ["Polity"],
                brief: "Study fundamental rights and citizen protections.",
                learningPoints: ["Constitutional rights", "Protections"]
            },
            {
                id: "equality",
                title: "Equality",
                role: "Understand constitutional equality",
                difficulty: "Intermediate",
                duration: "12 min",
                xp: 120,
                url: "https://www.iastune.in/mindmaps/polity/Equality.html",
                subjects: ["Polity"],
                brief: "Explore equality principles in constitution.",
                learningPoints: ["Equal protection", "Non-discrimination"]
            },
            {
                id: "justice",
                title: "Justice",
                role: "Study social and economic justice",
                difficulty: "Intermediate",
                duration: "14 min",
                xp: 140,
                url: "https://www.iastune.in/mindmaps/polity/Justice.html",
                subjects: ["Polity"],
                brief: "Understand justice provisions in constitution.",
                learningPoints: ["Justice types", "Equity principles"]
            },
            {
                id: "democracy",
                title: "Democracy",
                role: "Understand democratic principles",
                difficulty: "Intermediate",
                duration: "13 min",
                xp: 130,
                url: "https://www.iastune.in/mindmaps/polity/Democracy.html",
                subjects: ["Polity"],
                brief: "Study democratic concepts and practices.",
                learningPoints: ["Democratic values", "Governance"]
            },
            {
                id: "constitution-features",
                title: "Constitution - Features",
                role: "Study constitution characteristics",
                difficulty: "Intermediate",
                duration: "14 min",
                xp: 140,
                url: "https://www.iastune.in/mindmaps/polity/Constitution - Features.html",
                subjects: ["Polity"],
                brief: "Explore unique features of Indian Constitution.",
                learningPoints: ["Constitutional features", "Uniqueness"]
            },
            {
                id: "constitution-amendment",
                title: "Constitution Amendment",
                role: "Study amendment process",
                difficulty: "Intermediate",
                duration: "13 min",
                xp: 130,
                url: "https://www.iastune.in/mindmaps/polity/Constitution Amendment.html",
                subjects: ["Polity"],
                brief: "Understand constitutional amendment procedures.",
                learningPoints: ["Amendment process", "Constitutional changes"]
            },
            {
                id: "federal-system",
                title: "Federal System",
                role: "Study federalism structure",
                difficulty: "Intermediate",
                duration: "14 min",
                xp: 140,
                url: "https://www.iastune.in/mindmaps/polity/Federal System.html",
                subjects: ["Polity"],
                brief: "Explore federal structure of Indian government.",
                learningPoints: ["Federal structure", "Power distribution"]
            },
            {
                id: "centre-state",
                title: "Centre-State Relations",
                role: "Analyze center-state dynamics",
                difficulty: "Intermediate",
                duration: "15 min",
                xp: 150,
                url: "https://www.iastune.in/mindmaps/polity/Centre-State Relations.html",
                subjects: ["Polity"],
                brief: "Study relationships between center and states.",
                learningPoints: ["Fiscal relations", "Administrative coordination"]
            },
            {
                id: "inter-state",
                title: "Inter-State Relations",
                role: "Study inter-state dynamics",
                difficulty: "Intermediate",
                duration: "14 min",
                xp: 140,
                url: "https://www.iastune.in/mindmaps/polity/Inter-State Relations.html",
                subjects: ["Polity"],
                brief: "Explore relations among Indian states.",
                learningPoints: ["Interstate relations", "Cooperation"]
            },
            {
                id: "emergency-provisions",
                title: "Emergency Provisions",
                role: "Study emergency powers",
                difficulty: "Intermediate",
                duration: "13 min",
                xp: 130,
                url: "https://www.iastune.in/mindmaps/polity/Emergency Provisions.html",
                subjects: ["Polity"],
                brief: "Understand constitutional emergency provisions.",
                learningPoints: ["Emergency types", "Constitutional powers"]
            },
            {
                id: "jammu-kashmir-status",
                title: "Special Status of Jammu and Kashmir",
                role: "Study J&K constitutional position",
                difficulty: "Advanced",
                duration: "15 min",
                xp: 150,
                url: "https://www.iastune.in/mindmaps/polity/Special status of Jammu and Kashmir.html",
                subjects: ["Polity"],
                brief: "Explore special constitutional status of J&K.",
                learningPoints: ["Article 370", "Special provisions"]
            },
            {
                id: "special-provisions-states",
                title: "Special Provisions - Some States",
                role: "Study special state provisions",
                difficulty: "Intermediate",
                duration: "14 min",
                xp: 140,
                url: "https://www.iastune.in/mindmaps/polity/Special Provisions- Some states.html",
                subjects: ["Polity"],
                brief: "Understand special constitutional provisions for states.",
                learningPoints: ["Regional provisions", "Special status"]
            },
            {
                id: "state-council-ministers",
                title: "State Council of Ministers",
                role: "Study state government structure",
                difficulty: "Intermediate",
                duration: "13 min",
                xp: 130,
                url: "https://www.iastune.in/mindmaps/polity/State Council of Ministers.html",
                subjects: ["Polity"],
                brief: "Explore state government and council of ministers.",
                learningPoints: ["State governance", "Council structure"]
            },
            {
                id: "high-court",
                title: "High Court",
                role: "Study high court system",
                difficulty: "Intermediate",
                duration: "14 min",
                xp: 140,
                url: "https://www.iastune.in/mindmaps/polity/High Court.html",
                subjects: ["Polity"],
                brief: "Understand high courts and their jurisdiction.",
                learningPoints: ["Judicial system", "Court powers"]
            },
            {
                id: "subordinate-courts",
                title: "Subordinate Courts",
                role: "Study lower court system",
                difficulty: "Intermediate",
                duration: "13 min",
                xp: 130,
                url: "https://www.iastune.in/mindmaps/polity/Subordinate courts.html",
                subjects: ["Polity"],
                brief: "Explore subordinate court structure.",
                learningPoints: ["Court hierarchy", "Judicial administration"]
            },
            {
                id: "local-govt",
                title: "Local Government",
                role: "Study local governance",
                difficulty: "Intermediate",
                duration: "14 min",
                xp: 140,
                url: "https://www.iastune.in/mindmaps/polity/Local Govt.html",
                subjects: ["Polity"],
                brief: "Understand local government systems.",
                learningPoints: ["Panchayats", "Local administration"]
            },
            {
                id: "pressure-groups",
                title: "Pressure Groups",
                role: "Study political pressure groups",
                difficulty: "Intermediate",
                duration: "13 min",
                xp: 130,
                url: "https://www.iastune.in/mindmaps/polity/Pressure Groups.html",
                subjects: ["Polity"],
                brief: "Explore pressure groups and civil society.",
                learningPoints: ["Civil society", "Political participation"]
            },
            {
                id: "rpa",
                title: "Representation of Peoples Act (RPA)",
                role: "Study electoral law",
                difficulty: "Intermediate",
                duration: "14 min",
                xp: 140,
                url: "https://www.iastune.in/mindmaps/polity/Salient Features of Representation of Peoples Act (RPA).html",
                subjects: ["Polity"],
                brief: "Understand electoral law and representation.",
                learningPoints: ["Electoral provisions", "Voting rights"]
            },
            {
                id: "scheduled-tribal-areas",
                title: "Administration of Scheduled and Tribal Areas",
                role: "Study special administration",
                difficulty: "Intermediate",
                duration: "15 min",
                xp: 150,
                url: "https://www.iastune.in/mindmaps/polity/Administration of Scheduled and Tribal Areas.html",
                subjects: ["Polity"],
                brief: "Explore administration of scheduled areas.",
                learningPoints: ["Tribal administration", "Special provisions"]
            },
            {
                id: "constitutional-amendment-acts",
                title: "Constitutional (Amendment) Acts",
                role: "Study amendment history",
                difficulty: "Advanced",
                duration: "16 min",
                xp: 160,
                url: "https://www.iastune.in/mindmaps/polity/Constitutional (Amendment) Acts.html",
                subjects: ["Polity"],
                brief: "Understand constitutional amendment history.",
                learningPoints: ["Amendment timeline", "Major changes"]
            },
            {
                id: "natural-justice",
                title: "Principles of Natural Justice",
                role: "Study legal principles",
                difficulty: "Advanced",
                duration: "15 min",
                xp: 150,
                url: "https://www.iastune.in/mindmaps/polity/Principles of Natural Justice Vs Legal Justice.html",
                subjects: ["Polity"],
                brief: "Understand natural justice principles.",
                learningPoints: ["Justice principles", "Legal theory"]
            },
            {
                id: "indian-economy-independence",
                title: "Indian Economy Since Independence",
                role: "Study post-independence economy",
                difficulty: "Intermediate",
                duration: "15 min",
                xp: 150,
                url: "https://www.iastune.in/mindmaps/post-independence/Indian Economy Since Independence.html",
                subjects: ["Post-Independence"],
                brief: "Explore India's economic development post-1947.",
                learningPoints: ["Economic growth", "Development stages"]
            },
            {
                id: "elections-1989",
                title: "1989 Elections and After",
                role: "Study contemporary politics",
                difficulty: "Intermediate",
                duration: "14 min",
                xp: 140,
                url: "https://www.iastune.in/mindmaps/post-independence/1989 elections and after.html",
                subjects: ["Post-Independence"],
                brief: "Understand post-1989 political developments.",
                learningPoints: ["Coalition politics", "Democratic evolution"]
            },
            {
                id: "sources-history",
                title: "Sources and Historical Construction",
                role: "Learn historical methodology",
                difficulty: "Intermediate",
                duration: "13 min",
                xp: 130,
                url: "https://www.iastune.in/sample-simulations/",
                subjects: ["Indian Ancient History"],
                brief: "Understand historical sources and interpretation.",
                learningPoints: ["Historical sources", "Methodology"]
            },
            {
                id: "prehistory-protohistory",
                title: "Pre History & Proto History",
                role: "Explore early human periods",
                difficulty: "Basic",
                duration: "12 min",
                xp: 120,
                url: "https://www.iastune.in/sample-simulations/",
                subjects: ["Indian Ancient History"],
                brief: "Study pre-history and proto-history of India.",
                learningPoints: ["Early humans", "Ancient periods"]
            },
            {
                id: "aryans-vedic",
                title: "Aryans & Vedic Period",
                role: "Study Vedic civilization",
                difficulty: "Intermediate",
                duration: "14 min",
                xp: 140,
                url: "https://www.iastune.in/sample-simulations/",
                subjects: ["Indian Ancient History"],
                brief: "Explore the Vedic period and Aryan migration.",
                learningPoints: ["Vedic literature", "Social structure"]
            },
            {
                id: "ashoka-dhamma",
                title: "Ashoka and his Concept of Dhamma",
                role: "Study Ashoka's reign",
                difficulty: "Intermediate",
                duration: "13 min",
                xp: 130,
                url: "https://www.iastune.in/sample-simulations/",
                subjects: ["Indian Ancient History"],
                brief: "Understand Emperor Ashoka and his Dhamma concept.",
                learningPoints: ["Mauryan empire", "Buddhist influence"]
            },
            {
                id: "satavahanas",
                title: "Satavahanas",
                role: "Study Satavahana dynasty",
                difficulty: "Intermediate",
                duration: "12 min",
                xp: 120,
                url: "https://www.iastune.in/sample-simulations/",
                subjects: ["Indian Ancient History"],
                brief: "Explore the Satavahana dynasty and its contributions.",
                learningPoints: ["South India", "Trade networks"]
            },
            {
                id: "gupta-empire",
                title: "Gupta Empire",
                role: "Study the Golden Age",
                difficulty: "Intermediate",
                duration: "14 min",
                xp: 140,
                url: "https://www.iastune.in/sample-simulations/",
                subjects: ["Indian Ancient History"],
                brief: "Understand the Gupta Empire and its cultural achievements.",
                learningPoints: ["Golden Age", "Cultural development"]
            },
            {
                id: "history-deep-south",
                title: "History in Deep South",
                role: "Explore southern India history",
                difficulty: "Intermediate",
                duration: "13 min",
                xp: 130,
                url: "https://www.iastune.in/sample-simulations/",
                subjects: ["Indian Ancient History"],
                brief: "Study the history of deep southern India.",
                learningPoints: ["South Indian kingdoms", "Dravidian culture"]
            },
            {
                id: "peninsula-300-750",
                title: "Peninsula (300-750 AD)",
                role: "Study medieval southern period",
                difficulty: "Intermediate",
                duration: "14 min",
                xp: 140,
                url: "https://www.iastune.in/sample-simulations/",
                subjects: ["Indian Ancient History"],
                brief: "Explore the peninsula period from 300-750 AD.",
                learningPoints: ["Chalukyas", "Regional kingdoms"]
            },
            {
                id: "civilisation-east-india",
                title: "Civilisation in East India",
                role: "Study eastern India history",
                difficulty: "Intermediate",
                duration: "12 min",
                xp: 120,
                url: "https://www.iastune.in/sample-simulations/",
                subjects: ["Indian Ancient History"],
                brief: "Understand civilization development in eastern India.",
                learningPoints: ["Bengal history", "Eastern kingdoms"]
            },
            {
                id: "philosophical-dev",
                title: "Philosophical Developments",
                role: "Explore ancient philosophies",
                difficulty: "Advanced",
                duration: "15 min",
                xp: 150,
                url: "https://www.iastune.in/sample-simulations/",
                subjects: ["Indian Ancient History"],
                brief: "Study ancient Indian philosophical systems.",
                learningPoints: ["Schools of thought", "Philosophical concepts"]
            },
            {
                id: "social-changes",
                title: "Social Changes",
                role: "Analyze social evolution",
                difficulty: "Intermediate",
                duration: "13 min",
                xp: 130,
                url: "https://www.iastune.in/sample-simulations/",
                subjects: ["Indian Ancient History"],
                brief: "Understand social transformations in ancient India.",
                learningPoints: ["Social structure", "Evolution"]
            },
            {
                id: "cultural-contacts",
                title: "Cultural Contacts with Other Countries",
                role: "Study ancient trade and culture",
                difficulty: "Intermediate",
                duration: "14 min",
                xp: 140,
                url: "https://www.iastune.in/sample-simulations/",
                subjects: ["Indian Ancient History"],
                brief: "Explore cultural contacts and trade routes.",
                learningPoints: ["Silk route", "Cultural exchange"]
            },
            {
                id: "architecture",
                title: "Architecture",
                role: "Study Indian architectural styles",
                difficulty: "Intermediate",
                duration: "13 min",
                xp: 130,
                url: "https://www.iastune.in/mindmaps/indian-art-&-culture/Architecture.html",
                subjects: ["Indian Art & Culture"],
                brief: "Explore Indian architectural traditions and monuments.",
                learningPoints: ["Architectural styles", "Monument conservation"]
            },
            {
                id: "sculpture-crafts",
                title: "Crafts",
                role: "Study sculptural arts and crafts",
                difficulty: "Intermediate",
                duration: "12 min",
                xp: 120,
                url: "https://www.iastune.in/mindmaps/indian-art-&-culture/Crafts.html",
                subjects: ["Indian Art & Culture"],
                brief: "Understand Indian sculpture and craft traditions.",
                learningPoints: ["Sculptural forms", "Traditional crafts"]
            },
            {
                id: "painting",
                title: "Painting",
                role: "Explore Indian painting styles",
                difficulty: "Intermediate",
                duration: "13 min",
                xp: 130,
                url: "https://www.iastune.in/mindmaps/indian-art-&-culture/Painting.html",
                subjects: ["Indian Art & Culture"],
                brief: "Study Indian painting schools and techniques.",
                learningPoints: ["Painting styles", "Artistic traditions"]
            },
            {
                id: "music",
                title: "Music",
                role: "Study Indian classical music",
                difficulty: "Intermediate",
                duration: "14 min",
                xp: 140,
                url: "https://www.iastune.in/mindmaps/indian-art-&-culture/2.-Music.html",
                subjects: ["Indian Art & Culture"],
                brief: "Explore Indian music systems and traditions.",
                learningPoints: ["Classical music", "Musical forms"]
            },
            {
                id: "dance",
                title: "Dance",
                role: "Understand classical dance forms",
                difficulty: "Intermediate",
                duration: "13 min",
                xp: 130,
                url: "https://www.iastune.in/mindmaps/indian-art-&-culture/3.-Dance.html",
                subjects: ["Indian Art & Culture"],
                brief: "Study Indian classical dance traditions.",
                learningPoints: ["Dance forms", "Cultural expression"]
            },
            {
                id: "architecture",
                title: "Architecture",
                role: "Study Indian architectural styles",
                difficulty: "Intermediate",
                duration: "13 min",
                xp: 130,
                url: "https://www.iastune.in/mindmaps/indian-art-&-culture/4.-Architecture.html",
                subjects: ["Indian Art & Culture"],
                brief: "Explore Indian architectural traditions and monuments.",
                learningPoints: ["Architectural styles", "Monument conservation"]
            },
            {
                id: "sculpture-crafts",
                title: "Crafts",
                role: "Study sculptural arts and crafts",
                difficulty: "Intermediate",
                duration: "12 min",
                xp: 120,
                url: "https://www.iastune.in/mindmaps/indian-art-&-culture/5.-Crafts.html",
                subjects: ["Indian Art & Culture"],
                brief: "Understand Indian sculpture and craft traditions.",
                learningPoints: ["Sculptural forms", "Traditional crafts"]
            },
            {
                id: "painting",
                title: "Painting",
                role: "Explore Indian painting styles",
                difficulty: "Intermediate",
                duration: "13 min",
                xp: 130,
                url: "https://www.iastune.in/mindmaps/indian-art-&-culture/6.-Painting.html",
                subjects: ["Indian Art & Culture"],
                brief: "Study Indian painting schools and techniques.",
                learningPoints: ["Painting styles", "Artistic traditions"]
            },
            {
                id: "drama",
                title: "Drama",
                role: "Explore theatrical arts",
                difficulty: "Intermediate",
                duration: "12 min",
                xp: 120,
                url: "https://www.iastune.in/mindmaps/indian-art-&-culture/7.-Drama.html",
                subjects: ["Indian Art & Culture"],
                brief: "Understand Indian drama and theatre traditions.",
                learningPoints: ["Dramatic forms", "Theatre history"]
            },
            {
                id: "languages",
                title: "Languages",
                role: "Explore Indian languages",
                difficulty: "Intermediate",
                duration: "13 min",
                xp: 130,
                url: "https://www.iastune.in/mindmaps/indian-art-&-culture/9.-Languages.html",
                subjects: ["Indian Art & Culture"],
                brief: "Study Indian languages and linguistic diversity.",
                learningPoints: ["Language families", "Script systems"]
            },
            {
                id: "literature",
                title: "Literature",
                role: "Study Indian literary traditions",
                difficulty: "Intermediate",
                duration: "14 min",
                xp: 140,
                url: "https://www.iastune.in/mindmaps/indian-art-&-culture/10.-Literature.html",
                subjects: ["Indian Art & Culture"],
                brief: "Explore Indian literature across languages and periods.",
                learningPoints: ["Literary forms", "Major works"]
            },
            {
                id: "philosophy",
                title: "Philosophy",
                role: "Study Indian philosophical systems",
                difficulty: "Advanced",
                duration: "15 min",
                xp: 150,
                url: "https://www.iastune.in/mindmaps/indian-art-&-culture/11.-Philosophy.html",
                subjects: ["Indian Art & Culture"],
                brief: "Understand major Indian philosophical schools.",
                learningPoints: ["Philosophical systems", "Concepts"]
            },
            {
                id: "culture-civilization",
                title: "Culture and Civilization",
                role: "Understand Indian cultural identity",
                difficulty: "Intermediate",
                duration: "14 min",
                xp: 140,
                url: "https://www.iastune.in/mindmaps/indian-art-&-culture/Culture and Civilization.html",
                subjects: ["Indian Art & Culture"],
                brief: "Explore Indian cultural values and civilization.",
                learningPoints: ["Cultural values", "Civilization characteristics"]
            },
            {
                id: "salient-features",
                title: "Salient Features of Indian Society",
                role: "Understand Indian society characteristics",
                difficulty: "Intermediate",
                duration: "13 min",
                xp: 130,
                url: "https://www.iastune.in/mindmaps/indian-society-&-social-justice/1. Salient features of Indian society.html",
                subjects: ["Indian Society & Social Justice"],
                brief: "Study distinctive features of Indian society.",
                learningPoints: ["Social structure", "Cultural features"]
            },
            {
                id: "population-issues",
                title: "Population and Associated Issues",
                role: "Analyze population challenges",
                difficulty: "Intermediate",
                duration: "14 min",
                xp: 140,
                url: "https://www.iastune.in/mindmaps/indian-society-&-social-justice/3. Population and associated issues.html",
                subjects: ["Indian Society & Social Justice"],
                brief: "Understand population issues in India.",
                learningPoints: ["Population dynamics", "Policy challenges"]
            },
            {
                id: "objectives-determinants",
                title: "Objectives and Determinants",
                role: "Study IR fundamentals",
                difficulty: "Intermediate",
                duration: "13 min",
                xp: 130,
                url: "https://www.iastune.in/mindmaps/international-relations/1. Objectives and Determinants.html",
                subjects: ["International Relations"],
                brief: "Understand objectives and determinants of international relations.",
                learningPoints: ["IR theory", "National interests"]
            },
            {
                id: "early-medieval",
                title: "Early Medieval India (700-1200AD)",
                role: "Study early medieval period",
                difficulty: "Intermediate",
                duration: "14 min",
                xp: 140,
                url: "https://www.iastune.in/mindmaps/medieval-indian-history/2. Early Medieval India (700-1200AD).html",
                subjects: ["Medieval Indian History"],
                brief: "Explore early medieval India and its kingdoms.",
                learningPoints: ["Medieval kingdoms", "Political structure"]
            },
            {
                id: "contemporary-700-1200",
                title: "Contemporary World (700-1200 AD)",
                role: "Study medieval world context",
                difficulty: "Intermediate",
                duration: "13 min",
                xp: 130,
                url: "https://www.iastune.in/mindmaps/medieval-indian-history/1. Contemporary world (700 - 1200 AD).html",
                subjects: ["Medieval Indian History"],
                brief: "Understand the world context during 700-1200 AD.",
                learningPoints: ["World history", "Contemporary events"]
            },
            {
                id: "south-kingdoms",
                title: "South Indian Kingdoms (1350 - 1565)",
                role: "Study southern medieval states",
                difficulty: "Intermediate",
                duration: "14 min",
                xp: 140,
                url: "https://www.iastune.in/mindmaps/medieval-indian-history/5. South Indian Kingdoms (1350 - 1565).html",
                subjects: ["Medieval Indian History"],
                brief: "Explore southern Indian kingdoms and empires.",
                learningPoints: ["Chola", "Vijayanagara", "Regional powers"]
            },
            {
                id: "portuguese-trade",
                title: "Portuguese Trade",
                role: "Study Portuguese expansion",
                difficulty: "Basic",
                duration: "12 min",
                xp: 120,
                url: "https://www.iastune.in/mindmaps/medieval-indian-history/6. Portuguese Trade.html",
                subjects: ["Medieval Indian History"],
                brief: "Understand Portuguese maritime trade in India.",
                learningPoints: ["European trade", "Trade routes"]
            },
            {
                id: "mughal-empire",
                title: "Mughal Empire",
                role: "Study Mughal period",
                difficulty: "Intermediate",
                duration: "15 min",
                xp: 150,
                url: "https://www.iastune.in/mindmaps/medieval-indian-history/9. Mughal Empire.html",
                subjects: ["Medieval Indian History"],
                brief: "Understand the Mughal Empire and its administration.",
                learningPoints: ["Mughal rulers", "Administration", "Culture"]
            },
            {
                id: "maratha-empire",
                title: "Maratha Empire",
                role: "Study Maratha period",
                difficulty: "Intermediate",
                duration: "14 min",
                xp: 140,
                url: "https://www.iastune.in/mindmaps/medieval-indian-history/10. Maratha Empire.html",
                subjects: ["Medieval Indian History"],
                brief: "Explore the Maratha Empire and its expansion.",
                learningPoints: ["Maratha power", "Regional dominance"]
            },
            {
                id: "terms-medieval",
                title: "Terms in Medieval Indian History",
                role: "Learn medieval terminology",
                difficulty: "Basic",
                duration: "11 min",
                xp: 110,
                url: "https://www.iastune.in/mindmaps/medieval-indian-history/TERMS IN MEDIEVAL INDIAN HISTORY.html",
                subjects: ["Medieval Indian History"],
                brief: "Understand key terms in medieval Indian history.",
                learningPoints: ["Historical terminology", "Concepts"]
            },
            {
                id: "18th-century-india",
                title: "18th century India",
                role: "Study transition period",
                difficulty: "Intermediate",
                duration: "13 min",
                xp: 130,
                url: "https://www.iastune.in/mindmaps/modern-indian-history/2. 18th century India.html",
                subjects: ["Modern Indian History"],
                brief: "Explore 18th century India during transition.",
                learningPoints: ["Political changes", "Social conditions"]
            },
            {
                id: "mughal-decline",
                title: "Decline of Mughal Empire",
                role: "Study Mughal downfall",
                difficulty: "Intermediate",
                duration: "14 min",
                xp: 140,
                url: "https://www.iastune.in/mindmaps/modern-indian-history/1. Decline of Mughal Empire.html",
                subjects: ["Modern Indian History"],
                brief: "Understand the decline of Mughal Empire.",
                learningPoints: ["Political fragmentation", "Causes of decline"]
            },
            {
                id: "european-penetration",
                title: "European Penetration in India",
                role: "Study European arrival",
                difficulty: "Intermediate",
                duration: "13 min",
                xp: 130,
                url: "https://www.iastune.in/mindmaps/modern-indian-history/3. European Penetration in India.html",
                subjects: ["Modern Indian History"],
                brief: "Explore European penetration and early trading.",
                learningPoints: ["European traders", "Trading posts"]
            },
            {
                id: "british-expansion",
                title: "British Expansion in India",
                role: "Study British consolidation",
                difficulty: "Intermediate",
                duration: "14 min",
                xp: 140,
                url: "https://www.iastune.in/mindmaps/modern-indian-history/4. British Expansion in India.html",
                subjects: ["Modern Indian History"],
                brief: "Understand British expansion and military victories.",
                learningPoints: ["Colonial warfare", "Territorial expansion"]
            },
            {
                id: "british-policies",
                title: "British Policies",
                role: "Analyze colonial policies",
                difficulty: "Intermediate",
                duration: "15 min",
                xp: 150,
                url: "https://www.iastune.in/mindmaps/modern-indian-history/5. British Policies.html",
                subjects: ["Modern Indian History"],
                brief: "Study British colonial policies in India.",
                learningPoints: ["Administrative policies", "Economic policies"]
            },
            {
                id: "economic-impact",
                title: "Economic Impact of British Colonial Rule",
                role: "Analyze colonial economics",
                difficulty: "Advanced",
                duration: "16 min",
                xp: 160,
                url: "https://www.iastune.in/mindmaps/modern-indian-history/6. Economic Impact of British Colonial Rule.html",
                subjects: ["Modern Indian History"],
                brief: "Understand economic effects of British rule.",
                learningPoints: ["Drain theory", "Economic exploitation"]
            },
            {
                id: "british-social-culture",
                title: "Social and Cultural Policy of British",
                role: "Study British cultural impact",
                difficulty: "Intermediate",
                duration: "14 min",
                xp: 140,
                url: "https://www.iastune.in/mindmaps/modern-indian-history/8. Social and Cultural policy of British.html",
                subjects: ["Modern Indian History"],
                brief: "Explore British social and cultural policies.",
                learningPoints: ["Education policy", "Cultural impact"]
            },
            {
                id: "indian-response",
                title: "Indian Response to British Rule",
                role: "Study Indian resistance",
                difficulty: "Intermediate",
                duration: "13 min",
                xp: 130,
                url: "https://www.iastune.in/mindmaps/modern-indian-history/9. Indian Response to British Rule.html",
                subjects: ["Modern Indian History"],
                brief: "Understand Indian responses and resistance movements.",
                learningPoints: ["Reform movements", "Resistance"]
            },
            {
                id: "admin-changes-1858",
                title: "Administrative Changes After 1858",
                role: "Study post-rebellion reforms",
                difficulty: "Intermediate",
                duration: "14 min",
                xp: 140,
                url: "https://www.iastune.in/mindmaps/modern-indian-history/10. Administrative changes after 1858.html",
                subjects: ["Modern Indian History"],
                brief: "Explore administrative changes after 1858.",
                learningPoints: ["Imperial administration", "Governance structure"]
            },
            {
                id: "nationalist-1858-1905",
                title: "Nationalist Movement (1858-1905)",
                role: "Study early nationalism",
                difficulty: "Intermediate",
                duration: "15 min",
                xp: 150,
                url: "https://www.iastune.in/mindmaps/modern-indian-history/13. NATIONALIST MOVEMENT (1858-1905).html",
                subjects: ["Modern Indian History"],
                brief: "Understand early nationalist movement.",
                learningPoints: ["Congress formation", "Early nationalism"]
            },
            {
                id: "nationalist-1919-1927",
                title: "Nationalist Movement (1919-1927)",
                role: "Study mid-period nationalism",
                difficulty: "Intermediate",
                duration: "14 min",
                xp: 140,
                url: "https://www.iastune.in/mindmaps/modern-indian-history/15. NATIONALIST MOVEMENT (1919-1927).html",
                subjects: ["Modern Indian History"],
                brief: "Explore nationalism during 1919-1927 period.",
                learningPoints: ["Non-cooperation", "Civil disobedience"]
            },
            {
                id: "nationalist-movement",
                title: "The Nationalist Movement",
                role: "Study complete nationalist journey",
                difficulty: "Advanced",
                duration: "17 min",
                xp: 170,
                url: "https://www.iastune.in/mindmaps/modern-indian-history/12. The Nationalist Movement.html",
                subjects: ["Modern Indian History"],
                brief: "Comprehensive study of Indian nationalist movement.",
                learningPoints: ["Freedom struggle", "Independence movement"]
            },
            {
                id: "history-map",
                title: "Map",
                role: "Study historical geography",
                difficulty: "Basic",
                duration: "10 min",
                xp: 100,
                url: "https://www.iastune.in/mindmaps/modern-indian-history/Map4.html",
                subjects: ["Modern Indian History"],
                brief: "Visual reference for historical geography.",
                learningPoints: ["Historical boundaries", "Territory changes"]
            }
        ];

        // Subject categories
        const subjects = [
            { name: "Agriculture", icon: "🚜" },
            { name: "Economics", icon: "💰" },
            { name: "Environment & Ecology", icon: "🌱" },
            { name: "Ethics", icon: "⚖️" },
            { name: "Geography", icon: "🌍" },
            { name: "Indian Ancient History", icon: "🏺" },
            { name: "Indian Art & Culture", icon: "🎭" },
            { name: "Indian Society & Social Justice", icon: "👥" },
            { name: "International Relations", icon: "🌐" },
            { name: "Medieval Indian History", icon: "🏰" },
            { name: "Modern Indian History", icon: "📚" },
            { name: "Polity", icon: "🏛️" },
            { name: "Post-Independence", icon: "🇮  " },
            { name: "World History", icon: "🌍" }
        ];

        // Application state
        let currentSubject = 0;
        let searchQuery = '';
        let filteredSimulations = [...simulations];

        // DOM elements
        const searchInput = document.getElementById('searchInput');
        const clearSearch = document.getElementById('clearSearch');
        const clearSearchBtn = document.getElementById('clearSearchBtn');
        const themeToggle = document.getElementById('themeToggle');
        const subjectList = document.getElementById('subjectList');
        const activeSubjectIcon = document.getElementById('activeSubjectIcon');
        const activeSubjectName = document.getElementById('activeSubjectName');
        const simulationsGrid = document.getElementById('simulationsGrid');
        const emptyState = document.getElementById('emptyState');
        const modal = document.getElementById('modal');
        const shortcutsModal = document.getElementById('shortcutsModal');

        // Initialize application
        function init() {
            console.log('Initializing SimuLab...');
            console.log('Total simulations:', simulations.length);
            loadTheme();
            renderSubjects();
            renderSimulations();
            bindEvents();
        }

        // Theme management
        function loadTheme() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            if (savedTheme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
            updateThemeToggleIcon();
        }

        function toggleTheme() {
            const isDark = document.documentElement.classList.contains('dark');
            if (isDark) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
            updateThemeToggleIcon();
        }

        function updateThemeToggleIcon() {
            const isDark = document.documentElement.classList.contains('dark');
            const icon = themeToggle.querySelector('span');
            icon.textContent = isDark ? '☀️' : '🌙';
            themeToggle.className = isDark 
                ? 'p-2 rounded-full bg-slate-700 border border-slate-600 hover:bg-slate-600 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 shadow-sm' 
                : 'p-2 rounded-full bg-indigo-100 border border-indigo-300 hover:bg-indigo-200 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 shadow-sm';
        }

        // Render subjects sidebar
        function renderSubjects() {
            console.log('Rendering subjects...');
            subjectList.innerHTML = subjects.map((subject, index) => `
                <li class="stagger-animation" style="animation-delay: ${index * 80}ms">
                    <button 
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 text-left hover:bg-indigo-50 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-slate-700 ${
                            index === currentSubject ? 'bg-gradient-to-r from-indigo-100 to-blue-100 border border-indigo-300/60 shadow-md text-indigo-800 font-semibold' : 'border border-slate-200/40 hover:border-indigo-200'
                        }"
                        data-subject="${index}"
                    >
                        <span class="text-2xl floating-element" style="animation-delay: ${index * 200}ms">${subject.icon}</span>
                        <span class="font-medium text-sm">${subject.name}</span>
                    </button>
                </li>
            `).join('');
        }

        // Render simulations grid
        function renderSimulations() {
            console.log('Rendering simulations...');
            const currentSubjectName = subjects[currentSubject].name;
            
            // Update active subject chip with animation
            activeSubjectIcon.textContent = subjects[currentSubject].icon;
            activeSubjectName.textContent = currentSubjectName;
            
            // Add bounce animation to subject chip
            activeSubjectChip.style.animation = 'none';
            setTimeout(() => {
                activeSubjectChip.style.animation = 'bounce 0.6s ease-out';
            }, 10);

            // Filter by current subject
            let displaySimulations = filteredSimulations.filter(sim => 
                sim.subjects.includes(currentSubjectName)
            );

            console.log('Display simulations:', displaySimulations.length);

            if (displaySimulations.length === 0) {
                simulationsGrid.classList.add('hidden');
                emptyState.classList.remove('hidden');
                return;
            }

            simulationsGrid.classList.remove('hidden');
            emptyState.classList.add('hidden');

            // Add grid refresh animation
            simulationsGrid.style.opacity = '0';
            simulationsGrid.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                simulationsGrid.innerHTML = displaySimulations.map((sim, index) => {
                const highlightedTitle = highlightSearchTerm(sim.title, searchQuery);
                const emoji = getSimulationEmoji(sim.id);
                
                return `
                    <div 
                        class="card-hover bg-gradient-to-br from-white to-indigo-50/40 border border-indigo-200/40 rounded-2xl p-6 cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500 group stagger-animation relative z-10 shadow-md hover:shadow-xl"
                        data-sim-id="${sim.id}"
                        tabindex="0"
                        style="animation-delay: ${index * 120}ms"
                    >
                        <div class="mb-4 flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-slate-800 mb-2 group-hover:text-indigo-600 transition-colors duration-200">${highlightedTitle}</h3>
                                <p class="text-slate-600 text-sm leading-relaxed">${sim.role}</p>
                            </div>
                            <div class="text-4xl ml-2 floating-element">${emoji}</div>
                        </div>
                        
                        <div class="flex flex-wrap gap-2 mb-4">
                            ${sim.subjects.map(subject => `
                                <span class="px-2 py-1 bg-indigo-100 border border-indigo-300/50 rounded-full text-xs text-indigo-700 font-medium">
                                    ${subject}
                                </span>
                            `).join('')}
                        </div>
                        
                        <button class="w-full px-4 py-2 bg-white hover:bg-slate-100 text-slate-800 border border-slate-300 rounded-lg text-sm font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-slate-400 group-hover:scale-105 shadow-md">
                            Open
                        </button>
                    </div>
                `;
            }).join('');
            
            // Animate grid back in
            setTimeout(() => {
                simulationsGrid.style.transition = 'all 0.5s ease-out';
                simulationsGrid.style.opacity = '1';
                simulationsGrid.style.transform = 'translateY(0)';
            }, 50);
            }, 100);
        }

        // Get difficulty color scheme
        function getDifficultyColor(difficulty) {
            switch (difficulty) {
                case 'Basic':
                    return {
                        bg: 'bg-emerald-500/20',
                        border: 'border-emerald-500/30',
                        text: 'text-emerald-300'
                    };
                case 'Intermediate':
                    return {
                        bg: 'bg-amber-500/20',
                        border: 'border-amber-500/30',
                        text: 'text-amber-300'
                    };
                case 'Advanced':
                    return {
                        bg: 'bg-rose-500/20',
                        border: 'border-rose-500/30',
                        text: 'text-rose-300'
                    };
                default:
                    return {
                        bg: 'bg-gray-500/20',
                        border: 'border-gray-500/30',
                        text: 'text-gray-300'
                    };
            }
        }

        // Get 3D emoji for simulation
        function getSimulationEmoji(simId) {
            const emojiMap = {
                // Agriculture
                'land-reforms': '🌾',
                // Polity
                'preamble': '📜',
                'constitution-features': '🏛️',
                'constitutional-amendments': '✏️',
                'fundamental-rights': '⚖️',
                'equality': '⚖️',
                'justice': '👨‍⚖️',
                'democracy': '🗳️',
                'federal-system': '🌐',
                'centre-state-relations': '🤝',
                'inter-state-relations': '🔗',
                'emergency-provisions': '🚨',
                'j-k-special-status': '🗻',
                'special-provisions-states': '🛡️',
                'council-of-ministers': '👔',
                'high-court': '⚖️',
                'subordinate-courts': '📋',
                'local-government': '🏢',
                'representation-people-act': '📜',
                'pressure-groups': '  ',
                'other-constitutional-dimensions': '📚',
                'constitution-comparison': '🔄',
                'administration-scheduled-tribal': '🏹',
                'natural-justice': '⚖️',
                // Post-Independence
                'indian-economy-independence': '📈',
                '1989-elections-after': '🗳️',
                // World History
                'renaissance': '🎨',
                'geographical-discoveries': '🧭',
                'reformation-counter': '⛪',
                'enlightenment-ideas': '💡',
                'european-states-system': '🏰',
                'american-independence': '🗽',
                'french-revolution': '🥖',
                'agricultural-revolution': '🌾',
                'industrial-revolution': '🏭',
                'colonialism-imperialism': '🚢',
                'world-war-i': '💣',
                'russian-revolution': '❤️',
                'affu-afthaab': '⭐',
                'world-war-ii': '✈️',
                'cold-war': '❄️',
                'rise-china-japan': '🏯',
                // Economics
                'introduction': '📊',
                'industry': '🏭',
                'financial-market': '💹',
                'intl-econ-orgs': '🌍',
                'progress-growth': '📈',
                'public-finance': '  ',
                'inclusive-growth': '🤝',
                'indian-economy-evolution': '📜',
                'economic-planning': '📋',
                'planning-india': '🗺️',
                'economic-reforms': '🔧',
                'inflation-deflation': '💱',
                'business-cycle': '🔄',
                'investment-models': '💼',
                // Environment & Ecology
                'ecosystem': '🌲',
                'natural-ecosystem': '🏞️',
                'env-pollution': '🌫️',
                'indian-biodiversity': '🦁',
                'marine-organisms': '🐚',
                'scheduled-animals-wpa': '🦅',
                // Ethics
                'ethics-human-interface': '🤝',
                'attitude': '💭',
                'emotional-intelligence': '❤️',
                'moral-thinkers': '  ',
                'case-studies-ethics': '📖',
                // Geography
                'interior-earth': '🌍',
                'physical-features': '⛰️',
                'drainage-system': '🌊',
                'dynamic-surface': '🌊',
                'endogenetic-exogenetic': '⚡',
                'landforms': '🏔️',
                'rocks': '🪨',
                'soil-types': '🌱',
                'atmosphere': '☁️',
                'pressure-winds': '  ',
                'humidity-precipitation': '🌧️',
                'jet-streams': '🌪️',
                'climate-change': '🌡   ',
                'hydrological-cycle': '💧',
                'ocean-deposits': '🏖️',
                'oceans-relief': '🌊',
                'coral-reefs': '🪸',
                'coral-bleaching': '⚪',
                'glaciers-waves': '❄️',
                'natural-ecosys-geo': '🌿',
                'crops-cropping': '🌽',
                'demographic-attributes': '👥',
                'population-growth': '📈',
                'population-development': '🏗️',
                'climate-regions': '🌍',
                'world-resources': '⛏️',
                'world-trade': '📦',
                'latitudes-longitudes': '🧭',
                'motions-earth': '🌍',
                'origin-universe': '🌌',
                'oceans-countries': '🗺️',
                'location-factors': '📍',
                // Indian Ancient History
                'sources-history': '📚',
                'prehistory-protohistory': '🦴',
                'aryans-vedic': '📖',
                'ashoka-dhamma': '🙏',
                'satavahanas': '🛕',
                'gupta-empire': '  ',
                'history-deep-south': '🏛️',
                'peninsula-300-750': '🏰',
                'civilisation-east-india': '🏛️',
                'philosophical-dev': '🧘',
                'social-changes': '👥',
                'cultural-contacts': '🤝',
                // Indian Art & Culture
                'architecture': '🏛️',
                'sculpture-crafts': '🗿',
                'painting': '🎨',
                'music': '🎵',
                'dance': '💃',
                'drama': '🎭',
                'literature': '📚',
                'philosophy': '🧠',
                'languages': '🗣️',
                'culture-civilization': '🎭',
                // Indian Society & Social Justice
                'salient-features': '  🇳',
                'population-issues': '📊',
                // International Relations
                'objectives-determinants': '🌐',
                // Medieval Indian History
                'early-medieval': '🏰',
                'contemporary-700-1200': '🌍',
                'south-kingdoms': '🏛️',
                'mughal-empire': '👑',
                'maratha-empire': '⚔️',
                'portuguese-trade': '⛵',
                'terms-medieval': '📜',
                // Modern Indian History
                '18th-century-india': '🏛️',
                'mughal-decline': '📉',
                'european-penetration': '🚢',
                'british-expansion': '🚩',
                'british-policies': '📋',
                'economic-impact': '💰',
                'british-social-culture': '📚',
                'indian-response': '✊',
                'admin-changes-1858': '📋',
                'nationalist-1858-1905': '🇮  ',
                'nationalist-1919-1927': '✊',
                'nationalist-movement': '🇮🇳',
                'history-map': '🗺️'
            };
            return emojiMap[simId] || '   ';
        }

        // Highlight search terms
        function highlightSearchTerm(text, term) {
            if (!term) return text;
            const regex = new RegExp(`(${term})`, 'gi');
            return text.replace(regex, '<span class="search-highlight">$1</span>');
        }

        // Search functionality
        function performSearch(query) {
            searchQuery = query.toLowerCase();
            
            if (query) {
                clearSearch.style.display = 'flex';
                filteredSimulations = simulations.filter(sim => 
                    sim.title.toLowerCase().includes(searchQuery) ||
                    sim.role.toLowerCase().includes(searchQuery) ||
                    sim.difficulty.toLowerCase().includes(searchQuery) ||
                    sim.subjects.some(subject => subject.toLowerCase().includes(searchQuery))
                );
            } else {
                clearSearch.style.display = 'none';
                filteredSimulations = [...simulations];
            }
            
            renderSimulations();
        }

        function clearSearchQuery() {
            searchInput.value = '';
            performSearch('');
            searchInput.focus();
        }

        // Subject navigation
        function changeSubject(newIndex) {
            if (newIndex >= 0 && newIndex < subjects.length) {
                currentSubject = newIndex;
                renderSubjects();
                renderSimulations();
            }
        }

        // Modal functions
        function openModal(simId) {
            const sim = simulations.find(s => s.id === simId);
            if (!sim) return;

            const difficultyColor = getDifficultyColor(sim.difficulty);

            document.getElementById('modalTitle').textContent = sim.title;
            document.getElementById('modalRole').textContent = sim.role;
            document.getElementById('modalBrief').textContent = sim.brief;

            // Store simulation ID for launch function
            document.getElementById('launchSimulation').dataset.simId = simId;

            // Render badges
            document.getElementById('modalBadges').innerHTML = `
                ${sim.subjects.map(subject => `
                    <span class="px-3 py-1 bg-indigo-100 border border-indigo-300/60 rounded-full text-sm text-indigo-700 font-medium">
                        ${subject}
                    </span>
                `).join('')}
                <span class="px-3 py-1 ${difficultyColor.bg} border ${difficultyColor.border} rounded-full text-sm ${difficultyColor.text}">
                    ${sim.difficulty}
                </span>
                <span class="px-3 py-1 bg-slate-100 border border-slate-300/60 rounded-full text-sm text-slate-700 font-medium">
                       ️ ${sim.duration}
                </span>
                <span class="px-3 py-1 bg-amber-100 border border-amber-300/60 rounded-full text-sm text-amber-700 font-medium">
                    ⭐ ${sim.xp} XP
                </span>
            `;

            // Render learning points
            document.getElementById('modalLearningPoints').innerHTML = sim.learningPoints
                .map(point => `<li class="flex items-start gap-2"><span class="text-indigo-600 mt-1 font-bold">•</span><span>${point}</span></li>`)
                .join('');

            modal.classList.remove('hidden');
            document.getElementById('launchSimulation').focus();
        }

        function closeModal() {
            modal.classList.add('hidden');
        }

        function launchSimulation() {
            const currentSim = simulations.find(s => s.id === document.getElementById('launchSimulation').dataset.simId);
            if (!currentSim) return;

            // Open the simulation URL directly in a new tab
            window.open(currentSim.url, '_blank');
            closeModal();
        }

        // Event bindings
        function bindEvents() {
            // Search
            searchInput.addEventListener('input', (e) => performSearch(e.target.value));
            clearSearch.addEventListener('click', clearSearchQuery);
            clearSearchBtn.addEventListener('click', clearSearchQuery);

            // Theme toggle
            themeToggle.addEventListener('click', toggleTheme);

            // Subject navigation
            subjectList.addEventListener('click', (e) => {
                const subjectBtn = e.target.closest('[data-subject]');
                if (subjectBtn) {
                    const index = parseInt(subjectBtn.dataset.subject);
                    changeSubject(index);
                }
            });

            // Simulation cards - direct link
            simulationsGrid.addEventListener('click', (e) => {
                const openBtn = e.target.closest('button');
                if (openBtn) {
                    const card = openBtn.closest('[data-sim-id]');
                    if (card) {
                        const sim = simulations.find(s => s.id === card.dataset.simId);
                        if (sim && sim.url) {
                            window.open(sim.url, '_blank');
                        }
                    }
                }
            });

            // Modal events
            document.getElementById('closeModal').addEventListener('click', closeModal);
            document.getElementById('closeModalBtn').addEventListener('click', closeModal);
            document.getElementById('modalOverlay').addEventListener('click', closeModal);
            document.getElementById('launchSimulation').addEventListener('click', launchSimulation);

            // Shortcuts modal
            document.getElementById('shortcutsBtn').addEventListener('click', () => {
                shortcutsModal.classList.remove('hidden');
            });
            document.getElementById('closeShortcuts').addEventListener('click', () => {
                shortcutsModal.classList.add('hidden');
            });
            document.getElementById('shortcutsOverlay').addEventListener('click', () => {
                shortcutsModal.classList.add('hidden');
            });

            // Keyboard shortcuts
            document.addEventListener('keydown', (e) => {
                if (e.target.tagName === 'INPUT' && e.key !== 'Escape' && e.key !== '/') return;

                switch (e.key) {
                    case '/':
                        e.preventDefault();
                        searchInput.focus();
                        break;
                    case 'Escape':
                        if (modal.classList.contains('hidden') && shortcutsModal.classList.contains('hidden')) {
                            clearSearchQuery();
                        } else {
                            closeModal();
                            shortcutsModal.classList.add('hidden');
                        }
                        break;
                    case '[':
                        e.preventDefault();
                        changeSubject(currentSubject - 1);
                        break;
                    case ']':
                        e.preventDefault();
                        changeSubject(currentSubject + 1);
                        break;
                    case 'l':
                    case 'L':
                        if (!e.target.matches('input')) {
                            e.preventDefault();
                            toggleTheme();
                        }
                        break;
                }
            });
        }

        // Initialize the application
        document.addEventListener('DOMContentLoaded', init);
    </script>
 <script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'9c939b2d63d63a38',t:'MTc3MDMwNzA3NC4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>