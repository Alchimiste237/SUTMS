<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'SUTMS') }} - University Management</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        <style>
            body { font-family: 'Inter', sans-serif; }
        </style>
    </head>
    <body class="bg-zinc-50 text-zinc-900 dark:bg-zinc-950 dark:text-zinc-100 antialiased selection:bg-indigo-500 selection:text-white">
        <!-- Navigation -->
        <nav class="sticky top-0 z-50 w-full border-b border-zinc-200 bg-white/80 backdrop-blur-md dark:border-zinc-800 dark:bg-zinc-950/80">
            <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-2">
                    <x-app-logo-icon class="h-8 w-8 text-indigo-600 dark:text-indigo-500" />
                    <span class="text-xl font-bold tracking-tight">SUTMS</span>
                </div>
                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-sm font-medium hover:text-indigo-600 dark:hover:text-indigo-400">Dashboard</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="rounded-full bg-zinc-900 px-4 py-2 text-sm font-semibold text-white hover:bg-zinc-800 dark:bg-zinc-100 dark:text-zinc-900 dark:hover:bg-zinc-200">Log out</button>
                        </form>
                    @else
                        <a href="{{ route('timetable') }}" class="text-sm font-medium hover:text-indigo-600 dark:hover:text-indigo-400">Public Timetable</a>
                        <a href="{{ route('login') }}" class="rounded-full bg-indigo-600 px-5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Sign in</a>
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <main>
            <div class="relative isolate overflow-hidden bg-white dark:bg-zinc-950">
                <svg class="absolute inset-0 -z-10 h-full w-full stroke-zinc-200 dark:stroke-zinc-800 [mask-image:radial-gradient(100%_100%_at_top_right,white,transparent)]" aria-hidden="true">
                    <defs>
                        <pattern id="0787a7c5-978c-4f66-83c7-11c213f99cb7" width="200" height="200" x="50%" y="-1" patternUnits="userSpaceOnUse">
                            <path d="M.5 200V.5H200" fill="none" />
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" stroke-width="0" fill="url(#0787a7c5-978c-4f66-83c7-11c213f99cb7)" />
                </svg>
                <div class="mx-auto max-w-7xl px-6 pb-24 pt-10 sm:pb-32 lg:flex lg:px-8 lg:py-40">
                    <div class="mx-auto max-w-2xl lg:mx-0 lg:max-w-xl lg:flex-shrink-0 lg:pt-8">
                        <div class="mt-24 sm:mt-32 lg:mt-16">
                            <a href="#" class="inline-flex space-x-6">
                                <span class="rounded-full bg-indigo-600/10 px-3 py-1 text-sm font-semibold leading-6 text-indigo-600 ring-1 ring-inset ring-indigo-600/10 dark:text-indigo-400">What's new</span>
                                <span class="inline-flex items-center space-x-2 text-sm font-medium leading-6 text-zinc-600 dark:text-zinc-400">
                                    <span>Just shipped v1.0</span>
                                </span>
                            </a>
                        </div>
                        <h1 class="mt-10 text-4xl font-bold tracking-tight text-zinc-900 dark:text-white sm:text-6xl">
                            University Scheduling <span class="text-indigo-600 dark:text-indigo-500">Simplified.</span>
                        </h1>
                        <p class="mt-6 text-lg leading-8 text-zinc-600 dark:text-zinc-400">
                            Smart University Timetable Management System (SUTMS) provides a powerful, conflict-aware engine for managing classes, teachers, and academic schedules with ease.
                        </p>
                        <div class="mt-10 flex items-center gap-x-6">
                            <a href="{{ route('timetable') }}" class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                View Class Timetable
                            </a>
                            <a href="{{ route('login') }}" class="text-sm font-semibold leading-6 text-zinc-900 dark:text-white">Staff Login <span aria-hidden="true">→</span></a>
                        </div>
                    </div>
                    <div class="mx-auto mt-16 flex max-w-2xl sm:mt-24 lg:ml-10 lg:mr-0 lg:mt-0 lg:max-w-none lg:flex-none xl:ml-32">
                        <div class="max-w-3xl flex-none sm:max-w-5xl lg:max-w-none">
                            <div class="-m-2 rounded-xl bg-zinc-900/5 p-2 ring-1 ring-inset ring-zinc-900/10 lg:-m-4 lg:rounded-2xl lg:p-4 dark:bg-white/5 dark:ring-white/10">
                                <div class="rounded-md bg-white shadow-2xl ring-1 ring-zinc-900/10 dark:bg-zinc-900 dark:ring-white/10 overflow-hidden">
                                    <!-- Placeholder UI image -->
                                    <div class="bg-zinc-800 p-2 flex gap-1">
                                        <div class="w-2 h-2 rounded-full bg-red-500"></div>
                                        <div class="w-2 h-2 rounded-full bg-amber-500"></div>
                                        <div class="w-2 h-2 rounded-full bg-green-500"></div>
                                    </div>
                                    <div class="p-4 bg-zinc-50 dark:bg-zinc-900">
                                        <div class="grid grid-cols-4 gap-2">
                                            @for($i=0; $i<12; $i++)
                                                <div class="h-20 bg-indigo-100 dark:bg-indigo-900/20 rounded border border-indigo-200 dark:border-indigo-800 animate-pulse"></div>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features -->
            <div class="bg-white py-24 sm:py-32 dark:bg-zinc-950 border-t border-zinc-200 dark:border-zinc-800">
                <div class="mx-auto max-w-7xl px-6 lg:px-8">
                    <div class="mx-auto max-w-2xl lg:text-center">
                        <h2 class="text-base font-semibold leading-7 text-indigo-600 dark:text-indigo-400">Better Scheduling</h2>
                        <p class="mt-2 text-3xl font-bold tracking-tight text-zinc-900 dark:text-white sm:text-4xl">Everything you need to manage your university</p>
                    </div>
                    <div class="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-24 lg:max-w-none">
                        <dl class="grid max-w-xl grid-cols-1 gap-x-8 gap-y-16 lg:max-w-none lg:grid-cols-3">
                            <div class="flex flex-col">
                                <dt class="flex items-center gap-x-3 text-base font-semibold leading-7 text-zinc-900 dark:text-white">
                                    <div class="h-10 w-10 flex items-center justify-center rounded-lg bg-indigo-600">
                                        <x-app-logo-icon class="h-6 w-6 text-white" />
                                    </div>
                                    Automated Generation
                                </dt>
                                <dd class="mt-4 flex flex-auto flex-col text-base leading-7 text-zinc-600 dark:text-zinc-400">
                                    <p class="flex-auto">Conflict-aware scheduling engine that respects teacher availability and class requirements.</p>
                                </dd>
                            </div>
                            <div class="flex flex-col">
                                <dt class="flex items-center gap-x-3 text-base font-semibold leading-7 text-zinc-900 dark:text-white">
                                    <div class="h-10 w-10 flex items-center justify-center rounded-lg bg-indigo-600">
                                        <x-app-logo-icon class="h-6 w-6 text-white" />
                                    </div>
                                    Class-Centric Views
                                </dt>
                                <dd class="mt-4 flex flex-auto flex-col text-base leading-7 text-zinc-600 dark:text-zinc-400">
                                    <p class="flex-auto">Tailored timetables for every class group and teacher, accessible anytime, anywhere.</p>
                                </dd>
                            </div>
                            <div class="flex flex-col">
                                <dt class="flex items-center gap-x-3 text-base font-semibold leading-7 text-zinc-900 dark:text-white">
                                    <div class="h-10 w-10 flex items-center justify-center rounded-lg bg-indigo-600">
                                        <x-app-logo-icon class="h-6 w-6 text-white" />
                                    </div>
                                    Academic Management
                                </dt>
                                <dd class="mt-4 flex flex-auto flex-col text-base leading-7 text-zinc-600 dark:text-zinc-400">
                                    <p class="flex-auto">Comprehensive tools for levels, class groups, subjects, and teaching assignments.</p>
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </main>

        <footer class="bg-zinc-50 dark:bg-zinc-950 border-t border-zinc-200 dark:border-zinc-800 py-12">
            <div class="mx-auto max-w-7xl px-6 lg:px-8 text-center">
                <p class="text-sm leading-5 text-zinc-500">&copy; {{ date('Y') }} SUTMS. All rights reserved.</p>
            </div>
        </footer>
    </body>
</html>
