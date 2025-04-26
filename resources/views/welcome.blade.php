<!DOCTYPE html>
<html lang="pt-br" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Adote um Pet</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- DaisyUI and Tailwind
    <link href="https://cdn.jsdelivr.net/npm/daisyui@3.9.4/dist/full.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet"> -->

    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.24/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
        .hero-pattern {
            background-color: #40CFFD;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .pet-card {
            transition: all 0.3s ease;
        }
        .pet-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }
        .custom-shape-divider {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            overflow: hidden;
            line-height: 0;
        }
        .custom-shape-divider svg {
            position: relative;
            display: block;
            width: calc(100% + 1.3px);
            height: 70px;
        }
        .custom-shape-divider .shape-fill {
            fill: #FFFFFF;
        }
        .nav-item-active {
            color: #FF6B6B !important;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <!-- Top Bar
    <div class="bg-neutral text-neutral-content py-2">
        <div class="container mx-auto px-4 flex justify-between items-center text-sm">
            <div class="flex items-center gap-4">
                <a href="mailto:contato@adoteumpet.com.br" class="flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    contato@adoteumpet.com.br
                </a>
                <a href="tel:+551198765432" class="flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    (11) 98765-4321
                </a>
            </div>
            <div class="flex items-center gap-3">
                <a href="#" class="hover:text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-instagram" viewBox="0 0 16 16">
                        <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z"/>
                    </svg>
                </a>
                <a href="#" class="hover:text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-facebook" viewBox="0 0 16 16">
                        <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z"/>
                    </svg>
                </a>
                <a href="#" class="hover:text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-youtube" viewBox="0 0 16 16">
                        <path d="M8.051 1.999h.089c.822.003 4.987.033 6.11.335a2.01 2.01 0 0 1 1.415 1.42c.101.38.172.883.22 1.402l.01.104.022.26.008.104c.065.914.073 1.77.074 1.957v.075c-.001.194-.01 1.108-.082 2.06l-.008.105-.009.104c-.05.572-.124 1.14-.235 1.558a2.007 2.007 0 0 1-1.415 1.42c-1.16.312-5.569.334-6.18.335h-.142c-.309 0-1.587-.006-2.927-.052l-.17-.006-.087-.004-.171-.007-.171-.007c-1.11-.049-2.167-.128-2.654-.26a2.007 2.007 0 0 1-1.415-1.419c-.111-.417-.185-.986-.235-1.558L.09 9.82l-.008-.104A31.4 31.4 0 0 1 0 7.68v-.123c.002-.215.01-.958.064-1.778l.007-.103.003-.052.008-.104.022-.26.01-.104c.048-.519.119-1.023.22-1.402a2.007 2.007 0 0 1 1.415-1.42c.487-.13 1.544-.21 2.654-.26l.17-.007.172-.006.086-.003.171-.007A99.788 99.788 0 0 1 7.858 2h.193zM6.4 5.209v4.818l4.157-2.408L6.4 5.209z"/>
                    </svg>
                </a>
                <a href="#" class="hover:text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-twitter-x" viewBox="0 0 16 16">
                        <path d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.601.75Zm-.86 13.028h1.36L4.323 2.145H2.865l8.875 11.633Z"/>
                    </svg>
                </a>
            </div>
        </div>
    </div> -->

    <!-- Main Navbar -->
    <div class="navbar bg-base-100 shadow-md sticky top-0 z-50">
        <div class="container mx-auto">
            <div class="navbar-start">
                <div class="dropdown">
                    <label tabindex="0" class="btn btn-ghost lg:hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" />
                        </svg>
                    </label>
                    <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-52">
                        <li><a href="#como-funciona" class="font-medium">Como Funciona</a></li>
                        <li><a href="#encontre" class="font-medium">Encontre um Pet</a></li>
                        <li><a href="#ongs" class="font-medium">ONGs Parceiras</a></li>
                        <li><a href="#app" class="font-medium">App</a></li>
                        <li><a href="#contato" class="font-medium">Contato</a></li>
                    </ul>
                </div>
                <a class="flex items-center gap-2 normal-case text-xl">
                    <div class="avatar">
                        <div class="w-20 rounded-full">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo" />
                        </div>
                    </div>
                    <div>
                        <span class="font-bold text-error">adote</span><span class="font-bold text-info">um</span><span class="font-bold text-warning">pet</span>
                    </div>
                </a>
            </div>
            <div class="navbar-center hidden lg:flex">
                <ul class="menu menu-horizontal px-1">
                    <li><a href="#como-funciona" class="font-medium hover:text-error">Como Funciona</a></li>
                    <li><a href="#encontre" class="font-medium hover:text-error">Encontre um Pet</a></li>
                    <li><a href="#ongs" class="font-medium hover:text-error">ONGs Parceiras</a></li>
                    <li><a href="#app" class="font-medium hover:text-error">App</a></li>
                    <li><a href="#contato" class="font-medium hover:text-error">Contato</a></li>
                </ul>
            </div>
            <div class="navbar-end gap-2">
                <a class="btn btn-ghost btn-sm rounded-btn">Login</a>
                <a href="#download" class="btn btn-error btn-sm text-white">Baixar o App</a>
            </div>
        </div>
    </div>

    <!-- Hero Section -->
    <div class="relative hero-pattern min-h-[85vh] overflow-hidden">
        <div class="custom-shape-divider">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="shape-fill"></path>
            </svg>
        </div>
        <div class="container mx-auto px-4 py-16">

            <div class="flex flex-col lg:flex-row items-center justify-between gap-12">
                <div class="lg:w-1/2 text-white">
                    <div class="badge badge-warning mb-4 text-sm font-medium py-3 px-4">Encontre seu melhor amigo</div>
                    <h1 class="text-5xl font-bold leading-tight mb-6">Adote um Pet e transforme duas vidas</h1>
                    <p class="text-lg mb-8 opacity-90">Nosso aplicativo conecta você a milhares de animais que estão esperando por um lar amoroso. Encontre o companheiro perfeito para sua família!</p>

                    <div class="flex flex-wrap gap-4 mb-8">
                        <a href="#encontre" class="btn btn-warning btn-lg text-white gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Buscar Pets
                        </a>
                        <a href="#como-funciona" class="btn btn-outline text-white hover:bg-white hover:text-info btn-lg">
                            Como Funciona
                        </a>
                    </div>

                    <div class="stats shadow bg-white text-gray-800">
                        <div class="stat">
                            <div class="stat-figure text-info">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div class="stat-title">Usuários</div>
                            <div class="stat-value text-info">15.8K</div>
                            <div class="stat-desc">↗︎ 400 (30%)</div>
                        </div>

                        <div class="stat">
                            <div class="stat-figure text-error">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </div>
                            <div class="stat-title">Adoções</div>
                            <div class="stat-value text-error">2.6K</div>
                            <div class="stat-desc">↗︎ 90 (14%)</div>
                        </div>

                        <div class="stat">
                            <div class="stat-figure text-warning">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                            </div>
                            <div class="stat-title">ONGs</div>
                            <div class="stat-value text-warning">142</div>
                            <div class="stat-desc">↗︎ 12 (9%)</div>
                        </div>
                    </div>
                </div>

                <div class="lg:w-1/2 relative">
                    <div class="mockup-phone border-primary">
                        <div class="camera"></div>
                        <div class="display">
                            <div class="artboard artboard-demo phone-1">
                                <img src="/api/placeholder/320/650" alt="App Screenshot" class="w-full h-full object-cover">
                            </div>
                        </div>
                    </div>

                    <div class="absolute -bottom-8 -left-8 bg-warning p-4 rounded-xl shadow-lg z-10">
                        <div class="flex gap-3 items-center">
                            <div class="avatar">
                                <div class="w-12 rounded-full ring ring-white ring-offset-base-100 ring-offset-2">
                                    <img src="/api/placeholder/80/80" alt="User avatar" />
                                </div>
                            </div>
                            <div>
                                <div class="text-sm font-bold">Mariana adotou Thor!</div>
                                <div class="text-xs">Há 2 dias</div>
                            </div>
                        </div>
                    </div>

                    <div class="absolute -top-4 -right-4 bg-error text-white p-4 rounded-xl shadow-lg z-10">
                        <div class="text-sm font-bold">+120 adoções este mês</div>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <!-- Como Funciona Section -->
    <div id="como-funciona" class="py-24">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold mb-4">Como <span class="text-error">funciona</span> o Adote um Pet</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">Nosso processo de adoção é simples, seguro e feito pensando no bem-estar dos animais e das famílias.</p>
                <div class="divider max-w-xs mx-auto mt-4"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Step 1 -->
                <div class="card bg-base-100 shadow-xl">
                    <figure class="px-10 pt-10">
                        <div class="bg-info/10 rounded-full w-20 h-20 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-info" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </figure>
                    <div class="card-body items-center text-center">
                        <div class="badge badge-info">Passo 1</div>
                        <h3 class="card-title text-xl mt-2">Busque um pet</h3>
                        <p class="text-gray-600">Encontre o pet perfeito para o seu lar. Filtre por tipo, idade, tamanho e localização mais próxima de você.</p>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="card bg-base-100 shadow-xl">
                    <figure class="px-10 pt-10">
                        <div class="bg-error/10 rounded-full w-20 h-20 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-error" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                        </div>
                    </figure>
                    <div class="card-body items-center text-center">
                        <div class="badge badge-error">Passo 2</div>
                        <h3 class="card-title text-xl mt-2">Entre em contato</h3>
                        <p class="text-gray-600">Converse diretamente com o abrigo ou protetor através do nosso chat integrado e agende uma visita.</p>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="card bg-base-100 shadow-xl">
                    <figure class="px-10 pt-10">
                        <div class="bg-warning/10 rounded-full w-20 h-20 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-warning" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </div>
                    </figure>
                    <div class="card-body items-center text-center">
                        <div class="badge badge-warning">Passo 3</div>
                        <h3 class="card-title text-xl mt-2">Adote com amor</h3>
                        <p class="text-gray-600">Complete o processo de adoção, assine o termo de compromisso e dê um lar cheio de carinho para seu novo amigo.</p>
                    </div>
                </div>
            </div>

            <div class="mt-16 text-center">
                <a href="#" class="btn btn-error text-white btn-lg">Quero Adotar Agora</a>
            </div>
        </div>
    </div>

    <!-- Divider -->
    <div class="py-8 bg-base-200">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap justify-center gap-10 items-center">
                <div class="stat">
                    <div class="stat-title">Animais Adotados</div>
                    <div class="stat-value text-primary">2,652</div>
                    <div class="stat-desc">Janeiro a Abril 2025</div>
                </div>

                <div class="stat">
                    <div class="stat-title">Abrigos Cadastrados</div>
                    <div class="stat-value text-secondary">142</div>
                    <div class="stat-desc">Em todo o Brasil</div>
                </div>

                <div class="stat">
                    <div class="stat-title">Usuários Ativos</div>
                    <div class="stat-value text-accent">15,840</div>
                    <div class="stat-desc">↗︎ 400 (22%)</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pets Section -->
    <div id="encontre" class="py-24">
        <div class="container mx-auto px-4">
            <div class="text-center mb-10">
                <h2 class="text-4xl font-bold mb-4">Encontre o seu novo melhor amigo</h2>
                <p class="text-gray-600">Descubra milhares de animais disponíveis para adoção perto de você. Nossa plataforma conecta você com abrigos e protetores de todo o Brasil.</p>
            </div>
                       <!-- Search Filters -->
                       <div class="bg-base-100 shadow-xl rounded-box p-6 mb-10 max-w-4xl mx-auto">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Tipo de Pet</span>
                                </label>
                                <select class="select select-bordered w-full">
                                    <option selected>Todos</option>
                                    <option>Cachorros</option>
                                    <option>Gatos</option>
                                    <option>Outros</option>
                                </select>
                            </div>

                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Idade</span>
                                </label>
                                <select class="select select-bordered w-full">
                                    <option selected>Todas</option>
                                    <option>Filhote (0-1 ano)</option>
                                    <option>Jovem (1-3 anos)</option>
                                    <option>Adulto (3-8 anos)</option>
                                    <option>Idoso (8+ anos)</option>
                                </select>
                            </div>

                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Porte</span>
                                </label>
                                <select class="select select-bordered w-full">
                                    <option selected>Todos</option>
                                    <option>Pequeno</option>
                                    <option>Médio</option>
                                    <option>Grande</option>
                                </select>
                            </div>

                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Localização</span>
                                </label>
                                <select class="select select-bordered w-full">
                                    <option selected>Todas</option>
                                    <option>São Paulo</option>
                                    <option>Rio de Janeiro</option>
                                    <option>Belo Horizonte</option>
                                    <option>Curitiba</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex justify-center mt-6">
                            <button class="btn btn-error text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                Buscar Pets
                            </button>
                        </div>
                    </div>
                        <!-- Pet Cards -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            <!-- Pet Card 1 -->
                            <div class="card bg-base-100 shadow-xl pet-card">
                                <figure class="relative">
                                    <img src="/api/placeholder/400/300" alt="Thor, Border Collie" class="w-full h-64 object-cover" />
                                    <div class="absolute top-4 right-4">
                                        <button class="btn btn-circle btn-sm bg-white hover:bg-error hover:text-white border-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="absolute bottom-0 left-0 w-full bg-gradient-to-t from-black/80 to-transparent text-white p-4">
                                        <h3 class="text-xl font-bold">Thor</h3>
                                        <div class="flex justify-between items-center">
                                            <div class="flex items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                <span class="text-sm">São Paulo, SP</span>
                                            </div>
                                            <div class="badge badge-warning">5 anos</div>
                                        </div>
                                    </div>
                                </figure>
                                <div class="card-body">
                                    <div class="flex justify-between items-center">
                                        <p class="text-sm text-gray-600">Border Collie • Macho</p>
                                        <div class="badge badge-outline badge-info">Vacinado</div>
                                    </div>
                                    <p class="text-gray-700">Thor é um cão inteligente, energético e muito carinhoso. Ele adora brincar e aprender truques novos.</p>
                                    <div class="card-actions justify-end mt-2">
                                        <button class="btn btn-outline btn-info btn-sm">Ver Detalhes</button>
                                        <button class="btn btn-error text-white btn-sm">Quero Adotar</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Pet Card 2 -->
                            <div class="card bg-base-100 shadow-xl pet-card">
                                <figure class="relative">
                                    <img src="/api/placeholder/400/300" alt="Luna, Gata" class="w-full h-64 object-cover" />
                                    <div class="absolute top-4 right-4">
                                        <button class="btn btn-circle btn-sm bg-white hover:bg-error hover:text-white border-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="absolute bottom-0 left-0 w-full bg-gradient-to-t from-black/80 to-transparent text-white p-4">
                                        <h3 class="text-xl font-bold">Luna</h3>
                                        <div class="flex justify-between items-center">
                                            <div class="flex items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                <span class="text-sm">Rio de Janeiro, RJ</span>
                                            </div>
                                            <div class="badge badge-warning">2 anos</div>
                                        </div>
                                    </div>
                                </figure>
                                <div class="card-body">
                                    <div class="flex justify-between items-center">
                                        <p class="text-sm text-gray-600">SRD • Fêmea</p>
                                        <div class="badge badge-outline badge-info">Castrada</div>
                                    </div>
                                    <p class="text-gray-700">Luna é uma gatinha dócil e meiga. Adora carinho e tem uma personalidade tranquila, perfeita para apartamentos.</p>
                                    <div class="card-actions justify-end mt-2">
                                        <button class="btn btn-outline btn-info btn-sm">Ver Detalhes</button>
                                        <button class="btn btn-error text-white btn-sm">Quero Adotar</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Pet Card 3 -->
                            <div class="card bg-base-100 shadow-xl pet-card">
                                <figure class="relative">
                                    <img src="/api/placeholder/400/300" alt="Billy, Cachorro" class="w-full h-64 object-cover" />
                                    <div class="absolute top-4 right-4">
                                        <button class="btn btn-circle btn-sm bg-white hover:bg-error hover:text-white border-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="absolute bottom-0 left-0 w-full bg-gradient-to-t from-black/80 to-transparent text-white p-4">
                                        <h3 class="text-xl font-bold">Billy</h3>
                                        <div class="flex justify-between items-center">
                                            <div class="flex items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                <span class="text-sm">Belo Horizonte, MG</span>
                                            </div>
                                            <div class="badge badge-warning">1 ano</div>
                                        </div>
                                    </div>
                                </figure>
                                <div class="card-body">
                                    <div class="flex justify-between items-center">
                                        <p class="text-sm text-gray-600">Pug • Macho</p>
                                        <div class="badge badge-outline badge-info">Vacinado</div>
                                    </div>
                                    <p class="text-gray-700">Billy é brincalhão e cheio de energia. Adora crianças e está sempre pronto para uma nova aventura.</p>
                                    <div class="card-actions justify-end mt-2">
                                        <button class="btn btn-outline btn-info btn-sm">Ver Detalhes</button>
                                        <button class="btn btn-error text-white btn-sm">Quero Adotar</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-12">
                            <button class="btn btn-outline btn-lg">Ver Mais Pets</button>
                        </div>
                    </div>
                </div>
        </div>
    </div>

        <!-- ONGs Section -->
        <div id="ongs" class="py-24 bg-base-200">
            <div class="container mx-auto px-4">
                <div class="text-center mb-10">
                    <h2 class="text-4xl font-bold mb-4">ONGs <span class="text-error">Parceiras</span></h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">Trabalhamos com diversas organizações sérias e comprometidas com o bem-estar animal.</p>
                    <div class="divider max-w-xs mx-auto mt-4"></div>
                </div>

                <div class="card bg-base-100 shadow-xl max-w-4xl mx-auto">
                    <div class="card-body">
                        <div class="flex flex-col md:flex-row items-start gap-6">
                            <div class="md:w-1/3">
                                <img src="/api/placeholder/300/300" alt="ONG Cãopanheiros" class="rounded-lg w-full">
                                <div class="flex justify-center gap-4 mt-4">
                                    <a href="#" class="btn btn-circle btn-outline btn-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-instagram" viewBox="0 0 16 16">
                                            <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z"/>
                                        </svg>
                                    </a>
                                    <a href="#" class="btn btn-circle btn-outline btn-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-facebook" viewBox="0 0 16 16">
                                            <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z"/>
                                        </svg>
                                    </a>
                                    <a href="#" class="btn btn-circle btn-outline btn-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-globe" viewBox="0 0 16 16">
                                            <path d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm7.5-6.923c-.67.204-1.335.82-1.887 1.855A7.97 7.97 0 0 0 5.145 4H7.5V1.077zM4.09 4a9.267 9.267 0 0 1 .64-1.539 6.7 6.7 0 0 1 .597-.933A7.025 7.025 0 0 0 2.255 4H4.09zm-.582 3.5c.03-.877.138-1.718.312-2.5H1.674a6.958 6.958 0 0 0-.656 2.5h2.49zM4.847 5a12.5 12.5 0 0 0-.338 2.5H7.5V5H4.847zM8.5 5v2.5h2.99a12.495 12.495 0 0 0-.337-2.5H8.5zM4.51 8.5a12.5 12.5 0 0 0 .337 2.5H7.5V8.5H4.51zm3.99 0V11h2.653c.187-.765.306-1.608.338-2.5H8.5zM5.145 12c.138.386.295.744.47 1.077.294.527.625.94.987 1.234a7.025 7.025 0 0 0-2.347-2.312zm.582-3.5c.03.877.138 1.718.312 2.5h1.147a12.5 12.5 0 0 0 .337-2.5h-1.796zM8.5 12.5h2.99c.074-.251.14-.513.198-.783.121-.557.188-1.14.196-1.717H8.5v2.5zM4.09 12.5h1.146c.183.663.408 1.267.67 1.787A7.026 7.026 0 0 0 4.09 12.5zm.582 3.5c.67-.204 1.335-.82 1.887-1.855A7.97 7.97 0 0 0 7.5 14.923V12.5H5.145a9.267 9.267 0 0 1-.64 1.539 6.7 6.7 0 0 1-.597.933A7.025 7.025 0 0 0 2.255 12.5H4.09zm9.575 0c.217-.506.403-1.046.553-1.617.155-.613.254-1.253.291-1.907h-1.146c-.096.656-.253 1.303-.468 1.908-.155.456-.338.868-.539 1.236a5.844 5.844 0 0 0 1.309.37zM12.63 4a7.025 7.025 0 0 0-1.822-2.385 6.7 6.7 0 0 1 .596.933c.241.404.45.865.626 1.365.177.5.328 1.032.446 1.587h-1.447A12.51 12.51 0 0 0 12.63 4zm-1.647-1.887C10.335.087 10.67 0 11 0a8 8 0 0 1 8 8c0 .329-.013.655-.038.976a7.024 7.024 0 0 0-1.822-2.363 6.7 6.7 0 0 1 .596.933c.246.499.455 1.040.647 1.622L12.631 4c-.204-.344-.42-.665-.648-.963a7.94 7.94 0 0 0-1-1.047z"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                            <div class="md:w-2/3">
                                <h3 class="text-2xl font-bold mb-2">ONG Cãopanheiros</h3>
                                <div class="flex gap-2 mb-3">
                                    <div class="badge badge-outline">São Paulo, SP</div>
                                    <div class="badge badge-outline">125 animais</div>
                                    <div class="badge badge-outline">Desde 2010</div>
                                </div>
                                <p class="text-gray-600 mb-4">A ONG Cãopanheiros atua há mais de 14 anos resgatando, cuidando e encontrando lares para cães e gatos abandonados. Todos os animais são vacinados, vermifugados e castrados antes da adoção.</p>

                                <p class="text-gray-600 mb-6">Além disso, realizamos ações educativas de conscientização sobre posse responsável, maus-tratos e abandono de animais em escolas e comunidades.</p>

                                <a href="#" class="link link-error font-medium">Conhecer mais sobre esta ONG</a>
                            </div>
                        </div>

                        <div class="divider"></div>

                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <div>
                                    <p class="font-bold">Campanha: <span class="text-info">Reforma do abrigo</span></p>
                                    <p class="text-sm text-gray-600">Meta: R$ 25.000,00</p>
                                </div>
                                <p class="font-semibold">R$ 15.780,00 arrecadados</p>
                            </div>
                            <progress class="progress progress-error w-full" value="63" max="100"></progress>
                        </div>

                        <div class="card-actions justify-end mt-4 flex-wrap gap-2">
                            <button class="btn btn-outline">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Fazer Doação
                            </button>
                            <button class="btn btn-info text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Ser Voluntário
                            </button>
                            <button class="btn btn-error text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                                Ver Pets da ONG
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex justify-center mt-10">
                    <button class="btn btn-outline btn-lg">Ver Todas as ONGs</button>
                </div>
            </div>
        </div>


        <!-- Testimonials Section -->
        <div class="py-24 bg-base-200">
            <div class="container mx-auto px-4">
                <div class="text-center mb-10">
                    <h2 class="text-4xl font-bold mb-4">Histórias de <span class="text-error">Sucesso</span></h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">Conheça algumas das histórias emocionantes de adoções realizadas através do nosso aplicativo.</p>
                    <div class="divider max-w-xs mx-auto mt-4"></div>
                </div>

                <div class="carousel w-full">
                    <!-- Testimony 1 -->
                    <div id="slide1" class="carousel-item relative w-full">
                        <div class="flex flex-col items-center w-full max-w-4xl mx-auto">
                            <div class="card bg-base-100 shadow-xl">
                                <div class="card-body">
                                    <div class="flex flex-col md:flex-row gap-6 items-center">
                                        <div class="md:w-1/3 flex flex-col items-center">
                                            <div class="avatar mb-4">
                                                <div class="w-24 rounded-full ring ring-primary ring-offset-base-100 ring-offset-2">
                                                    <img src="/api/placeholder/96/96" alt="Avatar" />
                                                </div>
                                            </div>
                                            <h3 class="font-bold text-xl">Pedro e Nina</h3>
                                            <p class="text-sm text-gray-500">São Paulo, SP</p>
                                            <div class="rating rating-sm mt-2">
                                                <input type="radio" name="rating-1" class="mask mask-star-2 bg-orange-400" checked />
                                                <input type="radio" name="rating-1" class="mask mask-star-2 bg-orange-400" checked />
                                                <input type="radio" name="rating-1" class="mask mask-star-2 bg-orange-400" checked />
                                                <input type="radio" name="rating-1" class="mask mask-star-2 bg-orange-400" checked />
                                                <input type="radio" name="rating-1" class="mask mask-star-2 bg-orange-400" checked />
                                            </div>
                                        </div>
                                        <div class="md:w-2/3">
                                            <div class="chat chat-start">
                                                <div class="chat-bubble chat-bubble-primary">
                                                    <p class="mb-3">Adotei a Nina através do app há 6 meses e não poderia estar mais feliz! O processo foi super simples e rápido.</p>
                                                    <p>A Nina era uma cadelinha tímida no abrigo, mas agora é a alegria da casa. O aplicativo nos conectou perfeitamente, parece que ela sempre foi parte da nossa família.</p>
                                                </div>
                                            </div>
                                            <div class="flex justify-end mt-4">
                                                <div class="badge badge-outline">Adoção de Sucesso</div>
                                                <div class="badge badge-outline ml-2">Maio 2025</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="absolute flex justify-between transform -translate-y-1/2 left-5 right-5 top-1/2">
                            <a href="#slide3" class="btn btn-circle">❮</a>
                            <a href="#slide2" class="btn btn-circle">❯</a>
                        </div>
                    </div>

                    <!-- Testimony 2 -->
                    <div id="slide2" class="carousel-item relative w-full">
                        <div class="flex flex-col items-center w-full max-w-4xl mx-auto">
                            <div class="card bg-base-100 shadow-xl">
                                <div class="card-body">
                                    <div class="flex flex-col md:flex-row gap-6 items-center">
                                        <div class="md:w-1/3 flex flex-col items-center">
                                            <div class="avatar mb-4">
                                                <div class="w-24 rounded-full ring ring-primary ring-offset-base-100 ring-offset-2">
                                                    <img src="/api/placeholder/96/96" alt="Avatar" />
                                                </div>
                                            </div>
                                            <h3 class="font-bold text-xl">Mariana e Thor</h3>
                                            <p class="text-sm text-gray-500">Rio de Janeiro, RJ</p>
                                            <div class="rating rating-sm mt-2">
                                                <input type="radio" name="rating-2" class="mask mask-star-2 bg-orange-400" checked />
                                                <input type="radio" name="rating-2" class="mask mask-star-2 bg-orange-400" checked />
                                                <input type="radio" name="rating-2" class="mask mask-star-2 bg-orange-400" checked />
                                                <input type="radio" name="rating-2" class="mask mask-star-2 bg-orange-400" checked />
                                                <input type="radio" name="rating-2" class="mask mask-star-2 bg-orange-400" checked />
                                            </div>
                                        </div>
                                        <div class="md:w-2/3">
                                            <div class="chat chat-start">
                                                <div class="chat-bubble chat-bubble-primary">
                                                    <p class="mb-3">Thor era um cão que já tinha passado por 3 lares diferentes quando o encontrei no app. Desde o primeiro encontro, tivemos uma conexão incrível!</p>
                                                    <p>A equipe do Adote um Pet me deu todo o suporte durante a adaptação. Hoje ele é meu companheiro inseparável. Muito obrigada por terem facilitado esse encontro!</p>
                                                </div>
                                            </div>
                                            <div class="flex justify-end mt-4">
                                                <div class="badge badge-outline">Adoção de Sucesso</div>
                                                <div class="badge badge-outline ml-2">Abril 2025</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="absolute flex justify-between transform -translate-y-1/2 left-5 right-5 top-1/2">
                            <a href="#slide1" class="btn btn-circle">❮</a>
                            <a href="#slide3" class="btn btn-circle">❯</a>
                        </div>
                    </div>

                    <!-- Testimony 3 -->
                    <div id="slide3" class="carousel-item relative w-full">
                        <div class="flex flex-col items-center w-full max-w-4xl mx-auto">
                            <div class="card bg-base-100 shadow-xl">
                                <div class="card-body">
                                    <div class="flex flex-col md:flex-row gap-6 items-center">
                                        <div class="md:w-1/3 flex flex-col items-center">
                                            <div class="avatar mb-4">
                                                <div class="w-24 rounded-full ring ring-primary ring-offset-base-100 ring-offset-2">
                                                    <img src="/api/placeholder/96/96" alt="Avatar" />
                                                </div>
                                            </div>
                                            <h3 class="font-bold text-xl">Carlos e Luna</h3>
                                            <p class="text-sm text-gray-500">Belo Horizonte, MG</p>
                                            <div class="rating rating-sm mt-2">
                                                <input type="radio" name="rating-3" class="mask mask-star-2 bg-orange-400" checked />
                                                <input type="radio" name="rating-3" class="mask mask-star-2 bg-orange-400" checked />
                                                <input type="radio" name="rating-3" class="mask mask-star-2 bg-orange-400" checked />
                                                <input type="radio" name="rating-3" class="mask mask-star-2 bg-orange-400" checked />
                                                <input type="radio" name="rating-3" class="mask mask-star-2 bg-orange-400" checked />
                                            </div>
                                        </div>
                                        <div class="md:w-2/3">
                                            <div class="chat chat-start">
                                                <div class="chat-bubble chat-bubble-primary">
                                                    <p class="mb-3">Nunca imaginei que uma gatinha pudesse trazer tanta alegria! A Luna era muito arisca quando a adotei, mas com paciência e amor, ela se transformou.</p>
                                                    <p>O aplicativo tem uma comunidade incrível que me ajudou com dicas para a adaptação. Hoje ela dorme na minha cama todas as noites e me recebe na porta quando chego do trabalho.</p>
                                                </div>
                                            </div>
                                            <div class="flex justify-end mt-4">
                                                <div class="badge badge-outline">Adoção de Sucesso</div>
                                                <div class="badge badge-outline ml-2">Março 2025</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="absolute flex justify-between transform -translate-y-1/2 left-5 right-5 top-1/2">
                            <a href="#slide2" class="btn btn-circle">❮</a>
                            <a href="#slide1" class="btn btn-circle">❯</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- Download Section -->
    <div id="download" class="relative py-24 bg-gradient-to-r from-info to-info-content text-white overflow-hidden">
        <div class="container mx-auto px-4 text-center relative z-10">
            <div class="badge badge-warning mb-4 text-sm font-medium py-3 px-4">Disponível nas lojas</div>
            <h2 class="text-4xl font-bold mb-6">Baixe o App Adote um Pet</h2>
            <p class="max-w-2xl mx-auto mb-10 text-lg">Disponível para iOS e Android. Baixe agora e comece a busca pelo seu novo melhor amigo com apenas alguns cliques.</p>

            <div class="flex flex-wrap justify-center gap-4 mb-10">
                <a href="#" class="btn btn-neutral gap-2 btn-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-google-play" viewBox="0 0 16 16">
                        <path d="M14.222 9.374c1.037-.61 1.037-2.137 0-2.748L11.528 5.04 8.32 8l3.207 2.96 2.694-1.586Zm-3.595 2.116L7.583 8.68 1.03 14.73c.201.202.44.354.702.446.278.1.62.157.965.157.347 0 .689-.055.965-.157.264-.092.502-.244.704-.446l6.16-3.24Zm-6.487-3.5c-.887-.521-.887-1.822 0-2.344l4.746-2.784 3.74 3.886-3.74 3.824-4.746-2.582ZM1.03 1.27C.828 1.467.588 1.618.327 1.71c-.579.2-1.247.198-1.825 0-.263-.092-.503-.243-.704-.445l6.553 6.55 3.044-2.81L1.03 1.27Z"/>
                    </svg>
                    Google Play
                </a>

                <a href="#" class="btn btn-neutral gap-2 btn-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-apple" viewBox="0 0 16 16">
                        <path d="M11.182.008C11.148-.03 9.923.023 8.857 1.18c-1.066 1.156-.902 2.482-.878 2.516.024.034 1.52.087 2.475-1.258.955-1.345.762-2.391.728-2.43Zm3.314 11.733c-.048-.096-2.325-1.234-2.113-3.422.212-2.189 1.675-2.789 1.698-2.854.023-.065-.597-.79-1.254-1.157a3.692 3.692 0 0 0-1.563-.434c-.108-.003-.483-.095-1.254.116-.508.139-1.653.589-1.968.607-.316.018-1.256-.522-2.267-.665-.647-.125-1.333.131-1.824.328-.49.196-1.422.754-2.074 2.237-.652 1.482-.311 3.83-.067 4.56.244.729.625 1.924 1.273 2.796.576.984 1.34 1.667 1.659 1.899.319.232 1.219.386 1.843.067.502-.308 1.408-.485 1.766-.472.357.013 1.061.154 1.782.539.571.197 1.111.115 1.652-.105.541-.221 1.324-1.059 2.238-2.758.347-.79.505-1.217.473-1.282Z"/>
                        <path d="M11.182.008C11.148-.03 9.923.023 8.857 1.18c-1.066 1.156-.902 2.482-.878 2.516.024.034 1.52.087 2.475-1.258.955-1.345.762-2.391.728-2.43Zm3.314 11.733c-.048-.096-2.325-1.234-2.113-3.422.212-2.189 1.675-2.789 1.698-2.854.023-.065-.597-.79-1.254-1.157a3.692 3.692 0 0 0-1.563-.434c-.108-.003-.483-.095-1.254.116-.508.139-1.653.589-1.968.607-.316.018-1.256-.522-2.267-.665-.647-.125-1.333.131-1.824.328-.49.196-1.422.754-2.074 2.237-.652 1.482-.311 3.83-.067 4.56.244.729.625 1.924 1.273 2.796.576.984 1.34 1.667 1.659 1.899.319.232 1.219.386 1.843.067.502-.308 1.408-.485 1.766-.472.357.013 1.061.154 1.782.539.571.197 1.111.115 1.652-.105.541-.221 1.324-1.059 2.238-2.758.347-.79.505-1.217.473-1.282Z"/>
                    </svg>
                    App Store
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mt-16">
                <div class="flex flex-col items-center">
                    <div class="stat-value text-4xl font-bold mb-2">100K+</div>
                    <p class="text-md font-medium">Downloads</p>
                </div>
                <div class="flex flex-col items-center">
                    <div class="stat-value text-4xl font-bold mb-2">4.8</div>
                    <div class="rating rating-md">
                        <input type="radio" name="rating-app" class="mask mask-star-2 bg-warning" checked />
                        <input type="radio" name="rating-app" class="mask mask-star-2 bg-warning" checked />
                        <input type="radio" name="rating-app" class="mask mask-star-2 bg-warning" checked />
                        <input type="radio" name="rating-app" class="mask mask-star-2 bg-warning" checked />
                        <input type="radio" name="rating-app" class="mask mask-star-2 bg-warning" checked />
                    </div>
                    <p class="text-md font-medium">Avaliação</p>
                </div>
                <div class="flex flex-col items-center">
                    <div class="stat-value text-4xl font-bold mb-2">15K+</div>
                    <p class="text-md font-medium">Usuários Ativos</p>
                </div>
                <div class="flex flex-col items-center">
                    <div class="stat-value text-4xl font-bold mb-2">2.6K+</div>
                    <p class="text-md font-medium">Adoções</p>
                </div>
            </div>

            <div class="max-w-md mx-auto mt-12">
                <div class="join w-full">
                    <input type="email" placeholder="Seu e-mail" class="input input-lg join-item w-3/4" />
                    <button class="btn btn-error join-item w-1/4 text-white">Enviar</button>
                </div>
                <p class="text-sm mt-2">Receba o link para download diretamente no seu e-mail</p>
            </div>
        </div>

        <!-- Mobile Mockups -->
        <div class="absolute -bottom-16 -left-16 opacity-20 lg:opacity-100 lg:relative lg:left-auto lg:bottom-auto">
            <img src="/api/placeholder/300/600" alt="App Mockup" class="w-40 rounded-xl rotate-12" />
        </div>

        <div class="absolute -top-16 -right-16 opacity-20 lg:opacity-100 lg:relative lg:right-auto lg:top-auto">
            <img src="/api/placeholder/300/600" alt="App Mockup" class="w-40 rounded-xl -rotate-12" />
        </div>

        <div class="custom-shape-divider">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="shape-fill" style="fill: #FFFFFF"></path>
            </svg>
        </div>
    </div>




    <!-- Footer -->
    <footer class="bg-neutral text-neutral-content">
        <div class="container mx-auto px-4">
            <div class="footer py-10">
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <div class="avatar">
                            <div class="w-10 rounded-full">
                                <img src="/api/placeholder/40/40" alt="Logo" />
                            </div>
                        </div>
                        <span class="font-bold text-xl">
                            <span class="text-error">adote</span><span class="text-info">um</span><span class="text-warning">pet</span>
                        </span>
                    </div>
                    <p class="max-w-xs">Conectando pets a famílias amorosas desde 2023. Nossa missão é proporcionar um lar para todos os animais abandonados.</p>
                    <div class="flex gap-4 mt-4">
                        <a href="#" class="btn btn-circle btn-outline btn-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-instagram" viewBox="0 0 16 16">
                                <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z"/>
                            </svg>
                        </a>
                        <a href="#" class="btn btn-circle btn-outline btn-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-facebook" viewBox="0 0 16 16">
                                <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z"/>
                            </svg>
                        </a>
                        <a href="#" class="btn btn-circle btn-outline btn-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-youtube" viewBox="0 0 16 16">
                                <path d="M8.051 1.999h.089c.822.003 4.987.033 6.11.335a2.01 2.01 0 0 1 1.415 1.42c.101.38.172.883.22 1.402l.01.104.022.26.008.104c.065.914.073 1.77.074 1.957v.075c-.001.194-.01 1.108-.082 2.06l-.008.105-.009.104c-.05.572-.124 1.14-.235 1.558a2.007 2.007 0 0 1-1.415 1.42c-1.16.312-5.569.334-6.18.335h-.142c-.309 0-1.587-.006-2.927-.052l-.17-.006-.087-.004-.171-.007-.171-.007c-1.11-.049-2.167-.128-2.654-.26a2.007 2.007 0 0 1-1.415-1.419c-.111-.417-.185-.986-.235-1.558L.09 9.82l-.008-.104A31.4 31.4 0 0 1 0 7.68v-.123c.002-.215.01-.958.064-1.778l.007-.103.003-.052.008-.104.022-.26.01-.104c.048-.519.119-1.023.22-1.402a2.007 2.007 0 0 1 1.415-1.42c.487-.13 1.544-.21 2.654-.26l.17-.007.172-.006.086-.003.171-.007A99.788 99.788 0 0 1 7.858 2h.193zM6.4 5.209v4.818l4.157-2.408L6.4 5.209z"/>
                            </svg>
                        </a>
                        <a href="#" class="btn btn-circle btn-outline btn-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-twitter-x" viewBox="0 0 16 16">
                                <path d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.601.75Zm-.86 13.028h1.36L4.323 2.145H2.865l8.875 11.633Z"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <div>
                    <span class="footer-title">Navegação</span>
                    <a href="#como-funciona" class="link link-hover">Como Funciona</a>
                    <a href="#encontre" class="link link-hover">Encontre um Pet</a>
                    <a href="#ongs" class="link link-hover">ONGs Parceiras</a>
                    <a href="#app" class="link link-hover">App</a>
                    <a href="#contato" class="link link-hover">Contato</a>
                </div>

                <div>
                    <span class="footer-title">Links Úteis</span>
                    <a href="#" class="link link-hover">Blog</a>
                    <a href="#" class="link link-hover">Sobre Nós</a>
                    <a href="#" class="link link-hover">Perguntas Frequentes</a>
                    <a href="#" class="link link-hover">Seja um Voluntário</a>
                    <a href="#" class="link link-hover">Seja um Parceiro</a>
                </div>

                <div>
                    <span class="footer-title">Legal</span>
                    <a href="#" class="link link-hover">Termos de Uso</a>
                    <a href="#" class="link link-hover">Política de Privacidade</a>
                    <a href="#" class="link link-hover">Política de Cookies</a>
                    <a href="#" class="link link-hover">Termos de Adoção</a>
                </div>
            </div>

            <div class="footer footer-center p-4 border-t border-base-300 text-base-content">
                <div>
                    <p>© 2025 Adote um Pet. Todos os direitos reservados. Desenvolvido com ❤️ para os pets.</p>
                </div>
            </div>
        </div>
    </footer>

