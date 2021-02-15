<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg" >
                <!--<x-jet-welcome /> -->
                <div class="mt-8 text-2xl">
                    <h1>Welcome to Antilobby</h1>
                </div>

                <div class="mt-6 text-gray-500">
                    <p><a href="{{ url('/') }}">Click here to go back to the Antilobby Resource Hub.</a></p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
