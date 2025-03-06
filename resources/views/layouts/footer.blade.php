<!-- Navigation Bar -->
<footer class="fixed bottom-0 w-full bg-black shadow-lg">
    <div class="flex justify-around py-3 text-white">
        <!-- Task -->
        <a href="{{ route('task.index') }}"
            class="flex flex-col items-center px-4 py-2 rounded-lg transition-all duration-300 ease-in-out
            {{ request()->routeIs('task.index') ? 'bg-gray-700' : 'hover:bg-gray-800 hover:text-gray-300' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-6 h-6">
                <path fill-rule="evenodd"
                    d="M3.75 5.25a.75.75 0 0 1 .75-.75h15a.75.75 0 0 1 0 1.5H4.5a.75.75 0 0 1-.75-.75Zm0 6a.75.75 0 0 1 .75-.75h15a.75.75 0 0 1 0 1.5H4.5a.75.75 0 0 1-.75-.75Zm.75 5.25a.75.75 0 0 0 0 1.5h15a.75.75 0 0 0 0-1.5H4.5Z"
                    clip-rule="evenodd" />
            </svg>
            <span class="text-sm">Task</span>
        </a>

        <!-- Home -->
        <a href="{{ route('home.index') }}"
            class="flex flex-col items-center px-4 py-2 rounded-lg transition-all duration-300 ease-in-out
            {{ request()->routeIs('home.index') ? 'bg-gray-700' : 'hover:bg-gray-800 hover:text-gray-300' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-6 h-6">
                <path fill-rule="evenodd"
                    d="M10.5 3.59a2.25 2.25 0 0 1 3 0l6.75 6a2.25 2.25 0 0 1 .75 1.66v7.75A2.25 2.25 0 0 1 18.75 21H5.25A2.25 2.25 0 0 1 3 18.75v-7.75c0-.63.28-1.23.75-1.66l6.75-6ZM9 19.5v-3a3 3 0 0 1 6 0v3h3.75a.75.75 0 0 0 .75-.75v-7.75a.75.75 0 0 0-.25-.55l-6.75-6a.75.75 0 0 0-1 0l-6.75 6a.75.75 0 0 0-.25.55v7.75c0 .41.34.75.75.75H9Z"
                    clip-rule="evenodd" />
            </svg>
            <span class="text-sm">Home</span>
        </a>

        <!-- Logout -->
        <form action="#" method="POST" class="flex flex-col items-center">
            @csrf
            <button type="submit"
                class="flex flex-col items-center px-4 py-2 rounded-lg transition-all duration-300 ease-in-out
                hover:bg-red-700 hover:text-gray-300">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-6 h-6">
                    <path fill-rule="evenodd"
                        d="M6.75 4.5a.75.75 0 0 1 .75-.75h9a.75.75 0 0 1 .75.75v2.25a.75.75 0 0 0 1.5 0V4.5A2.25 2.25 0 0 0 16.5 2.25h-9A2.25 2.25 0 0 0 5.25 4.5v15a2.25 2.25 0 0 0 2.25 2.25h9A2.25 2.25 0 0 0 18.75 19.5v-2.25a.75.75 0 0 0-1.5 0v2.25a.75.75 0 0 1-.75.75h-9a.75.75 0 0 1-.75-.75v-15ZM16.22 12.53a.75.75 0 0 0 1.06-1.06l-3-3a.75.75 0 0 0-1.06 1.06L14.44 11H9a.75.75 0 0 0 0 1.5h5.44l-1.22 1.22a.75.75 0 0 0 1.06 1.06l3-3Z"
                        clip-rule="evenodd" />
                </svg>
                <span class="text-sm">Logout</span>
            </button>
        </form>
    </div>
</footer>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

@stack('script')
