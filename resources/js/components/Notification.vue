<script setup>

const props = defineProps({
    bookingMessage : String
})
</script>

<template>
    <div id="toast-message-cta" class="w-full max-w-xs p-4 text-gray-500 bg-white border rounded-lg shadow-sm dark:bg-gray-800 dark:text-gray-400 absolute z-50 right-8 top-28" role="alert">
    <div class="flex">
        
        <div class="ms-3 text-sm font-normal">
            <span class="mb-1 text-sm font-semibold text-gray-900 dark:text-white">New Booking</span>
            <div class="mb-2 text-sm font-normal mt-2">Hi Neil, thanks for sharing your thoughts regarding Flowbite.</div> 
  
        </div>
        <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-white justify-center items-center shrink-0 text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700" data-dismiss-target="#toast-message-cta" aria-label="Close">
            <span class="sr-only">Close</span>
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
        </button>
    </div>
</div>
</template>